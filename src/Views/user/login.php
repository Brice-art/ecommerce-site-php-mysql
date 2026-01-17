<section class="auth-section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <h1>Login</h1>
                <p class="auth-subtitle">Welcome back! Please login to your account.</p>

                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=login" class="auth-form">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control"
                            value="<?php echo htmlspecialchars($email ?? ''); ?>"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="form-group form-remember">
                        <label>
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a href="index.php?page=forgot-password" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-large">
                        Login
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="index.php?page=register">Sign up here</a></p>
                </div>
            </div>
        </div>
    </div>
</section>