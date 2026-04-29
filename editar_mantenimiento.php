<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id = $_GET['id'];

$stmt = $conexion->prepare("SELECT * FROM mantenimiento WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$registro = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar mantenimiento</title>
<style>
body {
    font-family: Arial;
    background: #f1f5f9;
    padding: 30px;
}
.contenedor {
    max-width: 700px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 18px;
}
input, select, textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
}
button {
    background: #2563eb;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
}
a {
    margin-left: 10px;
}
</style>
</head>
<body>

<div class="contenedor">
<h2>Editar mantenimiento</h2>

<form action="actualizar_mantenimiento.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $registro['id']; ?>">

    <select name="tipo" required>
        <option value="Condición de pago" <?php if($registro['tipo']=="Condición de pago") echo "selected"; ?>>Condición de pago</option>
        <option value="Servicio" <?php if($registro['tipo']=="Servicio") echo "selected"; ?>>Servicio</option>
        <option value="Examen" <?php if($registro['tipo']=="Examen") echo "selected"; ?>>Examen</option>
    </select>

    <input type="text" name="nombre" value="<?php echo $registro['nombre']; ?>" required>

    <textarea name="descripcion"><?php echo $registro['descripcion']; ?></textarea>

    <button type="submit">Guardar cambios</button>
    <a href="mantenimiento.php">Cancelar</a>
</form>
</div>

</body>
</html>