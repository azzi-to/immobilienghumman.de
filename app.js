// Language and Dark Mode
const langToggle = document.querySelector('.lang-toggle');
const darkModeToggle = document.querySelector('.dark-mode-toggle');
let currentLanguage = localStorage.getItem('language') || 'de';

function setLanguage(lang) {
    currentLanguage = lang;
    localStorage.setItem('language', lang);
    document.documentElement.lang = lang;
    updateContent();
}

function updateContent() {
    document.querySelectorAll('[lang]').forEach(el => {
        if (el.lang === currentLanguage) {
            el.style.display = 'block';
        } else {
            el.style.display = 'none';
        }
    });
    document.querySelectorAll('.lang-btn').forEach(btn => {
        if (btn.dataset.lang === currentLanguage) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}

langToggle.addEventListener('click', (e) => {
    if (e.target.matches('.lang-btn')) {
        setLanguage(e.target.dataset.lang);
    }
});

darkModeToggle.addEventListener('click', () => {
    const currentTheme = document.body.dataset.theme;
    if (currentTheme === 'dark') {
        document.body.removeAttribute('data-theme');
        localStorage.removeItem('theme');
    } else {
        document.body.dataset.theme = 'dark';
        localStorage.setItem('theme', 'dark');
    }
});

if (localStorage.getItem('theme') === 'dark') {
    document.body.dataset.theme = 'dark';
}

// Cookie Consent
const cookieBanner = document.getElementById('cookie-banner');
const websiteBlocker = document.getElementById('website-blocker');
const acceptBtn = document.getElementById('cookie-accept');
const rejectBtn = document.getElementById('cookie-reject');

const translations = {
    de: {
        cookieText: "Wir verwenden Cookies und Google Analytics, um unsere Website technisch einwandfrei bereitzustellen. Weitere Infos finden Sie in unserer Datenschutzerklärung.",
        accept: "Alle akzeptieren",
        reject: "Alle ablehnen",
        learnMore: "Mehr erfahren"
    },
    en: {
        cookieText: "We use cookies and Google Analytics to ensure proper functionality of our website. For more information, see our privacy policy.",
        accept: "Accept all",
        reject: "Reject all",
        learnMore: "Learn more"
    }
};

function showCookieBanner() {
    const lang = currentLanguage;
    document.getElementById('cookie-text').textContent = translations[lang].cookieText;
    document.getElementById('cookie-accept').textContent = translations[lang].accept;
    document.getElementById('cookie-reject').textContent = translations[lang].reject;
    document.getElementById('cookie-learn-more').textContent = translations[lang].learnMore;
    cookieBanner.style.display = 'block';
    websiteBlocker.style.display = 'block';
}

function hideCookieBanner() {
    cookieBanner.style.display = 'none';
    websiteBlocker.style.display = 'none';
}

function loadGoogleAnalytics() {
    const gaScript = document.createElement('script');
    gaScript.async = true;
    gaScript.src = 'https://www.googletagmanager.com/gtag/js?id=YOUR_GA_ID'; // Replace with your GA ID
    document.head.appendChild(gaScript);

    const gaScriptInline = document.createElement('script');
    gaScriptInline.innerHTML = `
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'YOUR_GA_ID'); // Replace with your GA ID
    `;
    document.head.appendChild(gaScriptInline);
}

const consent = localStorage.getItem('cookie_consent');

if (!consent) {
    showCookieBanner();
} else if (consent === 'accepted') {
    loadGoogleAnalytics();
}

acceptBtn.addEventListener('click', () => {
    localStorage.setItem('cookie_consent', 'accepted');
    hideCookieBanner();
    loadGoogleAnalytics();
});

rejectBtn.addEventListener('click', () => {
    localStorage.setItem('cookie_consent', 'rejected');
    hideCookieBanner();
});

// Contact Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';
            
            // Remove any existing messages
            removeMessages();
            
            // Send form data
            fetch('send-email.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    contactForm.reset();
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Sorry, there was an error sending your message. Please try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            });
        });
    }
    
    function showMessage(message, type) {
        // Remove any existing messages first
        removeMessages();
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `form-message form-message-${type}`;
        messageDiv.textContent = message;
        
        // Insert message after the form
        contactForm.parentNode.insertBefore(messageDiv, contactForm.nextSibling);
        
        // Auto-remove success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                removeMessages();
            }, 5000);
        }
    }
    
    function removeMessages() {
        const existingMessages = document.querySelectorAll('.form-message');
        existingMessages.forEach(msg => msg.remove());
    }
});

// Initial setup
document.addEventListener('DOMContentLoaded', () => {
    setLanguage(currentLanguage);
});
