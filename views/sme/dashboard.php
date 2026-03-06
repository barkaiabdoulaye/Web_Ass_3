<?php
// views/sme/dashboard.php
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h2>SME Dashboard</h2>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3><?php echo $stats['open_requests']; ?></h3>
        <p>Open Requests</p>
    </div>
    <div class="stat-card">
        <h3><?php echo $stats['active_projects']; ?></h3>
        <p>Active Projects</p>
    </div>
    <div class="stat-card">
        <h3><?php echo $stats['completed_projects']; ?></h3>
        <p>Completed Projects</p>
    </div>
</div>

<div class="dashboard-actions">
    <a href="/sme_stack/index.php?url=sme/postRequest" class="btn btn-primary">➕ Post New Request</a>
    <a href="/sme_stack/index.php?url=sme/viewApplications" class="btn btn-secondary">📋 View Applications</a>
</div>

<div class="dashboard-section">
    <h3>Your Recent Requests</h3>
    <?php if(!empty($requests)): ?>
        <div class="projects-list">
            <?php foreach($requests as $request): ?>
            <div class="project-card">
                <h4><?php echo htmlspecialchars($request['title']); ?></h4>
                <p><?php echo htmlspecialchars(substr($request['description'], 0, 150)); ?>...</p>
                <div class="project-meta">
                    <span class="status <?php echo $request['status']; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $request['status'])); ?>
                    </span>
                    <span>💰 Budget: <?php echo htmlspecialchars($request['budget_range']); ?></span>
                    <span>📊 Applications: <?php echo $request['applications_count'] ?? 0; ?></span>
                    <span>📅 Posted: <?php echo date('M d, Y', strtotime($request['created_at'])); ?></span>
                </div>
                <div class="project-actions" style="margin-top: 1rem;">
                    <a href="/sme_stack/index.php?url=sme/editRequest/<?php echo $request['id']; ?>" class="btn btn-secondary">Edit</a>
                    <?php if($request['status'] == 'open'): ?>
                    <a href="/sme_stack/index.php?url=sme/viewApplications/<?php echo $request['id']; ?>" class="btn btn-primary">View Applications</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-data">You haven't posted any requests yet. <a href="/sme_stack/index.php?url=sme/postRequest">Post your first request!</a></p>
    <?php endif; ?>
</div>

<?php if(!empty($projects)): ?>
<div class="dashboard-section">
    <h3>Active Projects</h3>
    <div class="projects-list">
        <?php foreach($projects as $project): 
            $milestones = ['ui_design', 'frontend', 'backend', 'testing', 'delivered'];
            $current_index = array_search($project['current_milestone'], $milestones);
            $progress = (($current_index + 1) / count($milestones)) * 100;
        ?>
        <div class="project-card">
            <h4><?php echo htmlspecialchars($project['title']); ?></h4>
            <p><strong>Student:</strong> <?php echo htmlspecialchars($project['student_name']); ?></p>
            
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
                <a href="/sme_stack/index.php?url=sme/approveMilestone/<?php echo $project['id']; ?>" class="btn btn-success">✓ Approve Milestone</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php
include 'includes/footer.php';
?>