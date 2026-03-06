<?php
// views/sme/view_applications.php
include 'includes/header.php';
?>

<div class="applications-container">
    <h2>View Applications</h2>
    
    <div class="form-group" style="margin-bottom: 2rem;">
        <label for="request_select">Select Project Request:</label>
        <select id="request_select" onchange="if(this.value) window.location.href='/sme_stack/index.php?url=sme/viewApplications/'+this.value">
            <option value="">Choose a request...</option>
            <?php foreach($requests as $req): ?>
                <option value="<?php echo $req['id']; ?>" <?php echo (isset($request_id) && $request_id == $req['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($req['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <?php if(isset($request_id) && isset($applications)): ?>
        <h3>Applications for Selected Request</h3>
        
        <?php if(!empty($applications)): ?>
            <div class="applications-list">
                <?php foreach($applications as $app): ?>
                <div class="project-card">
                    <h4><?php echo htmlspecialchars($app['full_name']); ?></h4>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($app['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($app['phone']); ?></p>
                    <div class="application-message">
                        <strong>Message:</strong>
                        <p><?php echo nl2br(htmlspecialchars($app['message'])); ?></p>
                    </div>
                    <div class="project-meta">
                        <span class="status <?php echo $app['status']; ?>">
                            Status: <?php echo ucfirst($app['status']); ?>
                        </span>
                        <span>Applied: <?php echo date('M d, Y', strtotime($app['applied_at'])); ?></span>
                    </div>
                    
                    <?php if($app['status'] == 'pending'): ?>
                    <div class="application-actions" style="margin-top: 1rem; display: flex; gap: 1rem;">
                        <form method="POST" action="/sme_stack/index.php?url=sme/updateApplicationStatus">
                            <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                            <input type="hidden" name="action" value="accept">
                            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Accept this application?')">✓ Accept</button>
                        </form>
                        <form method="POST" action="/sme_stack/index.php?url=sme/updateApplicationStatus">
                            <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                            <input type="hidden" name="action" value="reject">
                            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this application?')">✗ Reject</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-data">No applications received for this request yet.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>