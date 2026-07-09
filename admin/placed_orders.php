<?php
include '../components/connect.php';
session_start();

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pedidos Realizados</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link rel="stylesheet" href="../css/admin_style.css?v=<?= time() ?>">
  <link rel="stylesheet" href="../css/placed_orders_style.css?v=<?php echo time(); ?>">

  <script type="module" src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.js"></script>
  
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

    <div class="content">

      <!-- Flash -->
      <?php if(isset($message)): foreach($message as $msg): ?>
      <div class="flash-message flash-success" id="flash-msg">
        <span><i class="ti ti-circle-check" style="margin-right:.5rem"></i><?= $msg ?></span>
        <i class="ti ti-x flash-close"></i>
      </div>
      <?php endforeach; endif; ?>

      <!-- Cabeçalho -->
      <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:2rem">
        <h1 class="page-heading" style="margin-bottom:0">Pedidos Realizados</h1>
        <?php
          $q = $conn->prepare("SELECT COUNT(*) FROM `orders`");
          $q->execute();
          $total_orders = $q->fetchColumn();
        ?>
        <span class="text-muted"><?= $total_orders ?> pedido<?= $total_orders != 1 ? 's' : '' ?> no total</span>
      </div>

      <!-- Grid de pedidos -->
      <div class="orders-grid">
      <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders` ORDER BY placed_on DESC");
        $select_orders->execute();
        if ($select_orders->rowCount() > 0):
          while ($o = $select_orders->fetch(PDO::FETCH_ASSOC)):
            $st  = $o['payment_status'];
            $bcl = $st === 'completed' ? 'badge-done' : ($st === 'pending' ? 'badge-pending' : 'badge-cancelled');
            $spt = $st === 'completed' ? 'Concluído' : ($st === 'pending' ? 'Pendente' : 'Cancelado');
      ?>
      <div class="order-card">

        <!-- Header -->
        <div class="order-card-header">
          <div>
            <span class="order-id">#<?= $o['id'] ?></span>
            <span class="order-date"><?= date('d/m/Y H:i', strtotime($o['placed_on'])) ?></span>
          </div>
          <span class="status-badge <?= $bcl ?>"><?= $spt ?></span>
        </div>

        <!-- Cliente -->
        <div class="order-section-label">Cliente</div>
        <div class="order-info-grid">
          <div class="order-info-item">
            <span class="order-info-label"><i class="ti ti-user"></i> Nome</span>
            <span class="order-info-value"><?= htmlspecialchars($o['name']) ?></span>
          </div>
          <div class="order-info-item">
            <span class="order-info-label"><i class="ti ti-phone"></i> Telefone</span>
            <span class="order-info-value"><?= htmlspecialchars($o['number']) ?></span>
          </div>
          <div class="order-info-item order-info-item--full">
            <span class="order-info-label"><i class="ti ti-mail"></i> E-mail</span>
            <span class="order-info-value"><?= htmlspecialchars($o['email']) ?></span>
          </div>
          <div class="order-info-item order-info-item--full">
            <span class="order-info-label"><i class="ti ti-map-pin"></i> Endereço</span>
            <span class="order-info-value"><?= htmlspecialchars($o['address']) ?></span>
          </div>
        </div>

        <!-- Agendamento -->
        <?php if(!empty($o['appointment_date'])): ?>
        <div class="order-section-label">Agendamento</div>
        <div class="order-info-grid">
          <div class="order-info-item">
            <span class="order-info-label"><i class="ti ti-calendar"></i> Data</span>
            <span class="order-info-value"><?= date('d/m/Y', strtotime($o['appointment_date'])) ?></span>
          </div>
          <div class="order-info-item">
            <span class="order-info-label"><i class="ti ti-clock"></i> Horário</span>
            <span class="order-info-value"><?= htmlspecialchars($o['appointment_time']) ?></span>
          </div>
        </div>
        <?php endif; ?>

        <!-- Pedido -->
        <div class="order-section-label">Pedido</div>
        <div class="order-info-grid">
          <div class="order-info-item">
            <span class="order-info-label"><i class="ti ti-package"></i> Produtos</span>
            <span class="order-info-value"><?= htmlspecialchars($o['total_products']) ?></span>
          </div>
          <div class="order-info-item">
            <span class="order-info-label"><i class="ti ti-credit-card"></i> Pagamento</span>
            <span class="order-info-value"><?= htmlspecialchars($o['method']) ?></span>
          </div>
        </div>

        <!-- Total -->
        <div class="order-total">
          <span>Total do pedido</span>
          <span class="order-total-value">R$ <?= number_format($o['total_price'],2,',','.') ?></span>
        </div>

        <!-- Ações -->
        <form action="" method="POST" style="margin-top:1.4rem">
          <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
          <div style="margin-bottom:1rem">
            <label class="form-label">Atualizar status</label>
            <select name="payment_status" class="form-input" style="padding:1rem 1.2rem;cursor:pointer">
              <option value="" disabled selected><?= $spt ?></option>
              <option value="pending">Pendente</option>
              <option value="completed">Concluído</option>
            </select>
          </div>
          <div class="order-actions">
            <button type="submit" name="update_payment" class="btn btn-primary">
              <i class="ti ti-refresh"></i> Atualizar
            </button>
            <a href="placed_orders.php?delete=<?= $o['id'] ?>"
               class="btn btn-danger"
               data-confirm="Tem certeza que deseja excluir este pedido?">
              <i class="ti ti-trash"></i> Excluir
            </a>
          </div>
        </form>

      </div>
      <?php endwhile; else: ?>
        <p class="empty-state"><i class="ti ti-shopping-cart-off"></i>Nenhum pedido realizado ainda!</p>
      <?php endif; ?>
      </div>

    </div>
  </div>
</div>

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

<script src="../js/admin_script.js"></script>
</body>
</html>