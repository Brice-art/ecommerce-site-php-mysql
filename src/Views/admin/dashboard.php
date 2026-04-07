<!-- Admin Dashboard -->
<?php

usort($allUsers, function ($a, $b) {
    return $b['created_at'] <=> $a['created_at'];
});

usort($allOrders, function ($a, $b) {
    return $b['created_at'] <=> $a['created_at'];
});
?>
<section class="admin-section">
    <div class="container">
        <!-- Welcome Header -->
        <div class="admin-header">
            <div>
                <h1>📊 Admin Dashboard</h1>
                <p class="admin-subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! Here's what's happening today.</p>
            </div>
            <div class="header-date">
                <?php echo date('l, F j, Y'); ?>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon products">📦</div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo count($allProducts); ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
                <a href="index.php?page=admin&action=products" class="stat-link">Manage →</a>
            </div>

            <div class="stat-card">
                <div class="stat-icon orders">🛒</div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo count($allOrders); ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <a href="index.php?page=admin&action=orders" class="stat-link">View All →</a>
            </div>

            <div class="stat-card">
                <div class="stat-icon users">👥</div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo count($allUsers); ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <a href="index.php?page=admin&action=users" class="stat-link">Manage →</a>
            </div>

            <div class="stat-card">
                <div class="stat-icon revenue">💰</div>
                <div class="stat-info">
                    <div class="stat-value">$<?php echo number_format($revenue ?? 0, 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <span class="stat-link" style="cursor: default;">All Time</span>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="dashboard-grid">
            <!-- Quick Actions -->
            <div class="admin-card">
                <div class="card-header">
                    <h3>⚡ Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions-grid">
                        <a href="index.php?page=admin&action=create-product" class="quick-action-card">
                            <div class="quick-action-icon add">➕</div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Add Product</div>
                                <div class="quick-action-desc">Create new product</div>
                            </div>
                        </a>

                        <a href="index.php?page=admin&action=products" class="quick-action-card">
                            <div class="quick-action-icon products">📦</div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Products</div>
                                <div class="quick-action-desc">Manage inventory</div>
                            </div>
                        </a>

                        <a href="index.php?page=admin&action=orders" class="quick-action-card">
                            <div class="quick-action-icon orders">🛒</div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Orders</div>
                                <div class="quick-action-desc">Process orders</div>
                            </div>
                        </a>

                        <a href="index.php?page=admin&action=users" class="quick-action-card">
                            <div class="quick-action-icon users">👥</div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Users</div>
                                <div class="quick-action-desc">Manage accounts</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="admin-card full-width">
                <div class="card-header">
                    <h3>📦 Recent Orders</h3>
                    <?php if (!empty($allOrders)): ?>
                        <a href="index.php?page=admin&action=orders" class="view-all">View All →</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (!empty($allOrders)): ?>
                        <div class="dashboard-orders">
                            <?php foreach (array_slice($allOrders, 0, 5) as $order): ?>
                                <div class="dashboard-order-item">
                                    <div class="order-col order-id">
                                        <div class="order-label">Order</div>
                                        <strong>#<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></strong>
                                    </div>
                                    
                                    <div class="order-col order-customer">
                                        <div class="order-label">Customer</div>
                                        <div><?php echo htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']); ?></div>
                                    </div>
                                    
                                    <div class="order-col order-date">
                                        <div class="order-label">Date</div>
                                        <div><?php echo date('M d, Y', strtotime($order['created_at'])); ?></div>
                                    </div>
                                    
                                    <div class="order-col order-amount">
                                        <div class="order-label">Amount</div>
                                        <strong class="amount-value">$<?php echo number_format($order['total'], 2); ?></strong>
                                    </div>
                                    
                                    <div class="order-col order-status-col">
                                        <div class="order-label">Status</div>
                                        <span class="status-badge status-<?php echo $order['status'] ?? 'pending'; ?>">
                                            <?php echo ucfirst($order['status'] ?? 'Pending'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="order-col order-actions">
                                        <a href="index.php?page=admin&action=view-order&id=<?php echo $order['id']; ?>" 
                                           class="btn-mini">
                                            View
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state-dashboard">
                            <div class="empty-icon">📦</div>
                            <h4>No Orders Yet</h4>
                            <p>Orders will appear here once customers start purchasing.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="admin-card">
                <div class="card-header">
                    <h3>⚠️ Low Stock Alert</h3>
                </div>
                <div class="card-body">
                    <?php 
                    $lowStock = array_filter($allProducts, function($p) {
                        return $p['quantity'] < 10 && $p['quantity'] > 0;
                    });
                    ?>
                    <?php if (!empty($lowStock)): ?>
                        <div class="low-stock-list">
                            <?php foreach (array_slice($lowStock, 0, 5) as $product): ?>
                                <div class="low-stock-item">
                                    <div class="low-stock-info">
                                        <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                        <span class="low-stock-qty">Only <?php echo $product['quantity']; ?> left</span>
                                    </div>
                                    <a href="index.php?page=admin&action=edit-product&id=<?php echo $product['id']; ?>" 
                                       class="btn-mini">
                                        Restock
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="all-good">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">✅</div>
                            <p>All products are well stocked!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="admin-card">
                <div class="card-header">
                    <h3>👥 Recent Users</h3>
                    <a href="index.php?page=admin&action=users" class="view-all">View All →</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($allUsers)): ?>
                        <div class="recent-users-list">
                            <?php foreach (array_slice($allUsers, 0, 5) as $user): ?>
                                <div class="recent-user-item">
                                    <div class="user-avatar-small">
                                        <?php echo strtoupper(substr($user['first_name'], 0, 1)); ?>
                                    </div>
                                    <div class="user-info-small">
                                        <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                                        <small><?php echo htmlspecialchars($user['email']); ?></small>
                                    </div>
                                    <span class="user-date"><?php echo date('M d', strtotime($user['created_at'])); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="empty-message">No users yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>