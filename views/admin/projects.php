<?php
// views/admin/projects.php
include 'includes/header.php';

$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
?>

<div class="projects-management">
    <h2>Manage All Projects</h2>
    
    <!-- Filters -->
    <div class="filter-section" style="margin: 2rem 0;">
        <form method="GET" action="/sme_stack/index.php?url=admin/projects" style="display: flex; gap: 1rem; align-items: center;">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All Projects</option>
                <option value="active" <?php echo $status_filter == 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </form>
    </div>
    
    <!-- Projects Table -->
    <?php if(!empty($projects)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Project Title</th>
                    <th>SME</th>
                    <th>Student</th>
                    <th>Current Milestone</th>
                    <th>Status</th>
                    <th>Started</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($projects as $project): ?>
                <tr>
                    <td>#<?php echo $project['id']; ?></td>
                    <td><?php echo htmlspecialchars($project['project_title']); ?></td>
                    <td><?php echo htmlspecialchars($project['sme_name']); ?><br>
                        <small><?php echo htmlspecialchars($project['company_name']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($project['student_name']); ?></td>
                    <td><?php echo ucfirst(str_replace('_', ' ', $project['current_milestone'])); ?></td>
                    <td>
                        <span class="status <?php echo $project['status']; ?>">
                            <?php echo ucfirst($project['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('Y-m-d', strtotime($project['started_at'])); ?></td>
                    <td>
                        <a href="/sme_stack/index.php?url=admin/viewProject/<?php echo $project['id']; ?>" class="btn btn-small btn-primary">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">No projects found.</p>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>