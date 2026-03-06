<?php
// views/student/apply.php
include 'includes/header.php';
?>

<div class="apply-container" style="max-width: 800px; margin: 2rem auto;">
    <h2>Apply for Project</h2>
    
    <div class="project-card" style="margin-bottom: 2rem;">
        <h3><?php echo htmlspecialchars($request['title']); ?></h3>
        <p><strong>SME:</strong> <?php echo htmlspecialchars($request['company_name'] ?: $request['sme_name']); ?></p>
        <p><strong>Budget Range:</strong> <?php echo htmlspecialchars($request['budget_range']); ?></p>
        <p><strong>Required Skills:</strong> <?php echo htmlspecialchars($request['required_skills']); ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($request['description'])); ?></p>
    </div>
    
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
            <p style="margin-top: 1rem;"><a href="/sme_stack/index.php?url=student/dashboard" class="btn btn-primary">Go to Dashboard</a></p>
        </div>
    <?php else: ?>
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="message">Why are you interested in this project?</label>
                <textarea id="message" name="message" rows="5" required 
                          placeholder="Describe your relevant skills, experience, and why you're a good fit..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Submit Application</button>
            <a href="/sme_stack/index.php?url=student/browseRequests" class="btn btn-secondary btn-block">Cancel</a>
        </form>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>