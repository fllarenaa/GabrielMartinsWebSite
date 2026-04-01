<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'E-mail ou número já cadastrados!';
   }else{
      if($pass != $cpass){
         $message[] = 'A confirmação de senha não confere!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
         $insert_user->execute([$name, $email, $number, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cadastro</title>

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

</style>
</head>
<body>
   
<!-- Cabeçalho -->
<?php include 'components/user_header.php'; ?>
<!-- Fim do Cabeçalho -->

<section class="form-container">

   <form action="" method="post">
      <h3>Crie sua conta</h3>
      <input type="text" name="name" required placeholder="Digite seu nome completo" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="Digite seu e-mail" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Digite seu número de telefone" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="Digite sua senha" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirme sua senha" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Cadastrar agora" name="submit" class="btn">
      <p>Já possui uma conta? <a href="login.php">Entrar</a></p>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<!-- JS -->
<script src="js/script.js"></script>

</body>
</html>
