<?php
// views/student/dashboard.php
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h2>Student Dashboard</h2>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3><?php echo $stats['pending']; ?></h3>
        <p>Pending Applications</p>
    </div>
    <div class="stat-card">
        <h3><?php echo $stats['accepted']; ?></h3>
        <p>Accepted Applications</p>
    </div>
    <div class="stat-card">
        <h3><?php echo $stats['active']; ?></h3>
        <p>Active Projects</p>
    </div>
    <div class="stat-card">
        <h3><?php echo $stats['completed']; ?></h3>
        <p>Completed Projects</p>
    </div>
</div>

<div class="dashboard-actions">
    <a href="/sme_stack/index.php?url=student/browseRequests" class="btn btn-primary">🔍 Browse Projects</a>
</div>

<?php if(!empty($projects)): ?>
<div class="dashboard-section">
    <h3>Your Active Projects</h3>
    <div class="projects-list">
        <?php foreach($projects as $project): 
            $milestones = ['ui_design', 'frontend', 'backend', 'testing', 'delivered'];
            $current_index = array_search($project['current_milestone'], $milestones);
            $progress = (($current_index + 1) / count($milestones)) * 100;
        ?>
        <div class="project-card">
            <h4><?php echo htmlspecialchars($project['title']); ?></h4>
            <p><strong>SME:</strong> <?php echo htmlspecialchars($project['sme_name']); ?> 
               (<?php echo htmlspecialchars($project['company_name']); ?>)</p>
            
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
                </div>
                <div class="progress-stages">
                    <?php foreach($milestones as $index => $milestone): 
                        $status = '';
                        if ($index < $current_index) $status = 'completed';
                        elseif ($index == $current_index) $status = 'active';
                    ?>
                    <span class="stage <?php echo $status; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $milestone)); ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="project-actions" style="margin-top: 1rem;">
                <a href="/sme_stack/index.php?url=student/updateProgress/<?php echo $project['id']; ?>" class="btn btn-primary">Update Progress</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="dashboard-section">
    <h3>Your Recent Applications</h3>
    <?php if(!empty($applications)): ?>
        <div class="applications-list">
            <?php foreach(array_slice($applications, 0, 5) as $app): ?>
            <div class="project-card">
                <h4><?php echo htmlspecialchars($app['title']); ?></h4>
                <p><strong>SME:</strong> <?php echo htmlspecialchars($app['sme_name']); ?></p>
                <p><strong>Budget:</strong> <?php echo htmlspecialchars($app['budget_range']); ?></p>
                <div class="project-meta">
                    <span class="status <?php echo $app['status']; ?>">
                        Status: <?php echo ucfirst($app['status']); ?>
                    </span>
                    <span>Applied: <?php echo date('M d, Y', strtotime($app['applied_at'])); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-data">You haven't applied to any projects yet. <a href="/sme_stack/index.php?url=student/browseRequests">Browse available projects!</a></p>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>