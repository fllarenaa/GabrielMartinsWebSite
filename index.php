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
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
<link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="newstyle.css?v=<?php echo time(); ?>">

   <style>
      :root{
   --main-color:#4834d4;
   --red:#e74c3c;
   --orange:#f39c12;
   /* --black:rgba(39, 39, 39, 1); */
   --white:rgba(28, 28, 28, 1);
   --light-bg:rgba(7, 7, 7, 1);
   --light-color:#999;
   --border:.2rem solid var(--black);
   --box-shadow:0 .5rem 1rem rgba(0,0,0,.1);
}

body {
   background-color: var(--light-bg);
}




   </style>
   
   
</head>
<body>

<?php include 'components/user_header.php'; ?>



<section class="hero">

     
         <div class="images"> 
            <div class="info" style="width: 7%;">Stella Maris</div>
            <h3><span>D</span>ove l’arte 
            <br> incontra il gusto.</h3>
         <!-- <img src="ondas.png" alt=""> -->
      
        
      </div>

</section>
    <br> <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<!-- <section class="category">

   <h1 class="title">food category</h1>

   <div class="box-container">

      <a href="category.php?category=fast food" class="box">
         <img src="images/cat-1.png" alt="">
         <h3>fast food</h3>
      </a>

      <a href="category.php?category=main dish" class="box">
         <img src="images/cat-2.png" alt="">
         <h3>main dishes</h3>
      </a>

      <a href="category.php?category=drinks" class="box">
         <img src="images/cat-3.png" alt="">
         <h3>drinks</h3>
      </a>

      <a href="category.php?category=desserts" class="box">
         <img src="images/cat-4.png" alt="">
         <h3>desserts</h3>
      </a>

   </div>

</section> -->
<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about.png" alt="">
      </div>

      <div class="content">
         <h3>Benvenuti alla Pizzeria La Tradizione!</h3>
         <p>Na <strong>Stella Maris</strong>, cada fatia conta uma história de sabor e tradição. Nossas pizzas são preparadas com massa artesanal, fermentada lentamente e assadas em forno à lenha, como manda a autêntica receita napoletana. Utilizamos ingredientes frescos e selecionados, desde o molho de tomates italianos ao manjericão perfumado, garantindo um sabor inigualável.</p>
         <p>“La pizza non è solo cibo… è amore, è arte, è Italia!” Escolher a nossa pizzaria é saborear a verdadeira essência italiana, com o calor da hospitalidade, o toque da tradição e a paixão que faz de cada pizza uma obra de arte. Ordina subito e senti il vero gusto dell’Italia!</p>
         <a href="menu.php" class="btn">Ver Cardápio</a>
      </div>

   </div>

</section>


<section class="daily-dough">
 <?php
         $select_products = $conn->prepare("SELECT * FROM `massas` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
   <div class="row">

 
  
      <div class="content">
         <h3>Massas do dia: <span class="highlight"><?= $fetch_products['quantidade']; ?></span></h3>
         <p>Preparamos uma quantidade limitada de <strong>10 massas frescas</strong> por dia, seguindo o método tradicional napoletano. Cada massa é fermentada lentamente, garantindo textura leve e sabor inconfundível.</p>
         <p><em>As massas do dia estão disponíveis apenas nas terças e quartas-feiras</em>, garantindo o frescor e a exclusividade que fazem da <strong>Pizzeria La Tradizione</strong> uma verdadeira experiência italiana.</p>
         <p>Chegou a hora certa? Então venha saborear o melhor da Itália no dia certo!</p>
      </div>

   </div>
       <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>

</section>




<!-- about section ends -->

<!-- steps section starts  -->



<section class="steps">
  <h1 class="title">Sobre a Pizzeria</h1>

  <div class="box-container">

    <div class="box">
      <h3>Escolha o Pedido</h3>
      <h4>O início da experiência</h4>
      <div class="divider"></div>
      <p>Comece selecionando seu prato favorito — cada receita é inspirada nas tradições de Nápoles e Toscana, preparada com ingredientes autênticos e paixão italiana.</p>
      <p>Nosso cardápio é pensado para despertar memórias e sabores que conectam o coração à mesa.</p>
      <div class="quote">“Na Itália, comer é um ato de amor.”</div>
      <p class="note">Dica: escolha combinações que valorizem o frescor dos ingredientes — como massa artesanal e azeite extravirgem.</p>
    </div>

    <div class="box">
      <h3>Entrega Rápida</h3>
      <h4>Tradicional, mas moderna</h4>
      <div class="divider"></div>
      <p>Assim como um espresso bem tirado, o tempo é essencial. Nossa equipe garante que o sabor e a temperatura cheguem perfeitos à sua mesa.</p>
      <p>Usamos embalagens sustentáveis que mantêm o calor e o aroma original, respeitando o planeta e a tradição italiana.</p>
      <div class="quote">“A velocidade é moderna; o sabor é eterno.”</div>
      <p class="note">Nossos motoboys são treinados para preservar a integridade do prato — sem pressa, mas sem demora.</p>
    </div>

    <div class="box">
      <h3>Aproveite o Sabor</h3>
      <h4>O momento italiano</h4>
      <div class="divider"></div>
      <p>Sente-se, relaxe e permita-se saborear a simplicidade — porque cada garfada é uma viagem pela Itália.</p>
      <p>Deixe o aroma do manjericão e do parmesão envolver o ambiente e transformar sua refeição em um ritual de prazer e tradição.</p>
      <div class="quote">“Mangiare bene è vivere bene.” (Comer bem é viver bem.)</div>
      <p class="note">Sirva com vinho tinto suave e boa companhia — o segredo da verdadeira cozinha italiana.</p>
    </div>

  </div>
</section>


<!-- steps section ends -->

<!-- reviews section starts  -->





<section class="products">

   <h1 class="title">Cardápio</h1>

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
         
         <div class="actions">
            <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="view">Ver Produto</a>
            <button type="submit" name="add_to_cart" class="cart">Adicionar</button>
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



















<?php include 'components/footer.php'; ?>


<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".hero-slider", {
   loop:true,
   grabCursor: true,
   effect: "flip",
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
});

</script>

</body>
</html>