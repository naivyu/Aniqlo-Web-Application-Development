var video = document.getElementById("myVideo");
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("aniqloSlides");
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
        slides[i].style.opacity = 0;
    }
    slides[slideIndex - 1].style.display = "block";
    let fadeIn = function () {
        let opacity = parseFloat(slides[slideIndex - 1].style.opacity);
        if (opacity < 1) {
            opacity += 0.01;
            slides[slideIndex - 1].style.opacity = opacity;
            requestAnimationFrame(fadeIn);
        } else {
            slides[slideIndex - 1].style.opacity = 1;
        }
    };
    fadeIn();
}

let hoverImages = document.querySelectorAll('.hover-img');
hoverImages.forEach(img => {
    let newImg = new Image();
    newImg.src = img.src;
});

document.querySelectorAll('.category-box').forEach(box => {
    let defaultImg = box.querySelector('.default-img');
    let hoverImg = box.querySelector('.hover-img');

    box.addEventListener('mouseenter', () => {
        defaultImg.style.display = 'none';
        hoverImg.style.display = 'block';
    });

    box.addEventListener('mouseleave', () => {
        defaultImg.style.display = 'block';
        hoverImg.style.display = 'none';
    });
});

function handleIntersection(entries, observer) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('show-text');
        } else {
            entry.target.classList.remove('show-text');
        }
    });
}

const observer = new IntersectionObserver(handleIntersection, {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
});

const textContainer = document.querySelector('.main-container p.animate-on-scroll');

observer.observe(textContainer);
