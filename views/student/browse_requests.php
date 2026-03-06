<?php
// views/student/browse_requests.php
include 'includes/header.php';

// Get unique budget ranges for filter
$budget_options = ['50,000 - 100,000 RWF', '100,000 - 200,000 RWF', '200,000 - 500,000 RWF', '500,000 - 1,000,000 RWF', '1,000,000+ RWF'];
?>

<div class="browse-container">
    <h2>Browse Available Projects</h2>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <!-- Search and Filter -->
    <div class="filter-section" style="background: white; padding: 1.5rem; border-radius: var(--border-radius); margin-bottom: 2rem;">
        <form method="GET" action="/sme_stack/index.php?url=student/browseRequests" class="filter-form" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div class="form-group" style="flex: 2; min-width: 200px;">
                <input type="text" name="search" placeholder="Search by title, description, or skills..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="width: 100%; padding: 0.8rem;">
            </div>
            
            <div class="form-group" style="flex: 1; min-width: 150px;">
                <select name="budget" style="width: 100%; padding: 0.8rem;">
                    <option value="">All Budgets</option>
                    <?php foreach($budget_options as $budget_option): ?>
                        <option value="<?php echo $budget_option; ?>" 
                                <?php echo (isset($_GET['budget']) && $_GET['budget'] == $budget_option) ? 'selected' : ''; ?>>
                            <?php echo $budget_option; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Apply Filters</button>
            <a href="/sme_stack/index.php?url=student/browseRequests" class="btn btn-secondary">Clear</a>
        </form>
    </div>
    
    <!-- Results -->
    <?php if(!empty($requests)): ?>
        <div class="projects-list">
            <?php foreach($requests as $request): ?>
            <div class="project-card">
                <h4><?php echo htmlspecialchars($request['title']); ?></h4>
                <p><strong>SME:</strong> <?php echo htmlspecialchars($request['company_name'] ?: $request['sme_name']); ?></p>
                <p><?php echo htmlspecialchars(substr($request['description'], 0, 200)); ?>...</p>
                
                <div class="project-meta">
                    <span class="status open">Open</span>
                    <span>💰 <?php echo htmlspecialchars($request['budget_range']); ?></span>
                    <span>🔧 Skills: <?php echo htmlspecialchars($request['required_skills']); ?></span>
                    <span>📅 Posted: <?php echo date('M d, Y', strtotime($request['created_at'])); ?></span>
                </div>
                
                <?php if(in_array($request['id'], $applied_request_ids)): ?>
                    <div style="margin-top: 1rem;">
                        <span class="status pending">Already Applied</span>
                    </div>
                <?php else: ?>
                    <div style="margin-top: 1rem;">
                        <a href="/sme_stack/index.php?url=student/apply/<?php echo $request['id']; ?>" class="btn btn-primary">Apply Now</a>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <p>No open projects found matching your criteria.</p>
            <p><a href="/sme_stack/index.php?url=student/browseRequests">Clear filters</a> to see all available projects.</p>
        </div>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>