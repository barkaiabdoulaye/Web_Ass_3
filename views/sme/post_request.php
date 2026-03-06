<?php
// views/sme/post_request.php
include 'includes/header.php';
?>

<div class="form-container" style="max-width: 800px; margin: 2rem auto;">
    <h2>Post a New Project Request</h2>
    
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
            <p style="margin-top: 1rem;"><a href="/sme_stack/index.php?url=sme/dashboard" class="btn btn-primary">Back to Dashboard</a></p>
        </div>
    <?php endif; ?>
    
    <?php if(!isset($success) || !$success): ?>
    <form method="POST" action="/sme_stack/index.php?url=sme/postRequest" class="auth-form">
        <div class="form-group">
            <label for="title">Project Title</label>
            <input type="text" id="title" name="title" required 
                   placeholder="e.g., E-commerce Website for Local Crafts"
                   value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Project Description</label>
            <textarea id="description" name="description" rows="5" required 
                      placeholder="Describe your project needs in detail..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="required_skills">Required Skills</label>
            <input type="text" id="required_skills" name="required_skills" required 
                   placeholder="e.g., PHP, MySQL, JavaScript, HTML/CSS"
                   value="<?php echo isset($_POST['required_skills']) ? htmlspecialchars($_POST['required_skills']) : ''; ?>">
            <small>Separate skills with commas</small>
        </div>
        
        <div class="form-group">
            <label for="budget_range">Budget Range</label>
            <select id="budget_range" name="budget_range" required>
                <option value="">Select budget range</option>
                <option value="50,000 - 100,000 RWF" <?php echo (isset($_POST['budget_range']) && $_POST['budget_range'] == '50,000 - 100,000 RWF') ? 'selected' : ''; ?>>50,000 - 100,000 RWF</option>
                <option value="100,000 - 200,000 RWF" <?php echo (isset($_POST['budget_range']) && $_POST['budget_range'] == '100,000 - 200,000 RWF') ? 'selected' : ''; ?>>100,000 - 200,000 RWF</option>
                <option value="200,000 - 500,000 RWF" <?php echo (isset($_POST['budget_range']) && $_POST['budget_range'] == '200,000 - 500,000 RWF') ? 'selected' : ''; ?>>200,000 - 500,000 RWF</option>
                <option value="500,000 - 1,000,000 RWF" <?php echo (isset($_POST['budget_range']) && $_POST['budget_range'] == '500,000 - 1,000,000 RWF') ? 'selected' : ''; ?>>500,000 - 1,000,000 RWF</option>
                <option value="1,000,000+ RWF" <?php echo (isset($_POST['budget_range']) && $_POST['budget_range'] == '1,000,000+ RWF') ? 'selected' : ''; ?>>1,000,000+ RWF</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Post Request</button>
        <a href="/sme_stack/index.php?url=sme/dashboard" class="btn btn-secondary btn-block">Cancel</a>
    </form>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>