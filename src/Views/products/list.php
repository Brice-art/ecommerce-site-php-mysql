<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="search-container">
            <form method="GET" action="index.php" class="search-form">
                <input type="hidden" name="page" value="products">

                <div class="search-input-wrapper">
                    <input
                        type="text"
                        name="search"
                        class="search-input"
                        placeholder="Search for products..."
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                        autofocus>
                </div>

                <button type="submit" class="search-btn">
                    🔍 Search
                </button>

                <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                    <a href="index.php?page=products" class="clear-search">
                        ✕ Clear
                    </a>
                <?php endif; ?>
            </form>

            <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                <div class="search-results-info">
                    Found <strong><?php echo count($allProducts); ?></strong>
                    results for "<?php echo htmlspecialchars($_GET['search']); ?>"
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Products with Sidebar -->
<section class="products-with-sidebar">
    <div class="container">
        <div class="products-layout">
            <!-- Category Sidebar -->
            <aside class="category-sidebar">
                <h3>Categories</h3>
                <ul class="category-list">
                    <li>
                        <a href="index.php?page=products" 
                           class="category-link <?php echo !isset($_GET['category']) ? 'active' : ''; ?>">
                            All Products
                        </a>
                    </li>
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="index.php?page=products&category=<?php echo $category['id']; ?>" 
                               class="category-link <?php echo (isset($selectedCategory) && $selectedCategory == $category['id']) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <!-- Products Grid -->
            <div class="products-main">
                <div class="products-header">
                    <h2><?php echo htmlspecialchars($title); ?></h2>
                    <p class="products-count"><?php echo count($allProducts); ?> products found</p>
                </div>

                <div class="products-grid">
                    <?php if (!empty($allProducts)): ?>
                        <?php foreach ($allProducts as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <?php if ($product['main_image']): ?>
                                        <img src="images/products/<?php echo htmlspecialchars($product['main_image']); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <?php else: ?>
                                        <div class="no-image">No Image</div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="category"><?php echo htmlspecialchars($product['category_name']); ?></p>
                                    <div class="price">
                                        <?php if ($product['compare_price']): ?>
                                            <span class="old-price">$<?php echo number_format($product['compare_price'], 2); ?></span>
                                        <?php endif; ?>
                                        <span class="current-price">$<?php echo number_format($product['price'], 2); ?></span>
                                    </div>
                                    <div class="product-actions">
                                        <a href="index.php?page=product&id=<?php echo $product['id']; ?>" class="btn btn-secondary">
                                            View Details
                                        </a>
                                        <button class="btn btn-primary" onclick="addToCart(<?php echo $product['id']; ?>)">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-products">
                            <p>No products available at the moment.</p>
                            <a href="index.php?page=products" class="btn btn-primary">View All Products</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>