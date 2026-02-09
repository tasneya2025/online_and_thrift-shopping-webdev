let currentSlide = 0;
const slides = document.querySelectorAll('.hero-slide');

function nextSlide() {
    
    slides[currentSlide].classList.remove('active');
    

    currentSlide = (currentSlide + 1) % slides.length;
    

    slides[currentSlide].classList.add('active');
}

setInterval(nextSlide, 4000);