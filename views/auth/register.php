<?php
// views/auth/register.php
include 'includes/header.php';
?>

<div class="auth-container">
    <h2>Register for Musanze Skill-Share</h2>
    
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="/sme_stack/index.php?url=auth/register" class="auth-form" id="registerForm">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required 
                   value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                   placeholder="your.email@example.com">
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required 
                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                   placeholder="0788XXXXXX">
        </div>
        
        <div class="form-group">
            <label for="role">I am a:</label>
            <select name="role" id="role" required>
                <option value="">Select role</option>
                <option value="sme" <?php echo (isset($_POST['role']) && $_POST['role'] == 'sme') ? 'selected' : ''; ?>>SME Owner</option>
                <option value="student" <?php echo (isset($_POST['role']) && $_POST['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
            </select>
        </div>
        
        <div class="form-group" id="company-field" style="<?php echo (isset($_POST['role']) && $_POST['role'] == 'sme') ? 'display: block;' : 'display: none;'; ?>">
            <label for="company_name">Company Name</label>
            <input type="text" id="company_name" name="company_name"
                   value="<?php echo isset($_POST['company_name']) ? htmlspecialchars($_POST['company_name']) : ''; ?>">
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
    
    <p class="auth-link">Already have an account? <a href="/sme_stack/index.php?url=auth/login">Login here</a></p>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const companyField = document.getElementById('company-field');
    const companyInput = document.getElementById('company_name');
    
    if (this.value === 'sme') {
        companyField.style.display = 'block';
        companyInput.required = true;
    } else {
        companyField.style.display = 'none';
        companyInput.required = false;
    }
});

document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match!');
    }
});
</script>

<?php
include 'includes/footer.php';
?>