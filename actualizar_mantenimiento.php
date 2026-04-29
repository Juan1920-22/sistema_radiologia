<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id = $_POST['id'];
$tipo = $_POST['tipo'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

$stmt = $conexion->prepare("UPDATE mantenimiento SET tipo=?, nombre=?, descripcion=? WHERE id=?");
$stmt->bind_param("sssi", $tipo, $nombre, $descripcion, $id);

if ($stmt->execute()) {
    header("Location: mantenimiento.php?mensaje=editado");
    exit();
} else {
    echo "Error al actualizar";
}
?>