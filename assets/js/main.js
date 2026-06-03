/**
 * Main JavaScript - RobiCodes Developer Portfolio
 * Handles: Theme toggle, navbar, cursor, particles, scroll animations, counters, typing effect
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {

    /* ================================================================
       1. LOADING SCREEN
    ================================================================ */
    const loader = document.querySelector('.loader');
    if (loader) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                loader.classList.add('hidden');
                document.body.style.overflow = '';
            }, 600);
        });

        if (document.readyState === 'complete') {
            setTimeout(() => {
                loader.classList.add('hidden');
                document.body.style.overflow = '';
            }, 600);
        }
    }


    /* ================================================================
       2. THEME TOGGLE (Dark / Light)
    ================================================================ */
    const themeToggle = document.querySelector('.theme-toggle');
    const html = document.documentElement;

    if (themeToggle) {
        const saved = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', saved);

        themeToggle.addEventListener('click', () => {
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        });
    }


    /* ================================================================
       3. CUSTOM CURSOR
    ================================================================ */
    const cursor = document.querySelector('.custom-cursor');
    const cursorDot = document.querySelector('.custom-cursor--dot');

    if (cursor && cursorDot) {
        document.addEventListener('mousemove', (e) => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
            cursorDot.style.left = e.clientX + 'px';
            cursorDot.style.top = e.clientY + 'px';
        });

        document.addEventListener('mousedown', () => cursor.classList.add('active'));
        document.addEventListener('mouseup', () => cursor.classList.remove('active'));

        document.querySelectorAll('a, button, .clickable').forEach(el => {
            el.addEventListener('mouseenter', () => cursor.classList.add('active'));
            el.addEventListener('mouseleave', () => cursor.classList.remove('active'));
        });
    }


    /* ================================================================
       4. NAVBAR
    ================================================================ */
    const navbar = document.querySelector('.navbar');
    const toggle = document.querySelector('.navbar__toggle');
    const navLinks = document.querySelector('.navbar__links');
    const overlay = document.querySelector('.navbar__overlay');

    // Scroll effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Mobile toggle
    if (toggle && navLinks) {
        toggle.addEventListener('click', () => {
            toggle.classList.toggle('active');
            navLinks.classList.toggle('active');
            if (overlay) overlay.classList.toggle('active');
            document.body.style.overflow = navLinks.classList.contains('active') ? 'hidden' : '';
        });

        if (overlay) {
            overlay.addEventListener('click', () => {
                toggle.classList.remove('active');
                navLinks.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        navLinks.querySelectorAll('.navbar__link').forEach(link => {
            link.addEventListener('click', () => {
                toggle.classList.remove('active');
                navLinks.classList.remove('active');
                if (overlay) overlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }

    // Active link highlighting
    const currentPath = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.navbar__link').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPath) {
            link.classList.add('active');
        }
    });


    /* ================================================================
       5. TYPING EFFECT
    ================================================================ */
    const typingElement = document.querySelector('.hero__typing');
    if (typingElement) {
        const phrases = JSON.parse(typingElement.dataset.texts || '["Web Developer", "PHP Developer", "API Developer", "Software Engineer"]');
        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let currentText = '';

        function type() {
            const currentPhrase = phrases[phraseIndex];

            if (isDeleting) {
                currentText = currentPhrase.substring(0, charIndex - 1);
                charIndex--;
            } else {
                currentText = currentPhrase.substring(0, charIndex + 1);
                charIndex++;
            }

            typingElement.textContent = currentText;

            let speed = isDeleting ? 50 : 100;

            if (!isDeleting && charIndex === currentPhrase.length) {
                speed = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                speed = 500;
            }

            setTimeout(type, speed);
        }

        type();
    }


    /* ================================================================
       6. SCROLL REVEAL ANIMATIONS
    ================================================================ */
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));


    /* ================================================================
       7. ANIMATED COUNTERS
    ================================================================ */
    function animateCounter(el, target, duration = 2000) {
        const suffix = el.dataset.suffix || '';
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        function update() {
            current += increment;
            if (current >= target) {
                el.textContent = target.toLocaleString() + suffix;
                return;
            }
            el.textContent = Math.floor(current).toLocaleString() + suffix;
            requestAnimationFrame(update);
        }

        update();
    }

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.dataset.target);
                if (target && !el.dataset.counted) {
                    el.dataset.counted = 'true';
                    animateCounter(el, target);
                }
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-card__value').forEach(el => counterObserver.observe(el));


    /* ================================================================
       8. SKILL BARS ANIMATION
    ================================================================ */
    const skillObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const percent = bar.dataset.percent;
                if (percent) {
                    bar.style.width = percent + '%';
                }
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('.skill-item__fill').forEach(el => skillObserver.observe(el));


    /* ================================================================
       9. CIRCULAR PROGRESS ANIMATION
    ================================================================ */
    const circularObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const circle = entry.target;
                const percent = parseInt(circle.dataset.percent);
                if (percent && !circle.dataset.animated) {
                    circle.dataset.animated = 'true';
                    const radius = 42;
                    const circumference = 2 * Math.PI * radius;
                    const offset = circumference - (percent / 100) * circumference;
                    circle.style.strokeDasharray = circumference;
                    circle.style.strokeDashoffset = circumference;
                    setTimeout(() => {
                        circle.style.strokeDashoffset = offset;
                    }, 200);
                }
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('.circular-skill__progress').forEach(el => circularObserver.observe(el));


    /* ================================================================
       10. PROJECTS FILTERING & SEARCH
    ================================================================ */
    const searchInput = document.querySelector('.projects__search-input');
    const filterBtns = document.querySelectorAll('.projects__filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    function filterProjects() {
        const searchTerm = (searchInput?.value || '').toLowerCase();
        const activeFilter = document.querySelector('.projects__filter-btn.active');
        const category = activeFilter?.dataset?.category || 'all';

        projectCards.forEach(card => {
            const title = (card.dataset.title || '').toLowerCase();
            const desc = (card.dataset.description || '').toLowerCase();
            const techs = (card.dataset.techs || '').toLowerCase();
            const cardCategory = card.dataset.category || 'all';

            const matchesSearch = !searchTerm || title.includes(searchTerm) || desc.includes(searchTerm) || techs.includes(searchTerm);
            const matchesCategory = category === 'all' || cardCategory === category;

            if (matchesSearch && matchesCategory) {
                card.style.display = '';
                card.style.animation = 'scaleIn 0.4s ease';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterProjects);
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filterProjects();
        });
    });


    /* ================================================================
       11. CONTACT FORM VALIDATION
    ================================================================ */
    const contactForm = document.querySelector('.contact__form');
    if (contactForm) {
        const fields = {
            name: { required: true, minLength: 2, label: 'Name' },
            email: { required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, label: 'Email' },
            subject: { required: true, minLength: 3, label: 'Subject' },
            message: { required: true, minLength: 10, label: 'Message' }
        };

        function validateField(input) {
            const field = fields[input.name];
            const group = input.closest('.form-group');
            if (!field || !group) return true;

            const value = input.value.trim();
            let error = '';

            if (field.required && !value) {
                error = field.label + ' is required';
            } else if (field.minLength && value.length < field.minLength) {
                error = field.label + ' must be at least ' + field.minLength + ' characters';
            } else if (field.pattern && !field.pattern.test(value)) {
                error = 'Please enter a valid ' + field.label.toLowerCase();
            }

            const errorEl = group.querySelector('.form-group__error');
            if (error) {
                group.classList.add('has-error');
                if (errorEl) errorEl.textContent = error;
                return false;
            } else {
                group.classList.remove('has-error');
                if (errorEl) errorEl.textContent = '';
                return true;
            }
        }

        contactForm.querySelectorAll('.form-group__input, .form-group__textarea').forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', () => {
                if (input.closest('.form-group').classList.contains('has-error')) {
                    validateField(input);
                }
            });
        });

        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            let isValid = true;
            contactForm.querySelectorAll('.form-group__input, .form-group__textarea').forEach(input => {
                if (!validateField(input)) isValid = false;
            });

            if (!isValid) return;

            const formData = new FormData(contactForm);
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            try {
                const response = await fetch(contactForm.action, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Message sent successfully! I will get back to you soon.', 'success');
                    contactForm.reset();
                } else {
                    showToast(result.message || 'Failed to send message. Please try again.', 'error');
                }
            } catch (err) {
                showToast('Network error. Please check your connection.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }


    /* ================================================================
       12. TOAST NOTIFICATIONS
    ================================================================ */
    function showToast(message, type = 'info') {
        const container = document.querySelector('.toast-container');
        if (!container) return;

        const icons = { success: '✓', error: '✕', info: 'ℹ' };
        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.innerHTML = `
            <span class="toast__icon">${icons[type] || 'ℹ'}</span>
            <p class="toast__message">${message}</p>
            <button class="toast__close">&times;</button>
        `;

        container.appendChild(toast);

        toast.querySelector('.toast__close').addEventListener('click', () => {
            toast.classList.add('removing');
            setTimeout(() => toast.remove(), 300);
        });

        setTimeout(() => {
            if (toast.isConnected) {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }


    /* ================================================================
       13. BACK TO TOP BUTTON
    ================================================================ */
    const backToTop = document.querySelector('.back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }


    /* ================================================================
       14. SMOOTH SCROLL FOR ANCHOR LINKS
    ================================================================ */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

});
