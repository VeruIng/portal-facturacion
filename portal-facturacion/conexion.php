<?php
// conexion.php
$host = "localhost";
$user = "root";
$pass = ""; // XAMPP default
$dbname = "facturacion_db";

$conexion = new mysqli($host, $user, $pass, $dbname);
if ($conexion->connect_errno) {
    die("Error conexión MySQL: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");
?>