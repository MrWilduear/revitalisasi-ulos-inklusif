document.addEventListener('DOMContentLoaded', function() {

    // ========== NAVBAR SCROLL ==========
    var navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // ========== MOBILE MENU TOGGLE ==========
    var toggle = document.querySelector('.navbar-toggle');
    var menu = document.querySelector('.navbar-menu');
    if (toggle) {
        toggle.addEventListener('click', function() {
            menu.classList.toggle('active');
        });
        menu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                menu.classList.remove('active');
            });
        });
    }

    // ========== COUNTDOWN TIMER ==========
    var eventDateEl = document.getElementById('event-date');
    if (eventDateEl) {
        var eventDate = new Date(eventDateEl.value).getTime();
        var daysEl = document.getElementById('countdown-days');
        var hoursEl = document.getElementById('countdown-hours');
        var minutesEl = document.getElementById('countdown-minutes');
        var secondsEl = document.getElementById('countdown-seconds');

        function updateCountdown() {
            var now = new Date().getTime();
            var diff = eventDate - now;

            if (diff <= 0) {
                daysEl.textContent = '0';
                hoursEl.textContent = '0';
                minutesEl.textContent = '0';
                secondsEl.textContent = '0';
                return;
            }

            daysEl.textContent = Math.floor(diff / (1000 * 60 * 60 * 24));
            hoursEl.textContent = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            minutesEl.textContent = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            secondsEl.textContent = Math.floor((diff % (1000 * 60)) / 1000);
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // ========== SCROLL ANIMATIONS ==========
    var fadeElements = document.querySelectorAll('.fade-in, .timeline-item');

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.15 });

    fadeElements.forEach(function(el) {
        observer.observe(el);
    });

    // ========== ACTIVE NAV LINK ==========
    var sections = document.querySelectorAll('.section[id]');
    var navLinks = document.querySelectorAll('.navbar-menu a');

    window.addEventListener('scroll', function() {
        var current = '';
        sections.forEach(function(section) {
            var top = section.offsetTop - 100;
            if (window.scrollY >= top) {
                current = section.getAttribute('id');
            }
        });
        navLinks.forEach(function(link) {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });

    // ========== LIGHTBOX ==========
    var lightbox = document.getElementById('lightbox');
    var lightboxImg = document.getElementById('lightbox-img');
    var galleryItems = document.querySelectorAll('.gallery-item img');

    galleryItems.forEach(function(img) {
        img.addEventListener('click', function() {
            lightboxImg.src = this.src;
            lightbox.classList.add('active');
        });
    });

    if (lightbox) {
        lightbox.addEventListener('click', function() {
            lightbox.classList.remove('active');
        });
    }

    // ========== REGISTRATION FORM ==========
    var regForm = document.getElementById('register-form');
    if (regForm) {
        regForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(regForm);
            var msgEl = document.getElementById('form-message');

            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                msgEl.textContent = data.message;
                msgEl.className = 'form-message ' + (data.success ? 'success' : 'error');
                if (data.success) {
                    regForm.reset();
                }
            })
            .catch(function() {
                msgEl.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                msgEl.className = 'form-message error';
            });
        });
    }

});
