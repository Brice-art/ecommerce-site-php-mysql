<!-- Admin Orders List -->
<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <div>
                <h1>🛒 Orders Management</h1>
                <p class="admin-subtitle"><?php echo count($orders); ?> total orders</p>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="admin-card">
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <strong>#<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']); ?>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($order['shipping_email']); ?></small>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <?php
                                        // You'll need to get order items count
                                        echo 'N/A'; // TODO: Add order items count
                                        ?>
                                    </td>
                                    <td><strong>$<?php echo number_format($order['total'], 2); ?></strong></td>
                                    <td>
                                        <select class="status-select status-<?php echo $order['status']; ?>"
                                            onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)">
                                            <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="processing" <?php echo ($order['status'] == 'processing') ? 'selected' : ''; ?>>Processing</option>
                                            <option value="shipped" <?php echo ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="delivered" <?php echo ($order['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="cancelled" <?php echo ($order['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="index.php?page=admin&action=view-order&id=<?php echo $order['id']; ?>"
                                                class="btn-action btn-view" title="View Details">
                                                👁️
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty-message">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    function updateOrderStatus(orderId, newStatus) {
        if (confirm('Update order status to: ' + newStatus + '?')) {
            window.location.href = 'index.php?page=admin&action=update-order-status&id=' + orderId + '&status=' + newStatus;
        }
    }
</script>