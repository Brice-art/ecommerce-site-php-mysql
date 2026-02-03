<!-- Hero Section - Compact -->
<section class="hero-compact">
    <div class="container">
        <div class="hero-content">
            <h1>Welcome to MyStore</h1>
            <p>Discover amazing products at great prices</p>
            <a href="index.php?page=products" class="btn btn-primary">Shop Now</a>
        </div>
    </div>
</section>

<!-- Horizontal Category Tabs -->
<section class="home-category-tabs">
    <div class="container">
        <h2 class="section-title-small">Shop by Category</h2>
        <div class="category-tabs-horizontal">
            <div class="category-tabs-scroll">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <a href="index.php?page=products&category=<?php echo $category['id']; ?>" class="category-tab-card">
                            <div class="category-tab-icon">
                                <?php
                                // Dynamic icons based on category name
                                $icons = [
                                    'Electronics' => '💻',
                                    'Clothing' => '👕',
                                    'Books' => '📚',
                                    'Home & Garden' => '🏡',
                                    'Sports' => '⚽',
                                    'Toys' => '🎮'
                                ];
                                echo $icons[$category['name']] ?? '📦';
                                ?>
                            </div>
                            <span class="category-tab-name"><?php echo htmlspecialchars($category['name']); ?></span>
                            <span class="category-tab-arrow">→</span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products - Compact Grid -->
<section class="products-section-compact">
    <div class="container">
        <div class="section-header-compact">
            <h2>⭐ Featured Products</h2>
            <a href="index.php?page=products&filter=featured" class="view-all-link">View All →</a>
        </div>

        <div class="products-grid-compact">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="product-card-compact">
                        <a href="index.php?page=product&id=<?php echo $product['id']; ?>" class="product-link">
                            <div class="product-image-compact">
                                <?php if ($product['main_image']): ?>
                                    <img src="images/products/<?php echo htmlspecialchars($product['main_image']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <div class="no-image-compact">No Image</div>
                                <?php endif; ?>

                                <?php if ($product['is_featured']): ?>
                                    <span class="badge-featured">⭐ Featured</span>
                                <?php endif; ?>

                                <?php if ($product['compare_price']): ?>
                                    <span class="badge-sale">SALE</span>
                                <?php endif; ?>
                            </div>

                            <div class="product-info-compact">
                                <h3 class="product-name-compact"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-category-compact"><?php echo htmlspecialchars($product['category_name']); ?></p>

                                <div class="product-price-compact">
                                    <?php if ($product['compare_price']): ?>
                                        <span class="price-old-compact">$<?php echo number_format($product['compare_price'], 2); ?></span>
                                    <?php endif; ?>
                                    <span class="price-current-compact">$<?php echo number_format($product['price'], 2); ?></span>
                                </div>

                                <?php if ($product['quantity'] > 0): ?>
                                    <div class="product-stock-compact stock-available">
                                        ✓ In Stock
                                    </div>
                                <?php else: ?>
                                    <div class="product-stock-compact stock-out">
                                        ✗ Out of Stock
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>

                        <div class="product-actions-compact">
                            <button class="btn-add-cart-compact" onclick="addToCart(<?php echo $product['id']; ?>); event.stopPropagation();">
                                <span class="cart-icon">🛒</span>
                                <span class="cart-text">Add</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-products-message">No featured products available.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Latest Products - Compact Grid -->
<section class="products-section-compact latest-section">
    <div class="container">
        <div class="section-header-compact">
            <h2>🆕 Latest Products</h2>
            <a href="index.php?page=products&filter=latest" class="view-all-link">View All →</a>
        </div>

        <div class="products-grid-compact">
            <?php if (!empty($latestProducts)): ?>
                <?php foreach ($latestProducts as $product): ?>
                    <div class="product-card-compact">
                        <a href="index.php?page=product&id=<?php echo $product['id']; ?>" class="product-link">
                            <div class="product-image-compact">
                                <?php if ($product['main_image']): ?>
                                    <img src="images/products/<?php echo htmlspecialchars($product['main_image']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <div class="no-image-compact">No Image</div>
                                <?php endif; ?>

                                <?php if ($product['is_featured']): ?>
                                    <span class="badge-featured">⭐</span>
                                <?php endif; ?>

                                <?php if ($product['compare_price']): ?>
                                    <span class="badge-sale">SALE</span>
                                <?php endif; ?>
                            </div>

                            <div class="product-info-compact">
                                <h3 class="product-name-compact"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-category-compact"><?php echo htmlspecialchars($product['category_name']); ?></p>

                                <div class="product-price-compact">
                                    <?php if ($product['compare_price']): ?>
                                        <span class="price-old-compact">$<?php echo number_format($product['compare_price'], 2); ?></span>
                                    <?php endif; ?>
                                    <span class="price-current-compact">$<?php echo number_format($product['price'], 2); ?></span>
                                </div>

                                <?php if ($product['quantity'] > 0): ?>
                                    <div class="product-stock-compact stock-available">
                                        ✓ In Stock
                                    </div>
                                <?php else: ?>
                                    <div class="product-stock-compact stock-out">
                                        ✗ Out of Stock
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>

                        <div class="product-actions-compact">
                            <button class="btn-add-cart-compact" onclick="addToCart(<?php echo $product['id']; ?>); event.stopPropagation();">
                                <span class="cart-icon">🛒</span>
                                <span class="cart-text">Add</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>