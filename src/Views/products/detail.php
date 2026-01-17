<section>
    <div class="top-detail">
        <div class="image-detail">
            <?php if (!empty($product['main_image'])): ?>
                <img src="images/products/<?php echo htmlspecialchars($product['main_image']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>">
            <?php else: ?>
                <div class="no-image">No Image Available</div>
            <?php endif; ?>
        </div>

        <div class="right-detail">
            <h3><?php echo htmlspecialchars($title); ?></h3>
            <h4><?php echo htmlspecialchars($category ?? 'Uncategorized'); ?></h4>

            <div class="stock-info">
                <?php if ($product['quantity'] > 0): ?>
                    <span class="in-stock">✓ In Stock (<?php echo $product['quantity']; ?> available)</span>
                <?php else: ?>
                    <span class="out-of-stock">✗ Out of Stock</span>
                <?php endif; ?>
            </div>

            <div class="price">
                <?php if (!empty($product['compare_price'])): ?>
                    <span class="old-price">$<?php echo number_format($product['compare_price'], 2); ?></span>
                <?php endif; ?>
                <span class="current-price">$<?php echo number_format($product['price'], 2); ?></span>
            </div>

            <div class="product-actions">
                <?php if ($product['quantity'] > 0): ?>
                    <button class="btn btn-primary" onclick="addToCart(<?php echo $product['id']; ?>)">
                        Add to Cart
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>
                        Out of Stock
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bottom-detail">
        <div>
            <div class="description-tab">Description</div>
            <div class="reviews-tab">Reviews</div>
        </div>

        <div>
            <h3>Product Details</h3>
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <p><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?></p>
            <?php if (!empty($product['weight'])): ?>
                <p><strong>Weight:</strong> <?php echo htmlspecialchars($product['weight']); ?> kg</p>
            <?php endif; ?>
        </div>

        <div>
            <h3>Customer Reviews</h3>
            <p>No reviews yet. Be the first to review this product!</p>
            <!-- TODO: Add reviews functionality later -->
        </div>
    </div>
</section>