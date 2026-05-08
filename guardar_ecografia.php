<?php
include("conexion.php");

$historia_clinica = $_POST['historia_clinica'];
$dni = $_POST['dni'];
$fecha = $_POST['fecha'];
$apellidos = $_POST['apellidos'];
$nombres = $_POST['nombres'];
$sexo = $_POST['sexo'];

$id_condicion = $_POST['condicion'];
$id_servicio = $_POST['servicio_solicitante'];
$id_examen = $_POST['examen_solicitado'];

$medico_turno = $_POST['medico_turno'];
$tipo_atencion = $_POST['tipo_atencion'];
$diagnostico = $_POST['diagnostico'];
$monto = $_POST['monto'] ?? null;
$numero_boleta = $_POST['numero_boleta'] ?? null;
$convenio = $_POST['convenio'] ?? null;

/* Obtener nombres desde mantenimiento */
$condicion = '';
$servicio_solicitante = '';
$examen_solicitado = '';

$res = $conexion->query("SELECT nombre FROM mantenimiento WHERE id = '$id_condicion'");
if ($row = $res->fetch_assoc()) {
    $condicion = $row['nombre'];
}

$res = $conexion->query("SELECT nombre FROM mantenimiento WHERE id = '$id_servicio'");
if ($row = $res->fetch_assoc()) {
    $servicio_solicitante = $row['nombre'];
}

$res = $conexion->query("SELECT nombre FROM mantenimiento WHERE id = '$id_examen'");
if ($row = $res->fetch_assoc()) {
    $examen_solicitado = $row['nombre'];
}

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
    monto,
    numero_boleta,
    convenio,
    id_condicion,
    id_servicio,
    id_examen,
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
    '$monto',
    '$numero_boleta',
    '$convenio',
    '$id_condicion',
    '$id_servicio',
    '$id_examen',
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