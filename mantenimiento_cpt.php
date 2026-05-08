<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");
$examenes = $conexion->query("
    SELECT id, nombre 
    FROM mantenimiento 
    WHERE tipo='Examen' AND estado=1 
    ORDER BY nombre ASC
");

/* GUARDAR */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $id_examen = $_POST['id_examen'];

$res = $conexion->query("SELECT nombre FROM mantenimiento WHERE id='$id_examen'");
$row = $res->fetch_assoc();
$examen = $row['nombre'];
    $codigo = trim($_POST['codigo_cpt']);
    $co_codups = trim($_POST['co_codups']);
    $servicio = trim($_POST['servicio_especialidad']);

    if ($examen != "" && $codigo != "") {
        $stmt = $conexion->prepare("
            INSERT INTO cpt_codes 
            (examen_solicitado, id_examen, codigo_cpt, co_codups, servicio_especialidad)
VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sisss", $examen, $id_examen, $codigo, $co_codups, $servicio);
        $stmt->execute();

        header("Location: mantenimiento_cpt.php?ok=1");
        exit();
    }
}

/* ACTUALIZAR */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $id = intval($_POST['id_cpt']);
    $id_examen = $_POST['id_examen'];

$res = $conexion->query("SELECT nombre FROM mantenimiento WHERE id='$id_examen'");
$row = $res->fetch_assoc();
$examen = $row['nombre'];
    $codigo = trim($_POST['codigo_cpt']);
    $co_codups = trim($_POST['co_codups']);
    $servicio = trim($_POST['servicio_especialidad']);

    $stmt = $conexion->prepare("
        UPDATE cpt_codes
SET examen_solicitado = ?, id_examen = ?, codigo_cpt = ?, co_codups = ?, servicio_especialidad = ?
WHERE id_cpt = ?
    ");
    $stmt->bind_param("sisssi", $examen, $id_examen, $codigo, $co_codups, $servicio, $id);
    $stmt->execute();

    header("Location: mantenimiento_cpt.php?editado=1");
    exit();
}

/* ELIMINAR */
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);

    $stmt = $conexion->prepare("DELETE FROM cpt_codes WHERE id_cpt = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: mantenimiento_cpt.php?eliminado=1");
    exit();
}

/* EDITAR */
$editar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);

    $stmt = $conexion->prepare("SELECT * FROM cpt_codes WHERE id_cpt = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $editar = $stmt->get_result()->fetch_assoc();
}

$resultado = $conexion->query("SELECT * FROM cpt_codes ORDER BY codigo_cpt ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mantenimiento CPT-Code</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(180deg, #172b4d 0%, #1e3a5f 100%);
    color: #0f172a;
}

.contenedor {
    max-width: 1150px;
    margin: 40px auto;
    background: white;
    padding: 32px;
    border-radius: 20px;
    box-shadow: 0 12px 30px rgba(0,0,0,.18);
}

.volver {
    display: inline-block;
    margin-bottom: 20px;
    background: #64748b;
    color: white;
    padding: 11px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
}

h1 {
    text-align: center;
    color: #1e3a8a;
    margin-bottom: 6px;
}

.subtitulo {
    text-align: center;
    color: #64748b;
    margin-bottom: 28px;
}

.formulario {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 14px;
    background: #f8fafc;
    padding: 20px;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    margin-bottom: 28px;
    align-items: end;
}

.campo label {
    display: block;
    font-weight: bold;
    color: #334155;
    margin-bottom: 7px;
    font-size: 14px;
}

input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    font-size: 14px;
    outline: none;
}

input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.15);
}

button {
    border: none;
    background: #2563eb;
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
    height: 44px;
}

button:hover {
    background: #1d4ed8;
}

.btn-cancelar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #64748b;
    color: white;
    text-decoration: none;
    padding: 12px 18px;
    border-radius: 12px;
    font-weight: bold;
    height: 44px;
}

.tabla-contenedor {
    overflow-x: auto;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

th {
    background: #1e3a8a;
    color: white;
    padding: 13px 10px;
    text-align: center;
}

td {
    padding: 12px 10px;
    border-bottom: 1px solid #e2e8f0;
    text-align: center;
}

td.examen {
    text-align: left;
    font-weight: bold;
}

.acciones {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-editar,
.btn-eliminar {
    color: white;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: bold;
}

.btn-editar {
    background: #f59e0b;
}

.btn-eliminar {
    background: #dc2626;
}

.alerta {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: bold;
}

.ok {
    background: #dcfce7;
    color: #166534;
}

.editado {
    background: #dbeafe;
    color: #1e40af;
}

.eliminado {
    background: #fee2e2;
    color: #991b1b;
}

@media (max-width: 900px) {
    .formulario {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<div class="contenedor">

    <a href="reporte_cpt.php" class="volver">← Volver a Reporte CPT-Code</a>

    <h1>Mantenimiento CPT-Code</h1>
    <p class="subtitulo">
        Administra los códigos CPT asociados a los exámenes ecográficos.
    </p>

    <?php if (isset($_GET['ok'])) { ?>
        <div class="alerta ok">Código CPT registrado correctamente.</div>
    <?php } ?>

    <?php if (isset($_GET['editado'])) { ?>
        <div class="alerta editado">Código CPT actualizado correctamente.</div>
    <?php } ?>

    <?php if (isset($_GET['eliminado'])) { ?>
        <div class="alerta eliminado">Código CPT eliminado correctamente.</div>
    <?php } ?>

    <form method="POST" class="formulario">

        <?php if ($editar) { ?>
            <input type="hidden" name="id_cpt" value="<?php echo $editar['id_cpt']; ?>">
        <?php } ?>

        <div class="campo">
            <label>Examen solicitado</label>
            <select name="id_examen" required>
    <option value="">Seleccione examen</option>

    <?php
    $listaExamenes = $conexion->query("
        SELECT id, nombre 
        FROM mantenimiento 
        WHERE tipo='Examen' AND estado=1 
        ORDER BY nombre ASC
    ");

    while($e = $listaExamenes->fetch_assoc()) {
        $selected = ($editar && $editar['id_examen'] == $e['id']) ? 'selected' : '';
        echo "<option value='{$e['id']}' $selected>{$e['nombre']}</option>";
    }
    ?>
</select>
</div>
        <div class="campo">
            <label>Código CPT</label>
            <input 
                type="text" 
                name="codigo_cpt"
                value="<?php echo $editar ? $editar['codigo_cpt'] : ''; ?>"
                placeholder="Ej: 76700"
                required
            >
        </div>

        <div class="campo">
            <label>CO-CODUPS</label>
            <input 
                type="text" 
                name="co_codups"
                value="<?php echo $editar ? $editar['co_codups'] : '00003414'; ?>"
                placeholder="00003414"
                required
            >
        </div>

        <div class="campo">
            <label>Servicio / Especialidad</label>
            <input 
                type="text" 
                name="servicio_especialidad"
                value="<?php echo $editar ? $editar['servicio_especialidad'] : '080900'; ?>"
                placeholder="080900"
                required
            >
        </div>

        <div>
            <?php if ($editar) { ?>
                <button type="submit" name="actualizar">Actualizar</button>
                <a href="mantenimiento_cpt.php" class="btn-cancelar">Cancelar</a>
            <?php } else { ?>
                <button type="submit" name="guardar">Guardar</button>
            <?php } ?>
        </div>

    </form>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>Examen solicitado</th>
                    <th>Código CPT</th>
                    <th>CO-CODUPS</th>
                    <th>Servicio / Especialidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0) { ?>
                    <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td class="examen"><?php echo $fila['examen_solicitado']; ?></td>
                            <td><?php echo $fila['codigo_cpt']; ?></td>
                            <td><?php echo $fila['co_codups']; ?></td>
                            <td><?php echo $fila['servicio_especialidad']; ?></td>
                            <td>
                                <div class="acciones">
                                    <a class="btn-editar" href="mantenimiento_cpt.php?editar=<?php echo $fila['id_cpt']; ?>">
                                        Editar
                                    </a>

                                    <a 
                                        class="btn-eliminar" 
                                        href="mantenimiento_cpt.php?eliminar=<?php echo $fila['id_cpt']; ?>"
                                        onclick="return confirm('¿Seguro que deseas eliminar este código CPT?');"
                                    >
                                        Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5">No hay códigos CPT registrados.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>