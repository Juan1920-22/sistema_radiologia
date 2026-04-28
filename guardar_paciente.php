<?php
include("conexion.php");

$dni = $_POST['dni'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$edad = $_POST['edad'];

$sql = "INSERT INTO pacientes (dni, nombres, apellidos, edad)
        VALUES ('$dni', '$nombres', '$apellidos', '$edad')";

if ($conexion->query($sql)) {
    echo "Paciente guardado correctamente ✅";
    echo "<br><a href='registrar_paciente.php'>Registrar otro paciente</a>";
} else {
    echo "Error al guardar: " . $conexion->error;
}
?>