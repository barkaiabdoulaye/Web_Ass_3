<?php
// views/admin/dashboard.php
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! Here's your platform overview.</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3><?php echo $userStats['total_users']; ?></h3>
        <p>Total Users</p>
        <small>SMEs: <?php echo $userStats['total_smes']; ?> | Students: <?php echo $userStats['total_students']; ?> | Admins: <?php echo $userStats['total_admins']; ?></small>
    </div>
    <div class="stat-card">
        <h3><?php echo $projectStats['total_projects']; ?></h3>
        <p>Total Projects</p>
        <small>Active: <?php echo $projectStats['active_projects']; ?> | Completed: <?php echo $projectStats['completed_projects']; ?></small>
    </div>
</div>

<?php if(!empty($pendingApplications)): ?>
<div class="dashboard-section">
    <h3>Applications Ready for Team Assignment</h3>
    <div class="projects-list">
        <?php foreach($pendingApplications as $app): ?>
        <div class="project-card">
            <h4><?php echo htmlspecialchars($app['project_title']); ?></h4>
            <p><strong>Student:</strong> <?php echo htmlspecialchars($app['student_name']); ?></p>
            <p><strong>SME:</strong> <?php echo htmlspecialchars($app['sme_name']); ?></p>
            <div class="project-meta">
                <span class="status accepted">Accepted</span>
                <span>Applied: <?php echo date('M d, Y', strtotime($app['applied_at'])); ?></span>
            </div>
            <div style="margin-top: 1rem;">
                <a href="/sme_stack/index.php?url=admin/assignTeam/<?php echo $app['id']; ?>" class="btn btn-primary">Assign to Project</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="dashboard-section">
    <h3>Quick Actions</h3>
    <div class="dashboard-actions">
        <a href="/sme_stack/index.php?url=admin/projects" class="btn btn-primary">Manage All Projects</a>
        <a href="/sme_stack/index.php?url=admin/users" class="btn btn-secondary">Manage Users</a>
    </div>
</div>

<div class="dashboard-section">
    <h3>Recent Activity Log</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Time</th>
                <th>User</th>
                <th>Role</th>
                <th>Action</th>
                <th>Details</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($activityLog)): ?>
                <?php foreach($activityLog as $log): ?>
                <tr>
                    <td><?php echo date('Y-m-d H:i', strtotime($log['created_at'])); ?></td>
                    <td><?php echo htmlspecialchars($log['full_name'] ?: 'System'); ?></td>
                    <td><?php echo $log['role'] ?: 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($log['action']); ?></td>
                    <td><?php echo htmlspecialchars($log['details']); ?></td>
                    <td><?php echo $log['ip_address']; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No activity logs found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
include 'includes/footer.php';
?>