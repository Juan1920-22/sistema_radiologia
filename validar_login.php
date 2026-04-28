<?php
include("conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios 
        WHERE usuario='$usuario' AND password='$password'";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    header("Location: menu.php");
    exit();
} else {
    echo "Usuario o contraseña incorrectos ❌";
    echo "<br><a href='login.php'>Volver</a>";
}
?>