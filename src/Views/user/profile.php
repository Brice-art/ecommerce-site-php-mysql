<?php
// Expecting a `$user` associative array and optional `$orders` array provided by controller
// Minimal profile UI with inline styles (per request)
?>
<div class="profile-container">
    <style>
    .profile-container{max-width:980px;margin:24px auto;padding:18px;background:#fff;border:1px solid #e6e6e6;border-radius:8px;font-family:Arial,Helvetica,sans-serif}
    .profile-grid{display:flex;gap:24px;align-items:flex-start}
    .profile-card{flex:0 0 260px;padding:18px;border:1px solid #f0f0f0;border-radius:6px;text-align:center}
    .avatar{width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #f3f3f3;margin-bottom:12px}
    .profile-info{flex:1}
    .profile-info h2{margin:0 0 8px;font-size:20px}
    .profile-info p{margin:4px 0;color:#555}
    .profile-actions{margin-top:12px;display:flex;gap:8px;justify-content:center}
    .btn{display:inline-block;padding:8px 12px;border-radius:6px;border:1px solid transparent;background:#1976d2;color:#fff;text-decoration:none}
    .btn.secondary{background:#f5f5f5;color:#222;border-color:#ddd}
    .section{margin-top:22px}
    .section h3{margin:0 0 8px;font-size:16px}
    .orders{border-top:1px dashed #eee;padding-top:12px}
    .order-item{padding:10px 0;border-bottom:1px solid #fafafa;display:flex;justify-content:space-between}
    .muted{color:#888;font-size:13px}
    @media(max-width:700px){.profile-grid{flex-direction:column}.profile-card{flex:unset}}
    </style>

    <div class="profile-grid">
        <div class="profile-card">
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="avatar" class="avatar">
            <?php else: ?>
                <div style="width:120px;height:120px;border-radius:50%;background:#f0f0f0;display:inline-flex;align-items:center;justify-content:center;font-size:32px;color:#aaa"><?= isset($user['name']) ? strtoupper(substr($user['name'],0,1)) : 'U' ?></div>
            <?php endif; ?>
            <h3 style="margin-top:12px;">Account</h3>
            <p class="muted">Member since: <?= isset($user['created_at']) ? htmlspecialchars($user['created_at']) : '—' ?></p>
            <div class="profile-actions">
                <a href="index.php?page=user&action=edit" class="btn">Edit Profile</a>
                <a href="index.php?page=user&action=change_password" class="btn secondary">Change Password</a>
            </div>
        </div>

        <div class="profile-info">
            <h2><?= isset($user['last_name']) ? htmlspecialchars($user['first_name'] . ' '. $user['last_name']) : 'Your Name' ?></h2>
            <p><strong>Email:</strong> <?= isset($user['email']) ? htmlspecialchars($user['email']) : '—' ?></p>
            <p><strong>Phone:</strong> <?= isset($user['phone']) ? htmlspecialchars($user['phone']) : '—' ?></p>

            <div class="section">
                <h3>Shipping Address</h3>
                <p class="muted"><?= isset($user['address']) && $user['address'] ? nl2br(htmlspecialchars($user['address'])) : 'No shipping address saved.' ?></p>
            </div>

            <div class="section">
                <h3>Recent Orders</h3>
                <div class="orders">
                    <?php if (!empty($orders) && is_array($orders)): ?>
                        <?php foreach ($orders as $o): ?>
                            <div class="order-item">
                                <div>
                                    <div><strong>Order #<?= htmlspecialchars($o['id'] ?? $o['order_number'] ?? '—') ?></strong></div>
                                    <div class="muted">Placed: <?= htmlspecialchars($o['created_at'] ?? '—') ?></div>
                                </div>
                                <div style="text-align:right">
                                    <div class="muted">Total: <?= htmlspecialchars($o['total'] ?? '—') ?></div>
                                    <div style="margin-top:6px"><a href="index.php?page=orders&action=view&id=<?= urlencode($o['id'] ?? '') ?>" class="btn secondary">View</a></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="muted">You have no recent orders.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
