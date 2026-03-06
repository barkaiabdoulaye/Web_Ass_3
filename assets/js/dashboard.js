// assets/js/dashboard.js

// Calculate progress percentage
function calculateProgress(currentStage) {
    const stages = ['ui_design', 'frontend', 'backend', 'testing', 'delivered'];
    const currentIndex = stages.indexOf(currentStage);
    
    if (currentIndex === -1) return 0;
    return ((currentIndex + 1) / stages.length) * 100;
}

// Update all progress bars
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach(bar => {
        const currentStage = bar.dataset.currentStage;
        if (currentStage) {
            const percentage = calculateProgress(currentStage);
            const fill = bar.querySelector('.progress-fill');
            if (fill) {
                fill.style.width = percentage + '%';
            }
        }
    });
    
    // Simulate real-time updates (polling every 10 seconds)
    setupRealTimeUpdates();
});

// Real-time updates simulation
function setupRealTimeUpdates() {
    console.log('Real-time updates enabled - checking for changes every 10s');
    
    setInterval(function() {
        // This would normally check for updates via AJAX
        // For now, just log that we're polling
        console.log('Polling for updates...');
    }, 10000);
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.animation = 'slideIn 0.3s ease';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Form validation
function validateProjectForm(formData) {
    const errors = [];
    
    if (!formData.title || formData.title.length < 5) {
        errors.push('Title must be at least 5 characters long');
    }
    
    if (!formData.description || formData.description.length < 20) {
        errors.push('Description must be at least 20 characters long');
    }
    
    if (!formData.budget_range) {
        errors.push('Please specify a budget range');
    }
    
    return errors;
}

// Confirm actions
function confirmAction(message) {
    return confirm(message || 'Are you sure you want to perform this action?');
}

// Filter projects
function filterProjects(category) {
    const projects = document.querySelectorAll('.project-card');
    
    projects.forEach(project => {
        if (category === 'all' || project.dataset.category === category) {
            project.style.display = 'block';
        } else {
            project.style.display = 'none';
        }
    });
}

// Search functionality
function searchProjects(query) {
    const projects = document.querySelectorAll('.project-card');
    const searchTerm = query.toLowerCase();
    
    projects.forEach(project => {
        const title = project.querySelector('h4')?.textContent.toLowerCase() || '';
        const description = project.querySelector('p')?.textContent.toLowerCase() || '';
        
        if (title.includes(searchTerm) || description.includes(searchTerm)) {
            project.style.display = 'block';
        } else {
            project.style.display = 'none';
        }
    });
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

// Export functions for use in other files
window.showNotification = showNotification;
window.confirmAction = confirmAction;
window.filterProjects = filterProjects;
window.searchProjects = searchProjects;