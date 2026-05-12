<?php
include("conexion.php");

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final'] : '';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

$medico_turno = isset($_GET['medico_turno']) ? $_GET['medico_turno'] : '';
$servicio_solicitante = isset($_GET['servicio_solicitante']) ? $_GET['servicio_solicitante'] : '';
$condicion = isset($_GET['condicion']) ? $_GET['condicion'] : '';
$tipo_atencion = isset($_GET['tipo_atencion']) ? $_GET['tipo_atencion'] : '';
$examen_solicitado = isset($_GET['examen_solicitado']) ? $_GET['examen_solicitado'] : '';

$where = [];

if (!empty($fecha_inicio) && !empty($fecha_final)) {
    $where[] = "fecha BETWEEN '$fecha_inicio' AND '$fecha_final'";
} elseif (!empty($fecha_inicio)) {
    $where[] = "fecha >= '$fecha_inicio'";
} elseif (!empty($fecha_final)) {
    $where[] = "fecha <= '$fecha_final'";
}

if (!empty($busqueda)) {
    $where[] = "(historia_clinica LIKE '%$busqueda%' 
                OR dni LIKE '%$busqueda%'
                OR nombres LIKE '%$busqueda%' 
                OR apellidos LIKE '%$busqueda%')";
}

if (!empty($medico_turno)) {
    $where[] = "medico_turno = '$medico_turno'";
}

if (!empty($servicio_solicitante)) {
    $where[] = "e.id_servicio = '$servicio_solicitante'";
}

if (!empty($condicion)) {
    $where[] = "e.id_condicion = '$condicion'";
}

if (!empty($tipo_atencion)) {
    $where[] = "e.tipo_atencion = '$tipo_atencion'";
}

if (!empty($examen_solicitado)) {
    $where[] = "e.id_examen = '$examen_solicitado'";
}

$where_sql = "";

if (count($where) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$limite = 50;

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($pagina < 1) {
    $pagina = 1;
}

$inicio = ($pagina - 1) * $limite;
$sql_count = "SELECT COUNT(*) AS total
              FROM ecografias e
              $where_sql";

$res_count = mysqli_query($conexion, $sql_count);
$fila_count = mysqli_fetch_assoc($res_count);

$total_registros = $fila_count['total'];
$total_paginas = ceil($total_registros / $limite);
$sql = "SELECT 
            e.*,
            c.nombre AS condicion_nombre,
            s.nombre AS servicio_nombre,
            ex.nombre AS examen_nombre
        FROM ecografias e
        LEFT JOIN mantenimiento c ON e.id_condicion = c.id
        LEFT JOIN mantenimiento s ON e.id_servicio = s.id
        LEFT JOIN mantenimiento ex ON e.id_examen = ex.id
        $where_sql 
        ORDER BY e.fecha DESC
LIMIT $inicio, $limite";
$resultado = mysqli_query($conexion, $sql);

$sql_total_monto = "SELECT SUM(e.monto) AS total_monto 
                    FROM ecografias e 
                    $where_sql";
$resultado_monto = mysqli_query($conexion, $sql_total_monto);
$fila_monto = mysqli_fetch_assoc($resultado_monto);

$total_monto = $fila_monto['total_monto'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ecografías</title>

    <style>
        body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    min-height: 100vh;
    background: linear-gradient(180deg, #172b4d 0%, #172b4d 28%, #172b4d 100%);
    color: #0f172a;
    position: relative;
    }

        .contenedor {
            max-width: 1300px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,.12);
        }

        h1 {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 8px;
        }

        .subtitulo {
            text-align: center;
            color: #64748b;
            margin-bottom: 25px;
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

        .filtros {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            margin-bottom: 25px;
        }

        .campo {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 7px;
            color: #334155;
        }

        input, select {
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 15px;
            background: white;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }

        .acciones-tabla {
    display: flex;
    flex-direction: column;
    gap: 6px;
    align-items: center;
}


        .btn {
    border: none;
    height: 48px;
    min-width: 115px;
    padding: 0 20px;
    border-radius: 12px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    font-size: 14px;
    transition: .25s;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    white-space: nowrap;
    line-height: 1;
}


        .buscar {
            background: #2563eb;
        }

        .limpiar {
            background: #16a34a;
        }
        .export {
    min-width: 135px;
    background: #dc2626;
}

        .export:hover {
           background:  #d90606;
        }
        .tabla-contenedor {
           overflow-x: auto;
           border-radius: 14px;
           border: 1px solid #e2e8f0;
           }

        table {
           width: 100%;
           border-collapse: collapse;
           font-size: 13px;
           min-width: 1250px;
        }

           th, td {
           padding: 8px;
           word-wrap: break-word;
           text-align: center;
        }

th {
    background: #1e3a8a !important;
    color: white !important;
    font-weight: bold;
}

        td {
           padding: 12px 10px;
           border-bottom: 1px solid #e2e8f0;
           text-align: center;
           vertical-align: middle;
           word-break: normal;
        }

        tr:hover {
            background: #f8fafc;
        }

        .vacio {
            text-align: center;
            padding: 25px;
            color: #64748b;
        }

        @media (max-width: 900px) {
            .filtros {
                grid-template-columns: 1fr;
            }

        .acciones {
                flex-direction: column;
                align-items: stretch;
            }
        }

        .btn-editar,
.btn-eliminar {
    width: 80px;
    text-align: center;
    padding: 7px 0;
    border-radius: 8px;
    color: white !important;
    text-decoration: none !important;
    font-size: 12px;
    font-weight: bold;
    display: inline-block;
}
        .btn-editar {
    background: #f59e0b;
}

.btn-eliminar {
    background: #dc2626;
}
        .btn-editar:hover {
                background: #d97706;
        }
        .btn-eliminar:hover {
                background: #b91c1c;
        }
        td:nth-child(4),
        td:nth-child(8),
        td:nth-child(9),
        td:nth-child(10),
        td:nth-child(11) {
         max-width: 130px;
         white-space: normal;
        }
        .filtros-botones {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 12px;
    margin-top: 4px;
}
/* FRANJA AZUL SUPERIOR EN LA TARJETA DEL HISTORIAL */
.contenedor {
    position: relative;
    overflow: hidden;
    padding-top: 48px;
}

.contenedor::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: px;
    background: #172b4d;
}
.imprimir {
    background: #7c3aed;
}

.imprimir:hover {
    background: #6d28d9;
}
* {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
}
@media print {
    body {
        background: white;
    }

    .volver,
    .filtros,
    .acciones-tabla,
    th:last-child,
    td:last-child {
        display: none !important;
    }

    .contenedor {
        margin: 0;
        max-width: 100%;
        box-shadow: none;
        border-radius: 0;
        padding: 10px;
    }

    table {
        min-width: 100%;
        font-size: 8px;
    }

    th,
    td {
        padding: 4px;
    }

    @page {
        size: A4 landscape;
        margin: 8mm;
    }
}
@media print {
    .tabla-contenedor {
        background: white !important;
        border: 1px solid #cbd5e1 !important;
    }

    .contenedor {
        background: white !important;
    }
}
.paginacion {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 18px;
    margin: 25px 0 5px;
    font-weight: bold;
    color: #1e3a8a;
}

.btn-pagina {
    background: #1e3a8a;
    color: white;
    padding: 11px 18px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
}

.btn-pagina:hover {
    background: #172554;
}
.scroll-top {
    overflow-x: auto;
    overflow-y: hidden;
    height: 18px;
    margin-bottom: 5px;
}

.scroll-top div {
    height: 1px;
}
.diagnostico-celda {
    max-width: 260px;
    min-width: 220px;
    text-align: left;
    line-height: 1.4;
    font-size: 12px;
    color: #1e293b;
    background: #f8fafc;
    border-radius: 8px;
}
th:last-child,
td:last-child {
    position: sticky;
    right: 0;
    min-width: 110px;
    background: white;
    z-index: 10;
}

th:last-child {
    background: #1e3a8a !important;
    color: white !important;
    z-index: 20;
}
    </style>
</head>

<body>

<div class="contenedor">

    <a href="menu.php" class="volver">← Volver al menú</a>

    <h1>Historial de Ecografías</h1>
    <p class="subtitulo"></p>

    <form method="GET" class="filtros">

        <div class="campo">
            <label>Fecha inicio:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
        </div>

        <div class="campo">
            <label>Fecha final:</label>
            <input type="date" name="fecha_final" id="fecha_final" value="<?php echo $fecha_final; ?>">
        </div>

        <div class="campo">
            <label>Buscar paciente o H.C:</label>
            <input type="text" name="busqueda" placeholder="" value="<?php echo $busqueda; ?>">
        </div>

        <div class="campo">
            <label>Médico turno:</label>
            <select name="medico_turno">
                <option value="">Todos los médicos</option>
                <option <?php if($medico_turno=="BETTY CABRERA BENAVIDES") echo "selected"; ?>>BETTY CABRERA BENAVIDES</option>
                <option <?php if($medico_turno=="ELIEL MARAZA AQUINO") echo "selected"; ?>>ELIEL MARAZA AQUINO</option>
                <option <?php if($medico_turno=="FERNANDO DANIGNO AVALOS") echo "selected"; ?>>FERNANDO DANIGNO AVALOS</option>
                <option <?php if($medico_turno=="HUGO ALZAMORA SEBASTIAN") echo "selected"; ?>>HUGO ALZAMORA SEBASTIAN</option>
                <option <?php if($medico_turno=="JORGE LUIS MUÑANTE APARCANA") echo "selected"; ?>>JORGE LUIS MUÑANTE APARCANA</option>
                <option <?php if($medico_turno=="LUIS NAVARRO VALDIVIESO") echo "selected"; ?>>LUIS NAVARRO VALDIVIESO</option>
                <option <?php if($medico_turno=="MARITZA FLORES PAREDES") echo "selected"; ?>>MARITZA FLORES PAREDES</option>
                <option <?php if($medico_turno=="SONIA VALENZUELA RAMIREZ") echo "selected"; ?>>SONIA VALENZUELA RAMIREZ</option>
                <option <?php if($medico_turno=="VILA TOMAYQUISPE MANUEL") echo "selected"; ?>>VILA TOMAYQUISPE MANUEL</option>
            </select>
        </div>

        <div class="campo">
            <label>Servicio solicitante:</label>
            <select name="servicio_solicitante">
                <option value="">Todos los servicios</option>
                <option <?php if($servicio_solicitante=="Cardiología") echo "selected"; ?>>Cardiología</option>
                <option <?php if($servicio_solicitante=="Cirugía") echo "selected"; ?>>Cirugía</option>
                <option <?php if($servicio_solicitante=="Consultorio externo") echo "selected"; ?>>Consultorio externo</option>
                <option <?php if($servicio_solicitante=="COVID-19") echo "selected"; ?>>COVID-19</option>
                <option <?php if($servicio_solicitante=="Dengue") echo "selected"; ?>>Dengue</option>
                <option <?php if($servicio_solicitante=="Emergencia") echo "selected"; ?>>Emergencia</option>
                <option <?php if($servicio_solicitante=="Endocrinología") echo "selected"; ?>>Endocrinología</option>
                <option <?php if($servicio_solicitante=="Gastroenterología") echo "selected"; ?>>Gastroenterología</option>
                <option <?php if($servicio_solicitante=="Ginecología") echo "selected"; ?>>Ginecología</option>
                <option <?php if($servicio_solicitante=="Infectología") echo "selected"; ?>>Infectología</option>
                <option <?php if($servicio_solicitante=="Medicina") echo "selected"; ?>>Medicina</option>
                <option <?php if($servicio_solicitante=="Medicina física y rehabilitación") echo "selected"; ?>>Medicina física y rehabilitación</option>
                <option <?php if($servicio_solicitante=="Nefrología") echo "selected"; ?>>Nefrología</option>
                <option <?php if($servicio_solicitante=="Neonatología") echo "selected"; ?>>Neonatología</option>
                <option <?php if($servicio_solicitante=="Neumología") echo "selected"; ?>>Neumología</option>
                <option <?php if($servicio_solicitante=="Neurología") echo "selected"; ?>>Neurología</option>
                <option <?php if($servicio_solicitante=="Obstetricia") echo "selected"; ?>>Obstetricia</option>
                <option <?php if($servicio_solicitante=="Oncología") echo "selected"; ?>>Oncología</option>
                <option <?php if($servicio_solicitante=="Otorrinolaringología") echo "selected"; ?>>Otorrinolaringología</option>
                <option <?php if($servicio_solicitante=="Pediatría") echo "selected"; ?>>Pediatría</option>
                <option <?php if($servicio_solicitante=="Psiquiatría") echo "selected"; ?>>Psiquiatría</option>
                <option <?php if($servicio_solicitante=="Reumatología") echo "selected"; ?>>Reumatología</option>
                <option <?php if($servicio_solicitante=="Shock trauma") echo "selected"; ?>>Shock trauma</option>
                <option <?php if($servicio_solicitante=="Traumatología") echo "selected"; ?>>Traumatología</option>
                <option <?php if($servicio_solicitante=="UCIN") echo "selected"; ?>>UCIN</option>
                <option <?php if($servicio_solicitante=="Urología") echo "selected"; ?>>Urología</option>
                <option <?php if($servicio_solicitante=="UVI") echo "selected"; ?>>UVI</option>
                <option <?php if($servicio_solicitante=="UVICLIN") echo "selected"; ?>>UVICLIN</option>
                <option <?php if($servicio_solicitante=="Vésico prostático") echo "selected"; ?>>Vésico prostático</option>
            </select>
        </div>

        <div class="campo">
    <label>Condición:</label>
    <select name="condicion">
        <option value="">Todas</option>
        <?php
        $condicionesFiltro = $conexion->query("
            SELECT id, nombre 
            FROM mantenimiento 
            WHERE tipo = 'Condición de pago'
            ORDER BY nombre ASC
        ");

        while ($con = $condicionesFiltro->fetch_assoc()) {
            $selected = ($condicion == $con['id']) ? 'selected' : '';
            echo "<option value='" . $con['id'] . "' $selected>" . $con['nombre'] . "</option>";
        }
        ?>
    </select>
</div>

        <div class="campo">
            <label>Tipo de atención:</label>
            <select name="tipo_atencion">
                <option value="">Todos</option>
                <option <?php if($tipo_atencion=="Particular") echo "selected"; ?>>Particular</option>
                <option <?php if($tipo_atencion=="Periférica") echo "selected"; ?>>Periférica</option>
                <option <?php if($tipo_atencion=="Ambulatorio") echo "selected"; ?>>Ambulatorio</option>
                <option <?php if($tipo_atencion=="Emergencia") echo "selected"; ?>>Emergencia</option>
                <option <?php if($tipo_atencion=="Hospitalaria") echo "selected"; ?>>Hospitalaria</option>
            </select>
        </div>

        <div class="campo">
    <label>Examen solicitado:</label>
    <select name="examen_solicitado">
        <option value="">Todos los exámenes</option>
        <?php
        $examenesFiltro = $conexion->query("
            SELECT id, nombre 
            FROM mantenimiento 
            WHERE tipo = 'Examen'
            ORDER BY nombre ASC
        ");

        while ($ex = $examenesFiltro->fetch_assoc()) {
            $selected = ($examen_solicitado == $ex['id']) ? 'selected' : '';
            echo "<option value='" . $ex['id'] . "' $selected>" . $ex['nombre'] . "</option>";
        }
        ?>
    </select>
</div>

        <div class="acciones filtros-botones">
            <button type="button" id="btnPDF" class="btn export">Descargar PDF</button>

            <button type="button" class="btn imprimir" onclick="window.print()">Imprimir</button>

            <button type="submit" class="btn buscar">Buscar</button>

            <a href="historial_ecografias.php" class="btn limpiar">Limpiar</a>
        </div>

    </form>
<div class="scroll-top">
    <div></div>
</div>
    <div class="tabla-contenedor">
<div style="margin: 15px 0; font-weight: bold; color: #1e3a8a;">
    Registros encontrados: <?php echo $total_registros; ?> 
&nbsp;&nbsp; | &nbsp;&nbsp;
Monto total: S/ <?php echo number_format($total_monto, 2); ?>
</div>
<div class="paginacion paginacion-arriba">
    <?php
    $queryParams = $_GET;

    if ($pagina > 1) {
        $queryParams['pagina'] = $pagina - 1;
        echo '<a href="?' . http_build_query($queryParams) . '" class="btn-pagina">← Anterior</a>';
    }

    echo '<span>Página ' . $pagina . ' de ' . $total_paginas . '</span>';

    if ($pagina < $total_paginas) {
        $queryParams['pagina'] = $pagina + 1;
        echo '<a href="?' . http_build_query($queryParams) . '" class="btn-pagina">Siguiente →</a>';
    }
    ?>
</div>
        <table>
            <thead>
                <tr>
                    <th>H.C</th>
                    <th>DNI</th>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Sexo</th>
                    <th>Condición</th>
                    <th>Servicio</th>
                    <th>Médico</th>
                    <th>Tipo Atención</th>
                    <th>Examen</th>
                    <th>Diagnóstico</th>
                    <th>Monto</th>
                    <th>Boleta</th>
                    <th>Convenio</th>
                    <th>Acción</th>
                
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0) { ?>
                    <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
                        <tr>
                            <td><?php echo $fila['historia_clinica']; ?></td>
                            <td><?php echo $fila['dni']; ?></td>
                            <td><?php echo $fila['fecha']; ?></td>
                            <td><?php echo $fila['apellidos'] . " " . $fila['nombres']; ?></td>
                            <td><?php echo $fila['sexo']; ?></td>
                            <td><?php echo $fila['condicion_nombre']; ?></td>
                            <td><?php echo $fila['servicio_nombre']; ?></td>
                            <td><?php echo $fila['medico_turno']; ?></td>
                            <td><?php echo $fila['tipo_atencion']; ?></td>
                            <td><?php echo $fila['examen_nombre']; ?></td>
                            <td class="diagnostico-celda" title="<?php echo htmlspecialchars($fila['diagnostico']); ?>">
    <?php 
    $diagnostico = $fila['diagnostico'];

    if (strlen($diagnostico) > 80) {
        echo htmlspecialchars(substr($diagnostico, 0, 80)) . "...";
    } else {
        echo htmlspecialchars($diagnostico);
    }
    ?>
</td>
                            <td><?php 
                            if($fila['monto'] == 0 || $fila['monto'] == '0.00'){
                            echo "";
                            } else {
                            echo "S/ " . number_format($fila['monto'], 2);} ?> </td>
                            <td><?php echo !empty($fila['numero_boleta']) ? $fila['numero_boleta'] : ''; ?></td>
                            <td><?php echo !empty($fila['convenio']) ? $fila['convenio'] : ''; ?></td>
                            <td>
                            <div class="acciones-tabla">
                            <a class="btn-editar" href="editar_ecografia.php?id=<?php echo $fila['id_ecografia']; ?>">
                            Editar </a>
                            <a class="btn-eliminar" href="eliminar_ecografia.php?id=<?php echo $fila['id_ecografia']; ?>"
                            onclick="return confirmarEliminar();">
                            Eliminar </a>
                            </div>
</td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                        <td colspan="16" class="vacio">No se encontraron ecografías con esos filtros.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    const fechaActual = `${yyyy}-${mm}-${dd}`;

    document.getElementById("fecha_inicio").setAttribute("max", fechaActual);
    document.getElementById("fecha_final").setAttribute("max", fechaActual);
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
document.getElementById("btnPDF").addEventListener("click", function(){

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l','pt','a4');

    doc.setFontSize(16);
    doc.text("Historial de Ecografías", 40, 35);

    const filas = [];

    document.querySelectorAll("table tbody tr").forEach(row => {
        const c = row.querySelectorAll("td");

        if (c.length > 0) {
            filas.push([
                c[0].innerText,
                c[1].innerText,
                c[2].innerText,
                c[3].innerText,
                c[5].innerText,
                c[6].innerText,
                c[7].innerText,
                c[9].innerText,
                c[10].innerText,
                c[11].innerText,
                c[12].innerText === '-' ? '' : c[12].innerText,
                c[13].innerText === '-' ? '' : c[13].innerText
            ]);
        }
    });
let totalMonto = 0;

document.querySelectorAll("table tbody tr").forEach(row => {
    const c = row.querySelectorAll("td");

    if (c.length > 0) {

        let montoTexto = c[11].innerText
    .replace('S/', '')
    .replace(/,/g, '')
    .trim();

        if (montoTexto !== '' && montoTexto !== '-') {
            totalMonto += parseFloat(montoTexto);
        }
    }
});
    doc.autoTable({
        head: [[
            "H.C", "DNI", "Fecha", "Paciente", "Condición",
            "Servicio", "Médico", "Examen", "Diagnóstico", "Monto", "Boleta", "Convenio"
        ]],
        body: filas,
        startY: 55,
        theme: 'grid',
        styles: {
            fontSize: 8,
            cellPadding: 4,
            overflow: 'linebreak',
            valign: 'middle'
        }
    });
    doc.setFontSize(12);
    doc.text("TOTAL GENERAL: S/ " + totalMonto.toFixed(2), 40, doc.lastAutoTable.finalY + 25);
    doc.save("historial_ecografias.pdf");

});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(isset($_GET['editado'])) { ?>
<script>
    Swal.fire({
    icon: 'success',
    title: '¡Editado!',
    text: 'El registro se actualizó correctamente',
    showConfirmButton: false,
    timer: 2200,
    timerProgressBar: true
});
</script>
<?php } ?>
<?php if(isset($_GET['eliminado'])) { ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Eliminado',
    text: 'El registro fue eliminado correctamente',
    showConfirmButton: false,
    timer: 2000
});
</script>
<?php } ?>
<script>
    function confirmarEliminar() {
    return confirm("⚠️ ¿Seguro que deseas eliminar este registro?");
}
</script>
<script>
const scrollTop = document.querySelector('.scroll-top');
const scrollBottom = document.querySelector('.tabla-contenedor');

scrollTop.firstElementChild.style.width =
scrollBottom.scrollWidth + 'px';

scrollTop.addEventListener('scroll', () => {
    scrollBottom.scrollLeft = scrollTop.scrollLeft;
});

scrollBottom.addEventListener('scroll', () => {
    scrollTop.scrollLeft = scrollBottom.scrollLeft;
});
</script>
</body>
</html>