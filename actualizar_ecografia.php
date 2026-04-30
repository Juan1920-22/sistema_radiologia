<?php
include("conexion.php");

$id = $_POST['id_ecografia'];
$historia_clinica = $_POST['historia_clinica'];
$dni = $_POST['dni'];
$fecha = $_POST['fecha'];
$apellidos = $_POST['apellidos'];
$nombres = $_POST['nombres'];
$sexo = $_POST['sexo'];
$condicion = $_POST['condicion'];
$servicio_solicitante = $_POST['servicio_solicitante'];
$medico_turno = $_POST['medico_turno'];
$tipo_atencion = $_POST['tipo_atencion'];
$examen_solicitado = $_POST['examen_solicitado'];
$diagnostico = $_POST['diagnostico'];
$monto = $_POST['monto'];
$numero_boleta = $_POST['numero_boleta'];
$convenio = $_POST['convenio'];

$sql = "UPDATE ecografias SET
historia_clinica='$historia_clinica',
dni='$dni',
fecha='$fecha',
apellidos='$apellidos',
nombres='$nombres',
sexo='$sexo',
condicion='$condicion',
servicio_solicitante='$servicio_solicitante',
medico_turno='$medico_turno',
tipo_atencion='$tipo_atencion',
examen_solicitado='$examen_solicitado',
diagnostico='$diagnostico',
monto='$monto',
numero_boleta='$numero_boleta',
convenio='$convenio'

WHERE id_ecografia='$id'";

if (mysqli_query($conexion, $sql)) {
    header("Location: historial_ecografias.php?editado=ok");
exit();
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}
?>