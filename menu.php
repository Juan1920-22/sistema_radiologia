<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");
$fechaHoy = date('Y-m-d');

/* Guardar o actualizar meta diaria */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_meta'])) {
    $nueva_meta = intval($_POST['meta_diaria']);

    if ($nueva_meta < 0) {
        $nueva_meta = 0;
    }

    $stmt = $conexion->prepare("
        INSERT INTO metas_diarias (fecha, meta)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE meta = ?
    ");

    $stmt->bind_param("sii", $fechaHoy, $nueva_meta, $nueva_meta);
    $stmt->execute();

    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
exit();
}

$total = $conexion->query("SELECT COUNT(*) as total FROM ecografias")->fetch_assoc();
$hoy = $conexion->query("SELECT COUNT(*) as total FROM ecografias WHERE DATE(fecha)=CURDATE()")->fetch_assoc();
/* Obtener meta del día */
$stmtMeta = $conexion->prepare("SELECT meta FROM metas_diarias WHERE fecha = ?");
$stmtMeta->bind_param("s", $fechaHoy);
$stmtMeta->execute();
$resultMeta = $stmtMeta->get_result();
$metaData = $resultMeta->fetch_assoc();

$meta_diaria = $metaData ? intval($metaData['meta']) : 0;

$realizadas_hoy = intval($hoy['total']);

if ($meta_diaria > 0) {
    $faltantes = $meta_diaria - $realizadas_hoy;

    if ($faltantes < 0) {
        $faltantes = 0;
    }

    $porcentaje = ($realizadas_hoy / $meta_diaria) * 100;

    if ($porcentaje > 100) {
        $porcentaje = 100;
    }
} else {
    $faltantes = 0;
    $porcentaje = 0;
}
$filtro_dashboard = isset($_GET['filtro_dashboard']) ? $_GET['filtro_dashboard'] : 'dia';
$fecha_dashboard = isset($_GET['fecha_dashboard']) ? $_GET['fecha_dashboard'] : date('Y-m-d');

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_dashboard)) {
    $fecha_dashboard = date('Y-m-d');
}

$fecha_segura = $conexion->real_escape_string($fecha_dashboard);

$whereDashboard = "";
$tituloFiltro = "Ecografías del día";

switch ($filtro_dashboard) {
    case 'dia':
        $whereDashboard = "WHERE DATE(fecha) = '$fecha_segura'";
        $tituloFiltro = "Ecografías del " . date('d/m/Y', strtotime($fecha_dashboard));
        break;

    case 'semana':
        $whereDashboard = "WHERE YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)";
        $tituloFiltro = "Ecografías de esta semana";
        break;

    case 'mes':
        $whereDashboard = "WHERE YEAR(fecha) = YEAR(CURDATE()) 
                           AND MONTH(fecha) = MONTH(CURDATE())";
        $tituloFiltro = "Ecografías de este mes";
        break;

    case 'anio':
        $whereDashboard = "WHERE YEAR(fecha) = YEAR(CURDATE())";
        $tituloFiltro = "Ecografías de este año";
        break;

    case 'todos':
        $whereDashboard = "";
        $tituloFiltro = "Total de ecografías";
        break;

    default:
        $whereDashboard = "WHERE DATE(fecha) = CURDATE()";
        $tituloFiltro = "Ecografías de hoy";
        break;
}

$totalFiltrado = $conexion->query("SELECT COUNT(*) as total FROM ecografias $whereDashboard")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>PANEL PRINCIPAL - SISTEMA DE ECOGRAFÍAS</title>

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

.btn-salir {
    background: #dc2626;
    padding: 10px 16px;
    border-radius: 10px;
}

.btn-salir:hover {
    background: #b91c1c;
    color: white !important;
}

/* HERO PRINCIPAL */
.hero {
    height: 440px;
    position: relative;
    overflow: hidden;
}

.hero-slider {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
}

.hero-slide {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 60%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.hero-slide.active {
    opacity: 1;
}

/* CUADRO DE TEXTO DEL HERO */
.hero-contenido {
    position: relative;
    z-index: 3;
    height: 100%;
    max-width: 1180px;
    margin: auto;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding: 0 40px 0 90px;
    color: white;
}

.hero-texto {
    max-width: 560px;
    background: rgba(15, 23, 42, 0.42);
    padding: 22px 28px;
    border-radius: 18px;
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    transform: translateY(-18px);
}

.hero-texto h1 {
    font-size: 36px;
    line-height: 1.12;
    margin: 0 0 12px;
    font-weight: 800;
    text-shadow: 0 4px 12px rgba(0,0,0,.40);
}

.hero-texto p {
    font-size: 15px;
    line-height: 1.45;
    margin: 0;
    text-shadow: 0 3px 8px rgba(0,0,0,.35);
}

/* PUNTOS DEL SLIDER HERO */
.hero-puntos {
    position: absolute;
    bottom: 24px;
    left: 40px;
    display: flex;
    gap: 8px;
    z-index: 4;
}

.hero-punto {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255,255,255,.5);
    transition: .3s;
}

.hero-punto.active {
    width: 26px;
    border-radius: 20px;
    background: white;
}

/* CUATRO MÓDULOS PRINCIPALES */
.modulos {
    width: 78%;
    max-width: 1040px;
    margin: -70px auto 35px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    position: relative;
    z-index: 5;
    box-shadow: 0 16px 30px rgba(15,23,42,.20);
    border-radius: 16px;
    overflow: hidden;
}

.modulo {
    min-height: 135px;
    padding: 22px 22px;
    color: white;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: .25s;
}

.modulo:hover {
    transform: translateY(-4px);
    filter: brightness(1.06);
}

.modulo h3 {
    font-size: 19px;
    margin: 0 0 8px;
    font-weight: 800;
}

.modulo p {
    font-size: 12.5px;
    line-height: 1.45;
    margin: 0;
    font-weight: 500;
}

.modulo span {
    margin-top: 10px;
    font-size: 18px;
    font-weight: bold;
}

.registrar {
    background: rgba(30, 58, 138, .94);
}

.historial {
    background: rgba(14, 116, 144, .88);
}

.paloteo {
    background: rgba(22, 163, 74, .90);
}

.mantenimiento {
    background: rgba(51, 65, 85, .95);
}

/* ESTADÍSTICAS */
.panel-info {
    width: 90%;
    max-width: 1250px;
    margin: 0 auto 45px;
    display: grid;
    grid-template-columns: 1.25fr .95fr;
    gap: 24px;
}
.bienvenida {
    border-left: 6px solid #2563eb;
}

.bienvenida,
.card-estadistica,
.control-meta,
.slider-card {
    background: rgba(248, 250, 252, 0.90);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border-radius: 22px;
    box-shadow: 0 12px 30px rgba(15,23,42,.10);
    border: 1px solid rgba(203, 213, 225, 0.55);
}

.bienvenida {
    padding: 28px;
}

.bienvenida h2 {
    margin: 0 0 10px;
    color: #1e3a8a;
    font-size: 28px;
}

.bienvenida p {
    margin: 0;
    color: #475569;
    font-size: 16px;
    line-height: 1.6;
}

.fecha-actual {
    margin-top: 14px;
    color: #64748b;
    font-size: 14px;
    font-weight: bold;
}

.estadisticas {
    display: grid;
    grid-template-columns: minmax(240px, 1fr) minmax(190px, .8fr);
    gap: 18px;
}

.card-estadistica {
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.card-estadistica h2 {
    margin: 0;
    font-size: 42px;
    color: #2563eb;
}

.card-estadistica span {
    margin-top: 8px;
    color: #475569;
    font-weight: bold;
    font-size: 14px;
}

/* META DIARIA */
.zona-meta {
    width: 86%;
    max-width: 1180px;
    margin: 25px auto 45px;
    display: grid;
    grid-template-columns: 1.3fr .9fr;
    gap: 24px;
    align-items: stretch;
}

.control-meta {
    width: 100%;
    margin: 0;
    padding: 28px;
}

.form-meta label {
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #1e3a8a;
    margin-bottom: 12px;
}

.meta-fila {
    display: flex;
    gap: 12px;
    margin-bottom: 22px;
}

.meta-fila input {
    flex: 1;
    padding: 14px 16px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    font-size: 16px;
    outline: none;
}

.meta-fila input:focus {
    border-color: #2563eb;
}

.meta-fila button {
    background: #2563eb;
    color: white;
    border: none;
    padding: 14px 22px;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
}

.meta-fila button:hover {
    background: #1e40af;
}

.resumen-meta {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 22px;
}

.resumen-meta div {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 20px;
    border-radius: 16px;
}

.resumen-meta h3 {
    margin: 0;
    font-size: 34px;
    color: #2563eb;
}

.resumen-meta span {
    display: block;
    margin-top: 6px;
    color: #475569;
    font-weight: bold;
    font-size: 14px;
}

.avance-texto {
    display: flex;
    justify-content: space-between;
    color: #1e3a8a;
    margin-bottom: 10px;
}

.avance-texto span {
    font-weight: bold;
    color: #2563eb;
}

.barra-avance {
    width: 100%;
    height: 16px;
    background: #e2e8f0;
    border-radius: 20px;
    overflow: hidden;
}

.barra-progreso {
    height: 100%;
    background: linear-gradient(90deg, #2563eb, #22c55e);
    border-radius: 20px;
}

.mensaje-meta {
    margin: 14px 0 0;
    color: #475569;
    font-size: 15px;
}

/* SLIDER PEQUEÑO */
.slider-card {
    padding: 24px;
}

.slider-card h2 {
    margin: 0 0 16px;
    color: #1e3a8a;
    font-size: 22px;
}

.slider {
    width: 100%;
    height: 260px;
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    background: #e2e8f0;
}

.slide {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.slide.active {
    opacity: 1;
}

.slider::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15,23,42,.70), rgba(15,23,42,.10));
    z-index: 2;
}

.slider-overlay {
    position: absolute;
    left: 22px;
    right: 22px;
    bottom: 38px;
    z-index: 3;
    color: white;
}

.slider-overlay strong {
    display: block;
    font-size: 22px;
    margin-bottom: 6px;
}

.slider-overlay span {
    font-size: 14px;
    line-height: 1.4;
}

.slider-puntos {
    position: absolute;
    bottom: 16px;
    left: 22px;
    display: flex;
    gap: 8px;
    z-index: 4;
}

.punto {
    width: 9px;
    height: 9px;
    background: rgba(255,255,255,.55);
    border-radius: 50%;
}

.punto.active {
    background: white;
    width: 24px;
    border-radius: 20px;
}

/* PIE */
.footer {
    width: 100%;
    background: #172b4d;
    color: white;
    text-align: center;
    padding: 16px;
    font-size: 14px;
}

/* RESPONSIVE */
@media (max-width: 1000px) {
    .topbar {
        padding: 22px 28px;
    }

    .menu-superior {
        gap: 24px;
    }

    .modulos {
        width: 86%;
        grid-template-columns: repeat(2, 1fr);
        margin-top: -50px;
    }

    .panel-info {
        grid-template-columns: 1fr;
    }

    .zona-meta {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {
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

    .hero {
        height: 410px;
    }

    .hero-contenido {
        padding: 0 25px;
        text-align: center;
        justify-content: center;
    }

    .hero-texto {
        transform: none;
        max-width: 100%;
    }

    .hero-texto h1 {
        font-size: 30px;
    }

    .hero-texto p {
        font-size: 15px;
    }

    .modulos {
        width: 90%;
        grid-template-columns: 1fr;
        margin-top: -40px;
    }

    .panel-info,
    .zona-meta {
        width: 90%;
    }

    .estadisticas,
    .resumen-meta {
        grid-template-columns: 1fr;
    }

    .meta-fila {
        flex-direction: column;
    }
}
.card-filtro-dashboard {
    gap: 8px;
}

.form-filtro-dashboard {
    width: 100%;
    margin-bottom: 10px;
}

.form-filtro-dashboard label {
    display: block;
    font-size: 13px;
    font-weight: bold;
    color: #1e3a8a;
    margin-bottom: 8px;
}

.form-filtro-dashboard select,
.form-filtro-dashboard input {
    width: 100%;
    height: 42px;
    padding: 9px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    background: white;
    color: #1e293b;
    font-weight: bold;
    outline: none;
    margin-bottom: 8px;
}

.form-filtro-dashboard select:focus,
.form-filtro-dashboard input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.13);
}
.card-estadistica {
    min-height: 180px;
}

.card-filtro-dashboard {
    min-width: 240px;
}

.form-filtro-dashboard select,
.form-filtro-dashboard input {
    width: 100%;
    min-width: 190px;
}
</style>
</head>

<body>

<!-- BARRA SUPERIOR -->
<header class="topbar">
    <div class="logo-hospital">Hospital San José de Chincha</div>

    <nav class="menu-superior">
        <a href="menu.php">Inicio</a>
        <a href="registrar_ecografia.php">Registrar</a>
        <a href="historial_ecografias.php">Historial</a>
        <a href="paloteo.php">Paloteo</a>
        <a href="mantenimiento.php">Mantenimiento</a>
        <a href="logout.php" class="btn-salir">Salir</a>
    </nav>
</header>

<!-- IMAGEN PRINCIPAL -->
<section class="hero">

    <div class="hero-slider">
        <img src="img/portada2.jpg?v=1" class="hero-slide active" alt="Portada 2">
        <img src="img/hero2.jpg" class="hero-slide" alt="Imagen 2">
        <img src="img/hero3.jpg" class="hero-slide" alt="Imagen 3">
    </div>

    <div class="hero-contenido">
        <div class="hero-texto">
            <h1>Sistema de Gestión de Ecografías</h1>
            <p>
                Optimiza el registro, consulta y seguimiento de las atenciones
                ecográficas del Área de Diagnóstico por Imágenes.
            </p>
        </div>
    </div>

    <div class="hero-puntos">
        <span class="hero-punto active"></span>
        <span class="hero-punto"></span>
        <span class="hero-punto"></span>
    </div>

</section>

<!-- CUATRO MÓDULOS -->
<section class="modulos">

    <a href="registrar_ecografia.php" class="modulo registrar">
        <div>
            <h3>Registrar</h3>
            <p>Ingresar datos del paciente, examen ecográfico, fecha, diagnóstico y observaciones.</p>
        </div>
        <span>➜</span>
    </a>

    <a href="historial_ecografias.php" class="modulo historial">
        <div>
            <h3>Historial</h3>
            <p>Buscar, consultar, editar y revisar los registros ecográficos almacenados.</p>
        </div>
        <span>➜</span>
    </a>

    <a href="paloteo.php" class="modulo paloteo">
        <div>
            <h3>Paloteo</h3>
            <p>Controlar y revisar las atenciones realizadas durante la jornada.</p>
        </div>
        <span>➜</span>
    </a>

    <a href="mantenimiento.php" class="modulo mantenimiento">
        <div>
            <h3>Mantenimiento</h3>
            <p>Administrar configuraciones, usuarios y datos generales del sistema.</p>
        </div>
        <span>➜</span>
    </a>

</section>

<!-- INFORMACIÓN Y CONTADORES -->
<!-- INFORMACIÓN Y CONTADORES -->
<section class="panel-info">

    <div class="bienvenida">
        <h2>Panel principal</h2>
        <p>
            Bienvenido al sistema. Desde este panel puede acceder rápidamente a los principales
            módulos para gestionar la información ecográfica de manera segura, ordenada y eficiente.
        </p>
        <div class="fecha-actual" id="fechaActual"></div>
    </div>

    <div class="estadisticas">

        <div class="card-estadistica card-filtro-dashboard">

            <form method="GET" class="form-filtro-dashboard">
                <label>Ver registros por:</label>

                <select name="filtro_dashboard" id="filtro_dashboard" onchange="guardarScroll(); this.form.submit()">
                    <option value="dia" <?php if($filtro_dashboard == 'dia') echo 'selected'; ?>>
                        Día específico
                    </option>

                    <option value="semana" <?php if($filtro_dashboard == 'semana') echo 'selected'; ?>>
                        Esta semana
                    </option>

                    <option value="mes" <?php if($filtro_dashboard == 'mes') echo 'selected'; ?>>
                        Este mes
                    </option>

                    <option value="anio" <?php if($filtro_dashboard == 'anio') echo 'selected'; ?>>
                        Este año
                    </option>

                    <option value="todos" <?php if($filtro_dashboard == 'todos') echo 'selected'; ?>>
                        Todos
                    </option>
                </select>

                <input 
                type="date" 
                name="fecha_dashboard" 
                id="fecha_dashboard"
                value="<?php echo $fecha_dashboard; ?>"
                max="<?php echo date('Y-m-d'); ?>"
                onchange="guardarScroll(); this.form.submit()"
>
            </form>

            <h2><?php echo $totalFiltrado['total']; ?></h2>
            <span><?php echo $tituloFiltro; ?></span>

        </div>

        <div class="card-estadistica">
            <h2><?php echo $hoy['total']; ?></h2>
            <span>Registradas hoy</span>
        </div>

    </div>

</section>
<section class="zona-meta">
<div class="control-meta">

    <form method="POST" class="form-meta">
        <label>Meta de registros para hoy</label>

        <div class="meta-fila">
            <input 
                type="number" 
                name="meta_diaria" 
                min="0" 
                value="<?php echo $meta_diaria; ?>" 
                placeholder="Ej: 40, 50, 60"
                required
            >

            <button type="submit" name="guardar_meta">
                Guardar meta
            </button>
        </div>
    </form>

    <div class="resumen-meta">
        <div>
            <h3><?php echo $meta_diaria; ?></h3>
            <span>Meta del día</span>
        </div>

        <div>
            <h3><?php echo $realizadas_hoy; ?></h3>
            <span>Registradas hoy</span>
        </div>

        <div>
            <h3><?php echo $faltantes; ?></h3>
            <span>Faltan por registrar</span>
        </div>
    </div>

    <div class="avance-texto">
        <strong>Avance diario</strong>
        <span><?php echo round($porcentaje); ?>%</span>
    </div>

    <div class="barra-avance">
        <div class="barra-progreso" style="width: <?php echo $porcentaje; ?>%;"></div>
    </div>

    <?php if ($meta_diaria == 0) { ?>
        <p class="mensaje-meta">Aún no se ha definido una meta para hoy.</p>
    <?php } else { ?>
        <p class="mensaje-meta">
            Hoy se han registrado 
            <strong><?php echo $realizadas_hoy; ?></strong> de 
            <strong><?php echo $meta_diaria; ?></strong> ecografías programadas.
        </p>
    <?php } ?>

</div>

    <div class="slider-card">
        <h2>Área de Diagnóstico por Imágenes</h2>

        <div class="slider">
            <img src="img/ecografia1.jpg" class="slide active" alt="Ecografía 1">
            <img src="img/ecografia2.jpg" class="slide" alt="Ecografía 2">
            <img src="img/ecografia3.jpg" class="slide" alt="Ecografía 3">

            <div class="slider-overlay">
                <strong>Sistema de Ecografías</strong>
                <span>Gestión ordenada y segura de registros.</span>
            </div>

            <div class="slider-puntos">
                <span class="punto active"></span>
                <span class="punto"></span>
                <span class="punto"></span>
            </div>
        </div>
    </div>

</section>
<footer class="footer">
    Hospital San José de Chincha – Área de Diagnóstico por Imágenes
</footer>

<script>
const fecha = new Date();
const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
document.getElementById("fechaActual").innerText = fecha.toLocaleDateString('es-PE', opciones);
</script>
<script>
let indiceSlide = 0;

const slides = document.querySelectorAll(".slide");
const puntos = document.querySelectorAll(".punto");

function mostrarSlide() {
    slides.forEach(slide => slide.classList.remove("active"));
    puntos.forEach(punto => punto.classList.remove("active"));

    indiceSlide++;

    if (indiceSlide >= slides.length) {
        indiceSlide = 0;
    }

    slides[indiceSlide].classList.add("active");
    puntos[indiceSlide].classList.add("active");
}

setInterval(mostrarSlide, 5000);
</script>
<script>
let heroIndex = 0;

const heroSlides = document.querySelectorAll(".hero-slide");
const heroPuntos = document.querySelectorAll(".hero-punto");

function cambiarHero() {
    heroSlides.forEach(slide => slide.classList.remove("active"));
    heroPuntos.forEach(punto => punto.classList.remove("active"));

    heroIndex++;

    if (heroIndex >= heroSlides.length) {
        heroIndex = 0;
    }

    heroSlides[heroIndex].classList.add("active");
    heroPuntos[heroIndex].classList.add("active");
}

setInterval(cambiarHero, 5000);
</script>
<script>
function controlarFechaDashboard() {
    const filtro = document.getElementById("filtro_dashboard");
    const fecha = document.getElementById("fecha_dashboard");

    if (filtro.value === "dia") {
        fecha.style.display = "block";
    } else {
        fecha.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", controlarFechaDashboard);
document.getElementById("filtro_dashboard").addEventListener("change", controlarFechaDashboard);
</script>
<script>
function guardarScroll() {
    sessionStorage.setItem("posicionScrollMenu", window.scrollY);
}

window.addEventListener("load", function () {
    const posicion = sessionStorage.getItem("posicionScrollMenu");

    if (posicion !== null) {
        window.scrollTo(0, parseInt(posicion));
        sessionStorage.removeItem("posicionScrollMenu");
    }
});

document.querySelectorAll("form").forEach(function(formulario) {
    formulario.addEventListener("submit", function() {
        guardarScroll();
    });
});
</script>
</body>
</html>