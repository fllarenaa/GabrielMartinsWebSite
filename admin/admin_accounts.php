<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<?php


$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) { header('location:admin_login.php'); exit; }

if (isset($_POST['update_payment'])) {
  $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?")
       ->execute([$_POST['payment_status'], $_POST['order_id']]);
  $message[] = 'Status de pagamento atualizado com sucesso!';
}

if (isset($_GET['delete'])) {
  $conn->prepare("DELETE FROM `orders` WHERE id = ?")->execute([$_GET['delete']]);
  header('location:placed_orders.php'); exit;
}

$q = $conn->prepare("SELECT COUNT(*) FROM `messages`");
$q->execute();
$numbers_of_messages = $q->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contas de Administradores</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <script type="module" src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.js"></script>
   <link rel="stylesheet" href="../css/admin_style.css?v=<?php echo time(); ?>">

</head>
<body>

<!-- <?php include '../components/admin_header.php'; ?> -->

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo"><a href="dashboard.php">Gm<span>Admin</span></a></div>

    <p class="nav-section">Principal</p>

    <a href="dashboard.php" class="nav-item">
        <ion-icon name="home-outline"></ion-icon>
        Painel
    </a>

    <a href="products.php" class="nav-item">
        <ion-icon name="cube-outline"></ion-icon>
        Produtos
    </a>

    <a href="placed_orders.php" class="nav-item ">
        <ion-icon name="cash-outline"></ion-icon>
        Pedidos
    </a>

    <p class="nav-section">Gestão</p>

    <a href="users_accounts.php" class="nav-item">
        <ion-icon name="people-outline"></ion-icon>
        Usuários
    </a>

    <a href="admin_accounts.php" class="nav-item active">
        <ion-icon name="person-add-outline"></ion-icon>
        Admins
    </a>

    <a href="messages.php" class="nav-item">
        <ion-icon name="mail-open-outline"></ion-icon>
        Mensagens
        <?php if($numbers_of_messages > 0): ?>
            <span class="nav-badge"><?= $numbers_of_messages ?></span>
        <?php endif; ?>
    </a>

    <div class="sidebar-footer">
        <a href="update_profile.php" class="nav-item">
            <ion-icon name="person-circle-outline"></ion-icon>
            Atualizar perfil
        </a>

        <a href="../components/admin_logout.php" class="nav-item">
            <ion-icon name="exit-outline"></ion-icon>
            Sair
        </a>
    </div>
</aside>

<!-- MAIN -->
<div class="main-content">
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <ion-icon style="font-size:3em;" id="menu-btn" name="menu-outline"></ion-icon></i>
            <span class="topbar-title">Pedidos Realizados</span>
        </div>

        <div class="topbar-right">
            <a href="messages.php" class="topbar-icon">
                <ion-icon name="mail-open-outline"></ion-icon>
                <?php if($numbers_of_messages > 0): ?>
                    <span class="notif-dot"></span>
                <?php endif; ?>
            </a>

            <div class="avatar">
                <?= strtoupper(substr($fetch_profile['name'] ?? 'AD', 0, 2)) ?>
            </div>
        </div>
    </div>
<section class="accounts">

   <h1 class="heading">Contas de Administradores</h1>

   <div class="box-container">

      <div class="box">
         <p>Registrar novo administrador</p>
         <a href="register_admin.php" class="option-btn">Registrar</a>
      </div>

      <?php
         $select_account = $conn->prepare("SELECT * FROM `admin`");
         $select_account->execute();
         if($select_account->rowCount() > 0){
            while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <div class="box">
         <p>ID do Admin: <span><?= $fetch_accounts['id']; ?></span></p>
         <p>Nome de Usuário: <span><?= $fetch_accounts['name']; ?></span></p>
         <div class="flex-btn">
            <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Deseja realmente excluir esta conta?');">Excluir</a>
            <?php
               if($fetch_accounts['id'] == $admin_id){
                  echo '<a href="update_profile.php" class="option-btn">Atualizar</a>';
               }
            ?>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">Nenhuma conta disponível.</p>';
         }
      ?>

   </div>

</section>
<!-- Seção de contas de administradores termina -->

<!-- Script JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>
