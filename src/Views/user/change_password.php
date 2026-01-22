<div class="profile-container">
    <h2>Change Password</h2>
    <p>Please enter your current password and choose a new password.</p>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert-success">
            <?php
            echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=profile&action=change_password">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input
                type="password"
                id="current_password"
                name="current_password"
                class="form-control"
                required
                autofocus>
        </div>

        <div class="form-group">
            <label for="new_password">New Password</label>
            <input
                type="password"
                id="new_password"
                name="new_password"
                class="form-control"
                required>
            <div class="password-hint">Password must be at least 8 characters long</div>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                class="form-control"
                required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Change Password</button>
            <a href="index.php?page=profile" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
