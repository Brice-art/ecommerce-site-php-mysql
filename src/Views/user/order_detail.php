<?php
$title = $title ?? 'Order Details';
?>
<section class="order-detail-section">
    <div class="container">
        <div style="margin-bottom: 2rem;">
            <a href="index.php?page=orders" class="btn btn-secondary">← Back to Orders</a>
        </div>

        <h1>Order #<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></h1>
        
        <div class="order-detail-grid">
            <!-- Order Summary Card -->
            <div class="order-card">
                <div class="card-header">
                    <h3>Order Summary</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Order Date</span>
                        <span class="info-value">
                            <?php echo date('F d, Y g:i A', strtotime($order['created_at'])); ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="status-badge status-<?php echo htmlspecialchars($order['status'] ?? 'pending'); ?>">
                                <?php echo ucfirst(htmlspecialchars($order['status'] ?? 'Pending')); ?>
                            </span>
                        </span>
                    </div>
                    <?php if (!empty($order['tracking_number'])): ?>
                    <div class="info-row">
                        <span class="info-label">Tracking Number</span>
                        <span class="info-value"><?php echo htmlspecialchars($order['tracking_number']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Shipping Address Card -->
            <?php if (!empty($order['shipping_first_name'])): ?>
            <div class="order-card">
                <div class="card-header">
                    <h3>Shipping Address</h3>
                </div>
                <div class="card-body">
                    <p>
                        <strong><?php echo htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']); ?></strong><br>
                        <?php echo htmlspecialchars($order['shipping_address_line1']); ?><br>
                        <?php if (!empty($order['shipping_address_line2'])): ?>
                            <?php echo htmlspecialchars($order['shipping_address_line2']); ?><br>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($order['shipping_city'] . ', ' . $order['shipping_state'] . ' ' . $order['shipping_postal_code']); ?><br>
                        <?php echo htmlspecialchars($order['shipping_country']); ?><br>
                        <?php if (!empty($order['shipping_phone'])): ?>
                            Phone: <?php echo htmlspecialchars($order['shipping_phone']); ?><br>
                        <?php endif; ?>
                        <?php if (!empty($order['shipping_email'])): ?>
                            Email: <?php echo htmlspecialchars($order['shipping_email']); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Order Items Card -->
            <div class="order-card full-width">
                <div class="card-header">
                    <h3>Order Items</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($orderItems)): ?>
                        <p>No items found for this order.</p>
                    <?php else: ?>
                        <div class="order-items-table">
                            <?php foreach ($orderItems as $item): ?>
                                <div class="order-item-row">
                                    <div class="item-image">
                                        <?php if (!empty($item['main_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['main_image']); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'Product'); ?>">
                                        <?php else: ?>
                                            <div class="no-image">No Image</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="item-details">
                                        <h4><?php echo htmlspecialchars($item['name'] ?? 'Product'); ?></h4>
                                        <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                        <p>Price: $<?php echo number_format($item['price'], 2); ?> each</p>
                                    </div>
                                    <div class="item-subtotal">
                                        <strong>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Order Totals Card -->
            <div class="order-card">
                <div class="card-header">
                    <h3>Order Totals</h3>
                </div>
                <div class="card-body">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($order['subtotal'], 2); ?></span>
                    </div>
                    <?php if ($order['discount'] > 0): ?>
                    <div class="summary-row">
                        <span>Discount:</span>
                        <span>-$<?php echo number_format($order['discount'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>$<?php echo number_format($order['shipping_cost'], 2); ?></span>
                    </div>
                    <?php if ($order['tax'] > 0): ?>
                    <div class="summary-row">
                        <span>Tax:</span>
                        <span>$<?php echo number_format($order['tax'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="summary-row total">
                        <span><strong>Total:</strong></span>
                        <span><strong>$<?php echo number_format($order['total'], 2); ?></strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.order-detail-section {
    padding: 3rem 0;
    min-height: 80vh;
}

.order-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-top: 2rem;
}

.order-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
}

.order-card.full-width {
    grid-column: 1 / -1;
}

.order-items-table {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item-row {
    display: grid;
    grid-template-columns: 100px 2fr 1fr;
    gap: 1.5rem;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.order-item-row .item-image {
    width: 100px;
    height: 100px;
    border-radius: 10px;
    overflow: hidden;
    background: #f0f0f0;
}

.order-item-row .item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-item-row .item-image .no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 0.9rem;
}

.order-item-row .item-details h4 {
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.order-item-row .item-subtotal {
    text-align: right;
    font-size: 1.2rem;
    color: #27ae60;
}

@media (max-width: 768px) {
    .order-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .order-item-row {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .order-item-row .item-subtotal {
        text-align: center;
    }
}
</style>
