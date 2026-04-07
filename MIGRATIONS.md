# Database Migrations Guide

This guide explains how to set up and run database migrations for the ecommerce project.

## What are Migrations?

Migrations are SQL files that define your database schema. They allow you to version control your database structure and easily replicate it across different environments.

## Migration Files

All migration files are located in `src/Database/migrations/` and are numbered to ensure they run in order:

1. **001_create_users_table.sql** - Users table (admin and customer accounts)
2. **002_create_products_table.sql** - Products table (inventory)
3. **003_create_categories_table.sql** - Product categories (supports subcategories)
4. **004_create_orders_table.sql** - Customer orders
5. **005_create_order_items_table.sql** - Order line items
6. **006_create_cart_table.sql** - Shopping cart items

## Running Migrations

### Option 1: Command Line (CLI)

If you prefer the command line approach:

```bash
# Check migration status
php migrate.php status

# Run pending migrations
php migrate.php run

# Show help
php migrate.php help
```

**Requirements:**
- PHP CLI installed
- `.env` file configured with database credentials
- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` environment variables set

### Option 2: Web Interface

Access the migration interface through your browser:

1. Navigate to: `http://localhost/ecommerce-project/public/migrations.html`
2. Click **"Run Migrations"** to execute pending migrations
3. Click **"Check Status"** to see which migrations have been executed

**Notes:**
- Requires a running web server (Laragon, XAMPP, etc.)
- Must be able to connect to your database
- The web interface is useful for shared hosting environments

### Option 3: Programmatic (PHP)

For custom integration within your application:

```php
require_once 'src/Database/Migrator.php';

// Get PDO connection
$pdo = getConnection(); // Use your existing connection

// Initialize migrator
$migrator = new Migrator($pdo, __DIR__ . '/src/Database/migrations');

// Run migrations
$migrator->run();

// Check status
$migrator->status();
```

## Database Schema Overview

### Users Table
- Stores user accounts (admins and customers)
- Tracks user roles, status, and profile information
- Soft deletes with `deleted_at` field

### Products Table
- Stores product information
- Linked to categories
- Supports pricing, SKU, barcode, and quantity tracking
- Main image support for product display

### Categories Table
- Product categories with support for subcategories (parent_id)
- SEO-friendly slugs
- Category images for storefront display

### Orders Table
- Customer orders with comprehensive address information
- Supports separate shipping and billing addresses
- Order status tracking (pending, processing, shipped, delivered)
- Includes order notes, tracking numbers, and cost calculations

### Order Items Table
- Line items for each order
- Stores product snapshot data (price, SKU, name at time of purchase)
- Calculated subtotal for each item

### Cart Table
- Shopping cart functionality
- Supports both logged-in users and guest sessions
- Linked to products for real-time price and availability updates

## Tracking Migrations

A `migrations` table is automatically created to track which migrations have been executed:

```sql
SELECT * FROM migrations;
```

This prevents migrations from being run multiple times and maintains a history of database changes.

## Migration Status Output

When running migrations, you'll see output like:

```
=== Database Migrator ===

Running batch 1...

✓ Executed: 001_create_users_table.sql
✓ Executed: 002_create_products_table.sql
✓ Executed: 003_create_categories_table.sql
✓ Executed: 004_create_orders_table.sql
✓ Executed: 005_create_order_items_table.sql
✓ Executed: 006_create_cart_table.sql

=== Migration Summary ===
Successful: 6
Failed: 0
Total: 6
```

## Environment Configuration

Before running migrations, ensure your `.env` file is properly configured:

```env
DB_HOST=localhost
DB_NAME=ecommerce_db
DB_USER=root
DB_PASS=your_password
```

## Troubleshooting

### "Connection refused" error
- Ensure your database server is running (MySQL/MariaDB)
- Check that `DB_HOST`, `DB_USER`, and `DB_PASS` are correct

### "No migration files found"
- Verify migration files exist in `src/Database/migrations/`
- Check file permissions

### "Table already exists" error
- This is normal if migrations have already been run
- Migrations use `CREATE TABLE IF NOT EXISTS` to prevent duplicates

### Foreign key constraint errors
- Ensure migrations run in order (they do by default)
- Some tables depend on others (products requires categories, orders requires users)

## Adding New Migrations

To add a new migration:

1. Create a new SQL file in `src/Database/migrations/`
2. Name it with the next number: `007_create_table_name.sql`
3. Write your SQL schema
4. Run the migrations using any of the methods above

Example:

```sql
-- 007_create_reviews_table.sql
CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    rating INT UNSIGNED NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    comment TEXT,
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## API Endpoints

The web interface uses these endpoints:

### POST `/public/run-migrations.php`

**Request:**
```json
{
  "action": "run"
}
```

**Response:**
```json
{
  "success": true,
  "output": [
    "✓ Executed: 001_create_users_table.sql",
    "..."
  ]
}
```

### POST `/public/run-migrations.php`

**Request:**
```json
{
  "action": "status"
}
```

**Response:**
```json
{
  "success": true,
  "migrations": [
    {
      "name": "001_create_users_table.sql",
      "executed": true
    },
    {
      "name": "002_create_products_table.sql",
      "executed": true
    },
    ...
  ]
}
```

## Best Practices

1. **Always test migrations locally first** before running on production
2. **Backup your database** before running migrations on production
3. **Keep migrations small and focused** on a single schema change
4. **Never modify existing migration files** - create new ones for changes
5. **Document your migrations** with comments explaining the purpose
6. **Run migrations in order** - don't skip any

## Support

For issues or questions, refer to:
- MySQL Documentation: https://dev.mysql.com/doc/
- PDO Documentation: https://www.php.net/manual/en/book.pdo.php
