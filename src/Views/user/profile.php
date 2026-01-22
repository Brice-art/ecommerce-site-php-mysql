<?php
// Success message
if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php
        echo htmlspecialchars($_SESSION['success_message']);
        unset($_SESSION['success_message']);
        ?>
    </div>
<?php endif; ?>

<section class="profile-section">
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar-large">
                <?php
                $initials = strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1));
                ?>
                <div class="avatar-circle-large"><?php echo $initials; ?></div>
            </div>
            <div class="profile-header-info">
                <h1><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
                <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <div class="profile-badges">
                    <span class="badge badge-<?php echo $user['role']; ?>">
                        <?php echo ucfirst($user['role']); ?>
                    </span>
                    <span class="badge badge-member">
                        Member since <?php echo date('M Y', strtotime($user['created_at'])); ?>
                    </span>
                </div>
            </div>
            <div class="profile-header-actions">
                <a href="index.php?page=profile&action=edit" class="btn btn-primary">
                    <span>✏️</span> Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Content Grid -->
        <div class="profile-content">
            <!-- Left Column - Personal Info -->
            <div class="profile-card">
                <div class="card-header">
                    <h3>📋 Personal Information</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Full Name</span>
                        <span class="info-value">
                            <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email Address</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone Number</span>
                        <span class="info-value">
                            <?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : '<span class="text-muted">Not provided</span>'; ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Account Status</span>
                        <span class="info-value">
                            <?php if ($user['is_active']): ?>
                                <span class="status-active">● Active</span>
                            <?php else: ?>
                                <span class="status-inactive">● Inactive</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Account Stats -->
            <div class="profile-card">
                <div class="card-header">
                    <h3>📊 Account Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">🛍️</div>
                            <div class="stat-content">
                                <div class="stat-value"><?php echo isset($orders) ? count($orders) : 0; ?></div>
                                <div class="stat-label">Total Orders</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">🛒</div>
                            <div class="stat-content">
                                <div class="stat-value"><?php echo $count ?? 0; ?></div>
                                <div class="stat-label">Cart Items</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">💳</div>
                            <div class="stat-content">
                                <div class="stat-value">
                                    $<?php
                                        $total = 0;
                                        if (isset($orders)) {
                                            foreach ($orders as $order) {
                                                $total += $order['total'] ?? 0;
                                            }
                                        }
                                        echo number_format($total, 2);
                                        ?>
                                </div>
                                <div class="stat-label">Total Spent</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">📅</div>
                            <div class="stat-content">
                                <div class="stat-value">
                                    <?php
                                    $memberSince = new DateTime($user['created_at']);
                                    $now = new DateTime();
                                    $diff = $now->diff($memberSince);
                                    echo $diff->days;
                                    ?>
                                </div>
                                <div class="stat-label">Days Member</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="profile-card">
                <div class="card-header">
                    <h3>⚡ Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="index.php?page=profile&action=edit" class="action-item">
                            <span class="action-icon">✏️</span>
                            <div>
                                <div class="action-title">Edit Profile</div>
                                <div class="action-desc">Update your personal information</div>
                            </div>
                        </a>
                        <a href="index.php?page=profile&action=change_password" class="action-item">
                            <span class="action-icon">🔒</span>
                            <div>
                                <div class="action-title">Change Password</div>
                                <div class="action-desc">Update your account password</div>
                            </div>
                        </a>
                        <a href="index.php?page=orders" class="action-item">
                            <span class="action-icon">📦</span>
                            <div>
                                <div class="action-title">My Orders</div>
                                <div class="action-desc">View your order history</div>
                            </div>
                        </a>
                        <a href="index.php?page=cart" class="action-item">
                            <span class="action-icon">🛒</span>
                            <div>
                                <div class="action-title">Shopping Cart</div>
                                <div class="action-desc">View items in your cart</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="profile-card full-width">
                <div class="card-header">
                    <h3>📦 Recent Orders</h3>
                    <?php if (isset($orders) && count($orders) > 0): ?>
                        <a href="index.php?page=orders" class="view-all-link">View All →</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (isset($orders) && count($orders) > 0): ?>
                        <div class="orders-table">
                            <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                                <div class="order-row">
                                    <div class="order-info">
                                        <div class="order-number">
                                            <strong>Order #<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></strong>
                                        </div>
                                        <div class="order-date">
                                            <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                        </div>
                                    </div>
                                    <div class="order-status">
                                        <span class="status-badge status-<?php echo $order['status'] ?? 'pending'; ?>">
                                            <?php echo ucfirst($order['status'] ?? 'Pending'); ?>
                                        </span>
                                    </div>
                                    <div class="order-total">
                                        <strong>$<?php echo number_format($order['total'], 2); ?></strong>
                                    </div>
                                    <div class="order-action">
                                        <a href="index.php?page=orders&id=<?php echo $order['id']; ?>" class="btn btn-small">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📦</div>
                            <h4>No Orders Yet</h4>
                            <p>You haven't placed any orders. Start shopping to see your orders here!</p>
                            <a href="index.php?page=products" class="btn btn-primary">Browse Products</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>