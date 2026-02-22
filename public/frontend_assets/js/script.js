// Custom JavaScript for News Website

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initNavbar();
    initCarousel();
    initScrollToTop();
    initAnimations();
    initLazyLoading();
    initSearchFunctionality();
    initNewsletterForm();
    initContactForm();
});

// Navbar functionality
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
    
    // Mobile menu toggle
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });
    }
}

// Carousel functionality
function initCarousel() {
    const carousel = document.querySelector('#featuredCarousel');
    if (carousel) {
        const carouselInstance = new bootstrap.Carousel(carousel, {
            interval: 3000,
            ride: 'carousel'
        });
    }
}

// Scroll to top functionality
function initScrollToTop() {
    const backToTopBtn = document.querySelector('.back-to-top');
    
    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

// Animation on scroll
function initAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animateElements = document.querySelectorAll('.news-card, .category-card, .sidebar-widget');
    animateElements.forEach(el => {
        observer.observe(el);
    });
}

// Lazy loading for images
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => {
        imageObserver.observe(img);
    });
}

// Search functionality
function initSearchFunctionality() {
    const searchForm = document.querySelector('#searchForm');
    const searchInput = document.querySelector('#searchInput');
    const searchResults = document.querySelector('#searchResults');
    
    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            
            if (query.length > 2) {
                performSearch(query);
            }
        });
        
        // Real-time search suggestions
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length > 2) {
                debounce(function() {
                    showSearchSuggestions(query);
                }, 300)();
            } else {
                hideSearchSuggestions();
            }
        });
    }
}

// Newsletter subscription
function initNewsletterForm() {
    const newsletterForm = document.querySelector('#newsletterForm');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Simulate API call
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Subscribing...';
            submitBtn.disabled = true;
            
            setTimeout(function() {
                submitBtn.innerHTML = 'Subscribed!';
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-success');
                
                // Reset after 3 seconds
                setTimeout(function() {
                    submitBtn.innerHTML = 'Subscribe';
                    submitBtn.classList.remove('btn-success');
                    submitBtn.classList.add('btn-primary');
                    submitBtn.disabled = false;
                    newsletterForm.reset();
                }, 3000);
            }, 1500);
        });
    }
}

// Contact form functionality (only for forms that are not the main contact page form)
// The main contact form at /contact uses id="contactForm" and submits to the server normally.
// This init only runs for #contactFormAjax if you add one elsewhere (e.g. modal).
function initContactForm() {
    const contactForm = document.querySelector('#contactFormAjax');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            if (validateContactForm(formData)) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Sending...';
                submitBtn.disabled = true;
                
                fetch(contactForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(res) { return res.json().catch(function() { return { redirect: res.url }; }); })
                .then(function(data) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }
                    if (data.message) showAlert(data.message, 'success');
                    contactForm.reset();
                })
                .catch(function() {
                    showAlert('Something went wrong. Please try again.', 'error');
                })
                .finally(function() {
                    submitBtn.innerHTML = 'Send Message';
                    submitBtn.disabled = false;
                });
            }
        });
    }
}

// Search functions
function performSearch(query) {
    // Simulate search API call
    console.log('Searching for:', query);
    
    // In a real application, this would make an AJAX call to the server
    const searchResults = document.querySelector('#searchResults');
    if (searchResults) {
        searchResults.innerHTML = `<p>Searching for "${query}"...</p>`;
        
        // Simulate results
        setTimeout(function() {
            displaySearchResults(query);
        }, 1000);
    }
}

function showSearchSuggestions(query) {
    // Mock search suggestions
    const suggestions = [
        'राष्ट्रीय समाचार',
        'अंतरराष्ट्रीय समाचार',
        'राजनीति',
        'खेल',
        'मनोरंजन',
        'व्यापार',
        'स्वास्थ्य',
        'शिक्षा',
        'प्रौद्योगिकी',
        'जीवन शैली'
    ].filter(item => item.toLowerCase().includes(query.toLowerCase()));
    
    // Display suggestions (implement based on your UI)
    console.log('Search suggestions:', suggestions);
}

function hideSearchSuggestions() {
    // Hide search suggestions dropdown
    console.log('Hiding search suggestions');
}

function displaySearchResults(query) {
    // Mock search results display
    const searchResults = document.querySelector('#searchResults');
    if (searchResults) {
        searchResults.innerHTML = `
            <h3>Search Results for "${query}"</h3>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card news-card">
                        <img src="https://via.placeholder.com/400x200" class="card-img-top" alt="News Image">
                        <div class="card-body">
                            <h5 class="card-title">Sample News Article</h5>
                            <p class="card-text">This is a sample news article related to your search query.</p>
                            <div class="news-meta">
                                <small>Published: 2 hours ago | By: News Reporter</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

// Form validation
function validateContactForm(formData) {
    const name = formData.get('name');
    const email = formData.get('email');
    const message = formData.get('message');
    
    if (!name || name.trim().length < 2) {
        showAlert('Please enter a valid name', 'error');
        return false;
    }
    
    if (!email || !isValidEmail(email)) {
        showAlert('Please enter a valid email address', 'error');
        return false;
    }
    
    if (!message || message.trim().length < 10) {
        showAlert('Please enter a message with at least 10 characters', 'error');
        return false;
    }
    
    return true;
}

// Utility functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

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

function showAlert(message, type = 'info') {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at top of page
    const container = document.querySelector('.container') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(function() {
        alertDiv.remove();
    }, 5000);
}

// Category filter functionality
function filterNewsByCategory(category) {
    const newsCards = document.querySelectorAll('.news-card');
    
    newsCards.forEach(card => {
        const cardCategory = card.dataset.category;
        
        if (category === 'all' || cardCategory === category) {
            card.closest('.col-md-4, .col-lg-4').style.display = 'block';
        } else {
            card.closest('.col-md-4, .col-lg-4').style.display = 'none';
        }
    });
}

// Load more news functionality
function loadMoreNews() {
    const loadMoreBtn = document.querySelector('#loadMoreBtn');
    
    if (loadMoreBtn) {
        loadMoreBtn.innerHTML = '<span class="loading-spinner"></span> Loading...';
        loadMoreBtn.disabled = true;
        
        // Simulate loading more news
        setTimeout(function() {
            // Add more news cards here
            loadMoreBtn.innerHTML = 'Load More News';
            loadMoreBtn.disabled = false;
        }, 1500);
    }
}

// Share functionality
function shareArticle(url, title) {
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        });
    } else {
        // Fallback to copying URL to clipboard
        navigator.clipboard.writeText(url).then(function() {
            showAlert('Link copied to clipboard!', 'success');
        });
    }
}

// Dark mode toggle (optional feature)
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    
    // Save preference
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
}

// Load dark mode preference
function loadDarkModePreference() {
    const darkMode = localStorage.getItem('darkMode');
    if (darkMode === 'true') {
        document.body.classList.add('dark-mode');
    }
}

// Initialize dark mode on page load
loadDarkModePreference();
