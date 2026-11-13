<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
      $select_name->execute([$name]);
      if($select_name->rowCount() > 0){
         $message[] = 'Este nome de usuário já está em uso!';
      }else{
         $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
         $update_name->execute([$name, $admin_id]);
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'A senha antiga não confere!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'A confirmação da senha não confere!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'Senha atualizada com sucesso!';
         }else{
            $message[] = 'Por favor, insira uma nova senha!';
         }
      }
   }

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
   <title>Atualizar Perfil</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
 <script type="module" src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.js"></script>
   <link rel="stylesheet" href="../css/admin_style.css?v=<?php echo time(); ?>">
</head>
<body>

<!-- <?php include '../components/admin_header.php'; ?> -->

<div class="admin-layout">

  <!-- SIDEBAR -->
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

    <a href="placed_orders.php" class="nav-item">
        <ion-icon name="cash-outline"></ion-icon>
        Pedidos
    </a>

    <p class="nav-section">Gestão</p>

    <a href="users_accounts.php" class="nav-item">
        <ion-icon name="people-outline"></ion-icon>
        Usuários
    </a>

    <a href="admin_accounts.php" class="nav-item">
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
        <a href="update_profile.php" class="nav-item active">
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
<section class="form-container">

   <form action="" method="POST">
      <h3>Atualizar Perfil</h3>
      <input type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['name']; ?>">
      <input type="password" name="old_pass" maxlength="20" placeholder="Digite sua senha atual" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" maxlength="20" placeholder="Digite sua nova senha" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" maxlength="20" placeholder="Confirme sua nova senha" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Atualizar Agora" name="submit" class="btn">
   </form>

</section>
<!-- Seção de atualização de perfil termina -->

<!-- Script JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>
