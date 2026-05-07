<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

if ($mes < 1 || $mes > 12) {
    $mes = (int)date('m');
}

$periodo = sprintf('%04d%02d', $anio, $mes);
$codigo_ipress = '00003414';

$meses = [
    1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
    5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
    9 => 'SETIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
];
$periodo_busqueda = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT);
$stmt = $conexion->prepare("
    SELECT 
        c.codigo_cpt,
        c.examen_solicitado,
        c.co_codups,
        c.servicio_especialidad,
        COUNT(*) AS total
    FROM ecografias e
    INNER JOIN cpt_codes c 
        ON e.id_examen = c.id_examen
    WHERE DATE_FORMAT(e.fecha, '%Y-%m') = ?
    GROUP BY c.codigo_cpt, c.examen_solicitado, c.co_codups, c.servicio_especialidad
    ORDER BY c.codigo_cpt ASC
");

$stmt->bind_param("s", $periodo_busqueda);
$stmt->execute();
$resultado = $stmt->get_result();

$totalGeneral = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte CPT-Code</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #172b4d;
    color: #0f172a;
}

.contenedor {
    max-width: 1050px;
    margin: 35px auto;
    background: white;
    padding: 28px;
    border-radius: 18px;
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

.encabezado {
    text-align: center;
    margin-bottom: 20px;
}

.encabezado h1 {
    margin: 0;
    font-size: 20px;
    color: #1e3a8a;
}

.encabezado h2 {
    margin: 6px 0 0;
    font-size: 15px;
    color: #334155;
    text-decoration: underline;
}

.encabezado p {
    margin: 8px 0;
    color: #475569;
    font-weight: bold;
}

.filtros {
    display: flex;
    gap: 15px;
    align-items: end;
    background: #f8fafc;
    padding: 18px;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    margin-bottom: 22px;
}

.campo label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
    color: #334155;
}

select,
button {
    padding: 11px 14px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
}

button {
    background: #2563eb;
    color: white;
    border: none;
    font-weight: bold;
    cursor: pointer;
}

.btn-imprimir {
    background: #16a34a;
}

.tabla-contenedor {
    overflow-x: auto;
    border: 1px solid #94a3b8;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

th,
td {
    border: 1px solid #94a3b8;
    padding: 7px;
    text-align: center;
    height: 28px;
}

th {
    background: #e2e8f0;
    font-weight: bold;
}

.descripcion {
    text-align: left;
    font-weight: bold;
}

.total {
    background: #bbf7d0;
    font-weight: bold;
}

.catalogo {
    margin-top: 18px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 6px 35px;
    font-size: 12px;
    font-weight: bold;
}

.catalogo div {
    display: flex;
    gap: 12px;
}

.catalogo span {
    min-width: 60px;
}

@media print {
    body {
        background: white;
    }

    .volver,
    .filtros {
        display: none !important;
    }

    .contenedor {
        margin: 0;
        max-width: 100%;
        box-shadow: none;
        border-radius: 0;
        padding: 10px;
    }

    .encabezado h1 {
        font-size: 14px;
    }

    .encabezado h2 {
        font-size: 11px;
    }

    .encabezado p {
        font-size: 10px;
    }

    table {
        font-size: 9px;
    }

    th,
    td {
        padding: 4px;
        height: 20px;
    }

    .catalogo {
        font-size: 8px;
        gap: 3px 18px;
    }

    @page {
        size: A4 portrait;
        margin: 8mm;
    }
}
</style>
</head>

<body>

<div class="contenedor">

<a href="reportes.php" class="volver">← Volver a Reportes</a>

<div class="encabezado">
    <h1>HOSPITAL SAN JOSÉ DE CHINCHA</h1>
    <h2>REPORTE INFORMES ECOGRÁFICOS SEGÚN TABLA DE CODIFICACIÓN (CPT-CODE)</h2>
    <p>MES DE <?php echo $meses[$mes] . " " . $anio; ?></p>
</div>
<div style="
display:flex;
justify-content:flex-end;
margin-bottom:15px;
">

<a href="mantenimiento_cpt.php"
style="
background:#7c3aed;
color:white;
padding:12px 18px;
border-radius:12px;
text-decoration:none;
font-weight:bold;
box-shadow:0 4px 12px rgba(124,58,237,.25);
">

⚙ Configurar CPT-Code

</a>

</div>
<form method="GET" class="filtros">
    <div class="campo">
        <label>Mes</label>
        <select name="mes">
            <?php for ($m = 1; $m <= 12; $m++) { ?>
                <option value="<?php echo $m; ?>" <?php if ($m == $mes) echo 'selected'; ?>>
                    <?php echo $meses[$m]; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="campo">
        <label>Año</label>
        <select name="anio">
            <?php for ($a = 2024; $a <= 2030; $a++) { ?>
                <option value="<?php echo $a; ?>" <?php if ($a == $anio) echo 'selected'; ?>>
                    <?php echo $a; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <button type="submit">Generar reporte</button>
    <button type="button" class="btn-imprimir" onclick="window.print()">Imprimir</button>
</form>

<div class="tabla-contenedor">
<table>
    <thead>
        <tr>
            <th>PERIODO<br>DE REPORTE</th>
            <th>CÓDIGO DE LA<br>IPRESS</th>
            <th>CO-CODUPS</th>
            <th>CÓDIGO DEL<br>PROCEDIMIENTO</th>
            <th>TOTAL<br>PROCEDIMIENTOS</th>
            <th>SERVICIO /<br>ESPECIALIDAD</th>
        </tr>
    </thead>

    <tbody>
        <?php if ($resultado->num_rows > 0) { ?>
            <?php while ($fila = $resultado->fetch_assoc()) { 
                $totalGeneral += (int)$fila['total'];
            ?>
                <tr>
                    <td><?php echo $periodo; ?></td>
                    <td><?php echo $codigo_ipress; ?></td>
                    <td><?php echo $fila['co_codups']; ?></td>
                    <td><?php echo $fila['codigo_cpt']; ?></td>
                    <td><?php echo $fila['total']; ?></td>
                    <td><?php echo $fila['servicio_especialidad']; ?></td>
                </tr>
            <?php } ?>

            <tr>
                <td colspan="4" class="total">TOTAL GENERAL</td>
                <td class="total"><?php echo $totalGeneral; ?></td>
                <td class="total"></td>
            </tr>

        <?php } else { ?>
            <tr>
                <td colspan="6">No hay registros CPT para este periodo.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</div>

<div class="catalogo">
    <?php
    $sqlCatalogo = "
        SELECT codigo_cpt, examen_solicitado
        FROM cpt_codes
        ORDER BY codigo_cpt ASC
    ";
    $resCatalogo = $conexion->query($sqlCatalogo);

    while ($cat = $resCatalogo->fetch_assoc()) {
        echo "<div><span>" . $cat['codigo_cpt'] . "</span> " . $cat['examen_solicitado'] . "</div>";
    }
    ?>
</div>

</div>

</body>
</html>