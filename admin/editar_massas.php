<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
}

// Atualiza a quantidade
if(isset($_POST['update'])){
   $id = $_POST['id'];
   $quantidade = $_POST['quantidade'];

   $update = $conn->prepare("UPDATE massas SET quantidade = ? WHERE id = ?");
   $update->execute([$quantidade, $id]);

   $message = "Quantidade atualizada com sucesso!";
}

// Busca a quantidade atual
$select = $conn->prepare("SELECT * FROM massas WHERE id = 1"); // apenas uma linha
$select->execute();
$massa = $select->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Editar Quantidade de Massas</title>

   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      body {
         background-color: #121212;
         color: #eee;
         font-family: 'Cinzel', serif;
      }

      .container {
         max-width: 400px;
         margin: 100px auto;
         background: #1e1e1e;
         padding: 30px;
         border-radius: 15px;
         box-shadow: 0 0 15px rgba(0,0,0,0.5);
         text-align: center;
      }

      h2 {
         margin-bottom: 25px;
         color: #fed330;
      }

      .box {
         width: 100%;
         padding: 10px;
         font-size: 1.6rem;
         border-radius: 8px;
         border: 1px solid #333;
         background: #222;
         color: #fff;
         text-align: center;
         margin-bottom: 20px;
      }

      .btn {
         background: #fed330;
         color: #000;
         border: none;
         padding: 10px 25px;
         border-radius: 8px;
         cursor: pointer;
         font-size: 1.6rem;
         transition: 0.2s;
      }

      .btn:hover {
         background: #b11c1c;
         color: #fff;
      }

      .msg {
         background: #173b1a;
         color: #8aff8a;
         padding: 10px;
         border-radius: 8px;
         margin-bottom: 20px;
         font-size: 1.5rem;
      }
   </style>
</head>
<body>

<div class="container">
   <h2>Editar Quantidade</h2>

   <?php if(isset($message)): ?>
      <div class="msg"><?= $message; ?></div>
   <?php endif; ?>

   <form method="POST">
      <input type="hidden" name="id" value="<?= $massa['id']; ?>">
      <input type="number" name="quantidade" class="box" value="<?= $massa['quantidade']; ?>" min="0" required>
      <button type="submit" name="update" class="btn">Salvar</button>
   </form>
</div>

</body>
</html>
