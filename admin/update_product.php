<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $price, $pid]);

   $message[] = 'Produto atualizado com sucesso!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'O tamanho da imagem é muito grande!';
      }else{
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../uploaded_img/'.$old_image);
         $message[] = 'Imagem atualizada com sucesso!';
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
   <title>Atualizar Produto</title>

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

    <a href="placed_orders.php" class="nav-item active">
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
<section class="update-product">

   <h1 class="heading">Atualizar Produto</h1>

   <?php
      $update_id = $_GET['update'];
      $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $show_products->execute([$update_id]);
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      
      <span>Atualizar Nome</span>
      <input type="text" required placeholder="Digite o nome do produto" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
      
      <span>Atualizar Preço</span>
      <input type="number" min="0" max="9999999999" required placeholder="Digite o preço do produto" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">
      
      <span>Atualizar Categoria</span>
      <select name="category" class="box" required>
         <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
         <option value="" disabled selected>Selecione uma categoria --</option>
         <option value="corte de cabelo">Corte de cabelo</option>
         <option value="acabamento">Acabamento</option>
         <option value="barba">Barba</option>
         <option value="sobrancelhas">Sobrancelhas</option>
      </select>
      
      <span>Atualizar Imagem</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      
      <div class="flex-btn">
         <input type="submit" value="Atualizar" class="btn" name="update">
         <a href="products.php" class="option-btn">Voltar</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Nenhum produto encontrado!</p>';
      }
   ?>

</section>
<!-- Seção de atualização de produto termina -->

<!-- Script JS -->
<script src="../js/admin_script.js"></script>
<script>
   /* ============================================================
   ADMIN SCRIPT
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

  /* ── Menu mobile ────────────────────────────────────────── */
  const menuBtn = document.getElementById('menu-btn');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');

  function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (menuBtn) menuBtn.addEventListener('click', openSidebar);
  if (overlay) overlay.addEventListener('click', closeSidebar);

  /* Fecha sidebar ao clicar em link dentro dela (mobile) */
  if (sidebar) {
    sidebar.querySelectorAll('.nav-item').forEach(function (item) {
      item.addEventListener('click', function () {
        if (window.innerWidth <= 768) closeSidebar();
      });
    });
  }

  /* ── Flash message auto-fechar ──────────────────────────── */
  var flash = document.getElementById('flash-msg');
  if (flash) {
    setTimeout(function () {
      flash.style.transition = 'opacity .4s';
      flash.style.opacity = '0';
      setTimeout(function () { flash.remove(); }, 400);
    }, 4000);

    var closeBtn = flash.querySelector('.flash-close');
    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        flash.remove();
      });
    }
  }

  /* ── Confirmar exclusão ─────────────────────────────────── */
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      if (!confirm(el.getAttribute('data-confirm'))) {
        e.preventDefault();
      }
    });
  });

  /* ── Fechar sidebar ao redimensionar para desktop ───────── */
  window.addEventListener('resize', function () {
    if (window.innerWidth > 768) {
      closeSidebar();
      document.body.style.overflow = '';
    }
  });

  /* ── Marcar nav-item ativo pelo href atual ──────────────── */
  var currentPath = window.location.pathname.split('/').pop();
  document.querySelectorAll('.nav-item').forEach(function (item) {
    var href = item.getAttribute('href');
    if (href && href === currentPath) {
      document.querySelectorAll('.nav-item').forEach(function (i) { i.classList.remove('active'); });
      item.classList.add('active');
    }
  });

});
</script>
</body>
</html>
