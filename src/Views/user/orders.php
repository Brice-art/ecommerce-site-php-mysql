<?php
$title = $title ?? 'My Orders';
?>
<section class="orders-section">
    <div class="container">
        <h1><?php echo htmlspecialchars($title); ?></h1>
        
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <h4>No Orders Yet</h4>
                <p>You haven't placed any orders yet.</p>
                <a href="index.php?page=products" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="orders-table">
                <?php foreach ($orders as $order): ?>
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
                            <span class="status-badge status-<?php echo htmlspecialchars($order['status'] ?? 'pending'); ?>">
                                <?php echo ucfirst(htmlspecialchars($order['status'] ?? 'Pending')); ?>
                            </span>
                        </div>
                        <div class="order-total">
                            <strong>$<?php echo number_format($order['total'], 2); ?></strong>
                        </div>
                        <div class="order-action">
                            <a href="index.php?page=orders&id=<?php echo $order['id']; ?>" class="btn btn-small">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
