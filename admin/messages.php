<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mensagens</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- CSS personalizado -->
   <link rel="stylesheet" href="../css/admin_style.css?v=<?php echo time(); ?>">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- Seção de mensagens começa -->
<section class="messages">

   <h1 class="heading">Mensagens Recebidas</h1>

   <div class="box-container">

   <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();
      if($select_messages->rowCount() > 0){
         while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Nome: <span><?= $fetch_messages['name']; ?></span> </p>
      <p> Número: <span><?= $fetch_messages['number']; ?></span> </p>
      <p> E-mail: <span><?= $fetch_messages['email']; ?></span> </p>
      <p> Mensagem: <span><?= $fetch_messages['message']; ?></span> </p>
      <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Deseja excluir esta mensagem?');">Excluir</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Você não tem nenhuma mensagem.</p>';
      }
   ?>

   </div>

</section>
<!-- Seção de mensagens termina -->

<!-- Script JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>
