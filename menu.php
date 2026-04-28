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
<title>Dashboard</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial;
    background: #f1f5f9;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.contenedor {
    width: 95%;
    max-width: 1200px;
    display: flex;
    background: linear-gradient(135deg, #0f172a, #1e3a8a, #2563eb);
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,.35);
    margin: 30px;
}

.izquierda {
    width: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
}

.logo-card {
    width: 80%;
    max-width: 420px;
    background: rgba(255,255,255,.12);
    padding: 30px;
    border-radius: 25px;
    box-shadow: 0 20px 40px rgba(0,0,0,.35);
    text-align: center;
}

.logo-card img {
    width: 100%;
    border-radius: 15px;
}

.derecha {
    width: 50%;
    padding: 40px;
    color: white;
}

h1 {
    margin-top: 0;
}

.sub {
    color: rgba(255,255,255,.8);
    margin-bottom: 25px;
}

.cards {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
}

.card {
    flex: 1;
    background: rgba(255,255,255,.14);
    padding: 20px;
    border-radius: 15px;
    text-align: center;
}

.card h2 {
    margin: 0;
    font-size: 30px;
}

.card span {
    font-size: 13px;
    color: rgb(255, 255, 255);
}

.boton {
    display: block;
    text-align: center;
    padding: 15px;
    margin-bottom: 12px;
    border-radius: 12px;
    color: white;
    font-weight: bold;
    text-decoration: none;
    transition: 0.2s;
}

.azul { background: #2563eb; }
.verde { background: #16a34a; }
.rojo { background: #dc2626; }
.gris { background: #475569; }

.boton:hover {
    transform: scale(1.03);
    opacity: .9;
}

@media (max-width: 900px) {
    body {
        align-items: flex-start;
    }

    .contenedor {
        flex-direction: column;
        margin: 20px;
    }

    .izquierda, .derecha {
        width: 100%;
    }
}
</style>

</head>
<body>

<div class="contenedor">

    <div class="izquierda">
        <div class="logo-card">
            <img src="img/hospital.jpg" alt="Hospital">
        </div>
    </div>

    <div class="derecha">

        <h1>Hospital San José de Chincha</h1>
        <p class="sub">Sistema Web de Gestión de Ecografías</p>

        <div class="cards">
            <div class="card">
                <h2><?php echo $total['total']; ?></h2>
                <span>Total Ecografías</span>
            </div>

            <div class="card">
                <h2><?php echo $hoy['total']; ?></h2>
                <span>Hoy</span>
            </div>
        </div>

        <a class="boton azul" href="registrar_ecografia.php">Registrar nueva ecografía</a>
        <a class="boton verde" href="historial_ecografias.php">Ver historial de ecografías</a>
        <a class="boton rojo" href="#">Paloteo</a>
        <a class="boton gris" href="#">Mantenimiento</a>

    </div>

</div>

</body>
</html>