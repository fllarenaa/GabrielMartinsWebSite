<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

// ── Totais de pedidos ──────────────────────────────────────────
$total_pendings   = 0;
$count_pendings   = 0;
$select_pendings  = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
$select_pendings->execute(['pending']);
while($row = $select_pendings->fetch(PDO::FETCH_ASSOC)){
   $total_pendings += $row['total_price'];
   $count_pendings++;
}

$total_completes  = 0;
$count_completes  = 0;
$select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
$select_completes->execute(['completed']);
while($row = $select_completes->fetch(PDO::FETCH_ASSOC)){
   $total_completes += $row['total_price'];
   $count_completes++;
}

$select_orders   = $conn->prepare("SELECT * FROM `orders`");
$select_orders->execute();
$numbers_of_orders = $select_orders->rowCount();

$select_products = $conn->prepare("SELECT * FROM `products`");
$select_products->execute();
$numbers_of_products = $select_products->rowCount();

$select_users    = $conn->prepare("SELECT * FROM `users`");
$select_users->execute();
$numbers_of_users = $select_users->rowCount();

$select_admins   = $conn->prepare("SELECT * FROM `admin`");
$select_admins->execute();
$numbers_of_admins = $select_admins->rowCount();

$select_messages = $conn->prepare("SELECT * FROM `messages`");
$select_messages->execute();
$numbers_of_messages = $select_messages->rowCount();

// ── Massas ────────────────────────────────────────────────────
// $select_massas = $conn->prepare("SELECT SUM(quantidade) as total FROM `massas`");
// $select_massas->execute();
// $row_massas    = $select_massas->fetch(PDO::FETCH_ASSOC);
// $total_massas  = $row_massas['total'] ?? 0;

// ── Pedidos por dia (últimos 14 dias) ─────────────────────────
$orders_by_day_labels = [];
$orders_by_day_counts = [];
$orders_by_day_values = [];

$select_by_day = $conn->prepare("
   SELECT DATE(placed_on) as dia,
          COUNT(*) as total_pedidos,
          SUM(total_price) as receita
   FROM `orders`
   WHERE placed_on >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
   GROUP BY DATE(placed_on)
   ORDER BY dia ASC
");
$select_by_day->execute();
while($row = $select_by_day->fetch(PDO::FETCH_ASSOC)){
   $orders_by_day_labels[] = date('d/m', strtotime($row['dia']));
   $orders_by_day_counts[] = (int)$row['total_pedidos'];
   $orders_by_day_values[] = (float)$row['receita'];
}

// ── Pedidos por hora hoje ──────────────────────────────────────
$orders_by_hour_labels = [];
$orders_by_hour_counts = [];

$select_by_hour = $conn->prepare("
   SELECT HOUR(placed_on) as hora, COUNT(*) as total
   FROM `orders`
   WHERE DATE(placed_on) = CURDATE()
   GROUP BY HOUR(placed_on)
   ORDER BY hora ASC
");
$select_by_hour->execute();
$raw_hours = [];
while($row = $select_by_hour->fetch(PDO::FETCH_ASSOC)){
   $raw_hours[(int)$row['hora']] = (int)$row['total'];
}
for($h = 0; $h < 24; $h++){
   $orders_by_hour_labels[] = str_pad($h, 2, '0', STR_PAD_LEFT) . 'h';
   $orders_by_hour_counts[] = $raw_hours[$h] ?? 0;
}

// ── Lucro por mês (últimos 6 meses) ───────────────────────────
$profit_labels  = [];
$profit_values  = [];
$pending_values = [];

$select_profit = $conn->prepare("
   SELECT DATE_FORMAT(placed_on, '%Y-%m') as mes,
          SUM(CASE WHEN payment_status = 'completed' THEN total_price ELSE 0 END) as lucro,
          SUM(CASE WHEN payment_status = 'pending'   THEN total_price ELSE 0 END) as pendente
   FROM `orders`
   WHERE placed_on >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
   GROUP BY mes
   ORDER BY mes ASC
");
$select_profit->execute();
while($row = $select_profit->fetch(PDO::FETCH_ASSOC)){
   $meses_pt = ['01'=>'Jan','02'=>'Fev','03'=>'Mar','04'=>'Abr','05'=>'Mai','06'=>'Jun',
                '07'=>'Jul','08'=>'Ago','09'=>'Set','10'=>'Out','11'=>'Nov','12'=>'Dez'];
   $parts = explode('-', $row['mes']);
   $profit_labels[]  = $meses_pt[$parts[1]] . '/' . substr($parts[0], 2);
   $profit_values[]  = (float)$row['lucro'];
   $pending_values[] = (float)$row['pendente'];
}

// JSON para JS
$js_day_labels    = json_encode($orders_by_day_labels);
$js_day_counts    = json_encode($orders_by_day_counts);
$js_day_values    = json_encode($orders_by_day_values);
$js_hour_labels   = json_encode($orders_by_hour_labels);
$js_hour_counts   = json_encode($orders_by_hour_counts);
$js_profit_labels = json_encode($profit_labels);
$js_profit_values = json_encode($profit_values);
$js_pending_vals  = json_encode($pending_values);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Painel de Controle</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
   <script type="module" src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.js"></script>
   <link rel="stylesheet" href="../css/admin_style.css?v=<?php echo time(); ?>">
 <link rel="stylesheet" href="../css/admin_style2.css?v=<?php echo time(); ?>">
   
</head>
<body>

<!-- <?php include '../components/admin_header.php'; ?> -->

<div class="admin-layout">

   <!-- ═══════════════════════════ SIDEBAR ═══════════════════════════ -->
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

         <!-- ── KPIs ─────────────────────────────────────────── -->
         <div class="kpi-row">
            <div class="kpi-card">
               <div class="kpi-label"><i class="ti ti-clock-hour-4"></i> Pagamentos pendentes</div>
               <div class="kpi-value">R$ <?= number_format($total_pendings, 2, ',', '.') ?></div>
               <div class="kpi-delta warn">↑ <?= $count_pendings ?> pedidos aguardando</div>
            </div>
            <div class="kpi-card">
               <div class="kpi-label"><i class="ti ti-circle-check"></i> Pagamentos concluídos</div>
               <div class="kpi-value">R$ <?= number_format($total_completes, 2, ',', '.') ?></div>
               <div class="kpi-delta up">✓ <?= $count_completes ?> pedidos pagos</div>
            </div>
            <div class="kpi-card">
               <div class="kpi-label"><i class="ti ti-shopping-cart"></i> Total de pedidos</div>
               <div class="kpi-value"><?= $numbers_of_orders ?></div>
               <div class="kpi-delta up">↑ atualizado agora</div>
            </div>
            <div class="kpi-card">
               <div class="kpi-label"><i class="ti ti-package"></i> Produtos cadastrados</div>
               <div class="kpi-value"><?= $numbers_of_products ?></div>
               <div class="kpi-delta up"><a href="products.php" style="color:inherit">Ver produtos →</a></div>
            </div>
            <div class="kpi-card">
               <div class="kpi-label"><i class="ti ti-users"></i> Usuários</div>
               <div class="kpi-value"><?= $numbers_of_users ?></div>
               <div class="kpi-delta up"><a href="users_accounts.php" style="color:inherit">Ver usuários →</a></div>
            </div>
            <div class="kpi-card">
               <div class="kpi-label"><i class="ti ti-wheat"></i> Administradores</div>
               <div class="kpi-value"><?= $numbers_of_admins; ?></div>
               <div class="kpi-delta up"><a href="editar_massas.php" style="color:inherit">Editar Permissões →</a></div>
            </div>
             <div class="kpi-card">
               <div class="kpi-label"><i class="ti ti-wheat"></i> Mensagens</div>
               <div class="kpi-value"><?= $numbers_of_messages; ?></div>
               <div class="kpi-delta up"><a href="editar_massas.php" style="color:inherit">Ver mensagens →</a></div>
            </div>
         </div>

         <!-- ── Gráfico: Pagamentos (Qtd e Valor) ─────────────── -->
         <div class="chart-card" style="margin-bottom:1.4rem">
            <div class="chart-card-title">
               Pagamentos — Quantidade e Valor
               <span class="chart-sub">pendentes vs. concluídos</span>
            </div>
            <div class="charts-row" style="margin-bottom:0">
               <div>
                  <p style="font-size:1.2rem;color:var(--text-muted);margin-bottom:.8rem">Quantidade de pedidos</p>
                  <div style="position:relative;height:18rem">
                     <canvas id="chartQtd" role="img" aria-label="Gráfico de barras: quantidade de pedidos pendentes vs concluídos">
                        Pendentes: <?= $count_pendings ?>, Concluídos: <?= $count_completes ?>
                     </canvas>
                  </div>
               </div>
               <div>
                  <p style="font-size:1.2rem;color:var(--text-muted);margin-bottom:.8rem">Valor total (R$)</p>
                  <div style="position:relative;height:18rem">
                     <canvas id="chartValor" role="img" aria-label="Gráfico de rosca: valor pendente vs concluído">
                        Pendentes: R$ <?= $total_pendings ?>, Concluídos: R$ <?= $total_completes ?>
                     </canvas>
                  </div>
                  <div class="chart-legend" style="justify-content:center;margin-top:.8rem">
                     <span><span class="legend-dot" style="background:#eda100"></span>Pendentes R$ <?= number_format($total_pendings,2,',','.') ?></span>
                     <span><span class="legend-dot" style="background:#1baf7a"></span>Concluídos R$ <?= number_format($total_completes,2,',','.') ?></span>
                  </div>
               </div>
            </div>
         </div>

         <!-- ── Gráfico: Pedidos por hora hoje ────────────────── -->
         <div class="chart-card" style="margin-bottom:1.4rem">
            <div class="chart-card-title">
               Pedidos de hoje por hora
               <span class="chart-sub"><?= date('d/m/Y') ?></span>
            </div>
            <div style="position:relative;height:18rem">
               <canvas id="chartHora" role="img" aria-label="Gráfico de linha com pedidos por hora do dia atual">Pedidos por hora de hoje.</canvas>
            </div>
         </div>

         <!-- ── Gráfico: Pedidos por dia (14 dias) ─────────────── -->
         <div class="chart-card" style="margin-bottom:1.4rem">
            <div class="chart-card-title">
               Pedidos recebidos
               <span class="chart-sub">últimos 14 dias</span>
            </div>
            <div class="chart-legend">
               <span><span class="legend-dot" style="background:#2a78d6"></span>Qtd. de pedidos</span>
            </div>
            <div style="position:relative;height:20rem">
               <canvas id="chartDia" role="img" aria-label="Gráfico de barras com quantidade de pedidos por dia nos últimos 14 dias">Pedidos por dia.</canvas>
            </div>
         </div>

         <!-- ── Gráfico: Lucro mensal ─────────────────────────── -->
         <div class="chart-card" style="margin-bottom:2rem">
            <div class="chart-card-title">
               Lucro mensal
               <span class="chart-sub">últimos 6 meses</span>
            </div>
            <div class="chart-legend">
               <span><span class="legend-dot" style="background:#1baf7a"></span>Concluídos</span>
               <span><span class="legend-dot" style="background:#eda100"></span>Pendentes</span>
            </div>
            <div style="position:relative;height:22rem">
               <canvas id="chartLucro" role="img" aria-label="Gráfico de linha com lucro e pendências mensais dos últimos 6 meses">Lucro por mês.</canvas>
            </div>
         </div>

      </div><!-- /content -->
   </div><!-- /main-content -->
</div><!-- /admin-layout -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
// ── Dados do PHP ──────────────────────────────────────────────
const dayLabels    = <?= $js_day_labels ?>;
const dayCounts    = <?= $js_day_counts ?>;
const dayValues    = <?= $js_day_values ?>;
const hourLabels   = <?= $js_hour_labels ?>;
const hourCounts   = <?= $js_hour_counts ?>;
const profitLabels = <?= $js_profit_labels ?>;
const profitValues = <?= $js_profit_values ?>;
const pendingVals  = <?= $js_pending_vals ?>;

const countPending   = <?= $count_pendings ?>;
const countComplete  = <?= $count_completes ?>;
const totalPending   = <?= $total_pendings ?>;
const totalComplete  = <?= $total_completes ?>;

// ── Helpers ───────────────────────────────────────────────────
const grid  = getComputedStyle(document.documentElement).getPropertyValue('--border').trim() || 'rgba(0,0,0,.08)';
const tick  = '#898781';
const brl   = v => 'R$ ' + Number(v).toLocaleString('pt-BR', {minimumFractionDigits:2});

const baseOpts = {
   responsive: true,
   maintainAspectRatio: false,
   plugins: { legend: { display: false } },
   scales: {
      x: { grid:{color:grid}, ticks:{color:tick,font:{size:11}}, border:{display:false} },
      y: { grid:{color:grid}, ticks:{color:tick,font:{size:11}}, border:{display:false} }
   }
};

// ── 1. Qtd. Pendentes vs Concluídos (barras horizontais) ─────
new Chart(document.getElementById('chartQtd'), {
   type: 'bar',
   data: {
      labels: ['Pendentes', 'Concluídos'],
      datasets: [{
         data: [countPending, countComplete],
         backgroundColor: ['#eda100', '#1baf7a'],
         borderRadius: 4,
         borderSkipped: false
      }]
   },
   options: {
      ...baseOpts,
      indexAxis: 'y',
      plugins: { legend:{display:false}, tooltip:{callbacks:{label: c => c.raw + ' pedidos'}} },
      scales: {
         x: { grid:{color:grid}, ticks:{color:tick,font:{size:11},stepSize:1}, border:{display:false} },
         y: { grid:{display:false}, ticks:{color:tick,font:{size:12,weight:'500'}}, border:{display:false} }
      }
   }
});

// ── 2. Valor Pendentes vs Concluídos (rosca) ─────────────────
new Chart(document.getElementById('chartValor'), {
   type: 'doughnut',
   data: {
      labels: ['Pendentes', 'Concluídos'],
      datasets: [{
         data: [totalPending, totalComplete],
         backgroundColor: ['#eda100', '#1baf7a'],
         borderWidth: 0,
         hoverOffset: 6
      }]
   },
   options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '68%',
      plugins: {
         legend: { display:false },
         tooltip: { callbacks: { label: c => c.label + ': ' + brl(c.raw) } }
      }
   }
});

// ── 3. Pedidos por hora hoje (linha) ─────────────────────────
new Chart(document.getElementById('chartHora'), {
   type: 'line',
   data: {
      labels: hourLabels,
      datasets: [{
         label: 'Pedidos',
         data: hourCounts,
         borderColor: '#2a78d6',
         backgroundColor: 'rgba(42,120,214,0.08)',
         borderWidth: 2,
         pointRadius: 4,
         pointBackgroundColor: '#2a78d6',
         pointBorderColor: '#fff',
         pointBorderWidth: 2,
         tension: 0.4,
         fill: true
      }]
   },
   options: {
      ...baseOpts,
      plugins: { legend:{display:false}, tooltip:{callbacks:{label: c => c.raw + ' pedido(s)'}} },
      scales: {
         x: {
            grid:{color:grid},
            ticks:{
               color:tick, font:{size:10},
               autoSkip:false, maxRotation:45,
               callback: (val,i) => (i % 2 === 0) ? hourLabels[i] : ''
            },
            border:{display:false}
         },
         y: {
            grid:{color:grid},
            ticks:{color:tick,font:{size:11},stepSize:1},
            border:{display:false},
            beginAtZero:true
         }
      }
   }
});

// ── 4. Pedidos por dia — últimos 14 dias (barras) ─────────────
new Chart(document.getElementById('chartDia'), {
   type: 'bar',
   data: {
      labels: dayLabels,
      datasets: [{
         label: 'Pedidos',
         data: dayCounts,
         backgroundColor: '#2a78d6',
         borderRadius: 4,
         borderSkipped: false
      }]
   },
   options: {
      ...baseOpts,
      plugins: { legend:{display:false}, tooltip:{callbacks:{label: c => c.raw + ' pedido(s)'}} },
      scales: {
         x: { grid:{display:false}, ticks:{color:tick,font:{size:11}}, border:{display:false} },
         y: {
            grid:{color:grid},
            ticks:{color:tick,font:{size:11},stepSize:1},
            border:{display:false},
            beginAtZero:true
         }
      }
   }
});

// ── 5. Lucro mensal — últimos 6 meses (linhas) ───────────────
new Chart(document.getElementById('chartLucro'), {
   type: 'line',
   data: {
      labels: profitLabels,
      datasets: [
         {
            label: 'Concluídos',
            data: profitValues,
            borderColor: '#1baf7a',
            backgroundColor: 'rgba(27,175,122,0.08)',
            borderWidth: 2,
            pointRadius: 5,
            pointBackgroundColor: '#1baf7a',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            tension: 0.4,
            fill: true
         },
         {
            label: 'Pendentes',
            data: pendingVals,
            borderColor: '#eda100',
            backgroundColor: 'rgba(237,161,0,0.07)',
            borderWidth: 2,
            pointRadius: 5,
            pointBackgroundColor: '#eda100',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            tension: 0.4,
            fill: true
         }
      ]
   },
   options: {
      ...baseOpts,
      plugins: { legend:{display:false}, tooltip:{callbacks:{label: c => c.dataset.label + ': ' + brl(c.raw)}} },
      scales: {
         x: { grid:{display:false}, ticks:{color:tick,font:{size:12}}, border:{display:false} },
         y: {
            grid:{color:grid},
            ticks:{color:tick,font:{size:11}, callback: v => 'R$' + (v/1000).toFixed(0) + 'k'},
            border:{display:false},
            beginAtZero:true
         }
      }
   }
});

// ── Menu mobile ───────────────────────────────────────────────
document.getElementById('menu-btn').addEventListener('click', () => {
   document.getElementById('sidebar').classList.toggle('open');
});
</script>

<script src="../js/admin_script.js"></script>
</body>
</html>