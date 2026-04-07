<?php

class Migrator
{
    private PDO $pdo;
    private string $migrationsPath;
    private string $migrationsTable = 'migrations';

    public function __construct(PDO $pdo, string $migrationsPath)
    {
        // Fail fast — don't let bad config silently produce empty migrations
        if (!is_dir($migrationsPath)) {
            throw new InvalidArgumentException("Migrations path does not exist: {$migrationsPath}");
        }

        $this->pdo = $pdo;
        $this->migrationsPath = rtrim($migrationsPath, '/');
    }

    public function initMigrationsTable(): void
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS {$this->migrationsTable} (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            batch INT UNSIGNED NOT NULL,
            executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function getMigrationFiles(): array
    {
        $files = glob($this->migrationsPath . '/*.sql') ?: [];
        natsort($files);
        return array_values($files);
    }

    public function getExecutedMigrations(): array
    {
        $stmt = $this->pdo->query("SELECT name FROM {$this->migrationsTable}");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function run(): bool
    {
        echo "=== Database Migrator ===\n\n";

        $this->initMigrationsTable();

        $allFiles = $this->getMigrationFiles();

        if (empty($allFiles)) {
            echo "No migration files found.\n";
            return false;
        }

        $executed = $this->getExecutedMigrations();
        $pending = array_filter($allFiles, fn($f) => !in_array(basename($f), $executed));

        if (empty($pending)) {
            echo "No pending migrations.\n";
            return true;
        }

        $batch = $this->getNextBatch();
        echo "Running batch {$batch}...\n\n";

        $successful = 0;
        $failed = 0;

        foreach ($pending as $file) {
            $name = basename($file);

            try {
                $sql = file_get_contents($file);

                // Wrap each migration in a transaction — all or nothing
                $this->pdo->beginTransaction();

                // Split on semicolons and execute each statement individually
                $statements = array_filter(
                    array_map('trim', explode(';', $sql)),
                    fn($s) => $s !== ''
                );

                foreach ($statements as $statement) {
                    $this->pdo->exec($statement);
                }

                // Record BEFORE committing so if this insert fails, we roll back
                $stmt = $this->pdo->prepare(
                    "INSERT INTO {$this->migrationsTable} (name, batch) VALUES (:name, :batch)"
                );
                $stmt->execute([':name' => $name, ':batch' => $batch]);

                $this->pdo->commit();

                echo "✓ Migrated: {$name}\n";
                $successful++;

            } catch (PDOException $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }

                echo "✗ Failed: {$name}\n";
                echo "  Error: " . $e->getMessage() . "\n";
                echo "  Stopping — fix this migration before continuing.\n";
                $failed++;

                // Stop immediately on failure — don't run dependent migrations on broken state
                break;
            }
        }

        echo "\n=== Summary ===\n";
        echo "Successful: {$successful}, Failed: {$failed}\n";

        return $failed === 0;
    }

    public function rollback(): bool
    {
        echo "=== Rollback Last Batch ===\n\n";

        $this->initMigrationsTable();

        $stmt = $this->pdo->query(
            "SELECT name, batch FROM {$this->migrationsTable} ORDER BY id DESC"
        );
        $all = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($all)) {
            echo "Nothing to roll back.\n";
            return true;
        }

        // Get last batch
        $lastBatch = $all[0]['batch'];
        $toRollback = array_filter($all, fn($m) => $m['batch'] === $lastBatch);

        foreach ($toRollback as $migration) {
            $downFile = $this->migrationsPath . '/down/' . $migration['name'];

            if (!file_exists($downFile)) {
                echo "✗ No down file found for: {$migration['name']}\n";
                echo "  Create: migrations/down/{$migration['name']}\n";
                continue;
            }

            try {
                $sql = file_get_contents($downFile);

                $this->pdo->beginTransaction();

                $statements = array_filter(
                    array_map('trim', explode(';', $sql)),
                    fn($s) => $s !== ''
                );

                foreach ($statements as $statement) {
                    $this->pdo->exec($statement);
                }

                $this->pdo->prepare(
                    "DELETE FROM {$this->migrationsTable} WHERE name = :name"
                )->execute([':name' => $migration['name']]);

                $this->pdo->commit();

                echo "✓ Rolled back: {$migration['name']}\n";

            } catch (PDOException $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                echo "✗ Rollback failed: {$migration['name']}\n";
                echo "  Error: " . $e->getMessage() . "\n";
                return false;
            }
        }

        return true;
    }

    public function status(): void
    {
        echo "=== Migration Status ===\n\n";

        $this->initMigrationsTable();

        $allFiles = $this->getMigrationFiles();

        if (empty($allFiles)) {
            echo "No migration files found.\n";
            return;
        }

        $executed = $this->getExecutedMigrations();

        foreach ($allFiles as $file) {
            $name = basename($file);
            $status = in_array($name, $executed) ? "✓ Executed" : "⊙ Pending ";
            echo "{$status}: {$name}\n";
        }

        echo "\n";
    }

    private function getNextBatch(): int
    {
        $stmt = $this->pdo->query("SELECT MAX(batch) FROM {$this->migrationsTable}");
        return (int)($stmt->fetchColumn() ?? 0) + 1;
    }
}