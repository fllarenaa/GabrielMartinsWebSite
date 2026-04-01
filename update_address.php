<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

if(isset($_POST['submit'])){

   $address = $_POST['flat'] .', '.$_POST['building'].', '.$_POST['area'].', '.$_POST['town'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $update_address = $conn->prepare("UPDATE `users` SET address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   $message[] = 'Endereço salvo com sucesso!';

}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Atualizar Endereço</title>

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
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Seu Endereço</h3>

      <input type="text" class="box" placeholder="Número do apartamento" required maxlength="50" name="flat">
      <input type="text" class="box" placeholder="Número do prédio" required maxlength="50" name="building">
      <input type="text" class="box" placeholder="Nome da rua ou bairro" required maxlength="50" name="area">
      <input type="text" class="box" placeholder="Nome da localidade" required maxlength="50" name="town">
      <input type="text" class="box" placeholder="Cidade" required maxlength="50" name="city">
      <input type="text" class="box" placeholder="Estado" required maxlength="50" name="state">
      <input type="text" class="box" placeholder="País" required maxlength="50" name="country">
      <input type="number" class="box" placeholder="CEP" required max="999999" min="0" maxlength="6" name="pin_code">

      <input type="submit" value="Salvar endereço" name="submit" class="btn">
   </form>

</section>

<?php include 'components/footer.php'; ?>

<!-- JS -->
<script src="js/script.js"></script>

</body>
</html>
