<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pedidos</title>
<link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
   <!-- Remix Icons -->
   <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

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


      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }


      .orders {
         display: flex;
         flex-direction: column;
         align-items: center;
         padding: 30px 15px;
      }

      .title {
         font-size: 26px;
         font-weight: 700;
         color: var(--black);
         margin-bottom: 20px;
         text-align: center;
      }

      .box-container {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
         gap: 25px;
         width: 100%;
         max-width: 1100px;
      }

      .pedido-box {
         background: var(--white);
         padding: 28px;
         border-radius: 20px;
         box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
         text-align: center;
         width: 330px;
         transition: all 0.3s ease;
      }

      .pedido-box:hover {
         transform: translateY(-4px);
         box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
      }

      .pedido-box h2 {
         font-size: 20px;
         font-weight: 600;
         color: #fff;
         margin-bottom: 15px;
      }

      /* Status icons */
      .status {
         display: flex;
         justify-content: space-around;
         margin-bottom: 20px;
         align-items: center;
      }

      .status label {
         display: flex;
         flex-direction: column;
         align-items: center;
         font-size: 13px;
         color: #ccc;
      }

      .status i {
         font-size: 34px;
         margin-bottom: 6px;
         color: #ccc;
         transition: 0.3s;
      }

      /* Progress bar */
      .progress {
         position: relative;
         width: 100%;
         height: 12px;
         background: #e5e5e5;
         border-radius: 10px;
         margin-bottom: 15px;
         overflow: hidden;
      }

      .bar {
         height: 100%;
         width: 0%;
         background: linear-gradient(90deg, var(--yellow), #f7b500);
         border-radius: 10px;
         transition: width 0.6s ease;
      }

      .status-mensagem {
         font-weight: 600;
         margin: 10px 0 15px 0;
         font-size: 15px;
         color: var(--black);
      }

      .pedido-box p {
         margin: 5px 0;
         font-size: 13px;
         color: #ccc;
      }

      /* Botão confirmar */
      .confirmar {
         display: flex;
         flex-direction: column;
         align-items: center;
         margin-top: 20px;
      }

      .confirmar button {
         background: linear-gradient(135deg, var(--yellow), #f7b500);
         color: var(--black);
         border: none;
         padding: 12px 25px;
         border-radius: 10px;
         cursor: pointer;
         font-weight: 700;
         font-size: 15px;
         box-shadow: 0 4px 10px rgba(254, 211, 48, 0.4);
         transition: 0.3s;
      }

      .confirmar button:hover {
         transform: translateY(-2px) scale(1.05);
         box-shadow: 0 8px 18px rgba(254, 211, 48, 0.5);
      }

      .confirmar input {
         margin-top: 10px;
         padding: 8px;
         border: 1px solid #ccc;
         border-radius: 8px;
         text-align: center;
         width: 70%;
      }

      .empty {
         font-size: 16px;
         margin-top: 50px;
         text-align: center;
      }

      /* Responsivo */
      @media (max-width: 768px) {
         .pedido-box {
            width: 90%;
         }

         .status i {
            font-size: 30px;
         }

         .confirmar button {
            width: 100%;
         }
      }
   </style>
</head>

<body>

<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>Pedidos</h3>
   <p><a href="index.php">home</a> <span> / pedidos</span></p>
</div>

<section class="orders">
   <h1 class="title">Acompanhe seus pedidos</h1>

   <div class="box-container">

   <?php
   if($user_id == ''){
      echo '<p class="empty">Por favor, faça login para ver seus pedidos.</p>';
   }else{
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
      $select_orders->execute([$user_id]);

      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){

            $order_status = $fetch_orders['payment_status']; // ou 'order_status'

            // Define status visual e mensagem
            $progress_width = '33%';
            $mensagem_status = 'Seu pedido está sendo preparado 👨‍🍳';
            $color_preparo = 'var(--yellow)';
            $color_caminho = '#ccc';
            $color_entregue = '#ccc';

            if($order_status == 'a_caminho'){
               $progress_width = '66%';
               $mensagem_status = 'Seu pedido está a caminho 🚗💨';
               $color_preparo = 'var(--yellow)';
               $color_caminho = 'var(--yellow)';
               $color_entregue = '#ccc';
            } elseif($order_status == 'completed'){
               $progress_width = '100%';
               $mensagem_status = 'Seu pedido foi entregue 🏡✨';
               $color_preparo = 'var(--yellow)';
               $color_caminho = 'var(--yellow)';
               $color_entregue = 'var(--yellow)';
            }
   ?>

   <div class="pedido-box">
      <h2><i class="ri-shopping-bag-3-fill" style="color:var(--yellow);"></i> Pedido #<?= $fetch_orders['id']; ?></h2>

      <!-- Status -->
      <div class="status">
         <label>
            <i class="ri-restaurant-line" style="color:<?= $color_preparo; ?>;"></i>
            <span>Em preparo</span>
         </label>
         <label>
            <i class="ri-truck-line" style="color:<?= $color_caminho; ?>;"></i>
            <span>A caminho</span>
         </label>
         <label>
            <i class="ri-home-4-line" style="color:<?= $color_entregue; ?>;"></i>
            <span>Entregue</span>
         </label>
      </div>

      <!-- Barra de progresso -->
      <div class="progress">
         <div class="bar" style="width: <?= $progress_width; ?>;"></div>
      </div>

      <!-- Mensagem dinâmica -->
      <div class="status-mensagem"><?= $mensagem_status; ?></div>

      <p><strong>Feito em:</strong> <?= $fetch_orders['placed_on']; ?></p>
      <p><strong>Cliente:</strong> <?= $fetch_orders['name']; ?></p>
      <p><strong>Email:</strong> <?= $fetch_orders['email']; ?></p>
      <p><strong>Telefone:</strong> <?= $fetch_orders['number']; ?></p>
      <p><strong>Endereço:</strong> <?= $fetch_orders['address']; ?></p>
      <p><strong>Pagamento:</strong> <?= $fetch_orders['method']; ?></p>
      <p><strong>Itens:</strong> <?= $fetch_orders['total_products']; ?></p>
      <p><strong>Total:</strong> $<?= $fetch_orders['total_price']; ?>/-</p>

      <?php if($order_status == 'completed'){ ?>
      <div class="confirmar">
         <p><strong>Confirme a entrega:</strong></p>
         <!-- <input type="password" placeholder="Digite o PIN"> -->
         <br>
         <button style=" color: green;"><i class="ri-check-line"></i> Confirmar Entrega</button>
      </div>
      <?php } ?>
   </div>

   <?php
         }
      }else{
         echo '<p class="empty">Nenhum pedido encontrado!</p>';
      }
   }
   ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="script.js"></script>

</body>
</html>
