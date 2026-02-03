<!-- Compact Search Bar -->
<section class="search-bar-compact">
    <div class="container">
        <form method="GET" action="index.php" class="search-form-compact">
            <input type="hidden" name="page" value="products">
            <div class="search-input-compact">
                <span class="search-icon">🔍</span>
                <input
                    type="text"
                    name="search"
                    placeholder="Search products..."
                    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                    <a href="index.php?page=products" class="clear-btn">✕</a>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>
</section>

<!-- Category Tabs (Horizontal Scrollable) -->
<section class="category-tabs-section">
    <div class="container">
        <div class="tabs-wrapper">
            <div class="tabs-scroll">
                <!-- All Products Tab -->
                <a href="index.php?page=products"
                    class="tab-item <?php echo !isset($_GET['category']) && !isset($_GET['filter']) ? 'active' : ''; ?>">
                    <span class="tab-icon">🛍️</span>
                    <span class="tab-label">All Products</span>
                </a>

                <!-- Featured Tab -->
                <a href="index.php?page=products&filter=featured"
                    class="tab-item <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'featured') ? 'active' : ''; ?>">
                    <span class="tab-icon">⭐</span>
                    <span class="tab-label">Featured</span>
                </a>

                <!-- Latest Tab -->
                <a href="index.php?page=products&filter=latest"
                    class="tab-item <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'latest') ? 'active' : ''; ?>">
                    <span class="tab-icon">🆕</span>
                    <span class="tab-label">Latest</span>
                </a>

                <!-- Category Tabs -->
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <a href="index.php?page=products&category=<?php echo $category['id']; ?>"
                            class="tab-item <?php echo (isset($selectedCategory) && $selectedCategory == $category['id']) ? 'active' : ''; ?>">
                            <span class="tab-icon">📦</span>
                            <span class="tab-label"><?php echo htmlspecialchars($category['name']); ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid - Compact -->
<section class="products-section-compact">
    <div class="container">
        <!-- Results Header -->
        <div class="results-header">
            <h2><?php echo htmlspecialchars($title ?? 'All Products'); ?></h2>
            <span class="results-count"><?php echo count($allProducts); ?> products</span>
        </div>

        <!-- Products Grid -->
        <div class="products-grid-compact">
            <?php if (!empty($allProducts)): ?>
                <?php foreach ($allProducts as $product): ?>
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
                                <span class="cart-text">Add to Cart</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products-found">
                    <div class="no-products-icon">📦</div>
                    <h3>No products found</h3>
                    <p>Try adjusting your search or browse all products</p>
                    <a href="index.php?page=products" class="btn btn-primary">View All Products</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>