<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>



<header class="header">

   <section class="flex">

      <a href="index.php" class="logo"><img src="images/logo.png" alt=""></a>

      <nav class="navbar">
         <a href="index.php">INÍCIO</a>
         <a href="about.php">SOBRE</a>
         <a href="menu.php">SERVIÇOS</a>
         <a href="orders.php">AGENDAMENTOS</a>
         <a href="contact.php">CONTATO</a>
      </nav>

      <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search search"></i></a>
        <div class="cart"> <a href="cart.php"><i class="fas fa-shopping-cart "></i><span>(<?= $total_cart_items; ?>)</span></a></div>
         <div id="user-btn" class="fas fa-user userBtn"></div>
         <div id="menu-btn" class="fas fa-bars barras"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">Perfil</a>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">Sair</a>
         </div>
         <p class="account">
            <a href="login.php">Login</a> or
            <a href="register.php">Registre-se</a>
         </p> 
         <?php
            }else{
         ?>
            <p class="name login-warning">Realize o login primeiro, por favor!</p>
            <a href="login.php" class="btn">Login</a>
         <?php
          }
         ?>
      </div>

   </section>

</header>

