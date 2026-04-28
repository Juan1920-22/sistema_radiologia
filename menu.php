<?php
include("conexion.php");

// CONSULTAS
$total = $conexion->query("SELECT COUNT(*) as total FROM ecografias")->fetch_assoc();
$hoy = $conexion->query("SELECT COUNT(*) as total FROM ecografias WHERE DATE(fecha)=CURDATE()")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Principal - Sistema de Ecografías</title>

<style>
* {
    box-sizing: border-box;
}

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
    color: #1e3a8a;
    font-size: 22px;
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
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    padding: 45px;
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
    max-width: 330px;
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
    padding: 16px 20px;
    border-radius: 15px;
    color: white;
    font-weight: bold;
    text-decoration: none;
    transition: 0.2s ease;
    box-shadow: 0 8px 18px rgba(0,0,0,.12);
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
</style>

</head>
<body>

<div class="header">
    <h2>Hospital San José de Chincha</h2>
    <span>Panel Principal del Sistema de Gestión de Ecografías</span>
</div>

<div class="contenedor">

    <div class="izquierda">
        <div class="logo-card">
            <img src="img/hospital.jpg" alt="Hospital San José de Chincha">
            <p>Área de Diagnóstico por Imágenes</p>
        </div>
    </div>

    <div class="derecha">

        <h1>Panel Principal</h1>
        <p class="sub">Gestión, consulta y control de registros ecográficos</p>

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
                    <small>Buscar, filtrar y consultar registros</small>
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

            <a class="boton gris" href="#">
                <div>
                    Mantenimiento
                    <small>Configuración y administración del sistema</small>
                </div>
                <span>➜</span>
            </a>
        </div>

        <div class="footer-info">
            Sistema diseñado para optimizar el registro y seguimiento de ecografías del Hospital San José de Chincha.
        </div>

    </div>

</div>

</body>
</html>