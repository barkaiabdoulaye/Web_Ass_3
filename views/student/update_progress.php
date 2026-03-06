<?php
// views/student/update_progress.php
include 'includes/header.php';

$milestones = ['ui_design', 'frontend', 'backend', 'testing', 'delivered'];
$current_index = array_search($project['current_milestone'], $milestones);
$progress = (($current_index + 1) / count($milestones)) * 100;

$tasks = [
    'ui_design' => ['Create wireframes', 'Design UI mockups', 'Get client approval'],
    'frontend' => ['Set up HTML structure', 'Implement CSS styling', 'Add JavaScript interactivity'],
    'backend' => ['Set up database', 'Create API endpoints', 'Implement business logic'],
    'testing' => ['Test all features', 'Fix bugs', 'Performance optimization'],
    'delivered' => ['Deploy application', 'Train client', 'Hand over documentation']
];
?>

<div class="progress-container" style="max-width: 800px; margin: 2rem auto;">
    <h2>Update Project Progress</h2>
    
    <div class="project-card" style="margin-bottom: 2rem;">
        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
        <p><strong>SME:</strong> <?php echo htmlspecialchars($project['sme_name']); ?></p>
        
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
        
        <div style="margin-top: 1rem;">
            <p><strong>Current Stage:</strong> <?php echo ucfirst(str_replace('_', ' ', $project['current_milestone'])); ?></p>
        </div>
    </div>
    
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if($project['current_milestone'] != 'delivered'): ?>
    <form method="POST" class="auth-form">
        <div class="form-group">
            <label for="progress_update">Progress Update</label>
            <textarea id="progress_update" name="progress_update" rows="4" required 
                      placeholder="Describe what you've completed and any challenges..."></textarea>
        </div>
        
        <div class="form-group">
            <label>Current Milestone Tasks:</label>
            <ul style="margin-left: 2rem; color: #666;">
                <?php if(isset($tasks[$project['current_milestone']])): ?>
                    <?php foreach($tasks[$project['current_milestone']] as $task): ?>
                        <li><?php echo $task; ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        
        <button type="submit" name="update_progress" class="btn btn-primary btn-block">Record Progress Update</button>
        <a href="/sme_stack/index.php?url=student/dashboard" class="btn btn-secondary btn-block">Back to Dashboard</a>
    </form>
    <?php else: ?>
    <div class="alert alert-success">
        <h3>Project Completed!</h3>
        <p>This project has been marked as delivered. Great work!</p>
        <a href="/sme_stack/index.php?url=student/dashboard" class="btn btn-primary">Back to Dashboard</a>
    </div>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>