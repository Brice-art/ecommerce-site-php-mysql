<div class="profile-card">
    <div class="card-header">
        <h3><?php echo $title ?></h3>
    </div>
    <div>
        <a href="index.php?page=admin&action=create-product" class="btn btn-primary">
            Add Products
        </a>
        <a href="index.php?page=admin&action=orders" class="btn btn-primary">
            View Orders
        </a>
        <a href="index.php?page=admin&action=users" class="btn btn-primary">
            Manage Users
        </a>
    </div>
    <div class="card-body">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo count($allUsers); ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-icon">📦</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo count($allProducts); ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-icon">🛍️</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo count($allOrders); ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-icon">💳</div>
                <div class="stat-content">
                    <div class="stat-value">
                        $<?php echo $revenue ?>
                    </div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="profile-card full-width">
    <div class="card-header">
        <h3>📦 Recent Orders</h3>
        <?php if (isset($allOrders) && count($allOrders) > 0): ?>
            <a href="index.php?page=orders" class="view-all-link">View All →</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (isset($allOrders) && count($allOrders) > 0): ?>
            <div class="orders-table">
                <?php foreach (array_slice($allOrders, 0, 5) as $order): ?>
                    <div class="dashboard-order-row">
                        <div class="order-date">
                            <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                        </div>
                        <div class="order-info">
                            <div class="order-number">
                                <strong>Order #<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></strong>
                            </div>
                        </div>
                        <div class="order-info">
                            <div class="order-number">
                                <strong><?php echo htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']) ?></strong>
                            </div>
                        </div>
                        <div class="order-total">
                            <strong>$<?php echo number_format($order['total'], 2); ?></strong>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-<?php echo $order['status'] ?? 'pending'; ?>">
                                <?php echo ucfirst($order['status'] ?? 'Pending'); ?>
                            </span>
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