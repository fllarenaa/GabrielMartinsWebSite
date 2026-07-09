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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Página de Pesquisa</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- CSS -->
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
   
<!-- Cabeçalho -->
<?php include 'components/user_header.php'; ?>
<!-- Fim do Cabeçalho -->

<!-- Formulário de pesquisa -->
<section class="search-form">
   <form method="post" action="">
      <input type="text" name="search_box" placeholder="Pesquise aqui..." class="box">
      <button type="submit" name="search_btn" class="fas fa-search"></button>
   </form>
</section>
<!-- Fim do formulário de pesquisa -->


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


<!-- Rodapé -->
<?php include 'components/footer.php'; ?>
<!-- Fim do rodapé -->

<!-- JS -->
<script src="js/script.js"></script>

</body>
</html>
