<?php
include("conexion.php");

$condiciones = $conexion->query("SELECT nombre FROM mantenimiento WHERE tipo='Condición de pago' AND estado=1 ORDER BY nombre ASC");
$servicios = $conexion->query("SELECT nombre FROM mantenimiento WHERE tipo='Servicio' AND estado=1 ORDER BY nombre ASC");
$examenes = $conexion->query("SELECT nombre FROM mantenimiento WHERE tipo='Examen' AND estado=1 ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Ecografía</title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e0f2fe, #f8fafc);
            color: #1e293b;
        }

        .header {
            position: relative;
            height: 230px;
            background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
                        url('img/hospital.1.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 50px;
            color: white;
            box-shadow: 0 6px 20px rgba(0,0,0,.20);
        }

        .header h1 {
            margin: 0;
            font-size: 34px;
            font-weight: bold;
        }

        .header p {
            margin-top: 8px;
            font-size: 17px;
            opacity: .95;
        }

        .contenedor {
            max-width: 1150px;
            margin: -45px auto 35px;
            background: white;
            padding: 35px;
            border-radius: 22px;
            box-shadow: 0 12px 35px rgba(0,0,0,.14);
            position: relative;
            z-index: 2;
        }

        .titulo { text-align: center; margin-bottom: 30px; }

        .titulo h2 {
            margin: 0;
            color: #1e3a8a;
            font-size: 28px;
        }

        .titulo span {
            color: #64748b;
            font-size: 14px;
        }

        form {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .campo {
            display: flex;
            flex-direction: column;
        }

        .completo { grid-column: span 3; }

        label {
            font-weight: bold;
            margin-bottom: 7px;
            color: #334155;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 15px;
            background: #f8fafc;
            outline: none;
        }

        input:focus, textarea:focus {
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 47px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b;
            line-height: 47px;
            padding-left: 14px;
            font-size: 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 47px;
            right: 10px;
        }

        .select2-dropdown {
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            overflow: hidden;
        }

        .select2-search__field {
            border-radius: 8px !important;
            padding: 10px !important;
            outline: none;
        }

        .botones {
            grid-column: span 3;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 15px;
        }

        button, .salir {
            border: none;
            padding: 14px 30px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
        }

        button {
            background: #2563eb;
            color: white;
        }

        button:hover { background: #1d4ed8; }

        .salir {
            background: #64748b;
            color: white;
        }

        .salir:hover { background: #475569; }

        .flatpickr-day.disabled,
        .flatpickr-day.disabled:hover {
            color: #cbd5e1 !important;
            cursor: not-allowed !important;
            background: #f1f5f9 !important;
        }

        @media (max-width: 900px) {
            .header {
                height: 200px;
                padding: 0 25px;
            }

            .header h1 {
                font-size: 26px;
            }

            form { grid-template-columns: 1fr; }

            .completo, .botones {
                grid-column: span 1;
            }

            .botones {
                flex-direction: column;
            }
        }
        /* ===== ESTILO GENERAL IGUAL AL DASHBOARD ===== */

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

/* ===== BARRA SUPERIOR ===== */

.topbar {
    width: 100%;
    background: #172b4d;
    color: white;
    padding: 20px 42px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 9999;
    box-shadow: 0 4px 18px rgba(0,0,0,.18);
}

.logo-hospital {
    font-size: 24px;
    font-weight: bold;
    letter-spacing: 1.2px;
    font-family: 'Times New Roman', serif;
    text-transform: uppercase;
}

.menu-superior {
    display: flex;
    align-items: center;
    gap: 28px;
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

.btn-salir-top {
    background: #dc2626;
    padding: 10px 16px;
    border-radius: 10px;
}

.btn-salir-top:hover {
    background: #b91c1c;
    color: white !important;
}

/* ===== ENCABEZADO DE REGISTRO ===== */

.page-header {
    height: 330px;
    background:
        linear-gradient(90deg, rgba(15,23,42,.62), rgba(15,23,42,.30)),
        url('img/hospital.1.jpg') center center / cover no-repeat;
    display: flex;
    align-items: center;
    padding: 0 7%;
    color: white;
    box-shadow: 0 8px 24px rgba(15,23,42,.18);
}

.page-header span {
    display: inline-block;
    background: rgba(255,255,255,.18);
    padding: 8px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 12px;
    backdrop-filter: blur(4px);
}

.page-header h1 {
    margin: 0;
    font-size: 42px;
    font-weight: 800;
    text-shadow: 0 4px 12px rgba(0,0,0,.35);
}

.page-header p {
    margin-top: 10px;
    font-size: 18px;
    opacity: .95;
}

/* ===== TARJETA DEL FORMULARIO ===== */

.contenedor {
    width: 88%;
    max-width: 1180px;
    margin: -25px auto 45px;
    background: rgba(255,255,255,.93);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 38px;
    border-radius: 26px;
    box-shadow: 0 18px 42px rgba(15,23,42,.16);
    position: relative;
    z-index: 2;
    border: 1px solid rgba(203,213,225,.60);
}

.titulo {
    text-align: center;
    margin-bottom: 32px;
}

.titulo h2 {
    margin: 0;
    color: #1e3a8a;
    font-size: 31px;
    font-weight: 800;
}

.titulo span {
    color: #64748b;
    font-size: 15px;
}

/* ===== FORMULARIO ===== */

form {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.campo {
    display: flex;
    flex-direction: column;
}

.completo {
    grid-column: span 3;
}

label {
    font-weight: bold;
    margin-bottom: 8px;
    color: #1e293b;
    font-size: 14px;
}

input,
select,
textarea {
    width: 100%;
    padding: 14px 15px;
    border: 1px solid #cbd5e1;
    border-radius: 13px;
    font-size: 15px;
    background: #f8fafc;
    outline: none;
    transition: .25s;
}

input:focus,
textarea:focus,
select:focus {
    border-color: #2563eb;
    background: white;
    box-shadow: 0 0 0 3px rgba(37,99,235,.13);
}

textarea {
    min-height: 120px;
    resize: vertical;
}

/* ===== SELECT2 ADAPTADO ===== */

.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--single {
    height: 50px;
    border: 1px solid #cbd5e1;
    border-radius: 13px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    transition: .25s;
}

.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default .select2-selection--single:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.13);
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #1e293b;
    line-height: 50px;
    padding-left: 14px;
    font-size: 15px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 50px;
    right: 10px;
}

.select2-dropdown {
    border-radius: 13px;
    border: 1px solid #cbd5e1;
    overflow: hidden;
    box-shadow: 0 12px 26px rgba(15,23,42,.12);
}

/* ===== BOTONES ===== */

.botones {
    grid-column: span 3;
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 22px;
}

.botones button,
.salir {
    border: none;
    padding: 14px 26px;
    border-radius: 13px;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    text-decoration: none;
    transition: .25s;
}

.botones button {
    background: linear-gradient(90deg, #2563eb, #1d4ed8);
    color: white;
    box-shadow: 0 10px 22px rgba(37,99,235,.22);
}

.botones button:hover {
    transform: translateY(-2px);
    background: #1d4ed8;
}
.salir {
    background: #64748b;
    color: white;
}

.salir:hover {
    transform: translateY(-2px);
    background: #475569;
}

/* ===== RESPONSIVE ===== */

@media (max-width: 1000px) {
    .topbar {
        flex-direction: column;
        gap: 14px;
        text-align: center;
        padding: 18px 24px;
    }

    .menu-superior {
        flex-wrap: wrap;
        justify-content: center;
        gap: 18px;
    }

    form {
        grid-template-columns: repeat(2, 1fr);
    }

    .completo,
    .botones {
        grid-column: span 2;
    }
}

@media (max-width: 700px) {
    .page-header {
        height: 220px;
        padding: 0 25px;
        text-align: center;
        justify-content: center;
    }

    .page-header h1 {
        font-size: 30px;
    }

    .page-header p {
        font-size: 15px;
    }

    .contenedor {
        width: 92%;
        padding: 26px;
        margin-top: -35px;
    }

    form {
        grid-template-columns: 1fr;
    }

    .completo,
    .botones {
        grid-column: span 1;
    }

    .botones {
        flex-direction: column;
    }

    button,
    .salir {
        width: 100%;
        text-align: center;
    }
}
.page-header-contenido {
    max-width: 700px;
}

.btn-volver-header {
    display: inline-block;
    margin-top: 22px;
    background: rgba(255,255,255,0.18);
    color: white;
    text-decoration: none;
    padding: 12px 18px;
    border-radius: 12px;
    font-weight: bold;
    font-size: 14px;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.25);
    transition: .25s;
    position: relative;
    z-index: 5;
}

.btn-volver-header:hover {
    background: rgba(255,255,255,0.28);
    transform: translateY(-2px);
    color: white;
}
/* CORRECCIÓN SELECT2: elimina la mancha azul */
.select2-selection__clear {
    display: none !important;
}

.select2-container--default .select2-selection--single button {
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
    border-radius: 0 !important;
    width: auto !important;
    height: auto !important;
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
        <a href="paloteo.php">Paloteo</a>
        <a href="mantenimiento.php">Mantenimiento</a>
        <a href="logout.php" class="btn-salir-top">Salir</a>
    </nav>
</header>

<section class="page-header">
    <div class="page-header-contenido">
        <span>Área de Diagnóstico por Imágenes</span>
        <h1>Registrar Nueva Ecografía</h1>
        <p>Complete los datos del paciente y del examen solicitado.</p>

        <a href="menu.php" class="btn-volver-header">← Volver al menú</a>
    </div>
</section>

<div class="contenedor">

    <div class="titulo">
        <h2>Registrar Nueva Ecografía</h2>
        <span>Complete los datos del paciente y del examen solicitado</span>
    </div>

    <form action="guardar_ecografia.php" method="POST">

        <div class="campo">
            <label>H.C:</label>
            <input type="text" name="historia_clinica" required>
        </div>

        <div class="campo">
         <label>DNI:</label>
         <input type="text" name="dni" id="dni" maxlength="8" placeholder="Ingrese DNI" required>
        </div>

        <div class="campo">
            <label>Fecha:</label>
            <input type="text" name="fecha" id="fecha" required placeholder="Seleccione una fecha">
        </div>

        <div class="campo">
            <label>Sexo:</label>
            <select class="buscador" name="sexo" required>
                <option value="">Seleccione o escriba...</option>
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
            </select>
        </div>

        <div class="campo">
            <label>Apellidos:</label>
            <input type="text" name="apellidos" required>
        </div>

        <div class="campo">
            <label>Nombres:</label>
            <input type="text" name="nombres" required>
        </div>

        <div class="campo">
    <label>Condición:</label>
    <select class="buscador" name="condicion" required>
        <option value="">Seleccione o escriba...</option>

        <?php while($c = $condiciones->fetch_assoc()) { ?>
            <option value="<?php echo $c['nombre']; ?>">
                <?php echo $c['nombre']; ?>
            </option>
        <?php } ?>
    </select>
</div>
<!-- 👇 CONVENIO -->
<div class="campo" id="campo_convenio" style="display:none;">
    <label>Convenio:</label>
    <select name="convenio" id="convenio">
        <option value="">Seleccione convenio</option>
        <option value="Policía">Policía</option>
        <option value="Municipalidad">Municipalidad</option>
        <option value="Empresa privada">Empresa privada</option>
    </select>
</div>

<div class="campo" id="campo_monto" style="display:none;">
    <label>Monto:</label>
    <input type="number" name="monto" id="monto" step="0.01" placeholder="Ingrese monto">
</div>

<div class="campo" id="campo_boleta" style="display:none;">
    <label>N° Boleta:</label>
    <input type="text" name="numero_boleta" id="numero_boleta" placeholder="Ingrese número de boleta">
</div>
        <div class="campo">
    <label>Servicio solicitante:</label>
    <select class="buscador" name="servicio_solicitante" required>
        <option value="">Seleccione o escriba...</option>

        <?php while($s = $servicios->fetch_assoc()) { ?>
            <option value="<?php echo $s['nombre']; ?>">
                <?php echo $s['nombre']; ?>
            </option>
        <?php } ?>
    </select>
</div>

        <div class="campo">
            <label>Médico turno:</label>
            <select class="buscador" name="medico_turno" required>
                <option value="">Seleccione o escriba...</option>
                <option>BETTY CABRERA BENAVIDES</option>
                <option>ELIEL MARAZA AQUINO</option>
                <option>FERNANDO DANIGNO AVALOS</option>
                <option>HUGO ALZAMORA SEBASTIAN</option>
                <option>JORGE LUIS MUÑANTE APARCANA</option>
                <option>LUIS NAVARRO VALDIVIESO</option>
                <option>MARITZA FLORES PAREDES</option>
                <option>SONIA VALENZUELA RAMIREZ</option>
                <option>VILA TOMAYQUISPE MANUEL</option>
            </select>
        </div>

        <div class="campo">
            <label>Tipo de atención:</label>
            <select class="buscador" name="tipo_atencion" required>
                <option value="">Seleccione o escriba...</option>
                <option>Particular</option>
                <option>Periférica</option>
                <option>Ambulatorio</option>
                <option>Emergencia</option>
                <option>Hospitalaria</option>
            </select>
        </div>

<div class="campo completo">
    <label>Examen solicitado:</label>
    <select class="buscador" name="examen_solicitado" required>
        <option value="">Seleccione o escriba...</option>

        <?php while($e = $examenes->fetch_assoc()) { ?>
            <option value="<?php echo $e['nombre']; ?>">
                <?php echo $e['nombre']; ?>
            </option>
        <?php } ?>
    </select>
</div>
        <div class="campo completo">
            <label>Diagnóstico:</label>
            <textarea name="diagnostico" placeholder="Ingrese el diagnóstico del paciente..."></textarea>
        </div>

        <div class="botones">
            <button type="submit">Registrar Ecografía</button>
        </div>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script>
    $(document).ready(function() {
    $('.buscador').select2({
        placeholder: "Seleccione o escriba...",
        allowClear: false,
        width: '100%'
    });
});

    flatpickr("#fecha", {
        locale: "es",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        maxDate: "today",
        allowInput: false,
        disableMobile: true
    });
</script>
<script>
document.getElementById("dni").addEventListener("input", function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- AQUÍ VA EL MENSAJE -->
<?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'ok'): ?>
<script>
window.onload = function(){
    Swal.fire({
        icon: 'success',
        title: '¡Guardado!',
        text: 'La ecografía se registró correctamente',
        confirmButtonColor: '#2563eb'
    });
}
</script>
<?php endif; ?>

<?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'error'): ?>
<script>
window.onload = function(){
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo guardar',
        confirmButtonColor: '#dc2626'
    });
}
</script>
<?php endif; ?>

<script>
function controlarCamposPago() {
    let condicion = $('[name="condicion"]').val();

    let campoMonto = document.getElementById("campo_monto");
    let campoBoleta = document.getElementById("campo_boleta");
    let campoConvenio = document.getElementById("campo_convenio");

    let monto = document.getElementById("monto");
    let boleta = document.getElementById("numero_boleta");

    campoMonto.style.display = "none";
    campoBoleta.style.display = "none";
    campoConvenio.style.display = "none";

    monto.required = false;
    boleta.required = false;

    if (condicion === "Particular") {
        campoMonto.style.display = "flex";
        monto.required = true;
    }

    if (
        condicion === "Contado" ||
        condicion === "Exonerado Parcial" ||
        condicion === "Pago parcial"
    ) {
        campoMonto.style.display = "flex";
        campoBoleta.style.display = "flex";
        monto.required = true;
        boleta.required = true;
    }

    if (condicion === "Convenios") {
        campoConvenio.style.display = "flex";
    }
}

$(document).ready(function() {
    $('[name="condicion"]').on('change', function() {
        controlarCamposPago();
    });

    controlarCamposPago();
});
</script>
</body>
</html>
