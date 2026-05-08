<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

$meses = [
    1 => 'ENERO',
    2 => 'FEBRERO',
    3 => 'MARZO',
    4 => 'ABRIL',
    5 => 'MAYO',
    6 => 'JUNIO',
    7 => 'JULIO',
    8 => 'AGOSTO',
    9 => 'SETIEMBRE',
    10 => 'OCTUBRE',
    11 => 'NOVIEMBRE',
    12 => 'DICIEMBRE'
];

$sql = "
SELECT 
    c.nombre AS condicion,
    COUNT(*) AS cantidad,
    SUM(
        CASE 
            WHEN e.monto IS NULL OR e.monto = '' 
            THEN 0
            ELSE e.monto
        END
    ) AS total_monto
FROM ecografias e
LEFT JOIN mantenimiento c 
    ON e.id_condicion = c.id
WHERE MONTH(e.fecha) = ?
AND YEAR(e.fecha) = ?
GROUP BY e.id_condicion, c.nombre
ORDER BY c.nombre ASC
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $mes, $anio);
$stmt->execute();

$resultado = $stmt->get_result();

$totalCantidad = 0;
$totalDinero = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Económico</title>

<style>
*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#172b4d;
    color:#0f172a;
}

.contenedor{
    max-width:1000px;
    margin:35px auto;
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 12px 30px rgba(0,0,0,.18);
}

.volver{
    display:inline-block;
    margin-bottom:20px;
    background:#64748b;
    color:white;
    padding:11px 20px;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
}

.encabezado{
    text-align:center;
    margin-bottom:25px;
}

.encabezado h1{
    margin:0;
    color:#1e3a8a;
    font-size:24px;
}

.encabezado h2{
    margin:8px 0;
    color:#334155;
    font-size:18px;
}

.encabezado p{
    color:#64748b;
    font-weight:bold;
}

.filtros{
    display:flex;
    gap:15px;
    align-items:end;
    background:#f8fafc;
    padding:18px;
    border-radius:16px;
    border:1px solid #e2e8f0;
    margin-bottom:25px;
}

.campo label{
    display:block;
    margin-bottom:7px;
    font-weight:bold;
}

select,
button{
    padding:11px 14px;
    border-radius:10px;
    border:1px solid #cbd5e1;
    font-size:14px;
}

button{
    background:#2563eb;
    color:white;
    border:none;
    font-weight:bold;
    cursor:pointer;
}

.btn-imprimir{
    background:#16a34a;
}

.tabla-contenedor{
    overflow-x:auto;
    border:1px solid #cbd5e1;
    border-radius:14px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#1e3a8a;
    color:white;
    padding:14px;
    font-size:14px;
}

td{
    padding:14px;
    border-bottom:1px solid #e2e8f0;
    text-align:center;
    font-size:14px;
}

.condicion{
    text-align:left;
    font-weight:bold;
}

.total{
    background:#dcfce7;
    font-weight:bold;
}

.monto{
    color:#166534;
    font-weight:bold;
}

.tarjetas{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
    margin-bottom:25px;
}

.card{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:18px;
    padding:22px;
    text-align:center;
}

.card h3{
    margin:0 0 10px;
    color:#64748b;
    font-size:14px;
}

.card .valor{
    font-size:28px;
    font-weight:bold;
    color:#1e3a8a;
}

.card.green .valor{
    color:#16a34a;
}

@media(max-width:850px){

    .tarjetas{
        grid-template-columns:1fr;
    }

    .filtros{
        flex-direction:column;
        align-items:stretch;
    }
}

@media print{

    body{
        background:white;
    }

    .volver,
    .filtros{
        display:none !important;
    }

    .contenedor{
        margin:0;
        max-width:100%;
        box-shadow:none;
        border-radius:0;
        padding:10px;
    }

    @page{
        size:A4 portrait;
        margin:10mm;
    }
}
</style>
</head>

<body>

<div class="contenedor">

<a href="reportes.php" class="volver">
← Volver a Reportes
</a>

<div class="encabezado">
    <h1>HOSPITAL SAN JOSÉ DE CHINCHA</h1>
    <h2>REPORTE ECONÓMICO DE ECOGRAFÍAS</h2>
    <p>
        MES DE <?php echo $meses[$mes] . " " . $anio; ?>
    </p>
</div>

<form method="GET" class="filtros">

    <div class="campo">
        <label>Mes</label>

        <select name="mes">
            <?php for($m=1; $m<=12; $m++){ ?>

                <option value="<?php echo $m; ?>"
                    <?php if($m == $mes) echo 'selected'; ?>>

                    <?php echo $meses[$m]; ?>

                </option>

            <?php } ?>
        </select>
    </div>

    <div class="campo">
        <label>Año</label>

        <select name="anio">
            <?php for($a=2024; $a<=2030; $a++){ ?>

                <option value="<?php echo $a; ?>"
                    <?php if($a == $anio) echo 'selected'; ?>>

                    <?php echo $a; ?>

                </option>

            <?php } ?>
        </select>
    </div>

    <button type="submit">
        Generar reporte
    </button>

    <button type="button"
        class="btn-imprimir"
        onclick="window.print()">

        Imprimir

    </button>

</form>

<div class="tarjetas">

    <div class="card">
        <h3>Total registros</h3>
        <div class="valor" id="cantidadTotal">0</div>
    </div>

    <div class="card green">
        <h3>Total recaudado</h3>
        <div class="valor" id="montoTotal">S/ 0</div>
    </div>

    <div class="card">
        <h3>Condiciones registradas</h3>
        <div class="valor" id="condicionesTotal">0</div>
    </div>

</div>

<div class="tabla-contenedor">

<table>

<thead>

<tr>
    <th>CONDICIÓN</th>
    <th>CANTIDAD</th>
    <th>TOTAL RECAUDADO</th>
</tr>

</thead>

<tbody>

<?php
$totalCondiciones = 0;

while($fila = $resultado->fetch_assoc()){

    $cantidad = intval($fila['cantidad']);
    $monto = floatval($fila['total_monto']);

    $totalCantidad += $cantidad;
    $totalDinero += $monto;
    $totalCondiciones++;
?>

<tr>

    <td class="condicion">
        <?php echo $fila['condicion']; ?>
    </td>

    <td>
        <?php echo $cantidad; ?>
    </td>

    <td class="monto">
        S/ <?php echo number_format($monto,2); ?>
    </td>

</tr>

<?php } ?>

<tr class="total">

    <td>
        TOTAL GENERAL
    </td>

    <td>
        <?php echo $totalCantidad; ?>
    </td>

    <td>
        S/ <?php echo number_format($totalDinero,2); ?>
    </td>

</tr>

</tbody>

</table>

</div>

</div>

<script>

document.getElementById('cantidadTotal').innerText =
'<?php echo $totalCantidad; ?>';

document.getElementById('montoTotal').innerText =
'S/ <?php echo number_format($totalDinero,2); ?>';

document.getElementById('condicionesTotal').innerText =
'<?php echo $totalCondiciones; ?>';

</script>

</body>
</html>