<section class="auth-section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <h1>Edit Account</h1>

                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=user" class="auth-form">
                    <div class="form-row">
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
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number (Optional)</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="form-control"
                            value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>

                    <!--<div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control"
                                required
                                minlength="8"
                            >
                            <small class="form-text">At least 8 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="form-control"
                                required
                            >
                        </div>
                    </div> 

                    <div class="form-group form-checkbox">
                        <label>
                            <input type="checkbox" name="terms" required>
                            I agree to the <a href="#" target="_blank">Terms and Conditions</a>
                        </label>
                    </div> -->

                    <button type="submit" class="btn btn-primary btn-block btn-large">
                        Confirm Changes
                    </button>
                </form>

            </div>
        </div>
    </div>
</section>