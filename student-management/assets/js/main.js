/**
 * Student Management System - Main JavaScript File
 */

// DOM Content Loaded Event
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initializeComponents();
    initializeEventListeners();
    initializeAnimations();
});

/**
 * Initialize all components
 */
function initializeComponents() {
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize form validations
    initializeFormValidation();
    
    // Initialize auto-hide alerts
    initializeAutoHideAlerts();
    
    // Initialize data tables enhancements
    initializeTableEnhancements();
}

/**
 * Initialize event listeners
 */
function initializeEventListeners() {
    // Form submission handling
    document.addEventListener('submit', handleFormSubmission);
    
    // Modal handling
    document.addEventListener('show.bs.modal', handleModalShow);
    document.addEventListener('hidden.bs.modal', handleModalHide);
    
    // Button click handling
    document.addEventListener('click', handleButtonClicks);
    
    // Input change handling
    document.addEventListener('change', handleInputChanges);
}

/**
 * Initialize animations
 */
function initializeAnimations() {
    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });
    
    // Add slide-in animation to navigation items
    const navItems = document.querySelectorAll('.nav-link');
    navItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        item.classList.add('slide-in');
    });
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
}

/**
 * Initialize auto-hide alerts
 */
function initializeAutoHideAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // Auto-hide after 5 seconds
    });
}

/**
 * Initialize table enhancements
 */
function initializeTableEnhancements() {
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(0, 123, 255, 0.05)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}

/**
 * Handle form submissions
 */
function handleFormSubmission(event) {
    const form = event.target;
    const submitButton = form.querySelector('button[type="submit"]');
    
    if (submitButton && form.checkValidity()) {
        // Show loading state
        showLoadingState(submitButton);
        
        // Disable form to prevent double submission
        disableForm(form);
    }
}

/**
 * Handle modal show events
 */
function handleModalShow(event) {
    const modal = event.target;
    
    // Reset form if it exists
    const form = modal.querySelector('form');
    if (form) {
        form.reset();
        form.classList.remove('was-validated');
    }
    
    // Focus on first input
    const firstInput = modal.querySelector('input, select, textarea');
    if (firstInput) {
        setTimeout(() => firstInput.focus(), 100);
    }
}

/**
 * Handle modal hide events
 */
function handleModalHide(event) {
    const modal = event.target;
    
    // Re-enable any disabled forms
    const forms = modal.querySelectorAll('form');
    forms.forEach(form => enableForm(form));
}

/**
 * Handle button clicks
 */
function handleButtonClicks(event) {
    const button = event.target.closest('button, .btn');
    
    if (button && button.dataset.confirm) {
        event.preventDefault();
        const message = button.dataset.confirm;
        
        if (confirm(message)) {
            // If it's a form button, submit the form
            const form = button.closest('form');
            if (form) {
                form.submit();
            } else if (button.href) {
                window.location.href = button.href;
            }
        }
    }
}

/**
 * Handle input changes
 */
function handleInputChanges(event) {
    const input = event.target;
    
    // Real-time validation feedback
    if (input.checkValidity) {
        if (input.checkValidity()) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    }
}

/**
 * Show loading state for buttons
 */
function showLoadingState(button) {
    const originalText = button.innerHTML;
    button.dataset.originalText = originalText;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Đang xử lý...';
    button.disabled = true;
}

/**
 * Hide loading state for buttons
 */
function hideLoadingState(button) {
    const originalText = button.dataset.originalText;
    if (originalText) {
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

/**
 * Disable form
 */
function disableForm(form) {
    const inputs = form.querySelectorAll('input, select, textarea, button');
    inputs.forEach(input => {
        input.disabled = true;
    });
}

/**
 * Enable form
 */
function enableForm(form) {
    const inputs = form.querySelectorAll('input, select, textarea, button');
    inputs.forEach(input => {
        input.disabled = false;
    });
}

/**
 * Show notification
 */
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after duration
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, duration);
}

/**
 * Confirm dialog with custom styling
 */
function confirmDialog(message, title = 'Xác nhận') {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary" id="confirm-btn">Xác nhận</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        modal.querySelector('#confirm-btn').addEventListener('click', () => {
            bsModal.hide();
            resolve(true);
        });
        
        modal.addEventListener('hidden.bs.modal', () => {
            modal.remove();
            resolve(false);
        });
    });
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Format date
 */
function formatDate(date, options = {}) {
    const defaultOptions = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    };
    
    return new Intl.DateTimeFormat('vi-VN', { ...defaultOptions, ...options }).format(new Date(date));
}

/**
 * Format number
 */
function formatNumber(number, decimals = 0) {
    return new Intl.NumberFormat('vi-VN', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}

/**
 * Debounce function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Throttle function
 */
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * Copy to clipboard
 */
async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        showNotification('Đã sao chép vào clipboard!', 'success');
    } catch (err) {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Đã sao chép vào clipboard!', 'success');
    }
}

/**
 * Download file
 */
function downloadFile(data, filename, type = 'text/plain') {
    const blob = new Blob([data], { type });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
}

/**
 * Search functionality
 */
function initializeSearch() {
    const searchInputs = document.querySelectorAll('[data-search]');
    
    searchInputs.forEach(input => {
        const searchTarget = input.dataset.search;
        const targets = document.querySelectorAll(searchTarget);
        
        const debouncedSearch = debounce((searchTerm) => {
            targets.forEach(target => {
                const text = target.textContent.toLowerCase();
                const isMatch = text.includes(searchTerm.toLowerCase());
                target.style.display = isMatch ? '' : 'none';
            });
        }, 300);
        
        input.addEventListener('input', (e) => {
            debouncedSearch(e.target.value);
        });
    });
}

/**
 * Initialize search when DOM is ready
 */
document.addEventListener('DOMContentLoaded', initializeSearch);

/**
 * Export functions to global scope
 */
window.StudentManagement = {
    showNotification,
    confirmDialog,
    formatCurrency,
    formatDate,
    formatNumber,
    copyToClipboard,
    downloadFile,
    debounce,
    throttle
};
