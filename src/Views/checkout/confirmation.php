<?php
$title = $title ?? 'Order Confirmation';
?>
<section class="checkout-section">
    <div class="container">
        <div class="confirmation-container">
            <div class="confirmation-icon">✅</div>
            <h1>Order Confirmed!</h1>
            <p class="confirmation-message">Thank you for your order. Your order has been placed successfully.</p>
            
            <div class="order-info-box">
                <h3>Order #<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></h3>
                <p>Order Date: <?php echo date('F d, Y g:i A', strtotime($order['created_at'])); ?></p>
                <p>Status: <span class="status-badge status-<?php echo htmlspecialchars($order['status']); ?>">
                    <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                </span></p>
            </div>

            <div class="order-summary-box">
                <h3>Order Summary</h3>
                <div class="order-items-list">
                    <?php foreach ($orderItems as $item): ?>
                        <div class="order-item-confirm">
                            <div class="item-image-small">
                                <?php if (!empty($item['main_image'])): ?>
                                    <img src="images/products/<?php echo htmlspecialchars($item['main_image']); ?>" 
                                        alt="<?php echo htmlspecialchars($item['name'] ?? 'Product'); ?>">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="item-details-confirm">
                                <h4><?php echo htmlspecialchars($item['name'] ?? 'Product'); ?></h4>
                                <p>Quantity: <?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></p>
                            </div>
                            <div class="item-total-confirm">
                                $<?php echo number_format($item['quantity'] * $item['price'], 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-totals-confirm">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($order['subtotal'], 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>$<?php echo number_format($order['shipping_cost'], 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Tax:</span>
                        <span>$<?php echo number_format($order['tax'], 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <strong>Total:</strong>
                        <strong>$<?php echo number_format($order['total'], 2); ?></strong>
                    </div>
                </div>
            </div>

            <div class="confirmation-actions">
                <a href="index.php?page=orders&id=<?php echo $order['id']; ?>" class="btn btn-primary">View Order Details</a>
                <a href="index.php?page=products" class="btn btn-secondary">Continue Shopping</a>
            </div>
        </div>
    </div>
</section>
