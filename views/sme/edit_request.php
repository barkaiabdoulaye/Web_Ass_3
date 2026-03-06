<?php
// views/sme/edit_request.php
include 'includes/header.php';
?>

<div class="form-container" style="max-width: 800px; margin: 2rem auto;">
    <h2>Edit Project Request</h2>
    
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" class="auth-form">
        <div class="form-group">
            <label for="title">Project Title</label>
            <input type="text" id="title" name="title" required 
                   value="<?php echo htmlspecialchars($request['title']); ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Project Description</label>
            <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($request['description']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="required_skills">Required Skills</label>
            <input type="text" id="required_skills" name="required_skills" required 
                   value="<?php echo htmlspecialchars($request['required_skills']); ?>">
        </div>
        
        <div class="form-group">
            <label for="budget_range">Budget Range</label>
            <select id="budget_range" name="budget_range" required>
                <option value="50,000 - 100,000 RWF" <?php echo ($request['budget_range'] == '50,000 - 100,000 RWF') ? 'selected' : ''; ?>>50,000 - 100,000 RWF</option>
                <option value="100,000 - 200,000 RWF" <?php echo ($request['budget_range'] == '100,000 - 200,000 RWF') ? 'selected' : ''; ?>>100,000 - 200,000 RWF</option>
                <option value="200,000 - 500,000 RWF" <?php echo ($request['budget_range'] == '200,000 - 500,000 RWF') ? 'selected' : ''; ?>>200,000 - 500,000 RWF</option>
                <option value="500,000 - 1,000,000 RWF" <?php echo ($request['budget_range'] == '500,000 - 1,000,000 RWF') ? 'selected' : ''; ?>>500,000 - 1,000,000 RWF</option>
                <option value="1,000,000+ RWF" <?php echo ($request['budget_range'] == '1,000,000+ RWF') ? 'selected' : ''; ?>>1,000,000+ RWF</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="open" <?php echo ($request['status'] == 'open') ? 'selected' : ''; ?>>Open</option>
                <option value="in_progress" <?php echo ($request['status'] == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                <option value="completed" <?php echo ($request['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?php echo ($request['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Update Request</button>
        <a href="/sme_stack/index.php?url=sme/dashboard" class="btn btn-secondary btn-block">Cancel</a>
    </form>
</div>

<?php
include 'includes/footer.php';
?>