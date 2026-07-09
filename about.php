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
   <h3>sobre mim</h3>
   <p><a href="index.php">início</a> <span> / sobre</span></p>
</div>

<!-- seção sobre -->
<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about.png" alt="">
      </div>

      <div class="content">
         <h3>Por que escolher o meus serviços?</h3>
         <p>Escolher um barbeiro vai muito além de encontrar alguém que saiba cortar cabelo. É confiar em um profissional que entende a importância de cada detalhe e trabalha com dedicação para entregar um resultado que valorize sua aparência e aumente sua autoestima.

Faço cada atendimento com amor e paixão pela profissão, porque acredito que a barbearia é uma forma de transformar a imagem e a confiança de cada cliente. Não se trata apenas de um corte ou de uma barba bem feita, mas de proporcionar uma experiência de qualidade, conforto e atenção personalizada.

Minha experiência em visagismo me permite analisar o formato do seu rosto, seu estilo de vida e sua personalidade para indicar o corte e o acabamento que mais combinam com você. Assim, cada serviço é pensado de forma exclusiva, respeitando sua identidade e destacando seus melhores traços.

Além da técnica e da experiência, estou sempre buscando aperfeiçoamento, acompanhando as tendências do mercado e utilizando métodos modernos para oferecer um atendimento de alto padrão. Meu compromisso é entregar qualidade, precisão e um resultado que supere suas expectativas.

Se você procura um profissional comprometido, apaixonado pelo que faz e que coloca dedicação em cada detalhe, será um prazer cuidar do seu visual.</p>
         <a href="menu.php" class="btn">nosso cardápio</a>
      </div>

   </div>

</section>
<!-- fim da seção sobre -->


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
