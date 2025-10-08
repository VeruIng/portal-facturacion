<?php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Búsqueda simple
$q = $_GET['q'] ?? '';
if ($q) {
    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE nombre LIKE CONCAT('%', ?, '%') OR nit LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("ss", $q, $q);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conexion->query("SELECT * FROM clientes ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Clientes - Security Access</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <?php include 'partials_nav.php'; ?>
  <main class="main-content">
    <h1>Clientes</h1>
    <form method="get" action="clientes.php" class="search-form">
      <input type="text" name="q" placeholder="Buscar por nombre o NIT" value="<?php echo htmlspecialchars($q); ?>">
      <button type="submit">Buscar</button>
    </form>

    <table class="table">
      <thead>
        <tr><th>ID</th><th>Nombre</th><th>NIT</th><th>Contrato</th><th>Valor</th><th>Fecha</th><th>Comercial</th></tr>
      </thead>
      <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['nombre']); ?></td>
          <td><?php echo htmlspecialchars($row['nit']); ?></td>
          <td><?php echo htmlspecialchars($row['contrato']); ?></td>
          <td><?php echo number_format($row['valor'], 2, ',', '.'); ?></td>
          <td><?php echo htmlspecialchars($row['fecha_registro']); ?></td>
          <td><?php echo htmlspecialchars($row['comercial']); ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</body>
</html>