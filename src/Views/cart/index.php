<section class="cart-section">
    <div class="container">
        <h1><?php echo htmlspecialchars($title); ?></h1>

        <!-- If cart is empty -->
        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="index.php?page=products" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <?php
            // Calculate totals
            $subtotal = $total ?? 0;
            $shipping = 10.00;
            $tax = $subtotal * 0.08; // 8% tax
            $grandTotal = $subtotal + $shipping + $tax;
            ?>

            <!-- If cart has items -->
            <div class="cart-content">
                <!-- Cart Items Table/List -->
                <div class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
                            <div class="item-image">
                                <?php if (!empty($item['main_image'])): ?>
                                    <img src="images/products/<?php echo htmlspecialchars($item['main_image']); ?>"
                                        alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>
                            </div>

                            <div class="item-details">
                                <h3 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="item-price">$<?php echo number_format($item['price'], 2); ?></p>
                            </div>

                            <div class="item-quantity">
                                <form method="POST" action="index.php?page=cart&action=update">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">

                                    <!-- Decrease button -->
                                    <button type="submit" name="quantity"
                                        value="<?php echo max(1, $item['quantity'] - 1); ?>"
                                        class="qty-btn">-</button>

                                    <!-- Current quantity (readonly) -->
                                    <input type="number" name="quantity_display"
                                        value="<?php echo $item['quantity']; ?>"
                                        min="1" max="<?php echo $item['stock_quantity']; ?>"
                                        readonly>

                                    <!-- Increase button -->
                                    <button type="submit" name="quantity"
                                        value="<?php echo min($item['stock_quantity'], $item['quantity'] + 1); ?>"
                                        class="qty-btn"
                                        <?php echo ($item['quantity'] >= $item['stock_quantity']) ? 'disabled' : ''; ?>>+</button>
                                </form>

                                <?php if ($item['quantity'] >= $item['stock_quantity']): ?>
                                    <p class="stock-warning">Max stock reached</p>
                                <?php endif; ?>
                            </div>

                            <div class="item-subtotal">
                                <p>$<?php echo number_format($item['subtotal'], 2); ?></p>
                            </div>

                            <div class="item-remove">
                                <a href="index.php?page=cart&action=remove&cart_id=<?php echo $item['id']; ?>"
                                    class="remove-btn"
                                    onclick="return confirm('Remove this item from cart?');">✕</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary Sidebar -->
                <div class="cart-summary">
                    <h3>Order Summary</h3>

                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>$<?php echo number_format($shipping, 2); ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Tax (8%):</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>

                    <div class="summary-row total">
                        <strong>Total:</strong>
                        <strong>$<?php echo number_format($grandTotal, 2); ?></strong>
                    </div>

                    <a href="index.php?page=checkout" class="btn btn-primary btn-block">
                        Proceed to Checkout
                    </a>

                    <a href="index.php?page=products" class="btn btn-secondary btn-block">
                        Continue Shopping
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>