<?php
$title = $title ?? 'Payment';
?>
<section class="checkout-section">
    <div class="container">
        <h1>Payment</h1>
        
        <div class="checkout-content">
            <!-- Payment Form -->
            <div class="checkout-form-container">
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=checkout&action=payment" class="checkout-form">
                    <h3>Payment Method</h3>
                    
                    <div class="form-group">
                        <label>
                            <input type="radio" name="payment_method" value="cash_on_delivery" checked required>
                            Cash on Delivery
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="radio" name="payment_method" value="credit_card" required>
                            Credit Card (Demo - No real payment)
                        </label>
                    </div>

                    <?php if (!isset($billing) || !$billing): ?>
                        <h3 style="margin-top: 2rem;">Billing Address</h3>
                        <p class="form-text">Enter billing address if different from shipping</p>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="billing_first_name">First Name *</label>
                                <input type="text" id="billing_first_name" name="billing_first_name" 
                                    class="form-control" required
                                    value="<?php echo htmlspecialchars($_POST['billing_first_name'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="billing_last_name">Last Name *</label>
                                <input type="text" id="billing_last_name" name="billing_last_name" 
                                    class="form-control" required
                                    value="<?php echo htmlspecialchars($_POST['billing_last_name'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="billing_email">Email</label>
                            <input type="email" id="billing_email" name="billing_email" 
                                class="form-control"
                                value="<?php echo htmlspecialchars($_POST['billing_email'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="billing_phone">Phone</label>
                            <input type="tel" id="billing_phone" name="billing_phone" 
                                class="form-control"
                                value="<?php echo htmlspecialchars($_POST['billing_phone'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="billing_address_line1">Address Line 1 *</label>
                            <input type="text" id="billing_address_line1" name="billing_address_line1" 
                                class="form-control" required
                                value="<?php echo htmlspecialchars($_POST['billing_address_line1'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="billing_address_line2">Address Line 2</label>
                            <input type="text" id="billing_address_line2" name="billing_address_line2" 
                                class="form-control"
                                value="<?php echo htmlspecialchars($_POST['billing_address_line2'] ?? ''); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="billing_city">City *</label>
                                <input type="text" id="billing_city" name="billing_city" 
                                    class="form-control" required
                                    value="<?php echo htmlspecialchars($_POST['billing_city'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="billing_state">State</label>
                                <input type="text" id="billing_state" name="billing_state" 
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($_POST['billing_state'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="billing_postal_code">Postal Code *</label>
                                <input type="text" id="billing_postal_code" name="billing_postal_code" 
                                    class="form-control" required
                                    value="<?php echo htmlspecialchars($_POST['billing_postal_code'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="billing_country">Country</label>
                            <select id="billing_country" name="billing_country" class="form-control">
                                <option value="US" <?php echo (($_POST['billing_country'] ?? 'US') === 'US') ? 'selected' : ''; ?>>United States</option>
                                <option value="CA" <?php echo (($_POST['billing_country'] ?? '') === 'CA') ? 'selected' : ''; ?>>Canada</option>
                                <option value="UK" <?php echo (($_POST['billing_country'] ?? '') === 'UK') ? 'selected' : ''; ?>>United Kingdom</option>
                            </select>
                        </div>
                    <?php else: ?>
                        <div class="info-box">
                            <p><strong>Billing Address:</strong> Same as shipping address</p>
                        </div>
                    <?php endif; ?>

                    <div class="checkout-actions">
                        <a href="index.php?page=checkout&action=shipping" class="btn btn-secondary">← Back</a>
                        <button type="submit" class="btn btn-primary">Place Order</button>
                    </div>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="checkout-summary">
                <h3>Order Summary</h3>
                <div class="summary-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="summary-item">
                            <div class="summary-item-image">
                                <?php if (!empty($item['main_image'])): ?>
                                    <img src="images/products/<?php echo htmlspecialchars($item['main_image']); ?>" 
                                        alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="summary-item-details">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p>Qty: <?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></p>
                            </div>
                            <div class="summary-item-total">
                                $<?php echo number_format($item['subtotal'], 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-totals">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($totals['subtotal'], 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>$<?php echo number_format($totals['shipping_cost'], 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Tax:</span>
                        <span>$<?php echo number_format($totals['tax'], 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <strong>Total:</strong>
                        <strong>$<?php echo number_format($totals['total'], 2); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
