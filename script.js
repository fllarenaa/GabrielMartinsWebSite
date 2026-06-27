const hero = document.querySelector(".hero-scroll");
const box = document.querySelector(".hero-box");
const img = document.querySelector(".hero-img");
const title = document.querySelector(".hero-title");
const topCarousel = document.querySelector(".top-carousel");
const bottomCarousel = document.querySelector(".bottom-carousel");
const carouselTexts = document.querySelectorAll(".track span");

function animateHero() {

    const rect = hero.getBoundingClientRect();
    const total = hero.offsetHeight - window.innerHeight;

    let progress = -rect.top / total;
    progress = Math.max(0, Math.min(progress, 1));

    const isMobile = window.innerWidth <= 768;

    if (isMobile) {

        // =========================
        // MOBILE
        // =========================

        const width = 100 - (progress * 40);
        const height = 100 - (progress * 60);

        box.style.width = `${width}vw`;
        box.style.height = `${height}vh`;

        const start = 255;
        const end = 48;

        const value = Math.round(start + (end - start) * progress);

        box.style.backgroundColor = `rgb(${value}, ${value}, ${value})`;

        box.style.borderRadius = `${progress * 25}px`;

        // imagem no mobile (leve diminuição)
        const imgScale = 1.2 - (progress * 0.4);
        img.style.transform = `scale(${imgScale})`;
        img.style.transformOrigin = "center bottom";

        title.style.fontSize = `${5 + (progress * 10)}vw`;

        topCarousel.style.top = `${73 + (progress * 8)}%`;
        bottomCarousel.style.top = `${78 + (progress * 8)}%`;

        const carouselFontSize = 2.8 - (progress * 1.0);

        carouselTexts.forEach(span => {
            span.style.fontSize = `${carouselFontSize}rem`;
        });

    } else {

        // =========================
        // DESKTOP
        // =========================

        const scale = 1 - progress * 0.25;
        box.style.transform = `scale(${scale})`;

        const width = 100 - (progress * 40);
        box.style.width = `${width}%`;
        box.style.height = "100%";

        const start = 255;
        const end = 48;

        const value = Math.round(start + (end - start) * progress);

        box.style.backgroundColor = `rgb(${value}, ${value}, ${value})`;

        box.style.borderRadius = "2px";

        // 🔥 CORREÇÃO: imagem cresce no scroll (mas sem cortar)
        const imgScale = 1 + (progress * 0.15);
        img.style.transform = `scale(${imgScale})`;
        img.style.transformOrigin = "center bottom";

        // reset carrossel desktop
        topCarousel.style.top = "45%";
        bottomCarousel.style.top = "58%";

        carouselTexts.forEach(span => {
            span.style.fontSize = "6rem";
        });
    }

    // =========================
    // DESATURAÇÃO
    // =========================
    img.style.filter = `saturate(${1 - progress})`;

    // =========================
    // TÍTULO
    // =========================
    if (progress > 0.8) {

        const p = Math.min((progress - 0.8) / 0.2, 1);

        title.style.opacity = p;
        title.style.transform = `translateY(${50 - (50 * p)}px)`;

    } else {

        title.style.opacity = 0;
        title.style.transform = "translateY(50px)";
    }

}

window.addEventListener("scroll", animateHero);
window.addEventListener("resize", animateHero);

animateHero();

function splitTextToSpans(el) {
    const text = el.textContent;
    el.textContent = "";

    text.split("").forEach((char) => {
        const span = document.createElement("span");
        span.className = "char";
        span.textContent = char === " " ? "\u00A0" : char;
        el.appendChild(span);
    });
}

const elements = document.querySelectorAll(".content h3, .content p");

elements.forEach(splitTextToSpans);

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        const chars = entry.target.querySelectorAll(".char");

        if (entry.isIntersecting) {
            // ANIMAÇÃO ENTRANDO
            chars.forEach((char, i) => {
                setTimeout(() => {
                    char.classList.add("show");
                }, i * 2);
            });
        } else {
            // RESET quando sai da tela
            chars.forEach(char => {
                char.classList.remove("show");
            });
        }
    });
}, {
    threshold: 0.2
});

elements.forEach(el => observer.observe(el));

const pin = document.querySelector(".reviews-pin");
const track = document.querySelector(".reviews-track");
const reviews = document.querySelectorAll(".review");

function animate() {

    const rect = pin.getBoundingClientRect();
    const total = pin.offsetHeight - window.innerHeight;

    let progress = -rect.top / total;
    progress = Math.max(0, Math.min(1, progress));

    // largura total da linha
    const trackWidth = track.scrollWidth;

    // movimento contínuo
    const maxMove = trackWidth - window.innerWidth;

    const x = progress * maxMove;

    track.style.transform = `translateX(${-x}px) translateY(-50%)`;
}

window.addEventListener("scroll", animate);
window.addEventListener("resize", animate);
animate();