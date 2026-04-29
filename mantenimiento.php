<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$condicionesLista = $conexion->query("SELECT * FROM mantenimiento WHERE tipo='Condición de pago' ORDER BY id DESC");
$serviciosLista = $conexion->query("SELECT * FROM mantenimiento WHERE tipo='Servicio' ORDER BY id DESC");
$examenesLista = $conexion->query("SELECT * FROM mantenimiento WHERE tipo='Examen' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mantenimiento</title>
<style>
    
body {
    font-family: Arial, sans-serif;
    background: #f1f5f9;
    margin: 0;
    padding: 30px;
}

.contenedor {
    max-width: 1100px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,.12);
}

h1 {
    color: #1e3a8a;
}

.formulario {
    display: grid;
    gap: 14px;
    margin-bottom: 25px;
}

input, select, textarea {
    padding: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    font-size: 15px;
}

textarea {
    resize: none;
    height: 90px;
}

button {
    background: #2563eb;
    color: white;
    border: none;
    padding: 13px;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #1d4ed8;
}

th {
    background: #1e3a8a;
    color: white;
    padding: 12px;
}

td {
    padding: 11px;
    border-bottom: 1px solid #e2e8f0;
}
th, td {
    text-align: left;
    vertical-align: middle;
}

th:nth-child(1), td:nth-child(1) {
    width: 70px;
    text-align: center;
}

th:nth-child(2), td:nth-child(2) {
    width: 220px;
}

th:nth-child(3), td:nth-child(3) {
    width: 320px;
}

th:nth-child(4), td:nth-child(4) {
    width: 220px;
    text-align: center;
}

th:nth-child(5), td:nth-child(5) {
    width: 160px;
    text-align: center;
}

.volver {
    display: inline-block;
    margin-top: 20px;
    background: #475569;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
}
h2 {
    color: #1e3a8a;
    margin-top: 35px;
    border-left: 5px solid #2563eb;
    padding-left: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed; /* 🔥 clave para alinear */
    margin-bottom: 25px;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}
td {
    word-wrap: break-word;
}
.titulo-barra {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.volver {
    background: #475569;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}
</style>
</head>

<body>

<div class="contenedor">
<div class="titulo-barra">
     <a class="volver" href="menu.php">Volver al menú</a>
    <h1>Mantenimiento del sistema</h1>
</div>

    <form class="formulario" action="guardar_mantenimiento.php" method="POST">
        <select name="tipo" required>
            <option value="">Seleccione tipo</option>
            <option value="Condición de pago">Condición de pago</option>
            <option value="Servicio">Servicio</option>
            <option value="Examen">Examen</option>
        </select>

        <input type="text" name="nombre" placeholder="Ingrese nombre" required>

        <textarea name="descripcion" placeholder="Ingrese descripción"></textarea>

        <button type="submit">Guardar registro</button>
    </form>

 <h2>Condiciones de pago</h2>
<table>
<tr>
    <th>ID</th><th>Nombre</th><th>Descripción</th><th>Fecha</th><th>Acciones</th>
</tr>
<?php while($fila = $condicionesLista->fetch_assoc()) { ?>
<tr>
    <td><?php echo $fila['id']; ?></td>
    <td><?php echo $fila['nombre']; ?></td>
    <td><?php echo $fila['descripcion']; ?></td>
    <td><?php echo $fila['fecha_registro']; ?></td>
    
    <td>
        <a href="editar_mantenimiento.php?id=<?php echo $fila['id']; ?>">Editar</a> |
        <a href="eliminar_mantenimiento.php?id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Eliminar registro?');">Eliminar</a>
    </td>
</tr>
<?php } ?>
</table>
<h2>Servicios</h2>
<table>
<tr>
    <th>ID</th><th>Nombre</th><th>Descripción</th><th>Fecha</th><th>Acciones</th>
</tr>
<?php while($fila = $serviciosLista->fetch_assoc()) { ?>
<tr>
    <td><?php echo $fila['id']; ?></td>
    <td><?php echo $fila['nombre']; ?></td>
    <td><?php echo $fila['descripcion']; ?></td>
    <td><?php echo $fila['fecha_registro']; ?></td>
    <td>
        <a href="editar_mantenimiento.php?id=<?php echo $fila['id']; ?>">Editar</a> |
        <a href="eliminar_mantenimiento.php?id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Eliminar registro?');">Eliminar</a>
    </td>
</tr>
<?php } ?>
</table>

<h2>Exámenes</h2>
<table>
<tr>
    <th>ID</th><th>Nombre</th><th>Descripción</th><th>Fecha</th><th>Acciones</th>
</tr>
<?php while($fila = $examenesLista->fetch_assoc()) { ?>
<tr>
    <td><?php echo $fila['id']; ?></td>
    <td><?php echo $fila['nombre']; ?></td>
    <td><?php echo $fila['descripcion']; ?></td>
    <td><?php echo $fila['fecha_registro']; ?></td>
    <td>
        <a href="editar_mantenimiento.php?id=<?php echo $fila['id']; ?>">Editar</a> |
        <a href="eliminar_mantenimiento.php?id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Eliminar registro?');">Eliminar</a>
    </td>
</tr>
<?php } ?>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'editado'): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Editado!',
    text: 'El registro se actualizó correctamente',
    confirmButtonColor: '#2563eb'
});
</script>
<?php endif; ?>

<?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'eliminado'): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Eliminado!',
    text: 'El registro se eliminó correctamente',
    confirmButtonColor: '#2563eb'
});
</script>
<?php endif; ?>
<?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'guardado'): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Guardado!',
    text: 'El registro se guardó correctamente',
    confirmButtonColor: '#2563eb'
});
</script>
<?php endif; ?>

</body>
</html>