<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Welcome to MyStore</h1>
        <p>Discover amazing products at great prices</p>
        <a href="index.php?page=products" class="btn btn-primary">Shop Now</a>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <h2>Shop by Category</h2>
        <div class="categories-grid">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="category-card">
                        <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                        <p><?php echo htmlspecialchars($category['description'] ?? ''); ?></p>
                        <a href="index.php?page=products&category=<?php echo $category['id']; ?>">
                            Browse →
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No categories available.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="products-section">
    <div class="container">
        <h2>Featured Products</h2>
        <div class="products-grid">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
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
                <p>No featured products available.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Latest Products Section -->
<section class="products-section latest-products">
    <div class="container">
        <h2>Latest Products</h2>
        <div class="products-grid">
            <?php if (!empty($latestProducts)): ?>
                <?php foreach ($latestProducts as $product): ?>
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
            <?php endif; ?>
        </div>
    </div>
</section>