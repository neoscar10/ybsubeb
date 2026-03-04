/**
 * SUBEB Yobe State - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // --- Navbar Active State on Scroll ---
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (scrollY >= (sectionTop - 150)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').includes(current)) {
                link.classList.add('active');
            }
        });
    });

    // --- Back to Top Button ---
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // --- Data Filter Simulation (UI Only) ---
    const filterBtn = document.getElementById('applyFilter');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const btnText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Filtering...';
            this.disabled = true;
            
            // Simulate network delay
            setTimeout(() => {
                this.innerHTML = btnText;
                this.disabled = false;
                alert('Filter applied! (This is a UI demo. Backend integration required.)');
            }, 800);
        });
    }

    // --- Contact Form Validation (Bootstrap Standard) ---
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

});
