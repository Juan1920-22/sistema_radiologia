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
<title>Mantenimiento del Sistema</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    min-height: 100vh;
    background: url('img/fondo_dashboard.jpg') center center / cover no-repeat fixed;
    color: #0f172a;
    position: relative;
}

body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(241, 245, 249, 0.78);
    z-index: -1;
}

/* BARRA SUPERIOR */
.topbar {
    width: 100%;
    background: rgba(23, 43, 77, 0.96);
    color: white;
    padding: 26px 42px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 9999;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: 0 4px 18px rgba(0,0,0,.18);
}

.logo-hospital {
    font-size: 25px;
    font-weight: bold;
    letter-spacing: 1.4px;
    font-family: 'Times New Roman', serif;
    text-transform: uppercase;
}

.menu-superior {
    display: flex;
    align-items: center;
    gap: 38px;
}

.menu-superior a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    transition: .25s;
}

.menu-superior a:hover {
    color: #bfdbfe;
}

.btn-salir-top {
    background: #dc2626;
    padding: 10px 16px;
    border-radius: 10px;
}

.btn-salir-top:hover {
    background: #b91c1c;
    color: white !important;
}

/* ENCABEZADO */
.page-header {
    height: 230px;
    background:
        linear-gradient(90deg, rgba(15,23,42,.68), rgba(15,23,42,.35)),
        url('img/hospital.1.jpg') center center / cover no-repeat;
    display: flex;
    align-items: center;
    padding: 0 7%;
    color: white;
    box-shadow: 0 8px 24px rgba(15,23,42,.18);
}

.page-header span {
    display: inline-block;
    background: rgba(255,255,255,.18);
    padding: 8px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 12px;
    backdrop-filter: blur(4px);
}

.page-header h1 {
    margin: 0;
    font-size: 40px;
    font-weight: 800;
    text-shadow: 0 4px 12px rgba(0,0,0,.35);
}

.page-header p {
    margin-top: 10px;
    font-size: 17px;
    opacity: .95;
}

/* CONTENEDOR PRINCIPAL */
.contenedor {
    width: 90%;
    max-width: 1200px;
    margin: -45px auto 50px;
    background: rgba(255,255,255,.94);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 32px;
    border-radius: 26px;
    box-shadow: 0 18px 42px rgba(15,23,42,.16);
    border: 1px solid rgba(203,213,225,.60);
    position: relative;
    z-index: 2;
    border-top: 14px solid #172b4d;
}

/* CABECERA INTERNA */
.titulo-barra {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 26px;
    gap: 20px;
}

.titulo-barra h1 {
    margin: 0;
    color: #1e3a8a;
    font-size: 30px;
    font-weight: 800;
    text-align: right;
}

.volver {
    background: #64748b;
    color: white;
    padding: 12px 18px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    transition: .25s;
}

.volver:hover {
    background: #475569;
    transform: translateY(-2px);
}

/* FORMULARIO */
.formulario {
    display: grid;
    gap: 16px;
    margin-bottom: 34px;
    background: rgba(248,250,252,.92);
    padding: 24px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 24px rgba(15,23,42,.06);
}

input,
select,
textarea {
    width: 100%;
    padding: 14px 15px;
    border: 1px solid #cbd5e1;
    border-radius: 13px;
    font-size: 15px;
    background: white;
    outline: none;
    transition: .25s;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.13);
}

textarea {
    resize: vertical;
    min-height: 100px;
}

button {
    background: linear-gradient(90deg, #2563eb, #1d4ed8);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 13px;
    font-weight: bold;
    cursor: pointer;
    transition: .25s;
}

button:hover {
    transform: translateY(-2px);
    background: #1d4ed8;
}

/* TÍTULOS DE SECCIONES */
h2 {
    color: #1e3a8a;
    margin-top: 36px;
    margin-bottom: 16px;
    border-left: 6px solid #2563eb;
    padding-left: 12px;
    font-size: 24px;
}

/* TABLAS */
.tabla-mantenimiento {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin-bottom: 30px;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 26px rgba(15,23,42,.08);
    border: 1px solid #e2e8f0;
}

.tabla-mantenimiento th {
    background: #1e3a8a;
    color: white;
    padding: 14px 12px;
    font-size: 14px;
    text-align: center;
}

.tabla-mantenimiento td {
    padding: 13px 12px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
    text-align: center;
    vertical-align: middle;
    word-wrap: break-word;
}

.tabla-mantenimiento tr:hover {
    background: #f8fafc;
}

.tabla-mantenimiento th:nth-child(1),
.tabla-mantenimiento td:nth-child(1) {
    width: 28%;
    text-align: left;
}

.tabla-mantenimiento th:nth-child(2),
.tabla-mantenimiento td:nth-child(2) {
    width: 38%;
    text-align: left;
}

.tabla-mantenimiento th:nth-child(3),
.tabla-mantenimiento td:nth-child(3) {
    width: 18%;
    text-align: center;
}

.tabla-mantenimiento th:nth-child(4),
.tabla-mantenimiento td:nth-child(4) {
    width: 16%;
    text-align: center;
}
.acciones-mantenimiento {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-editar,
.btn-eliminar {
    display: inline-block;
    padding: 8px 13px;
    border-radius: 8px;
    color: white !important;
    text-decoration: none !important;
    font-weight: bold;
    font-size: 13px;
    transition: .25s;
}

.btn-editar {
    background: #f59e0b;
}

.btn-eliminar {
    background: #dc2626;
}

.btn-editar:hover {
    background: #d97706;
    transform: translateY(-2px);
}

.btn-eliminar:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

.vacio {
    text-align: center !important;
    color: #64748b;
    padding: 18px !important;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .topbar {
        flex-direction: column;
        gap: 15px;
        padding: 18px 20px;
        text-align: center;
    }

    .menu-superior {
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
    }

    .page-header {
        height: 220px;
        padding: 0 25px;
        text-align: center;
        justify-content: center;
    }

    .page-header h1 {
        font-size: 30px;
    }

    .page-header p {
        font-size: 15px;
    }

    .contenedor {
        width: 92%;
        padding: 24px;
        margin-top: -35px;
    }

    .titulo-barra {
        flex-direction: column;
        align-items: flex-start;
    }

    .titulo-barra h1 {
        text-align: left;
        font-size: 26px;
    }

    .tabla-mantenimiento {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}
</style>
</head>

<body>

<header class="topbar">
    <div class="logo-hospital">Hospital San José de Chincha</div>

    <nav class="menu-superior">
        <a href="menu.php">Inicio</a>
        <a href="registrar_ecografia.php">Registrar</a>
        <a href="historial_ecografias.php">Historial</a>
        <a href="paloteo.php">Paloteo</a>
        <a href="mantenimiento.php">Mantenimiento</a>
        <a href="logout.php" class="btn-salir-top">Salir</a>
    </nav>
</header>

<section class="page-header">
    <div>
        <span>Área de Diagnóstico por Imágenes</span>
        <h1>Mantenimiento del Sistema</h1>
        <p>Administre condiciones de pago, servicios y exámenes utilizados en el registro de ecografías.</p>
    </div>
</section>

<div class="contenedor">

    <div class="titulo-barra">
        <a class="volver" href="menu.php">← Volver al menú</a>
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

    <table class="tabla-mantenimiento">
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>

        <?php if ($condicionesLista->num_rows > 0) { ?>
            <?php while($fila = $condicionesLista->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['descripcion']; ?></td>
                    <td><?php echo $fila['fecha_registro']; ?></td>
                    <td>
                        <div class="acciones-mantenimiento">
                            <a class="btn-editar" href="editar_mantenimiento.php?id=<?php echo $fila['id']; ?>">Editar</a>
                            <a class="btn-eliminar" href="eliminar_mantenimiento.php?id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Eliminar registro?');">Eliminar</a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4" class="vacio">No hay condiciones de pago registradas.</td>
            </tr>
        <?php } ?>
    </table>

    <h2>Servicios</h2>

<table class="tabla-mantenimiento">
    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>

        <?php if ($serviciosLista->num_rows > 0) { ?>
            <?php while($fila = $serviciosLista->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['descripcion']; ?></td>
                    <td><?php echo $fila['fecha_registro']; ?></td>
                    <td>
                        <div class="acciones-mantenimiento">
                            <a class="btn-editar" href="editar_mantenimiento.php?id=<?php echo $fila['id']; ?>">Editar</a>
                            <a class="btn-eliminar" href="eliminar_mantenimiento.php?id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Eliminar registro?');">Eliminar</a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4" class="vacio">No hay servicios registrados.</td>
            </tr>
        <?php } ?>
    </table>

    <h2>Exámenes</h2>

    <table class="tabla-mantenimiento">
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>

        <?php if ($examenesLista->num_rows > 0) { ?>
            <?php while($fila = $examenesLista->fetch_assoc()) { ?>
                <tr>
                
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['descripcion']; ?></td>
                    <td><?php echo $fila['fecha_registro']; ?></td>
                    <td>
                        <div class="acciones-mantenimiento">
                            <a class="btn-editar" href="editar_mantenimiento.php?id=<?php echo $fila['id']; ?>">Editar</a>
                            <a class="btn-eliminar" href="eliminar_mantenimiento.php?id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Eliminar registro?');">Eliminar</a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4" class="vacio">No hay exámenes registrados.</td>
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