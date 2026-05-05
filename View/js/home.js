    const header = document.getElementById('mainHeader');
    window.addEventListener('scroll', () => {
        header.classList.toggle('scrolled', window.scrollY > 10);
    });

    const dots = document.querySelectorAll('.dot');
    const slides = document.querySelectorAll('.hero-slide');
    let current = 0;

    function goToSlide(n) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = n;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    dots.forEach(dot => {
        dot.addEventListener('click', () => goToSlide(+dot.dataset.index));
    });

    setInterval(() => goToSlide((current + 1) % slides.length), 4000);