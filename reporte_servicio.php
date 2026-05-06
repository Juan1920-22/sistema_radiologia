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

$diasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

$servicios = [
    'Cardiología',
    'Cirugía',
    'Consultorio externo',
    'COVID-19',
    'Dengue',
    'Emergencia',
    'Endocrinología',
    'Gastroenterología',
    'Ginecología',
    'Infectología',
    'Medicina',
    'Medicina física y rehabilitación',
    'Nefrología',
    'Neonatología',
    'Neumología',
    'Neurología',
    'Obstetricia',
    'Oncología',
    'Otorrinolaringología',
    'Pediatría',
    'Psiquiatría',
    'Reumatología',
    'Shock trauma',
    'Traumatología',
    'UCIN',
    'Urología',
    'UVI',
    'UVICLIN',
    'Vésico prostático'
];

$tipos_atencion = [
    'Particular',
    'Periférica',
    'Ambulatorio',
    'Emergencia',
    'Hospitalaria'
];

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte por Servicio</title>

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
    max-width: 98%;
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
    font-size: 22px;
    color: #1e3a8a;
}

.encabezado h2 {
    margin: 6px 0 0;
    font-size: 17px;
    color: #334155;
}

.encabezado p {
    margin: 6px 0;
    color: #64748b;
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
.btn-pdf{
    background:#dc2626;
    color:white;
}

.tabla-contenedor {
    overflow-x: auto;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
}

table {
    border-collapse: collapse;
    width: 100%;
    min-width: 1180px;
    font-size: 10px;
}

th,
td {
    border: 1px solid #94a3b8;
    padding: 3px;
    text-align: center;
    height: 24px;
}

th {
    background: #e2e8f0;
    color: #111827;
    font-weight: bold;
}

.servicio {
    background: #f1f5f9;
    font-weight: bold;
    width: 125px;
    max-width: 125px;
    font-size: 9px;
    text-transform: uppercase;
    word-break: break-word;
}

.tipo {
    background: #ffffff;
    width: 90px;
    max-width: 90px;
    font-size: 9px;
    text-transform: uppercase;
    word-break: break-word;
}

.numero {
    width: 24px;
    min-width: 24px;
}

.total {
    background: #fef3c7;
    font-weight: bold;
    width: 50px;
    min-width: 50px;
}

.total-servicio {
    background: #dcfce7;
    font-weight: bold;
    font-size: 12px;
    width: 60px;
    min-width: 60px;
}

.total-diario {
    background: #dbeafe;
    font-weight: bold;
}

.total-general {
    background: #bbf7d0;
    font-weight: bold;
    font-size: 13px;
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
        font-size: 16px;
    }

    .encabezado h2 {
        font-size: 12px;
    }

    .encabezado p {
        font-size: 11px;
    }

    .tabla-contenedor {
        overflow: visible;
        border: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 7px;
        table-layout: fixed;
    }

    th,
    td {
        padding: 2px;
        height: 15px;
        line-height: 1.1;
        overflow: hidden;
        vertical-align: middle;
    }

    .servicio {
        width: 85px;
        font-size: 6px;
        word-break: break-word;
    }

    .tipo {
        width: 65px;
        font-size: 6px;
        word-break: break-word;
    }

    .numero {
        width: 18px;
    }

    .total,
    .total-servicio {
        width: 28px;
        font-size: 6px;
    }

    @page {
        size: A4 landscape;
        margin: 7mm;
    }
}
</style>
</head>

<body>

<div class="contenedor">

    <a href="reportes.php" class="volver">← Volver a Reportes</a>

    <div class="encabezado">
        <h1>HOSPITAL SAN JOSÉ DE CHINCHA</h1>
        <h2>INFORME ESTADÍSTICO POR SERVICIO</h2>
        <p>MES DE <?php echo $meses[$mes] . " " . $anio; ?></p>
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
        <button type="button"
class="btn-imprimir"
onclick="window.print()">

Imprimir

</button>

<button type="button"
class="btn-pdf"
onclick="descargarPDF()">

Descargar PDF

</button>
    </form>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">SERV.</th>
                    <th rowspan="2">TIPO<br>ATENCIÓN</th>
                    <th colspan="<?php echo $diasMes; ?>">DÍAS DEL MES</th>
                    <th rowspan="2">TOTAL</th>
                    <th rowspan="2">TOTAL<br>SERV.</th>
                </tr>

                <tr>
                    <?php for ($d = 1; $d <= $diasMes; $d++) { ?>
                        <th class="numero"><?php echo $d; ?></th>
                    <?php } ?>
                </tr>
            </thead>

            <tbody>
                <?php
                $totalDias = array_fill(1, $diasMes, 0);
                $totalGeneral = 0;
$totalesTipos = [
    'Particular' => 0,
    'Periférica' => 0,
    'Ambulatorio' => 0,
    'Emergencia' => 0,
    'Hospitalaria' => 0
];
                foreach ($servicios as $servicio) {

                    $totalServicio = 0;
                    $filas = [];

                    foreach ($tipos_atencion as $tipo) {

                        $totalTipo = 0;
                        $celdasDias = "";

                        for ($d = 1; $d <= $diasMes; $d++) {
                            $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $d);

                            $stmt = $conexion->prepare("
                                SELECT COUNT(*) AS total
                                FROM ecografias
                                WHERE servicio_solicitante = ?
                                AND tipo_atencion = ?
                                AND DATE(fecha) = ?
                            ");

                            $stmt->bind_param("sss", $servicio, $tipo, $fecha);
                            $stmt->execute();

                            $resultado = $stmt->get_result();
                            $fila = $resultado->fetch_assoc();

                            $cantidad = intval($fila['total']);

                            $totalTipo += $cantidad;
                            $totalesTipos[$tipo] += $cantidad;
                            $totalServicio += $cantidad;
                            $totalDias[$d] += $cantidad;
                            $totalGeneral += $cantidad;

                            $celdasDias .= "<td>" . ($cantidad > 0 ? $cantidad : "") . "</td>";
                        }

                        $filas[] = [
                            'tipo' => $tipo,
                            'dias' => $celdasDias,
                            'total_tipo' => $totalTipo
                        ];
                    }

                    $primeraFila = true;

                    foreach ($filas as $filaReporte) {

                        echo "<tr>";

                        if ($primeraFila) {
                            echo "<td class='servicio' rowspan='" . count($tipos_atencion) . "'>$servicio</td>";
                        }

                        echo "<td class='tipo'>" . $filaReporte['tipo'] . "</td>";
                        echo $filaReporte['dias'];
                        echo "<td class='total'>" . ($filaReporte['total_tipo'] > 0 ? $filaReporte['total_tipo'] : "") . "</td>";

                        if ($primeraFila) {
                            echo "<td class='total-servicio' rowspan='" . count($tipos_atencion) . "'>" . ($totalServicio > 0 ? $totalServicio : "") . "</td>";
                            $primeraFila = false;
                        }

                        echo "</tr>";
                    }
                }
                ?>

                <tr>
                    <td class="total-diario" colspan="2">TOTAL DIARIO</td>

                    <?php for ($d = 1; $d <= $diasMes; $d++) { ?>
                        <td class="total-diario">
                            <?php echo $totalDias[$d] > 0 ? $totalDias[$d] : ""; ?>
                        </td>
                    <?php } ?>

                    <td class="total-general">TOTAL</td>
                    <td class="total-general"><?php echo $totalGeneral; ?></td>
                    
                </tr>
            </tbody>
        </table>
    </div>

</div>
<script>

function descargarPDF(){

    const tituloOriginal = document.title;

    document.title =
    "Reporte_Servicio_<?php echo $meses[$mes] . '_' . $anio; ?>";

    window.print();

    setTimeout(() => {
        document.title = tituloOriginal;
    }, 1000);
}

</script>
</body>
</html>