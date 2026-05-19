<?php
include("conexion.php");
$condiciones = $conexion->query("SELECT id, nombre FROM mantenimiento WHERE tipo='Condición de pago' AND estado=1 ORDER BY nombre ASC");
$servicios = $conexion->query("SELECT id, nombre FROM mantenimiento WHERE tipo='Servicio' AND estado=1 ORDER BY nombre ASC");
$examenes = $conexion->query("SELECT id, nombre FROM mantenimiento WHERE tipo='Examen' AND estado=1 ORDER BY nombre ASC");

$id = $_GET['id'];

$sql = "SELECT * FROM ecografias WHERE id_ecografia = '$id'";
$resultado = mysqli_query($conexion, $sql);
$fila = mysqli_fetch_assoc($resultado);

// Obtener IDs de los exámenes seleccionados previamente
$ids_examenes = explode(",", $fila['id_examen']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Ecografía</title>
<style>
body { font-family: Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 40px; }
.formulario { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,.12); }
h1 { text-align: center; color: #1e3a8a; }
.grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
.campo { display: flex; flex-direction: column; }
label { font-weight: bold; margin-bottom: 6px; }
input, select, textarea { padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; }
textarea { resize: vertical; }
.acciones { margin-top: 25px; display: flex; gap: 12px; }
.btn-guardar { background: #2563eb; color: white; padding: 13px 22px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; }
.btn-volver { background: #64748b; color: white; padding: 13px 22px; border-radius: 10px; text-decoration: none; font-weight: bold; }

/* Estilo tags de exámenes */
#listaExamenes { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
.tagExamen { background: #2563eb; color: white; padding: 5px 10px; border-radius: 12px; display: flex; align-items: center; font-size: 13px; }
.tagExamen span { margin-left: 6px; cursor: pointer; font-weight: bold; }
#btnAgregar { margin-top: 6px; background: #0d47a1; color: white; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer; transition: 0.3s; }
#btnAgregar:hover { background: #1565c0; }
</style>
</head>
<body>

<div class="formulario">
<h1>Editar Ecografía</h1>
<form action="actualizar_ecografia.php" method="POST">
<input type="hidden" name="id_ecografia" value="<?php echo $fila['id_ecografia']; ?>">

<div class="grid">
    <!-- Campos existentes -->
    <div class="campo"><label>Historia Clínica:</label><input type="text" name="historia_clinica" value="<?php echo $fila['historia_clinica']; ?>"></div>
    <div class="campo"><label>DNI:</label><input type="text" name="dni" value="<?php echo $fila['dni']; ?>"></div>
    <div class="campo"><label>Fecha:</label><input type="date" name="fecha" value="<?php echo $fila['fecha']; ?>"></div>
    <div class="campo"><label>Apellidos:</label><input type="text" name="apellidos" value="<?php echo $fila['apellidos']; ?>"></div>
    <div class="campo"><label>Nombres:</label><input type="text" name="nombres" value="<?php echo $fila['nombres']; ?>"></div>
    <div class="campo"><label>Sexo:</label>
        <select name="sexo">
            <option value="Hombre" <?php if($fila['sexo']=="Hombre") echo "selected"; ?>>Hombre</option>
            <option value="Mujer" <?php if($fila['sexo']=="Mujer") echo "selected"; ?>>Mujer</option>
        </select>
    </div>

    <!-- Condición y Servicio -->
    <div class="campo"><label>Condición:</label>
        <select name="condicion" required>
            <?php while($c = $condiciones->fetch_assoc()) { ?>
                <option value="<?php echo $c['id']; ?>" <?php if($fila['id_condicion'] == $c['id']) echo "selected"; ?>><?php echo $c['nombre']; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="campo"><label>Servicio solicitante:</label>
        <select name="servicio_solicitante" required>
            <?php while($s = $servicios->fetch_assoc()) { ?>
                <option value="<?php echo $s['id']; ?>" <?php if($fila['id_servicio'] == $s['id']) echo "selected"; ?>><?php echo $s['nombre']; ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Médico turno y tipo de atención -->
    <div class="campo"><label>Médico turno:</label>
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

    <div class="campo"><label>Tipo de atención:</label>
        <select name="tipo_atencion">
            <option value="Particular" <?php if($fila['tipo_atencion']=="Particular") echo "selected"; ?>>Particular</option>
            <option value="Periférica" <?php if($fila['tipo_atencion']=="Periférica") echo "selected"; ?>>Periférica</option>
            <option value="Ambulatorio" <?php if($fila['tipo_atencion']=="Ambulatorio") echo "selected"; ?>>Ambulatorio</option>
            <option value="Emergencia" <?php if($fila['tipo_atencion']=="Emergencia") echo "selected"; ?>>Emergencia</option>
            <option value="Hospitalaria" <?php if($fila['tipo_atencion']=="Hospitalaria") echo "selected"; ?>>Hospitalaria</option>
        </select>
    </div>

    <!-- Exámenes dinámicos con soporte múltiple -->
    <div class="campo">
        <label>Exámenes:</label>
        <div id="listaExamenes">
            <?php
            $examenes = $conexion->query("SELECT id, nombre FROM mantenimiento WHERE tipo='Examen' AND estado=1 ORDER BY nombre ASC");
            $todosExamenes = [];
            while($e = $examenes->fetch_assoc()) $todosExamenes[$e['id']] = $e['nombre'];
            foreach($ids_examenes as $idEx) {
                if(isset($todosExamenes[$idEx])) {
                    echo '<div class="tagExamen" data-id="'.$idEx.'">'.$todosExamenes[$idEx].'<span onclick="eliminarExamen(this)">×</span><input type="hidden" name="examenes_solicitados[]" value="'.$idEx.'"></div>';
                }
            }
            ?>
        </div>
        <select id="selectAgregarExamen">
            <option value="">--Agregar examen--</option>
            <?php foreach($todosExamenes as $id=>$nombre) echo '<option value="'.$id.'">'.$nombre.'</option>'; ?>
        </select>
        <button type="button" id="btnAgregar" onclick="agregarExamen()">Agregar</button>
    </div>

    <!-- Diagnóstico -->
    <div class="campo"><label>Diagnóstico:</label><textarea name="diagnostico" rows="3"><?php echo $fila['diagnostico']; ?></textarea></div>

    <!-- Monto -->
    <div class="campo"><label>Monto:</label><input type="number" step="0.01" name="monto" value="<?php echo ($fila['monto']==0)?'':$fila['monto']; ?>"></div>

    <!-- Boleta -->
    <div class="campo"><label>Número de boleta:</label><input type="text" name="numero_boleta" value="<?php echo $fila['numero_boleta']; ?>"></div>

    <!-- Convenio -->
    <div class="campo" id="campoConvenio" style="display:<?php echo !empty($fila['convenio'])?'block':'none'; ?>;">
        <label>Convenio:</label>
        <input type="text" name="convenio" value="<?php echo $fila['convenio']; ?>">
    </div>

    <!-- Hora -->
    <div class="campo"><label>Hora (opcional):</label><input type="time" name="hora_examen" value="<?php echo !empty($fila['hora_examen']) ? $fila['hora_examen'] : ''; ?>"></div>
</div>

<div class="acciones">
<button type="submit" class="btn-guardar">Guardar cambios</button>
<a href="historial_ecografias.php" class="btn-volver">Cancelar</a>
</div>
</form>
</div>

<script>
function eliminarExamen(span){span.parentElement.remove();}
function agregarExamen(){
    let select=document.getElementById('selectAgregarExamen');
    let id=select.value;
    let nombre=select.options[select.selectedIndex].text;
    if(id==='') return;
    let existentes=document.querySelectorAll('#listaExamenes .tagExamen');
    for(let tag of existentes){if(tag.dataset.id===id) return;}
    let div=document.createElement('div');
    div.className='tagExamen';
    div.dataset.id=id;
    div.innerHTML=nombre+'<span onclick="eliminarExamen(this)">×</span><input type="hidden" name="examenes_solicitados[]" value="'+id+'">';
    document.getElementById('listaExamenes').appendChild(div);
    select.value='';
}

// Mostrar Convenio si ya tiene valor o condición requiere
function validarConvenio(){
    let condicion=document.querySelector("select[name='condicion']").value;
    let campo=document.getElementById("campoConvenio");
    if(condicion==="Convenio" || condicion==="Asegurado" || campo.querySelector("input").value!="") campo.style.display="block";
    else campo.style.display="none";
}
validarConvenio();
document.querySelector("select[name='condicion']").addEventListener("change", validarConvenio);
</script>

</body>
</html>