<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id = $_GET['id'];

$stmt = $conexion->prepare("DELETE FROM mantenimiento WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: mantenimiento.php?mensaje=eliminado");
    exit();
} else {
    echo "Error al eliminar";
}
?>