<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Painel de Controle</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- CSS personalizado -->
   <link rel="stylesheet" href="../css/admin_style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- Seção do Painel de Controle começa -->
<section class="dashboard">

   <h1 class="heading">Painel de Controle</h1>

   <div class="box-container">

      <div class="box">
         <h3>Bem-vindo!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">Atualizar Perfil</a>
      </div>

        <div class="box">
         <?php
         $select_products = $conn->prepare("SELECT * FROM `massas` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
         <h3><?= $fetch_products['quantidade']; ?></h3>
         <p>Total de Massas</p>
         <a href="editar_massas.php" class="btn">Editar Massas</a>
          <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>
      </div>

      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
               $total_pendings += $fetch_pendings['total_price'];
            }
         ?>
         <h3><span>R$</span><?= $total_pendings; ?><span>/-</span></h3>
         <p>Pagamentos Pendentes</p>
         <a href="placed_orders.php" class="btn">Ver Pedidos</a>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
               $total_completes += $fetch_completes['total_price'];
            }
         ?>
         <h3><span>R$</span><?= $total_completes; ?><span>/-</span></h3>
         <p>Pagamentos Concluídos</p>
         <a href="placed_orders.php" class="btn">Ver Pedidos</a>
      </div>

      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $numbers_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $numbers_of_orders; ?></h3>
         <p>Total de Pedidos</p>
         <a href="placed_orders.php" class="btn">Ver Pedidos</a>
      </div>

      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $numbers_of_products = $select_products->rowCount();
         ?>
         <h3><?= $numbers_of_products; ?></h3>
         <p>Produtos Cadastrados</p>
         <a href="products.php" class="btn">Ver Produtos</a>
      </div>

      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $numbers_of_users = $select_users->rowCount();
         ?>
         <h3><?= $numbers_of_users; ?></h3>
         <p>Contas de Usuários</p>
         <a href="users_accounts.php" class="btn">Ver Usuários</a>
      </div>

      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admin`");
            $select_admins->execute();
            $numbers_of_admins = $select_admins->rowCount();
         ?>
         <h3><?= $numbers_of_admins; ?></h3>
         <p>Administradores</p>
         <a href="admin_accounts.php" class="btn">Ver Admins</a>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $numbers_of_messages = $select_messages->rowCount();
         ?>
         <h3><?= $numbers_of_messages; ?></h3>
         <p>Novas Mensagens</p>
         <a href="messages.php" class="btn">Ver Mensagens</a>
      </div>

   </div>

</section>
<!-- Seção do Painel de Controle termina -->

<!-- Script JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>
