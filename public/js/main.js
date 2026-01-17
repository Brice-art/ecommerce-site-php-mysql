// ============================================
// E-Commerce Main JavaScript
// ============================================

// Add to cart functionality (placeholder)
function addToCart(productId) {
    // Show loading state
    console.log('Adding product ' + productId + ' to cart...');
    
    // Simple alert for now (will be replaced with AJAX)
    alert('Product added to cart!\n\nProduct ID: ' + productId + '\n\n(Cart functionality coming soon)');
    
    // TODO: Implement AJAX call to add product to cart
    /*
    fetch('cart.php?action=add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart!');
            updateCartCount(data.cartCount);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add product to cart');
    });
    */
}

// Update cart count in navbar
function updateCartCount(count) {
    const cartElement = document.querySelector('.nav-menu a[href*="cart"]');
    if (cartElement) {
        cartElement.textContent = `Cart (${count})`;
    }
}

// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for all anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add animation class when elements come into view
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);
    
    // Observe product cards
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => observer.observe(card));
    
    // Observe category cards
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => observer.observe(card));
});

// Form validation helper (for future use)
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Format price helper
function formatPrice(price) {
    return '$' + parseFloat(price).toFixed(2);
}

// Show notification (for future use)
function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add to body
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Hide and remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Console log to show script is loaded
console.log('E-Commerce JavaScript loaded successfully!');