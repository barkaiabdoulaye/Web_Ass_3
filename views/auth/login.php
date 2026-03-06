<?php
// views/auth/login.php
include 'includes/header.php';
?>

<div class="auth-container">
    <h2>Login to Musanze Skill-Share</h2>
    
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="/sme_stack/index.php?url=auth/login" class="auth-form">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
    
    <p class="auth-link">Don't have an account? <a href="/sme_stack/index.php?url=auth/register">Register here</a></p>
</div>

<?php
include 'includes/footer.php';
?>