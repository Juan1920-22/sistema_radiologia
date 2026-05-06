<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reportes - Sistema de Ecografías</title>

<style>
* {
    box-sizing: border-box;
}
body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    min-height: 100vh;
    background: linear-gradient(180deg, #172b4d 0%, #1e3a5f 100%);
    color: #0f172a;
}
.topbar {
    width: 100%;
    background: rgba(23, 43, 77, 0.96);
    color: white;
    padding: 20px 42px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 18px rgba(0,0,0,.18);
}

.logo-hospital {
    font-size: 24px;
    font-weight: bold;
    letter-spacing: 1.4px;
    font-family: 'Times New Roman', serif;
    text-transform: uppercase;
}

.menu-superior {
    display: flex;
    align-items: center;
    gap: 24px;
    justify-content: flex-end;
    flex-shrink: 0;
}

.menu-superior a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
}

.btn-salir {
    background: #dc2626;
    padding: 10px 18px;
    border-radius: 10px;
    margin-left: 10px;
    white-space: nowrap;
}

.contenedor {
    max-width: 1180px;
    margin: 45px auto;
    background: white;
    padding: 35px;
    border-radius: 22px;
    box-shadow: 0 14px 35px rgba(0,0,0,.18);
}

.volver {
    display: inline-block;
    margin-bottom: 20px;
    background: #64748b;
    color: white;
    padding: 12px 22px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
}

h1 {
    text-align: center;
    color: #1e3a8a;
    margin-bottom: 8px;
}

.subtitulo {
    text-align: center;
    color: #64748b;
    margin-bottom: 35px;
}

.grid-reportes {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.card-reporte {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 8px 20px rgba(15,23,42,.08);
    transition: .25s;
    border-top: 7px solid #2563eb;
}

.card-reporte:hover {
    transform: translateY(-5px);
}

.card-reporte h2 {
    margin: 0 0 12px;
    color: #1e3a8a;
    font-size: 22px;
}

.card-reporte p {
    color: #475569;
    line-height: 1.5;
    font-size: 15px;
    min-height: 65px;
}

.card-reporte a {
    display: inline-block;
    margin-top: 12px;
    background: #2563eb;
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: bold;
}

.verde {
    border-top-color: #16a34a;
}

.verde a {
    background: #16a34a;
}

.morado {
    border-top-color: #7c3aed;
}

.morado a {
    background: #7c3aed;
}

.naranja {
    border-top-color: #f97316;
}

.naranja a {
    background: #f97316;
}

@media (max-width: 900px) {
    .grid-reportes {
        grid-template-columns: 1fr;
    }

    .topbar {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .menu-superior {
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
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
        <a href="reportes.php">Reportes</a>
        <a href="mantenimiento.php">Mantenimiento</a>
        <a href="logout.php" class="btn-salir">Salir</a>
    </nav>
</header>

<div class="contenedor">

    <a href="menu.php" class="volver">← Volver al menú</a>

    <h1>Módulo de Reportes</h1>
    <p class="subtitulo">
        Generación de informes estadísticos del Área de Diagnóstico por Imágenes.
    </p>

    <div class="grid-reportes">

        <div class="card-reporte">
            <h2>Reporte por Servicio</h2>
            <p>
                Muestra la producción mensual por servicio solicitante y tipo de atención:
                ambulatorio, hospitalaria, emergencia, periférica y particular.
            </p>
            <a href="reporte_servicio.php">Generar reporte</a>
        </div>

        <div class="card-reporte verde">
            <h2>Reporte por Ecografía</h2>
            <p>
                Muestra la producción mensual por examen solicitado y condición:
                SIS, particular, convenio, crédito, contado, entre otros.
            </p>
            <a href="reporte_ecografia.php">Generar reporte</a>
        </div>

        <div class="card-reporte morado">
            <h2>Reporte CPT-Code</h2>
            <p>
                Genera el informe de procedimientos ecográficos según tabla de codificación CPT-Code.
            </p>
            <a href="reporte_cpt.php">Generar reporte</a>
        </div>

        <div class="card-reporte naranja">
            <h2>Reporte Económico</h2>
            <p>
                Muestra montos recaudados por fecha, condición, boleta, convenio y tipo de atención.
            </p>
            <a href="reporte_economico.php">Generar reporte</a>
        </div>

    </div>

</div>

</body>
</html>