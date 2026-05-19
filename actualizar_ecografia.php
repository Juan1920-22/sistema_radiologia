<?php
include("conexion.php");

// --- 1. Recibir datos del formulario ---
$id = $_POST['id_ecografia'];
$historia_clinica = $_POST['historia_clinica'];
$dni = $_POST['dni'];
$fecha = $_POST['fecha'];
$apellidos = $_POST['apellidos'];
$nombres = $_POST['nombres'];
$sexo = $_POST['sexo'];

$id_condicion = $_POST['condicion'];
$id_servicio = $_POST['servicio_solicitante'];
$examenes_seleccionados = $_POST['examenes_solicitados'] ?? []; // Ahora es array para varios exámenes

$medico_turno = $_POST['medico_turno'];
$tipo_atencion = $_POST['tipo_atencion'];
$diagnostico = $_POST['diagnostico'];
$monto = $_POST['monto'] ?? null;
$numero_boleta = $_POST['numero_boleta'] ?? null;
$convenio = $_POST['convenio'] ?? null;
$hora = !empty($_POST['hora_examen']) ? $_POST['hora_examen'] : null;

// --- 2. Obtener nombres desde mantenimiento ---
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
foreach ($examenes_seleccionados as $id_ex) {
    $res = $conexion->query("SELECT nombre FROM mantenimiento WHERE id = '$id_ex'");
    if ($row = $res->fetch_assoc()) {
        $examen_nombres[] = $row['nombre'];
    }
}
$id_examen = implode(",", $examenes_seleccionados);      // IDs separados por coma
$examen_solicitado = implode(", ", $examen_nombres);     // Nombres concatenados

// --- 4. Actualizar registro ---
$sql = "UPDATE ecografias SET
historia_clinica='$historia_clinica',
dni='$dni',
fecha='$fecha',
apellidos='$apellidos',
nombres='$nombres',
sexo='$sexo',
condicion='$condicion',
id_condicion='$id_condicion',
servicio_solicitante='$servicio_solicitante',
id_servicio='$id_servicio',
medico_turno='$medico_turno',
tipo_atencion='$tipo_atencion',
examen_solicitado='$examen_solicitado',
id_examen='$id_examen',
diagnostico='$diagnostico',
monto='$monto',
numero_boleta='$numero_boleta',
convenio='$convenio',
hora_examen='$hora'
WHERE id_ecografia='$id'";

// --- 5. Ejecutar y redirigir ---
if (mysqli_query($conexion, $sql)) {
    header("Location: historial_ecografias.php?editado=ok");
    exit();
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}
?>