<!-- Admin Create Product -->
<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <div>
                <h1>➕ Add New Product</h1>
                <p class="admin-subtitle">Fill in the product details below</p>
            </div>
            <a href="index.php?page=admin&action=products" class="btn btn-secondary">
                ← Back to Products
            </a>
        </div>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=admin&action=create-product" class="admin-form">
            <div class="admin-card">
                <div class="card-header">
                    <h3>Basic Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($name ?? ''); ?>"
                                required
                                placeholder="e.g., Wireless Headphones">
                        </div>

                        <div class="form-group">
                            <label for="slug">URL Slug *</label>
                            <input
                                type="text"
                                id="slug"
                                name="slug"
                                class="form-control"
                                value="<?php echo htmlspecialchars($slug ?? ''); ?>"
                                placeholder="e.g., wireless-headphones">
                            <small class="form-text">Used in product URL</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            rows="4"
                            placeholder="Enter product description..."><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php
                                $categoryModel = new Category();
                                $categories = $categoryModel->getAllCategories();
                                foreach ($categories as $cat):
                                ?>
                                    <option value="<?php echo $cat['id']; ?>"
                                        <?php echo (isset($category) && $category == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input
                                type="text"
                                id="sku"
                                name="sku"
                                class="form-control"
                                value="<?php echo htmlspecialchars($sku ?? ''); ?>"
                                placeholder="e.g., WH-001">
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-header">
                    <h3>Pricing</h3>
                </div>
                <div class="card-body">
                    <div class="form-row form-row-3">
                        <div class="form-group">
                            <label for="price">Selling Price *</label>
                            <div class="input-group">
                                <span class="input-prefix">$</span>
                                <input
                                    type="number"
                                    id="price"
                                    name="price"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($price ?? ''); ?>"
                                    step="0.01"
                                    min="0"
                                    required
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="compare_price">Compare at Price</label>
                            <div class="input-group">
                                <span class="input-prefix">$</span>
                                <input
                                    type="number"
                                    id="compare_price"
                                    name="compare_price"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($compare_price ?? ''); ?>"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00">
                            </div>
                            <small class="form-text">Original price (for discounts)</small>
                        </div>

                        <div class="form-group">
                            <label for="cost_price">Cost per Item *</label>
                            <div class="input-group">
                                <span class="input-prefix">$</span>
                                <input
                                    type="number"
                                    id="cost_price"
                                    name="cost_price"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($cost_price ?? ''); ?>"
                                    step="0.01"
                                    min="0"
                                    required
                                    placeholder="0.00">
                            </div>
                            <small class="form-text">Your cost</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-header">
                    <h3>Inventory & Shipping</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantity">Quantity *</label>
                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                class="form-control"
                                value="<?php echo htmlspecialchars($quantity ?? ''); ?>"
                                min="0"
                                required
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for="weight">Weight (kg)</label>
                            <input
                                type="number"
                                id="weight"
                                name="weight"
                                class="form-control"
                                value="<?php echo htmlspecialchars($weight ?? ''); ?>"
                                step="0.01"
                                min="0"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-header">
                    <h3>Product Image</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="main_image">Image Filename</label>
                        <input
                            type="text"
                            id="main_image"
                            name="main_image"
                            class="form-control"
                            value="<?php echo htmlspecialchars($main_image ?? ''); ?>"
                            placeholder="e.g., product-1.jpg">
                        <small class="form-text">Upload image to public/images/products/ folder first, then enter filename here</small>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-header">
                    <h3>Product Options</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input
                                    type="checkbox"
                                    name="is_featured"
                                    value="1"
                                    <?php echo (isset($is_featured) && $is_featured) ? 'checked' : ''; ?>>
                                <span>Featured Product</span>
                            </label>
                            <small class="form-text">Show on homepage</small>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    <?php echo (!isset($is_active) || $is_active) ? 'checked' : ''; ?>>
                                <span>Active</span>
                            </label>
                            <small class="form-text">Visible to customers</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">
                    ✓ Create Product
                </button>
                <a href="index.php?page=admin&action=products" class="btn btn-secondary btn-large">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</section>