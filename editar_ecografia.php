<?php
include("conexion.php");

$id = $_GET['id'];

$sql = "SELECT * FROM ecografias WHERE id_ecografia = '$id'";
$resultado = mysqli_query($conexion, $sql);
$fila = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Ecografía</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f1f5f9;
    margin: 0;
    padding: 40px;
}

.formulario {
    max-width: 1000px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,.12);
}

h1 {
    text-align: center;
    color: #1e3a8a;
}

.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

.campo {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: bold;
    margin-bottom: 6px;
}

input, select, textarea {
    padding: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
}

textarea {
    resize: vertical;
}

.acciones {
    margin-top: 25px;
    display: flex;
    gap: 12px;
}

.btn-guardar {
    background: #2563eb;
    color: white;
    padding: 13px 22px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
}

.btn-volver {
    background: #64748b;
    color: white;
    padding: 13px 22px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
}
</style>
</head>

<body>

<div class="formulario">

<h1>Editar Ecografía</h1>

<form action="actualizar_ecografia.php" method="POST">

<input type="hidden" name="id_ecografia" value="<?php echo $fila['id_ecografia']; ?>">

<div class="grid">

    <div class="campo">
        <label>Historia Clínica:</label>
        <input type="text" name="historia_clinica" value="<?php echo $fila['historia_clinica']; ?>">
    </div>

    <div class="campo">
        <label>DNI:</label>
        <input type="text" name="dni" value="<?php echo $fila['dni']; ?>">
    </div>

    <div class="campo">
        <label>Fecha:</label>
        <input type="date" name="fecha" value="<?php echo $fila['fecha']; ?>">
    </div>

    <div class="campo">
        <label>Apellidos:</label>
        <input type="text" name="apellidos" value="<?php echo $fila['apellidos']; ?>">
    </div>

    <div class="campo">
        <label>Nombres:</label>
        <input type="text" name="nombres" value="<?php echo $fila['nombres']; ?>">
    </div>

    <div class="campo">
        <label>Sexo:</label>
        <select name="sexo">
            <option value="Hombre" <?php if($fila['sexo']=="Hombre") echo "selected"; ?>>Hombre</option>
            <option value="Mujer" <?php if($fila['sexo']=="Mujer") echo "selected"; ?>>Mujer</option>
        </select>
    </div>

    <div class="campo">
        <label>Condición:</label>
        <select name="condicion">
            <option value="Asegurado" <?php if($fila['condicion']=="Asegurado") echo "selected"; ?>>Asegurado</option>
            <option value="No asegurado" <?php if($fila['condicion']=="No asegurado") echo "selected"; ?>>No asegurado</option>
            <option value="Referido" <?php if($fila['condicion']=="Referido") echo "selected"; ?>>Referido</option>
            <option value="Particular" <?php if($fila['condicion']=="Particular") echo "selected"; ?>>Particular</option>
        </select>
    </div>

    <div class="campo">
        <label>Servicio solicitante:</label>
<select name="servicio_solicitante">

<option value="">Seleccione</option>

<option <?php if($fila['servicio_solicitante']=="Cardiología") echo "selected"; ?>>Cardiología</option>
<option <?php if($fila['servicio_solicitante']=="Cirugía") echo "selected"; ?>>Cirugía</option>
<option <?php if($fila['servicio_solicitante']=="Consultorio externo") echo "selected"; ?>>Consultorio externo</option>
<option <?php if($fila['servicio_solicitante']=="COVID-19") echo "selected"; ?>>COVID-19</option>
<option <?php if($fila['servicio_solicitante']=="Dengue") echo "selected"; ?>>Dengue</option>
<option <?php if($fila['servicio_solicitante']=="Emergencia") echo "selected"; ?>>Emergencia</option>
<option <?php if($fila['servicio_solicitante']=="Endocrinología") echo "selected"; ?>>Endocrinología</option>
<option <?php if($fila['servicio_solicitante']=="Gastroenterología") echo "selected"; ?>>Gastroenterología</option>
<option <?php if($fila['servicio_solicitante']=="Ginecología") echo "selected"; ?>>Ginecología</option>
<option <?php if($fila['servicio_solicitante']=="Infectología") echo "selected"; ?>>Infectología</option>
<option <?php if($fila['servicio_solicitante']=="Medicina") echo "selected"; ?>>Medicina</option>
<option <?php if($fila['servicio_solicitante']=="Medicina física y rehabilitación") echo "selected"; ?>>Medicina física y rehabilitación</option>
<option <?php if($fila['servicio_solicitante']=="Nefrología") echo "selected"; ?>>Nefrología</option>
<option <?php if($fila['servicio_solicitante']=="Neonatología") echo "selected"; ?>>Neonatología</option>
<option <?php if($fila['servicio_solicitante']=="Neumología") echo "selected"; ?>>Neumología</option>
<option <?php if($fila['servicio_solicitante']=="Neurología") echo "selected"; ?>>Neurología</option>
<option <?php if($fila['servicio_solicitante']=="Obstetricia") echo "selected"; ?>>Obstetricia</option>
<option <?php if($fila['servicio_solicitante']=="Oncología") echo "selected"; ?>>Oncología</option>
<option <?php if($fila['servicio_solicitante']=="Otorrinolaringología") echo "selected"; ?>>Otorrinolaringología</option>
<option <?php if($fila['servicio_solicitante']=="Pediatría") echo "selected"; ?>>Pediatría</option>
<option <?php if($fila['servicio_solicitante']=="Psiquiatría") echo "selected"; ?>>Psiquiatría</option>
<option <?php if($fila['servicio_solicitante']=="Reumatología") echo "selected"; ?>>Reumatología</option>
<option <?php if($fila['servicio_solicitante']=="Shock trauma") echo "selected"; ?>>Shock trauma</option>
<option <?php if($fila['servicio_solicitante']=="Traumatología") echo "selected"; ?>>Traumatología</option>
<option <?php if($fila['servicio_solicitante']=="UCIN") echo "selected"; ?>>UCIN</option>
<option <?php if($fila['servicio_solicitante']=="Urología") echo "selected"; ?>>Urología</option>
<option <?php if($fila['servicio_solicitante']=="UVI") echo "selected"; ?>>UVI</option>
<option <?php if($fila['servicio_solicitante']=="UVICLIN") echo "selected"; ?>>UVICLIN</option>
<option <?php if($fila['servicio_solicitante']=="Vésico prostático") echo "selected"; ?>>Vésico prostático</option>

</select>
    </div>

    <div class="campo">
        <label>Médico turno:</label>
<select name="medico_turno">

<option value="">Seleccione</option>

<option <?php if($fila['medico_turno']=="BETTY CABRERA BENAVIDES") echo "selected"; ?>>BETTY CABRERA BENAVIDES</option>
<option <?php if($fila['medico_turno']=="ELIEL MARAZA AQUINO") echo "selected"; ?>>ELIEL MARAZA AQUINO</option>
<option <?php if($fila['medico_turno']=="FERNANDO DANIGNO AVALOS") echo "selected"; ?>>FERNANDO DANIGNO AVALOS</option>
<option <?php if($fila['medico_turno']=="HUGO ALZAMORA SEBASTIAN") echo "selected"; ?>>HUGO ALZAMORA SEBASTIAN</option>
<option <?php if($fila['medico_turno']=="JORGE LUIS MUÑANTE APARCANA") echo "selected"; ?>>JORGE LUIS MUÑANTE APARCANA</option>
<option <?php if($fila['medico_turno']=="LUIS NAVARRO VALDIVIESO") echo "selected"; ?>>LUIS NAVARRO VALDIVIESO</option>
<option <?php if($fila['medico_turno']=="MARITZA FLORES PAREDES") echo "selected"; ?>>MARITZA FLORES PAREDES</option>
<option <?php if($fila['medico_turno']=="SONIA VALENZUELA RAMIREZ") echo "selected"; ?>>SONIA VALENZUELA RAMIREZ</option>
<option <?php if($fila['medico_turno']=="VILA TOMAYQUISPE MANUEL") echo "selected"; ?>>VILA TOMAYQUISPE MANUEL</option>

</select>
    </div>

    <div class="campo">
        <label>Tipo de atención:</label>
        <select name="tipo_atencion">
            <option value="Particular" <?php if($fila['tipo_atencion']=="Particular") echo "selected"; ?>>Particular</option>
            <option value="Periférica" <?php if($fila['tipo_atencion']=="Periférica") echo "selected"; ?>>Periférica</option>
            <option value="Ambulatorio" <?php if($fila['tipo_atencion']=="Ambulatorio") echo "selected"; ?>>Ambulatorio</option>
            <option value="Emergencia" <?php if($fila['tipo_atencion']=="Emergencia") echo "selected"; ?>>Emergencia</option>
            <option value="Hospitalaria" <?php if($fila['tipo_atencion']=="Hospitalaria") echo "selected"; ?>>Hospitalaria</option>
        </select>
    </div>

    <div class="campo">
        <label>Examen solicitado:</label>
<select name="examen_solicitado">

<option value="">Seleccione</option>

<option <?php if($fila['examen_solicitado']=="Abdominal superior") echo "selected"; ?>>Abdominal superior</option>
<option <?php if($fila['examen_solicitado']=="Retroperitoneal") echo "selected"; ?>>Retroperitoneal</option>
<option <?php if($fila['examen_solicitado']=="Pélvica") echo "selected"; ?>>Pélvica</option>
<option <?php if($fila['examen_solicitado']=="Transvaginal") echo "selected"; ?>>Transvaginal</option>
<option <?php if($fila['examen_solicitado']=="Obstetricia") echo "selected"; ?>>Obstetricia</option>
<option <?php if($fila['examen_solicitado']=="Renal y vejiga") echo "selected"; ?>>Renal y vejiga</option>
<option <?php if($fila['examen_solicitado']=="Vejiga y próstata") echo "selected"; ?>>Vejiga y próstata</option>
<option <?php if($fila['examen_solicitado']=="Transrectal") echo "selected"; ?>>Transrectal</option>
<option <?php if($fila['examen_solicitado']=="De pulmones") echo "selected"; ?>>De pulmones</option>
<option <?php if($fila['examen_solicitado']=="Ecografía de partes blandas") echo "selected"; ?>>Ecografía de partes blandas</option>
<option <?php if($fila['examen_solicitado']=="Transfontanelar") echo "selected"; ?>>Transfontanelar</option>
<option <?php if($fila['examen_solicitado']=="Tejidos blandos de cuero cabelludo") echo "selected"; ?>>Tejidos blandos de cuero cabelludo</option>
<option <?php if($fila['examen_solicitado']=="Tejidos blandos de cuello") echo "selected"; ?>>Tejidos blandos de cuello</option>
<option <?php if($fila['examen_solicitado']=="De tiroides") echo "selected"; ?>>De tiroides</option>
<option <?php if($fila['examen_solicitado']=="De mamas") echo "selected"; ?>>De mamas</option>
<option <?php if($fila['examen_solicitado']=="Tejidos blandos de tórax") echo "selected"; ?>>Tejidos blandos de tórax</option>
<option <?php if($fila['examen_solicitado']=="Tejidos blandos de abdomen") echo "selected"; ?>>Tejidos blandos de abdomen</option>
<option <?php if($fila['examen_solicitado']=="Tejidos blandos de pelvis") echo "selected"; ?>>Tejidos blandos de pelvis</option>
<option <?php if($fila['examen_solicitado']=="Testicular") echo "selected"; ?>>Testicular</option>
<option <?php if($fila['examen_solicitado']=="Histerosonografía") echo "selected"; ?>>Histerosonografía</option>
<option <?php if($fila['examen_solicitado']=="De hernia umbilical") echo "selected"; ?>>De hernia umbilical</option>
<option <?php if($fila['examen_solicitado']=="De hernia inguinal") echo "selected"; ?>>De hernia inguinal</option>
<option <?php if($fila['examen_solicitado']=="De hernia inguinal bilateral") echo "selected"; ?>>De hernia inguinal bilateral</option>
<option <?php if($fila['examen_solicitado']=="Eventración") echo "selected"; ?>>Eventración</option>
<option <?php if($fila['examen_solicitado']=="Partes blandas tumoraciones-colecciones") echo "selected"; ?>>Partes blandas tumoraciones-colecciones</option>
<option <?php if($fila['examen_solicitado']=="Doppler carotídeo") echo "selected"; ?>>Doppler carotídeo</option>
<option <?php if($fila['examen_solicitado']=="Doppler ABC. SUP") echo "selected"; ?>>Doppler ABC. SUP</option>
<option <?php if($fila['examen_solicitado']=="Doppler renal") echo "selected"; ?>>Doppler renal</option>
<option <?php if($fila['examen_solicitado']=="Doppler prostático") echo "selected"; ?>>Doppler prostático</option>
<option <?php if($fila['examen_solicitado']=="Doppler testicular") echo "selected"; ?>>Doppler testicular</option>
<option <?php if($fila['examen_solicitado']=="Doppler ginecología") echo "selected"; ?>>Doppler ginecología</option>
<option <?php if($fila['examen_solicitado']=="Doppler obstétrico") echo "selected"; ?>>Doppler obstétrico</option>
<option <?php if($fila['examen_solicitado']=="Doppler ART M.SUP") echo "selected"; ?>>Doppler ART M.SUP</option>
<option <?php if($fila['examen_solicitado']=="Doppler ART. M. SUP. BILATERAL") echo "selected"; ?>>Doppler ART. M. SUP. BILATERAL</option>
<option <?php if($fila['examen_solicitado']=="Doppler ART. M. INF") echo "selected"; ?>>Doppler ART. M. INF</option>
<option <?php if($fila['examen_solicitado']=="Doppler ART. M. INF. BILATERAL") echo "selected"; ?>>Doppler ART. M. INF. BILATERAL</option>
<option <?php if($fila['examen_solicitado']=="Doppler venoso M.SUP") echo "selected"; ?>>Doppler venoso M.SUP</option>
<option <?php if($fila['examen_solicitado']=="Doppler venoso M. SUP. BILATERAL") echo "selected"; ?>>Doppler venoso M. SUP. BILATERAL</option>
<option <?php if($fila['examen_solicitado']=="Doppler venoso M. INF") echo "selected"; ?>>Doppler venoso M. INF</option>
<option <?php if($fila['examen_solicitado']=="Doppler venoso M. INF. BILATERAL") echo "selected"; ?>>Doppler venoso M. INF. BILATERAL</option>

</select>
    </div>

    <div class="campo">
        <label>Diagnóstico:</label>
        <textarea name="diagnostico" rows="3"><?php echo $fila['diagnostico']; ?></textarea>
    </div>

</div>

<div class="acciones">
    <button type="submit" class="btn-guardar">Guardar cambios</button>
    <a href="historial_ecografias.php" class="btn-volver">Cancelar</a>
</div>

</form>

</div>

</body>
</html>