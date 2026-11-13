<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
   exit;
}

if(isset($_POST['submit'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
   $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);

   $appointment_date = $_POST['appointment_date'];
   $appointment_time = $_POST['appointment_time'];

   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      if($address == ''){

         $message[] = 'Por favor, adicione seu endereço!';

      }elseif(empty($appointment_date) || empty($appointment_time)){

         $message[] = 'Selecione uma data e horário para o agendamento!';

      }else{

         $insert_order = $conn->prepare("INSERT INTO orders
         (user_id,name,number,email,method,address,appointment_date,appointment_time,total_products,total_price)
         VALUES (?,?,?,?,?,?,?,?,?,?)");

         $insert_order->execute([
            $user_id,
            $name,
            $number,
            $email,
            $method,
            $address,
            $appointment_date,
            $appointment_time,
            $total_products,
            $total_price
         ]);

         $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         $message[] = 'Pedido realizado com sucesso!';

      }

   }else{

      $message[] = 'Seu carrinho está vazio!';

   }

}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Finalizar Pedido</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- CSS personalizado -->
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

#timeContainer{

display:grid;
grid-template-columns:repeat(auto-fill,minmax(100px,1fr));
gap:12px;
margin-top:15px;

}

.timeBox{

padding:15px;
background:#202020;
border:2px solid #444;
border-radius:8px;
cursor:pointer;
text-align:center;
font-size:16px;
font-weight:bold;
transition:.3s;
color:#fff;

}

.timeBox:hover{

background:#d4af37;
color:#000;

}

.selected{

background:#d4af37;
border-color:#d4af37;
color:#000;

}

.closed{

margin-top:15px;
padding:15px;
background:#b91c1c;
color:#fff;
border-radius:8px;
font-size:15px;

}



   </style>
</head>
<body>
   
<!-- Cabeçalho -->
<?php include 'components/user_header.php'; ?>
<!-- Fim do cabeçalho -->

<div class="heading">
   <h3>Finalizar Pedido</h3>
   <p><a href="index.php">Início</a> <span> / Finalizar Pedido</span></p>
</div>

<section class="checkout">

   <h1 class="title">Resumo do Pedido</h1>

<form action="" method="post">

   <div class="cart-items">
      <h3>Itens do Carrinho</h3>
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
      <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">R$<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">Seu carrinho está vazio!</p>';
         }
      ?>
      <p class="grand-total"><span class="name">Total Geral :</span><span class="price">R$<?= $grand_total; ?></span></p>
      <a href="cart.php" class="btn">Ver Carrinho</a>
   </div>

   <input type="hidden" name="total_products" value="<?= $total_products; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
   <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
   <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
   <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
   <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

   <div class="user-info">
      <h3>Suas Informações</h3>
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
      <a href="update_profile.php" class="btn">Atualizar Informações</a>
      <h3>Endereço de Entrega</h3>
      <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == ''){echo 'Por favor, adicione seu endereço';}else{echo $fetch_profile['address'];} ?></span></p>
      <a href="update_address.php" class="btn">Atualizar Endereço</a>
      <h3>Escolha a data do agendamento</h3>

<input
type="date"
name="appointment_date"
id="appointment_date"
class="box"
required
min="<?= date('Y-m-d'); ?>">

<div id="messageSunday"></div>

<h3>Horários disponíveis</h3>

<div id="timeContainer"></div>

<input
type="hidden"
name="appointment_time"
id="appointment_time"
required>
      <select name="method" class="box" required>
         <option value="" disabled selected>Selecione o método de pagamento --</option>
         <option value="cash on delivery">Dinheiro na entrega</option>
         <option value="credit card">Cartão de crédito</option>
         <option value="pix">Pix</option>
         <option value="paypal">PayPal</option>
      </select>
      <input type="submit" value="Finalizar Pedido" class="btn <?php if($fetch_profile['address'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
   </div>

</form>
   
</section>

<!-- Rodapé -->
<?php include 'components/footer.php'; ?>
<!-- Fim do rodapé -->

<!-- Script JS -->
<script src="js/script.js"></script>
<script>

const dateInput=document.getElementById("appointment_date");
const container=document.getElementById("timeContainer");
const hidden=document.getElementById("appointment_time");
const sunday=document.getElementById("messageSunday");

dateInput.addEventListener("change",()=>{

    container.innerHTML="";
    hidden.value="";
    sunday.innerHTML="";

    fetch("get_times.php?date="+dateInput.value)

    .then(r=>r.json())

    .then(data=>{

        if(data.status=="closed"){

            sunday.innerHTML=`
            <div class="closed">
            Não há funcionamento aos domingos.
            </div>
            `;

            return;

        }

        if(data.times.length==0){

            container.innerHTML=`
            <div class="closed">
            Não existem horários disponíveis nesta data.
            </div>
            `;

            return;

        }

        data.times.forEach(time=>{

            let box=document.createElement("div");

            box.className="timeBox";

            box.innerHTML=time;

            box.onclick=function(){

                document.querySelectorAll(".timeBox").forEach(e=>{

                    e.classList.remove("selected");

                });

                this.classList.add("selected");

                hidden.value=time;

            }

            container.appendChild(box);

        });

    });

});

</script>
</body>
</html>
