<?php
include("conexion.php");

$historia_clinica = $_POST['historia_clinica'];
$fecha = $_POST['fecha'];
$apellidos = $_POST['apellidos'];
$nombres = $_POST['nombres'];
$sexo = $_POST['sexo'];
$condicion = $_POST['condicion'];
$servicio_solicitante = $_POST['servicio_solicitante'];
$medico_turno = $_POST['medico_turno'];
$examen_solicitado = $_POST['examen_solicitado'];
$tipo_atencion = $_POST['tipo_atencion'];
$diagnostico = $_POST['diagnostico'];

$sql = "INSERT INTO ecografias (
    historia_clinica,
    fecha,
    apellidos,
    nombres,
    sexo,
    condicion,
    servicio_solicitante,
    medico_turno,
    examen_solicitado,
    tipo_atencion,
    diagnostico
) VALUES (
    '$historia_clinica',
    '$fecha',
    '$apellidos',
    '$nombres',
    '$sexo',
    '$condicion',
    '$servicio_solicitante',
    '$medico_turno',
    '$examen_solicitado',
    '$tipo_atencion',
    '$diagnostico'
)";

if ($conexion->query($sql)) {
    echo "Ecografía registrada correctamente ✅";
    echo "<br><a href='registrar_ecografia.php'>Registrar otra ecografía</a>";
    echo "<br><a href='menu.php'>Volver al menú</a>";
} else {
    echo "Error al registrar: " . $conexion->error;
}
?>