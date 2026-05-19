<?php
include("conexion.php");

// --- 1. Recibir datos del formulario ---
$historia_clinica = $_POST['historia_clinica'];
$dni = $_POST['dni'];
$fecha = $_POST['fecha'];
$apellidos = $_POST['apellidos'];
$nombres = $_POST['nombres'];
$sexo = $_POST['sexo'];

$id_condicion = $_POST['condicion'];
$id_servicio = $_POST['servicio_solicitante'];
$examenes_seleccionados = $_POST['examenes_solicitados'] ?? []; // ARRAY de IDs seleccionados

$medico_turno = $_POST['medico_turno'];
$tipo_atencion = $_POST['tipo_atencion'];
$diagnostico = $_POST['diagnostico'];
$monto = $_POST['monto'] ?? null;
$numero_boleta = $_POST['numero_boleta'] ?? null;
$convenio = $_POST['convenio'] ?? null;
$hora = !empty($_POST['hora_examen']) ? $_POST['hora_examen'] : null;

// --- 2. Obtener nombres de condición y servicio desde mantenimiento ---
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

// --- 3. Obtener nombres de los exámenes seleccionados ---
$examen_nombres = [];

foreach ($examenes_seleccionados as $id_examen) {
    $res = $conexion->query("SELECT nombre FROM mantenimiento WHERE id = '$id_examen'");
    if ($row = $res->fetch_assoc()) {
        $examen_nombres[] = $row['nombre'];
    }
}

// --- 4. Concatenar nombres y IDs ---
$examen_solicitado = implode(", ", $examen_nombres); // Para mostrar en historial
$id_examenes = implode(",", $examenes_seleccionados); // Para referencia de IDs

// --- 5. Insertar en la tabla 'ecografias' ---
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
    hora_examen,
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
    '$hora',
    '$id_condicion',
    '$id_servicio',
    '$id_examenes',
    NOW()
)";

// --- 6. Ejecutar consulta y redireccionar ---
if ($conexion->query($sql) === TRUE) {
    header("Location: registrar_ecografia.php?mensaje=ok");
    exit();
} else {
    header("Location: registrar_ecografia.php?mensaje=error");
    exit();
}
?>