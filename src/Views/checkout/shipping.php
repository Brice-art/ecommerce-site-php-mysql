<?php
$title = $title ?? 'Shipping Information';
?>
<section class="checkout-section">
    <div class="container">
        <h1>Shipping Information</h1>
        
        <div class="checkout-content">
            <!-- Shipping Form -->
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

                <form method="POST" action="index.php?page=checkout&action=shipping" class="checkout-form">
                    <h3>Shipping Address</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping_first_name">First Name *</label>
                            <input type="text" id="shipping_first_name" name="shipping_first_name" 
                                class="form-control" required 
                                value="<?php echo htmlspecialchars($_POST['shipping_first_name'] ?? $_SESSION['user']['first_name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="shipping_last_name">Last Name *</label>
                            <input type="text" id="shipping_last_name" name="shipping_last_name" 
                                class="form-control" required
                                value="<?php echo htmlspecialchars($_POST['shipping_last_name'] ?? $_SESSION['user']['last_name'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="shipping_email">Email *</label>
                        <input type="email" id="shipping_email" name="shipping_email" 
                            class="form-control" required
                            value="<?php echo htmlspecialchars($_POST['shipping_email'] ?? $_SESSION['user_email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="shipping_phone">Phone</label>
                        <input type="tel" id="shipping_phone" name="shipping_phone" 
                            class="form-control"
                            value="<?php echo htmlspecialchars($_POST['shipping_phone'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="shipping_address_line1">Address Line 1 *</label>
                        <input type="text" id="shipping_address_line1" name="shipping_address_line1" 
                            class="form-control" required
                            value="<?php echo htmlspecialchars($_POST['shipping_address_line1'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="shipping_address_line2">Address Line 2</label>
                        <input type="text" id="shipping_address_line2" name="shipping_address_line2" 
                            class="form-control"
                            value="<?php echo htmlspecialchars($_POST['shipping_address_line2'] ?? ''); ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping_city">City *</label>
                            <input type="text" id="shipping_city" name="shipping_city" 
                                class="form-control" required
                                value="<?php echo htmlspecialchars($_POST['shipping_city'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="shipping_state">State</label>
                            <input type="text" id="shipping_state" name="shipping_state" 
                                class="form-control"
                                value="<?php echo htmlspecialchars($_POST['shipping_state'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="shipping_postal_code">Postal Code *</label>
                            <input type="text" id="shipping_postal_code" name="shipping_postal_code" 
                                class="form-control" required
                                value="<?php echo htmlspecialchars($_POST['shipping_postal_code'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="shipping_country">Country</label>
                        <select id="shipping_country" name="shipping_country" class="form-control">
                            <option value="US" <?php echo (($_POST['shipping_country'] ?? 'US') === 'US') ? 'selected' : ''; ?>>United States</option>
                            <option value="CA" <?php echo (($_POST['shipping_country'] ?? '') === 'CA') ? 'selected' : ''; ?>>Canada</option>
                            <option value="UK" <?php echo (($_POST['shipping_country'] ?? '') === 'UK') ? 'selected' : ''; ?>>United Kingdom</option>
                        </select>
                    </div>

                    <div class="form-checkbox">
                        <label>
                            <input type="checkbox" name="same_as_billing" value="1" checked>
                            Use same address for billing
                        </label>
                    </div>

                    <div class="checkout-actions">
                        <a href="index.php?page=cart" class="btn btn-secondary">← Back to Cart</a>
                        <button type="submit" class="btn btn-primary">Continue to Payment →</button>
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
