<!-- Admin Products List -->
<?php
// Success message
if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php
        echo htmlspecialchars($_SESSION['success_message']);
        unset($_SESSION['success_message']);
        ?>
    </div>
<?php endif; ?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <div>
                <h1>📦 Products Management</h1>
                <p class="admin-subtitle"><?php echo count($allProducts); ?> total products</p>
            </div>
            <a href="index.php?page=admin&action=create-product" class="btn btn-primary">
                ➕ Add New Product
            </a>
        </div>

        <!-- Products Table -->
        <div class="admin-card">
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($allProducts)): ?>
                            <?php foreach ($allProducts as $product): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td>
                                        <?php if (!empty($product['main_image'])): ?>
                                            <img src="images/products/<?php echo htmlspecialchars($product['main_image']); ?>"
                                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                class="table-image">
                                        <?php else: ?>
                                            <div class="table-no-image">No img</div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                        <br>
                                        <small class="text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <strong>$<?php echo number_format($product['price'], 2); ?></strong>
                                        <?php if ($product['compare_price']): ?>
                                            <br><small class="text-muted strikethrough">$<?php echo number_format($product['compare_price'], 2); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($product['quantity'] > 10): ?>
                                            <span class="stock-badge stock-high"><?php echo $product['quantity']; ?></span>
                                        <?php elseif ($product['quantity'] > 0): ?>
                                            <span class="stock-badge stock-low"><?php echo $product['quantity']; ?></span>
                                        <?php else: ?>
                                            <span class="stock-badge stock-out">Out of Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($product['is_active']): ?>
                                            <span class="status-badge status-active">Active</span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="index.php?page=admin&action=edit-product&id=<?php echo $product['id']; ?>"
                                                class="btn-action btn-edit" title="Edit">
                                                ✏️
                                            </a>
                                            <a href="index.php?page=admin&action=delete-product&id=<?php echo $product['id']; ?>"
                                                class="btn-action btn-delete"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this product?');">
                                                🗑️
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="empty-message">No products found. <a href="index.php?page=admin&action=create-product">Add your first product</a></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>