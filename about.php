<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>sobre</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- arquivo css personalizado -->
   <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
   <style>
      :root{
         --main-color:#4834d4;
         --red:#e74c3c;
         --orange:#f39c12;
         --black:#34495e;
         --white:rgba(28, 28, 28, 1);
         --light-bg:rgba(7, 7, 7, 1);
         --light-color:#999;
         --border:.2rem solid var(--black);
         --box-shadow:0 .5rem 1rem rgba(0,0,0,.1);
      }

      body {
         background-color: var(--light-bg);
      }
      .box img {
         filter: invert(1);
      }
   </style>
</head>
<body>
   
<!-- início do cabeçalho -->
<?php include 'components/user_header.php'; ?>
<!-- fim do cabeçalho -->

<div class="heading">
   <h3>sobre nós</h3>
   <p><a href="index.php">início</a> <span> / sobre</span></p>
</div>

<!-- seção sobre -->
<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
         <h3>por que escolher a gente?</h3>
         <p>Na nossa pizzaria, cada prato é preparado com ingredientes selecionados e amor pela tradição italiana. Nosso compromisso é oferecer uma experiência autêntica, saborosa e acolhedora, com atendimento rápido e pizzas que encantam do primeiro ao último pedaço!</p>
         <a href="menu.php" class="btn">nosso cardápio</a>
      </div>

   </div>

</section>
<!-- fim da seção sobre -->

<!-- seção de etapas -->
<section class="steps">

   <h1 class="title">passos simples</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/step-1.png" alt="">
         <h3>escolha seu pedido</h3>
         <p>Escolha sua pizza favorita ou uma das nossas massas artesanais preparadas na hora.</p>
      </div>

      <div class="box">
         <img src="images/step-2.png" alt="">
         <h3>entrega rápida</h3>
         <p>Nosso time garante que seu pedido chegue quentinho e com a melhor qualidade.</p>
      </div>

      <div class="box">
         <img src="images/step-3.png" alt="">
         <h3>aprecie sua refeição</h3>
         <p>Desfrute de uma verdadeira experiência italiana sem sair de casa.</p>
      </div>

   </div>

</section>
<!-- fim da seção de etapas -->

<!-- seção de avaliações -->
<section class="reviews">

   <h1 class="title">avaliações dos clientes</h1>

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <img src="images/pic-1.png" alt="">
            <p>A pizza é maravilhosa! Massa leve e ingredientes frescos. Recomendo demais!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>João Silva</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-2.png" alt="">
            <p>Atendimento rápido e cordial. A pizza chegou quentinha e deliciosa!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Maria Santos</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-3.png" alt="">
            <p>Melhor pizzaria da cidade! O sabor é realmente tradicional italiano.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Carlos Pereira</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-4.png" alt="">
            <p>Simplesmente perfeita! A pizza marguerita me lembrou as de Nápoles.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Ana Oliveira</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-5.png" alt="">
            <p>Ambiente agradável e sabor inesquecível. Voltarei com certeza!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Lucas Almeida</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-6.png" alt="">
            <p>O atendimento é ótimo e a pizza doce é simplesmente incrível!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Fernanda Costa</h3>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>
<!-- fim da seção de avaliações -->

<!-- início do rodapé -->
<?php include 'components/footer.php'; ?>
<!-- fim do rodapé -->

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- arquivo js personalizado -->
<script src="js/script.js"></script>

<script>
var swiper = new Swiper(".reviews-slider", {
   loop:true,
   grabCursor: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 1,
      },
      700: {
         slidesPerView: 2,
      },
      1024: {
         slidesPerView: 3,
      },
   },
});
</script>

</body>
</html>
