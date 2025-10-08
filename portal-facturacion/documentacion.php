<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Documentación - Security Access</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <?php include 'partials_nav.php'; ?>
  <main class="main-content">
    <h1>Documentación</h1>
    <p>Listado de documentos por cliente (vista simulada en esta entrega).</p>
    <ul>
      <li>ACME S.A.S. - Contrato_servicio.pdf - 02/2025</li>
      <li>Transportes XYZ - Soporte_tecnico.zip - 11/2024</li>
      <li>Plaza Real - Contrato_PlazaReal.pdf - 02/2025</li>
    </ul>
  </main>
</body>
</html>