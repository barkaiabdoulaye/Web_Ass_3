<?php
// views/sme/approve_milestone.php
include 'includes/header.php';

$milestones = ['ui_design', 'frontend', 'backend', 'testing', 'delivered'];
$current_index = array_search($project['current_milestone'], $milestones);
$progress = (($current_index + 1) / count($milestones)) * 100;
?>

<div class="milestone-container" style="max-width: 800px; margin: 2rem auto;">
    <h2>Approve Milestone</h2>
    
    <div class="project-card" style="margin-bottom: 2rem;">
        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
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
    </div>
    
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if($project['current_milestone'] != 'delivered'): ?>
    <div class="current-milestone" style="background: var(--light-bg); padding: 2rem; border-radius: var(--border-radius); margin-bottom: 2rem;">
        <h3>Current Milestone: <?php echo ucfirst(str_replace('_', ' ', $project['current_milestone'])); ?></h3>
        <p>Please review the work completed for this milestone. If you're satisfied, approve it to move to the next stage.</p>
        
        <form method="POST" onsubmit="return confirm('Are you sure you want to approve this milestone?')">
            <button type="submit" name="approve" value="1" class="btn btn-success">✓ Approve Milestone</button>
            <a href="/sme_stack/index.php?url=sme/dashboard" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>
    <?php else: ?>
    <div class="alert alert-success">
        <h3>Project Completed!</h3>
        <p>This project has been successfully delivered. Thank you for using Musanze Skill-Share!</p>
        <a href="/sme_stack/index.php?url=sme/dashboard" class="btn btn-primary">Back to Dashboard</a>
    </div>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>