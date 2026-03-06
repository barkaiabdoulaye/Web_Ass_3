<?php
// views/admin/view_project.php
include 'includes/header.php';

$milestones = ['ui_design', 'frontend', 'backend', 'testing', 'delivered'];
$current_index = array_search($project['current_milestone'], $milestones);
$progress = (($current_index + 1) / count($milestones)) * 100;
?>

<div class="project-details">
    <h2>Project Details: <?php echo htmlspecialchars($project['title']); ?></h2>
    
    <div class="project-card" style="margin-bottom: 2rem;">
        <div class="project-meta">
            <p><strong>SME:</strong> <?php echo htmlspecialchars($project['sme_name']); ?> 
               (<?php echo htmlspecialchars($project['company_name']); ?>)</p>
            <p><strong>Student:</strong> <?php echo htmlspecialchars($project['student_name']); ?></p>
            <p><strong>Status:</strong> <span class="status <?php echo $project['status']; ?>"><?php echo ucfirst($project['status']); ?></span></p>
            <p><strong>Started:</strong> <?php echo date('F d, Y', strtotime($project['started_at'])); ?></p>
            <?php if($project['completed_at']): ?>
                <p><strong>Completed:</strong> <?php echo date('F d, Y', strtotime($project['completed_at'])); ?></p>
            <?php endif; ?>
        </div>
        
        <h3>Progress</h3>
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
    </div>
    
    <div class="dashboard-actions">
        <a href="index.php?url=admin/projects" class="btn btn-secondary">Back to Projects</a>
        <a href="index.php?url=admin/dashboard" class="btn btn-primary">Dashboard</a>
    </div>
</div>

<?php
include 'includes/footer.php';
?>