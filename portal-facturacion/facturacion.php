<?php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$res = $conexion->query("SELECT p.id, c.nombre as cliente, p.total, p.fecha_creacion, p.estado
                         FROM prefecturas p
                         LEFT JOIN clientes c ON p.cliente_id = c.id
                         ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Prefacturas - Security Access</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <?php include 'partials_nav.php'; ?>
  <main class="main-content">
    <h1>Prefacturas</h1>
    <table class="table">
      <thead><tr><th>ID</th><th>Cliente</th><th>Total</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
      <tbody>
        <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['cliente']); ?></td>
          <td><?php echo number_format($row['total'],2,',','.'); ?></td>
          <td><?php echo $row['fecha_creacion']; ?></td>
          <td><?php echo htmlspecialchars($row['estado']); ?></td>
          <td>
            <button>Ver</button>
            <button>Aprobar</button>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</body>
</html>