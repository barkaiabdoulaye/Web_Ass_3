<?php
// views/home.php
include 'includes/header.php';
?>

<div class="hero-section">
    <h1>Welcome to Musanze Skill-Share</h1>
    <p class="hero-subtitle">Bridging the gap between local SMEs and talented students at INES Ruhengeri</p>
    
    <div class="stats-container">
        <div class="stat-card">
            <h3><?php echo $stats['smes']; ?></h3>
            <p>Local SMEs</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $stats['students']; ?></h3>
            <p>Student Developers</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $stats['active_projects']; ?></h3>
            <p>Active Projects</p>
        </div>
    </div>
    
    <div class="cta-buttons">
        <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="/sme_stack/index.php?url=auth/register" class="btn btn-primary">Get Started</a>
            <a href="/sme_stack/index.php?url=auth/login" class="btn btn-secondary">Login</a>
        <?php else: ?>
            <a href="/sme_stack/index.php?url=dashboard" class="btn btn-primary">Go to Dashboard</a>
        <?php endif; ?>
    </div>
</div>

<div class="features-section">
    <h2>How It Works</h2>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">📋</div>
            <h3>1. Post Your Need</h3>
            <p>SMEs post their digital project requirements with details about budget and timeline</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">👥</div>
            <h3>2. Students Apply</h3>
            <p>Talented students from INES review and apply to projects matching their skills</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🚀</div>
            <h3>3. Collaborate & Build</h3>
            <p>Work together through structured milestones from design to delivery</p>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>