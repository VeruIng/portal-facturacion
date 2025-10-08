<?php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// KPIs simples
$res = $conexion->query("SELECT COUNT(*) as clientes FROM clientes");
$clientes = $res->fetch_assoc()['clientes'];

$res2 = $conexion->query("SELECT COUNT(*) as prefecturas FROM prefecturas");
$pref_result = $res2->fetch_assoc();
$pref = $pref_result['prefecturas'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Dashboard - Security Access</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <?php include 'partials_nav.php'; ?>
  <main class="main-content">
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre'] ?? $_SESSION['user_correo']); ?></h1>
    <div class="kpis">
      <div class="card">
        <h3>Clientes</h3>
        <p class="big"><?php echo $clientes; ?></p>
      </div>
      <div class="card">
        <h3>Prefacturas</h3>
        <p class="big"><?php echo $pref; ?></p>
      </div>
      <div class="card">
        <h3>Última actividad</h3>
        <p class="small">Ver registros</p>
      </div>
    </div>

    <section>
      <h2>Resumen rápido</h2>
      <p>Aquí puedes ver estadísticas y accesos rápidos.</p>
      <div class="quick-actions">
        <a class="btn" href="clientes.php">Consulta Clientes</a>
        <a class="btn" href="facturacion.php">Prefacturas</a>
      </div>
    </section>
  </main>
</body>
</html>