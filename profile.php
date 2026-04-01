<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Perfil</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- CSS -->
   <link rel="stylesheet" href="css/style.css">
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
.user-details .user img {
   filter: invert(1);  
}

</style>
</head>
<body>
   
<!-- Cabeçalho -->
<?php include 'components/user_header.php'; ?>
<!-- Fim do Cabeçalho -->

<section class="user-details">

   <div class="user">
      <?php
         // aqui geralmente é feito o SELECT no banco para buscar os dados do usuário
         // exemplo:
         // $select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
         // $select_profile->execute([$user_id]);
         // $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <img src="images/user-icon.png" alt="Usuário">
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name']; ?></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number']; ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email']; ?></span></p>
      <a href="update_profile.php" class="btn">Atualizar informações</a>
      <p class="address"><i class="fas fa-map-marker-alt"></i>
         <span>
            <?php 
               if($fetch_profile['address'] == ''){
                  echo 'Por favor, adicione seu endereço';
               }else{
                  echo $fetch_profile['address'];
               } 
            ?>
         </span>
      </p>
      <a href="update_address.php" class="btn">Atualizar endereço</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<!-- Script -->
<script src="js/script.js"></script>

</body>
</html>
