<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'Você já enviou essa mensagem!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);
      $message[] = 'Mensagem enviada com sucesso!';
   }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contato</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

   <!-- CSS principal -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      :root {
         --red:#b11c1c;
         --yellow:#fed330;
         --light-bg:#111;
         --input-bg:#1b1b1b;
         --white:#fff;
         --gray:#aaa;
         --border:1px solid rgba(255,255,255,0.1);
      }

      body {
         background-color: var(--light-bg);
         color: var(--white);
         font-family: 'Rubik', sans-serif;
      }

  

      /* ===== CONTATO ===== */
      .contact {
         padding: 5rem 8%;
         display: flex;
         justify-content: center;
         align-items: center;
      }

      .contact .row {
         display: flex;
         flex-wrap: wrap;
         align-items: center;
         justify-content: center;
         gap: 4rem;
         max-width: 1200px;
      }

      .contact .image img {
         width: 380px;
         max-width: 100%;
         animation: float 4s ease-in-out infinite;
      }

      @keyframes float {
         0%, 100% { transform: translateY(0); }
         50% { transform: translateY(-10px); }
      }

      .contact form {
         flex: 1 1 400px;
         background: rgba(255,255,255,0.05);
         border: var(--border);
         border-radius: 20px;
         padding: 3rem;
         backdrop-filter: blur(6px);
         box-shadow: 0 5px 25px rgba(0,0,0,0.2);
      }

      .contact form h3 {
         text-align: center;
         font-size: 2.5rem;
         margin-bottom: 2rem;
         color: var(--yellow);
         font-family: "Cinzel", serif;
      }

      .contact form .box,
      .contact form textarea {
         width: 100%;
         background: var(--input-bg);
         border: var(--border);
         border-radius: 10px;
         padding: 1.2rem 1.6rem;
         margin-bottom: 1.5rem;
         font-size: 1.6rem;
         color: var(--white);
         outline: none;
         transition: 0.3s;
      }

      .contact form .box:focus,
      .contact form textarea:focus {
         border-color: var(--yellow);
         background: #222;
      }

      .contact form textarea {
         resize: none;
         height: 12rem;
      }

      .contact form .btn {
         width: 100%;
         background: var(--red);
         color: var(--white);
         border: none;
         border-radius: 10px;
         padding: 1.2rem;
         font-size: 1.8rem;
         font-weight: 600;
         cursor: pointer;
         text-transform: uppercase;
         transition: 0.3s;
      }

      .contact form .btn:hover {
         background: var(--yellow);
         color: #111;
         letter-spacing: 0.5px;
      }

      @media (max-width:768px){
         .contact {
            padding: 3rem 5%;
         }
         .contact .row {
            flex-direction: column;
         }
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>Fale Conosco</h3>
   <p><a href="index.php">Início</a> <span> / Contato</span></p>
</div>

<section class="contact">
   <div class="row">
      <div class="image">
         <img src="images/contact-img.svg" alt="Contato">
      </div>

      <form action="" method="post">
         <h3>Envie-nos uma mensagem</h3>
         <input type="text" name="name" maxlength="50" class="box" placeholder="Digite seu nome" required>
         <input type="number" name="number" min="0" max="9999999999" class="box" placeholder="Digite seu número" required maxlength="10">
         <input type="email" name="email" maxlength="50" class="box" placeholder="Digite seu e-mail" required>
         <textarea name="msg" class="box" required placeholder="Digite sua mensagem" maxlength="500" cols="30" rows="10"></textarea>
         <input type="submit" value="Enviar mensagem" name="send" class="btn">
      </form>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
