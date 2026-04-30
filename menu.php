<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$total = $conexion->query("SELECT COUNT(*) as total FROM ecografias")->fetch_assoc();
$hoy = $conexion->query("SELECT COUNT(*) as total FROM ecografias WHERE DATE(fecha)=CURDATE()")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>PANEL PRINCIPAL - SISTEMA DE ECOGRAFÍAS</title>

<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #e0f2fe, #f8fafc);
    min-height: 100vh;
    color: #0f172a;
}

.header {
    width: 100%;
    background: #ffffff;
    padding: 18px 45px;
    box-shadow: 0 4px 18px rgba(0,0,0,.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header h2 {
    margin: 0;
    color: #1d49b1;
    font-size: 30px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-family: 'Times New Roman', serif;
}

.header span {
    color: #64748b;
    font-size: 14px;
}

.contenedor {
    width: 92%;
    max-width: 1180px;
    margin: 45px auto;
    background: white;
    border-radius: 26px;
    box-shadow: 0 18px 45px rgba(15,23,42,.16);
    overflow: hidden;
    display: grid;
    grid-template-columns: 42% 58%;
}

.izquierda {
    background: linear-gradient(135deg, #dbeafe, #93c5fd, #2563eb);
    padding: 35px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.logo-card {
    background: rgba(255,255,255,.18);
    padding: 28px;
    border-radius: 24px;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0,0,0,.18);
}

.logo-card img {
    width: 100%;
    max-width: 350px;
    border-radius: 18px;
    background: white;
    padding: 12px;
}

.logo-card p {
    color: white;
    font-size: 14px;
    margin-top: 18px;
}

.derecha {
    padding: 48px;
}

.derecha h1 {
    margin: 0;
    color: #1e3a8a;
    font-size: 34px;
}

.sub {
    color: #64748b;
    margin-top: 8px;
    margin-bottom: 30px;
    font-size: 16px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 30px;
}

.card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 22px;
    border-radius: 18px;
}

.card h2 {
    margin: 0;
    font-size: 34px;
    color: #2563eb;
}

.card span {
    display: block;
    margin-top: 5px;
    color: #475569;
    font-weight: bold;
    font-size: 14px;
}

.menu {
    display: grid;
    gap: 14px;
}

.boton {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-radius: 18px;
    color: white;
    font-weight: bold;
    text-decoration: none;
    transition: 0.25s ease;
    box-shadow: 0 10px 22px rgba(15,23,42,.16);
}

.boton:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 30px rgba(15,23,42,.22);
}

.boton small {
    display: block;
    font-weight: normal;
    font-size: 12px;
    opacity: .9;
    margin-top: 3px;
}

.boton:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(0,0,0,.18);
}

.azul { background: #2563eb; }
.verde { background: #16a34a; }
.celeste { background: #0891b2; }
.gris { background: #475569; }
.salir-sistema { background: #991b1b; }

.salir-sistema:hover {
    background: #7f1d1d;
}

.footer-info {
    margin-top: 28px;
    padding: 14px;
    background: #eff6ff;
    border-left: 5px solid #2563eb;
    border-radius: 12px;
    color: #334155;
    font-size: 14px;
}

@media (max-width: 900px) {
    .header {
        padding: 16px 22px;
        flex-direction: column;
        gap: 5px;
        text-align: center;
    }

    .contenedor {
        grid-template-columns: 1fr;
        margin: 25px auto;
    }

    .izquierda {
        padding: 30px;
    }

    .derecha {
        padding: 30px;
    }

    .derecha h1 {
        font-size: 27px;
        text-align: center;
    }

    .sub {
        text-align: center;
    }

    .cards {
        grid-template-columns: 1fr;
    }
}

.salir-contenedor {
    width: 100%;
    display: flex;
    justify-content: flex-end;
    margin-top: 5px;
}

.btn-salir {
    display: inline-block !important;
    background: #dc2626 !important;
    color: white !important;
    padding: 7px 14px !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    text-decoration: none !important;
    font-weight: bold !important;
}

.btn-salir:hover {
    background: #b91c1c !important;
}
.bienvenida {
    background: #eff6ff;
    border-left: 5px solid #2563eb;
    padding: 13px 16px;
    border-radius: 12px;
    color: #1e3a8a;
    font-size: 15px;
    margin-bottom: 25px;
}
.fecha-actual {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 25px;
}
.franja-inferior {
    width: 92%;
    max-width: 1180px;
    margin: -25px auto 30px;
    background: #1e3a8a;
    color: white;
    text-align: center;
    padding: 14px;
    border-radius: 0 0 18px 18px;
    font-size: 14px;
    letter-spacing: .5px;
}
</style>
</head>

<body>

<div class="header">
    <h2>HOSPITAL SAN JOSÉ DE CHINCHA</h2>
    <span>SISTEMA DE GESTIÓN DE ECOGRAFIAS</span>
</div>

<div class="contenedor">

    <div class="izquierda">
        <div class="logo-card">
            <img src="img/hospital.3.jpg" alt="Hospital San José de Chincha">
            <p>ÁREA DE DIAGNOSTICO POR IMAGEN</p>
        </div>
    </div>

    <div class="derecha">

        <h1>PANEL PRINCIPAL</h1>
        <p class="sub"> </p>
    <div class="bienvenida">
    Bienvenido al sistema. Gestione los registros ecográficos de manera rápida, segura y ordenada.
    </div>
    <div class="fecha-actual" id="fechaActual"></div>
        <div class="cards">
            <div class="card">
                <h2><?php echo $total['total']; ?></h2>
                <span>Total de ecografías</span>
            </div>

            <div class="card">
                <h2><?php echo $hoy['total']; ?></h2>
                <span>Registradas hoy</span>
            </div>
        </div>

        <div class="menu">
            <a class="boton azul" href="registrar_ecografia.php">
                <div>
                    Registrar nueva ecografía
                    <small>Ingresar datos del paciente y examen</small>
                </div>
                <span>➜</span>
            </a>

            <a class="boton verde" href="historial_ecografias.php">
                <div>
                    Ver historial de ecografías
                    <small>Buscar, filtrar, editar y consultar registros</small>
                </div>
                <span>➜</span>
            </a>

            <a class="boton celeste" href="#">
                <div>
                    Paloteo
                    <small>Control y revisión de atenciones</small>
                </div>
                <span>➜</span>
            </a>

            <a class="boton gris" href="mantenimiento.php">
                <div>
                    Mantenimiento
                    <small>Configuración y administración del sistema</small>
                </div>
                <span>➜</span>
            </a>

         <div class="salir-contenedor">
    <a href="logout.php" class="btn-salir"> Salir</a>
</div>

</div> <!-- cierre de menu -->

<div class="footer-info">
            Sistema diseñado para optimizar el registro y seguimiento de ecografías del Hospital San José de Chincha.
        </div>

    </div>

</div>
<script>
const fecha = new Date();
const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
document.getElementById("fechaActual").innerText = fecha.toLocaleDateString('es-PE', opciones);
</script>
<div class="franja-inferior">
    Hospital San José de Chincha – Área de Diagnóstico por Imágenes
</div>
</body>
</html>