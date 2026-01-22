<div class="profile-container">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 24px auto;
            padding: 24px;
            background: #fff;
            border: 1px solid #e6e6e6;
            border-radius: 8px
        }

        .form-group {
            margin-bottom: 18px
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px
        }

        .alert-error {
            padding: 12px;
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
            border-radius: 6px;
            margin-bottom: 18px
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px
        }
    </style>

    <h2>Edit Profile</h2>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=profile&action=edit">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input
                type="text"
                id="first_name"
                name="first_name"
                class="form-control"
                value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>"
                required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input
                type="text"
                id="last_name"
                name="last_name"
                class="form-control"
                value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>"
                required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                class="form-control"
                value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Email (cannot be changed)</label>
            <input
                type="email"
                class="form-control"
                value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                disabled>
        </div>

        <div style="margin-top:24px">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="index.php?page=profile" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>