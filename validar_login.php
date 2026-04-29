<?php
session_start();
include("conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=? AND password=?");
$stmt->bind_param("ss", $usuario, $password);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $_SESSION['usuario'] = $usuario;
    header("Location: menu.php");
    exit();
} else {
    header("Location: login.php?error=1");
    exit();
}
?>