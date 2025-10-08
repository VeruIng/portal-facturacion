<?php
// setup.php - Ejecutar UNA sola vez: http://localhost/portal-facturacion/setup.php
$host = "localhost";
$user = "root";
$pass = ""; // XAMPP por defecto sin password
$dbname = "facturacion_db";

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Crear BD
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
$conn->query($sql);

// Conectar a DB creada
$conn->select_db($dbname);

// Crear tablas
$conn->query("
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  correo VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  nombre VARCHAR(100),
  rol VARCHAR(50) DEFAULT 'admin',
  creado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

$conn->query("
CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150),
  nit VARCHAR(50),
  contrato VARCHAR(100),
  valor DECIMAL(12,2) DEFAULT 0,
  fecha_registro DATE,
  comercial VARCHAR(100)
) ENGINE=InnoDB;
");

$conn->query("
CREATE TABLE IF NOT EXISTS prefecturas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT,
  fecha_creacion DATE,
  total DECIMAL(12,2),
  estado VARCHAR(50) DEFAULT 'borrador',
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL
) ENGINE=InnoDB;
");

// Insertar admin por defecto si no existe
$correo_admin = 'admin@securityaccess.com';
$pass_admin_plain = 'Admin1234';
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo_admin);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $hash = password_hash($pass_admin_plain, PASSWORD_DEFAULT);
    $stmt2 = $conn->prepare("INSERT INTO usuarios (correo, contrasena, nombre, rol) VALUES (?, ?, ?, 'admin')");
    $nombre_admin = "Administrador";
    $stmt2->bind_param("sss", $correo_admin, $hash, $nombre_admin);
    $stmt2->execute();
    echo "Usuario admin creado: $correo_admin / $pass_admin_plain<br>";
} else {
    echo "Usuario admin ya existe.<br>";
}
$stmt->close();

// Insertar clientes de ejemplo si no hay
$res = $conn->query("SELECT COUNT(*) as c FROM clientes");
$row = $res->fetch_assoc();
if ($row['c'] == 0) {
    $conn->query("INSERT INTO clientes (nombre, nit, contrato, valor, fecha_registro, comercial) VALUES
    ('ACME S.A.S.', '900123456-1', 'Paquete Premium', 1200000.00, '2025-01-10', 'Juan Pérez'),
    ('Transportes XYZ', '800987654-2', 'Consumo', 350000.00, '2024-11-05', 'Ana López'),
    ('Plaza Real', '901234567-8', 'Paquete Básico', 450000.00, '2025-02-20', 'Carlos Ruiz')
    ");
    echo "Clientes de ejemplo insertados.<br>";
}

echo "<br>Setup completado. Por seguridad elimina o renombra setup.php después de ejecutarlo.";
$conn->close();
?>