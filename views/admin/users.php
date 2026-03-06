<?php
// views/admin/users.php
include 'includes/header.php';
?>

<div class="admin-container">
    <h2>Manage Users</h2>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Company</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td>#<?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <span class="status <?php echo $user['role']; ?>">
                        <?php echo ucfirst($user['role']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($user['company_name'] ?: '-'); ?></td>
                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                <td>
                    <span class="status <?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                <td>
                    <a href="/sme_stack/index.php?url=admin/toggleUser/<?php echo $user['id']; ?>" 
                       class="btn btn-small <?php echo $user['is_active'] ? 'btn-warning' : 'btn-success'; ?>"
                       onclick="return confirm('Toggle user status?')">
                        <?php echo $user['is_active'] ? 'Disable' : 'Enable'; ?>
                    </a>
                    
                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                    <a href="/sme_stack/index.php?url=admin/deleteUser/<?php echo $user['id']; ?>" 
                       class="btn btn-small btn-danger"
                       onclick="return confirm('Delete this user? This action cannot be undone.')">
                        Delete
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include 'includes/footer.php';
?>