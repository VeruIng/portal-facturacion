<?php
session_start();
require_once 'conexion.php';

$err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    if (empty($correo) || empty($contrasena)) {
        $err = "Complete correo y contraseña.";
    } else {
        $stmt = $conexion->prepare("SELECT id, correo, contrasena, nombre, rol FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if (password_verify($contrasena, $user['contrasena'])) {
                // Login correcto
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_correo'] = $user['correo'];
                $_SESSION['user_nombre'] = $user['nombre'];
                $_SESSION['user_rol'] = $user['rol'];
                header("Location: index.php");
                exit;
            } else {
                $err = "Credenciales inválidas.";
            }
        } else {
            $err = "Usuario no encontrado.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login - Security Access</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="login-page">
  <div class="container-login">
    <h2>Facturación Electrónica Security Access</h2>
    <?php if($err): ?>
      <div class="alert"><?php echo htmlspecialchars($err); ?></div>
    <?php endif; ?>
    <form method="post" action="login.php">
      <label>Correo</label>
      <input type="email" name="correo" required>
      <label>Contraseña</label>
      <input type="password" name="contrasena" required>
      <button type="submit">Iniciar sesión</button>
    </form>
    <p>Usuario demo: <strong>admin@securityaccess.com</strong> | Contraseña: <strong>Admin1234</strong></p>
  </div>
</body>
</html>