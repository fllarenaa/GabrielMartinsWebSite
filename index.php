<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="css/styleHero.css?v=<?php echo time(); ?>">
<style>
    @font-face {
    font-family: "aquiline";
    src: url("fonts/exmouth_.ttf") format("truetype");
    font-weight: 400;
    font-style: normal;
}
</style>
<title>Hero Animation</title>

</head>
<body>
<?php include 'components/user_header.php'; ?>
<section class="hero-scroll">

    <div class="sticky">

            <div class="titleLogo">
                <img src="images/logo.png" alt="">
            </div>

        <div class="hero-box">

            <img src="images/gabrielPng.png" class="hero-img" alt="">
            <img src="images/boxSelect.png" alt="" class="boxSelect">

        </div>

           <h1 class="hero-title" style="font-family: aquiline;">
            GabrielM.
           </h1>
           

          <div class="carousel top-carousel">
    <div class="track track-right">
        <span>Mais do que um corte, um compromisso com a excelência.</span>
        <span>Mais do que um corte, um compromisso com a excelência.</span>
        <span>Mais do que um corte, um compromisso com a excelência.</span>
    </div>
</div>

<div class="carousel bottom-carousel">
    <div class="track track-left">
        <span style="font-family:ConsolaMono; text-transform: uppercase;">Seu estilo começa onde a confiança encontra a tradição.</span>
        <span style="font-family:ConsolaMono; text-transform: uppercase;">Seu estilo começa onde a confiança encontra a tradição.</span>
        <span style="font-family:ConsolaMono; text-transform: uppercase;">Seu estilo começa onde a confiança encontra a tradição.</span>
    </div>
</div>

<div class="btn-agendar">
    <button class="agendar">Agendar Serviço</button>
</div>



    </div>

</section>

<section class="about">

   <div class="row">

      <div class="content">
         <h3>Bem-Vindo!</h3>
         <p>Eu sou o Gabriel Martins, barbeiro na barbearia Flávio <br> <br> Bezerra, e levo muito a sério cada detalhe do meu trabalho. Desde o primeiro atendimento até o último acabamento, eu me dedico para entregar não apenas um corte, mas <br> <br> uma experiência completa, onde cada cliente se sinta valorizado e confiante. Tenho verdadeira paixão pelo que <br> fa-ço, e isso me motiva a buscar evolução constante, aprimorando minhas técnicas e cuidando para que cada resultado seja melhor do que o anterior. Trabalho com determinação e foco, acreditando que a confiança do cliente é conquistada no dia a dia, com consistência e respeito. Na minha cadeira, cada pessoa que senta pode esperar comprometimento, atenção e o meu máximo esforço, porque meu objetivo é simples: sempre dar o melhor de mim em tudo o que faço.</p>

         <a href="menu.php" class="btn">Ver Serviços</a>
      </div>

   </div>

</section>

<section class="reviews-pin">
      <h1 class="title">Avaliações</h1>
    <div class="reviews-inner">
        
        <div class="reviews-track">

            <!-- EXEMPLO (repete 20x) -->
            <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>
             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>
             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>
             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>
             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>
             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>

             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>
             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>
             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>
             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>
             <div class="review">
                <img src="images/p1.jpg">
                <div class="stars">★★★★★</div>
                <p>Atendimento incrível!</p>
            </div>


            <!-- repete até 20 -->
        </div>
    </div>
</section>



<section class="products">

   <h1 class="title">Serviços</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <!-- Campos ocultos necessários para adicionar ao carrinho -->
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat">
            <?= $fetch_products['category']; ?>
         </a>
         
         <div class="name"><?= $fetch_products['name']; ?></div>
         
         <div class="flex">
            <div class="price"><span>R$</span><?= $fetch_products['price']; ?></div>
            <!-- Quantidade padrão -->
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
         <br>
         <div class="actions">
            <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="view">Ver Produto</a>
            <button type="submit" name="add_to_cart" class="cart btn">Adicionar</button>
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">Nenhum produto foi adicionado ainda!</p>';
         }
      ?>

   </div>

   <div class="more-btn">
      <a href="menu.html" class="btn">Ver Mais</a>
   </div>

</section>
<br>
<?php include 'components/footer.php'; ?>

<!-- <script src="js/scriptHero.js"></script> -->
<!-- <script>
const track2 = document.querySelector(".track");
const content = track2.innerHTML;

for (let i = 0; i < 5; i++) {
    track2.innerHTML += content;
}
</script> -->





<script>
const hero = document.querySelector(".hero-scroll");
const box = document.querySelector(".hero-box");
const img = document.querySelector(".hero-img");
const title = document.querySelector(".hero-title");
const topCarousel = document.querySelector(".top-carousel");
const bottomCarousel = document.querySelector(".bottom-carousel");
const carouselTexts = document.querySelectorAll(".track span");
const header = document.querySelector(".header");
const logoHeader = document.querySelector(".header .flex .logo");

function animateHero() {

    const rect = hero.getBoundingClientRect();
    const total = hero.offsetHeight - window.innerHeight;

    let progress = -rect.top / total;
    progress = Math.max(0, Math.min(progress, 1));

    const isMobile = window.innerWidth <= 768;

    // =========================
    // HERO ANIMATION
    // =========================
    if (isMobile) {

        const width = 100 - (progress * 40);
        const height = 100 - (progress * 60);

        box.style.width = `${width}vw`;
        box.style.height = `${height}vh`;

        const value = Math.round(255 + (48 - 255) * progress);
        box.style.backgroundColor = `rgb(${value}, ${value}, ${value})`;

        box.style.borderRadius = `${progress * 25}px`;

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

        const scale = 1 - progress * 0.25;
        box.style.transform = `scale(${scale})`;

        const width = 100 - (progress * 40);
        box.style.width = `${width}%`;
        box.style.height = "100%";

        const value = Math.round(255 + (48 - 255) * progress);
        box.style.backgroundColor = `rgb(${value}, ${value}, ${value})`;

        box.style.borderRadius = "2px";

        const imgScale = 1 + (progress * 0.15);
        img.style.transform = `scale(${imgScale})`;
        img.style.transformOrigin = "center bottom";

        topCarousel.style.top = "45%";
        bottomCarousel.style.top = "58%";

        carouselTexts.forEach(span => {
            span.style.fontSize = "6rem";
        });
    }

    img.style.filter = `saturate(${1 - progress})`;

    if (progress > 0.8) {
        const p = Math.min((progress - 0.8) / 0.2, 1);
        title.style.opacity = p;
        title.style.transform = `translateY(${50 - (50 * p)}px)`;
    } else {
        title.style.opacity = 0;
        title.style.transform = "translateY(50px)";
    }

 
    // =========================
// HEADER COLOR
// =========================
const headerProgress = Math.min(progress / 0.4, 1);

const value = Math.round(0 + (204 - 0) * headerProgress);
const color = `rgb(${value}, ${value}, ${value})`;

header.style.color = color;

// =========================
// LOGO FILTER
// =========================

// começa a inverter quando a animação está avançando
const start = 0.25;
const end = 0.40;

let logoProgress = (progress - start) / (end - start);
logoProgress = Math.max(0, Math.min(logoProgress, 1));

logoHeader.style.filter = `invert(${logoProgress})`;
}

window.addEventListener("scroll", animateHero);
window.addEventListener("resize", animateHero);
animateHero();
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

    function infiniteMarquee(selector, direction, speed) {

        const track = document.querySelector(selector);
        if (!track) return;

        const originalContent = track.innerHTML;

        // duplica conteúdo UMA vez (correto)
        track.innerHTML = originalContent + originalContent;

        let x = 0;
        const halfWidth = track.scrollWidth / 2;

        function animate() {
            x += direction * speed;

            // loop infinito suave
            if (direction > 0 && x >= 0) {
                x = -halfWidth;
            }

            if (direction < 0 && -x >= halfWidth) {
                x = 0;
            }

            track.style.transform = `translateX(${x}px)`;

            requestAnimationFrame(animate);
        }

        animate();
    }

    // direita → esquerda
    infiniteMarquee(".track-right", 1, 0.6);

    // esquerda → direita
    infiniteMarquee(".track-left", -1, 0.6);

});
</script>

<script>
    

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

    const trackWidth = track.scrollWidth;

    const maxMove = trackWidth - window.innerWidth;

    const x = progress * maxMove;

    track.style.transform = `translateX(${-x}px) translateY(-50%)`;
}

window.addEventListener("scroll", animate);
window.addEventListener("resize", animate);
animate();
document.addEventListener("DOMContentLoaded", () => {

    function infiniteMarquee(selector, direction, speed) {

        const track = document.querySelector(selector);

        if (!track) return;

        const original = track.innerHTML;

        // evita duplicação infinita
        track.innerHTML = original.repeat(10);

        let x = 0;
        const limit = track.scrollWidth / 10;

        function animate() {
            x += direction * speed;

            if (direction > 0 && x >= 0) {
                x = -limit;
            }

            if (direction < 0 && -x >= limit) {
                x = 0;
            }

            track.style.transform = `translateX(${x}px)`;

            requestAnimationFrame(animate);
        }

        animate();
    }

    infiniteMarquee(".track-right", 1, 0.6);
    infiniteMarquee(".track-left", -1, 0.6);

});
</script>



<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>