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
    $where[] = "servicio_solicitante = '$servicio_solicitante'";
}

if (!empty($condicion)) {
    $where[] = "condicion = '$condicion'";
}

if (!empty($tipo_atencion)) {
    $where[] = "tipo_atencion = '$tipo_atencion'";
}

if (!empty($examen_solicitado)) {
    $where[] = "examen_solicitado = '$examen_solicitado'";
}

$where_sql = "";

if (count($where) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$sql = "SELECT * FROM ecografias $where_sql ORDER BY fecha DESC";
$resultado = mysqli_query($conexion, $sql);
$total_registros = $resultado->num_rows;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ecografías</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
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

        .acciones {
            display: flex;
            align-items: end;
            gap: 10px;
        }

        .btn {
            border: none;
            padding: 13px 22px;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 14px;
        }

        .buscar {
            background: #2563eb;
        }

        .limpiar {
            background: #16a34a;
        }
        .export {
            background: #f59e0b;
            color: white;
        }

        .export:hover {
           background: #d97706;
        }
        .tabla-contenedor {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 1100px;
        }

        th {
            background: #1e3a8a;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 11px;
            border-bottom: 1px solid #e2e8f0;
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
                <option <?php if($condicion=="Asegurado") echo "selected"; ?>>Asegurado</option>
                <option <?php if($condicion=="No asegurado") echo "selected"; ?>>No asegurado</option>
                <option <?php if($condicion=="Referido") echo "selected"; ?>>Referido</option>
                <option <?php if($condicion=="Particular") echo "selected"; ?>>Particular</option>
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
                <option <?php if($examen_solicitado=="Abdominal superior") echo "selected"; ?>>Abdominal superior</option>
                <option <?php if($examen_solicitado=="Retroperitoneal") echo "selected"; ?>>Retroperitoneal</option>
                <option <?php if($examen_solicitado=="Pélvica") echo "selected"; ?>>Pélvica</option>
                <option <?php if($examen_solicitado=="Transvaginal") echo "selected"; ?>>Transvaginal</option>
                <option <?php if($examen_solicitado=="Obstetricia") echo "selected"; ?>>Obstetricia</option>
                <option <?php if($examen_solicitado=="Renal y vejiga") echo "selected"; ?>>Renal y vejiga</option>
                <option <?php if($examen_solicitado=="Vejiga y próstata") echo "selected"; ?>>Vejiga y próstata</option>
                <option <?php if($examen_solicitado=="Transrectal") echo "selected"; ?>>Transrectal</option>
                <option <?php if($examen_solicitado=="De pulmones") echo "selected"; ?>>De pulmones</option>
                <option <?php if($examen_solicitado=="Ecografía de partes blandas") echo "selected"; ?>>Ecografía de partes blandas</option>
                <option <?php if($examen_solicitado=="Transfontanelar") echo "selected"; ?>>Transfontanelar</option>
                <option <?php if($examen_solicitado=="Tejidos blandos de cuero cabelludo") echo "selected"; ?>>Tejidos blandos de cuero cabelludo</option>
                <option <?php if($examen_solicitado=="Tejidos blandos de cuello") echo "selected"; ?>>Tejidos blandos de cuello</option>
                <option <?php if($examen_solicitado=="De tiroides") echo "selected"; ?>>De tiroides</option>
                <option <?php if($examen_solicitado=="De mamas") echo "selected"; ?>>De mamas</option>
                <option <?php if($examen_solicitado=="Tejidos blandos de tórax") echo "selected"; ?>>Tejidos blandos de tórax</option>
                <option <?php if($examen_solicitado=="Tejidos blandos de abdomen") echo "selected"; ?>>Tejidos blandos de abdomen</option>
                <option <?php if($examen_solicitado=="Tejidos blandos de pelvis") echo "selected"; ?>>Tejidos blandos de pelvis</option>
                <option <?php if($examen_solicitado=="Testicular") echo "selected"; ?>>Testicular</option>
                <option <?php if($examen_solicitado=="Histerosonografía") echo "selected"; ?>>Histerosonografía</option>
                <option <?php if($examen_solicitado=="De hernia umbilical") echo "selected"; ?>>De hernia umbilical</option>
                <option <?php if($examen_solicitado=="De hernia inguinal") echo "selected"; ?>>De hernia inguinal</option>
                <option <?php if($examen_solicitado=="De hernia inguinal bilateral") echo "selected"; ?>>De hernia inguinal bilateral</option>
                <option <?php if($examen_solicitado=="Eventración") echo "selected"; ?>>Eventración</option>
                <option <?php if($examen_solicitado=="Partes blandas tumoraciones-colecciones") echo "selected"; ?>>Partes blandas tumoraciones-colecciones</option>
                <option <?php if($examen_solicitado=="Doppler carotídeo") echo "selected"; ?>>Doppler carotídeo</option>
                <option <?php if($examen_solicitado=="Doppler ABC. SUP") echo "selected"; ?>>Doppler ABC. SUP</option>
                <option <?php if($examen_solicitado=="Doppler renal") echo "selected"; ?>>Doppler renal</option>
                <option <?php if($examen_solicitado=="Doppler prostático") echo "selected"; ?>>Doppler prostático</option>
                <option <?php if($examen_solicitado=="Doppler testicular") echo "selected"; ?>>Doppler testicular</option>
                <option <?php if($examen_solicitado=="Doppler ginecología") echo "selected"; ?>>Doppler ginecología</option>
                <option <?php if($examen_solicitado=="Doppler obstétrico") echo "selected"; ?>>Doppler obstétrico</option>
                <option <?php if($examen_solicitado=="Doppler ART M.SUP") echo "selected"; ?>>Doppler ART M.SUP</option>
                <option <?php if($examen_solicitado=="Doppler ART. M. SUP. BILATERAL") echo "selected"; ?>>Doppler ART. M. SUP. BILATERAL</option>
                <option <?php if($examen_solicitado=="Doppler ART. M. INF") echo "selected"; ?>>Doppler ART. M. INF</option>
                <option <?php if($examen_solicitado=="Doppler ART. M. INF. BILATERAL") echo "selected"; ?>>Doppler ART. M. INF. BILATERAL</option>
                <option <?php if($examen_solicitado=="Doppler venoso M.SUP") echo "selected"; ?>>Doppler venoso M.SUP</option>
                <option <?php if($examen_solicitado=="Doppler venoso M. SUP. BILATERAL") echo "selected"; ?>>Doppler venoso M. SUP. BILATERAL</option>
                <option <?php if($examen_solicitado=="Doppler venoso M. INF") echo "selected"; ?>>Doppler venoso M. INF</option>
                <option <?php if($examen_solicitado=="Doppler venoso M. INF. BILATERAL") echo "selected"; ?>>Doppler venoso M. INF. BILATERAL</option>
            </select>
        </div>

        <div class="acciones">
            <button type="button" id="btnPDF" class="btn export">Descargar PDF</button>
            <button type="submit" class="btn buscar">Buscar</button>
            <a href="historial_ecografias.php" class="btn limpiar">Limpiar</a>
        </div>

    </form>

    <div class="tabla-contenedor">
<div style="margin: 15px 0; font-weight: bold; color: #1e3a8a;">
    Registros encontrados: <?php echo $total_registros; ?>
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
                            <td><?php echo $fila['condicion']; ?></td>
                            <td><?php echo $fila['servicio_solicitante']; ?></td>
                            <td><?php echo $fila['medico_turno']; ?></td>
                            <td><?php echo $fila['tipo_atencion']; ?></td>
                            <td><?php echo $fila['examen_solicitado']; ?></td>
                            <td><?php echo $fila['diagnostico']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="10" class="vacio">No se encontraron ecografías con esos filtros.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    const fechaActual = `${yyyy}-${mm}-${dd}`;

    document.getElementById("fecha_inicio").setAttribute("max", fechaActual);
    document.getElementById("fecha_final").setAttribute("max", fechaActual);
</script>
<script>
document.getElementById("btnPDF").addEventListener("click", function(){

    const { jsPDF } = window.jspdf;

    var doc = new jsPDF('l','pt','a4');

    doc.text("Historial de Ecografías", 40, 40);

    doc.autoTable({
        html: 'table',  // usa tu tabla sin modificar nada
        startY: 60
    });

    doc.save("historial_ecografias.pdf");

});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</body>
</html>