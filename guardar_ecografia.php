<?php
include("conexion.php");

$historia_clinica = $_POST['historia_clinica'];
$dni = $_POST['dni'];
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
    dni,
    fecha,
    apellidos,
    nombres,
    sexo,
    condicion,
    servicio_solicitante,
    medico_turno,
    examen_solicitado,
    tipo_atencion,
    diagnostico,
    fecha_registro
) VALUES (
    '$historia_clinica',
    '$dni',
    '$fecha',
    '$apellidos',
    '$nombres',
    '$sexo',
    '$condicion',
    '$servicio_solicitante',
    '$medico_turno',
    '$examen_solicitado',
    '$tipo_atencion',
    '$diagnostico',
    NOW()
)";

if ($conexion->query($sql) === TRUE) {
    header("Location: registrar_ecografia.php?mensaje=ok");
    exit();
} else {
   header("Location: registrar_ecografia.php?mensaje=error");
exit();
}
?>