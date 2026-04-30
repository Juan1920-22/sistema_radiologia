<?php
include("conexion.php");

$id = $_GET['id'];

$sql = "DELETE FROM ecografias WHERE id_ecografia = '$id'";

if (mysqli_query($conexion, $sql)) {
    header("Location: historial_ecografias.php?eliminado=ok");
    exit();
} else {
    echo "Error al eliminar: " . mysqli_error($conexion);
}
?>