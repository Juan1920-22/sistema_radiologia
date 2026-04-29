<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$tipo = $_POST['tipo'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

$sql = "INSERT INTO mantenimiento (tipo, nombre, descripcion) 
        VALUES (?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $tipo, $nombre, $descripcion);

if ($stmt->execute()) {
    header("Location: mantenimiento.php?mensaje=guardado");
    exit();
} else {
    echo "Error al guardar: " . $conexion->error;
}
?>