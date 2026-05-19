<?php
include("conexion.php");

$condiciones = $conexion->query("SELECT id, nombre FROM mantenimiento WHERE tipo='Condición de pago' AND estado=1 ORDER BY nombre ASC");

$servicios = $conexion->query("SELECT id, nombre FROM mantenimiento WHERE tipo='Servicio' AND estado=1 ORDER BY nombre ASC");

$examenes = $conexion->query("SELECT id, nombre FROM mantenimiento WHERE tipo='Examen' AND estado=1 ORDER BY nombre ASC");
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

        .select2-results__options {
    max-height: 120px !important;
    overflow-y: auto !important;
}

.select2-dropdown {
    z-index: 99999 !important;
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


.select2-dropdown {
    max-height: 230px !important;
    overflow-y: auto !important;
}

.select2-results__options {
    max-height: 160px !important;
    overflow-y: auto !important;
}

/* Tamaño controlado del desplegable */
.select2-results__options {
    max-height: 145px !important;
    overflow-y: auto !important;
}

/* Evita que el dropdown se vea demasiado grande */
.select2-dropdown {
    z-index: 99999 !important;
}
/* ESPACIO EXTRA PARA CUANDO APARECEN CAMPOS DINÁMICOS */

.select2-results__options {
    max-height: 150px !important;
    overflow-y: auto !important;
}
/* ===== CORRECCIÓN SELECT2 ===== */

.select2-dropdown {
    z-index: 999999 !important;
    border-radius: 13px;
    border: 1px solid #cbd5e1;
    box-shadow: 0 12px 26px rgba(15,23,42,.16);
}

.select2-results__options {
    max-height: 180px !important;
    overflow-y: auto !important;
}

.select2-container--open {
    z-index: 999999 !important;
}

.select2-selection__clear {
    display: none !important;
}

.select2-container--default .select2-selection--single button {
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
    border: none !important;
}
.select2-container--open .select2-dropdown--below {
    margin-top: 4px !important;
}

.select2-container--open .select2-dropdown--above {
    margin-top: 0 !important;
    margin-bottom: 4px !important;
}
.contenedor {
    padding-bottom: 260px !important;
}

.select2-dropdown {
    z-index: 999999 !important;
}

.select2-results__options {
    max-height: 170px !important;
    overflow-y: auto !important;
}
html {
    scroll-behavior: smooth;
}
body {
    padding-bottom: 350px;
}

.select2-dropdown {
    z-index: 999999 !important;
}
.btn-limpiar {
    background: #e2e8f0 !important;
    color: #1e293b !important;
    box-shadow: none !important;
}

.btn-limpiar:hover {
    background: #cbd5e1 !important;
}
/* Select2 múltiple optimizado */
.select2-container--default .select2-selection--multiple {
    min-height: 35px;       /* altura mínima */
    max-height: 60px;       /* evita que crezca demasiado */
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 4px 8px;
    background: #f8fafc;
    overflow-y: auto;       /* scroll si hay más tags */
    display: flex;
    flex-wrap: wrap;        /* tags en horizontal, luego baja si no caben */
    gap: 4px;               /* separación entre tags */
    align-items: flex-start;   /* fuerza que los tags estén arriba */
height: auto;              /* permite que el contenedor se ajuste automáticamente */
}


/* Tags de los elementos seleccionados */
/* Tags seleccionados */
/* Tags seleccionados */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    padding: 3px 8px;
    font-size: 13px;
    border-radius: 6px;
    background-color: #2563eb;
    color: white;
      margin-top: 2px;      /* reduce espacio extra arriba */
    margin-bottom: 0;     /* elimina espacio extra debajo */
}

/* X para eliminar tag */
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white;
    margin-right: 4px;
    font-weight: bold;
}

/* Zona donde se muestran los tags */
.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: flex;
    flex-wrap: wrap;        /* horizontal */
    align-items: center;
    gap: 4px;
    padding: 2px;
    min-height: 32px;
    font-size: 14px;
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
        <a href="reportes.php">Reportes</a>
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
            <input type="text"name="historia_clinica"id="historia_clinica" required>
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
    <input type="text" name="sexo" id="sexo" required placeholder="M = Masculino / F = Femenino">
</div>

        <div class="campo">
            <label>Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required>
        </div>

        <div class="campo">
            <label>Nombres:</label>
            <input type="text" name="nombres" id="nombres" required>
        </div>

        <div class="campo">
    <label>Condición:</label>
    <select class="buscador" name="condicion" required>
        <option value="">Seleccione o escriba...</option>

        <?php while($c = $condiciones->fetch_assoc()) { ?>
            <option value="<?php echo $c['id']; ?>" data-nombre="<?php echo $c['nombre']; ?>">
                <?php echo $c['nombre']; ?>
            </option>
        <?php } ?>
    </select>
</div>
<!-- 👇 CONVENIO -->
<div class="campo" id="campo_convenio" style="display:none;">
    <label>Convenio:</label>
    <select name="convenio" id="convenio" class="buscador">
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
            <option value="<?php echo $s['id']; ?>">
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
    <label>Exámenes solicitados (máx. 3):</label>
    <select class="buscador" id="examenes-multiple" name="examenes_solicitados[]" multiple="multiple" required>
        <?php 
        $examenes->data_seek(0);
        while($e = $examenes->fetch_assoc()) { ?>
            <option value="<?= $e['id'] ?>"><?= $e['nombre'] ?></option>
        <?php } ?>
    </select>
    <p style="font-size:12px; color:#64748b;">Seleccione hasta 3 exámenes</p>
</div>
        <div class="campo completo">
            <label>Diagnóstico:</label>
            <textarea name="diagnostico" placeholder="Ingrese el diagnóstico del paciente..."></textarea>
        </div>

<div class="campo">
    <label>Hora (opcional):</label>
    <input type="time" name="hora_examen" id="hora_examen" placeholder="HH:MM">
</div>
<div class="botones">
<button type="button" id="btnLimpiar" class="btn-limpiar">
        Limpiar
       </button>

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
    width: '100%',
    dropdownParent: $('body')
});
// Limitar selección a 3 exámenes
$('#examenes-multiple').select2({
    placeholder: "Seleccione hasta 3 exámenes",
    maximumSelectionLength: 3, // Limita a 3
    width: '100%',
    allowClear: true
});

// Mensaje si se alcanza el máximo
$('#examenes-multiple').on('select2:select', function(e) {
    if ($(this).select2('data').length > 3) {
        Swal.fire({
            icon: 'warning',
            title: 'Máximo alcanzado',
            text: 'Solo puede seleccionar hasta 3 exámenes.',
            confirmButtonColor: "#2563eb"
        });
    }
});
$('#convenio').select2('destroy').select2({
    placeholder: "Seleccione o escriba...",
    allowClear: false,
    width: '100%',
    dropdownParent: $('body'),
    tags: true,
    createTag: function(params) {
        let term = $.trim(params.term);

        if (term === '') {
            return null;
        }

        return {
            id: term,
            text: term,
            newTag: true
        };
    }
});
});

    const calendarioFecha = flatpickr("#fecha", {
    locale: "es",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d/m/Y",
    maxDate: "today",
    allowInput: true,
    disableMobile: true,

    onReady: function(selectedDates, dateStr, instance) {

    instance.altInput.addEventListener("keydown", function(e) {

        if (e.key === "Enter") {

            e.preventDefault();

            instance.close();

            // 🔥 IR AL SIGUIENTE CAMPO VACÍO

            setTimeout(() => {

                const form = document.querySelector("form");

                const camposVacios = Array.from(
                    form.querySelectorAll(
                        'input[required], select[required], textarea[required]'
                    )
                ).filter(campo => {

                    const contenedor = campo.closest(".campo");

                    if (contenedor && contenedor.style.display === "none") {
                        return false;
                    }

                    return !campo.value || campo.value.trim() === "";

                });

                if (camposVacios.length > 0) {

                    const siguiente = camposVacios[0];

                    if ($(siguiente).hasClass("buscador")) {

                        $(siguiente).select2("open");

                    } else {

                        siguiente.focus();
                    }
                }

            }, 100);
        }
    });
},

onChange: function(selectedDates, dateStr, instance) {

    instance.close();

    setTimeout(() => {

        const form = document.querySelector("form");

        const camposVacios = Array.from(
            form.querySelectorAll(
                'input[required], select[required], textarea[required]'
            )
        ).filter(campo => {

            const contenedor = campo.closest(".campo");

            if (contenedor && contenedor.style.display === "none") {
                return false;
            }

            return !campo.value || campo.value.trim() === "";

        });

        if (camposVacios.length > 0) {

            const siguiente = camposVacios[0];

            if ($(siguiente).hasClass("buscador")) {

                $(siguiente).select2("open");

            } else {

                siguiente.focus();
            }
        }

    }, 100);
}
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
    let condicion = $('[name="condicion"] option:selected').data('nombre');

    let campoMonto = document.getElementById("campo_monto");
    let campoBoleta = document.getElementById("campo_boleta");
    let campoConvenio = document.getElementById("campo_convenio");

    let monto = document.getElementById("monto");
    let boleta = document.getElementById("numero_boleta");
    let convenio = document.getElementById("convenio");

    campoMonto.style.display = "none";
    campoBoleta.style.display = "none";
    campoConvenio.style.display = "none";

    monto.required = false;
    boleta.required = false;
    convenio.required = false;

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
        convenio.required = true;
    }
}
$(document).ready(function() {
    $('[name="condicion"]').on('change', function() {
        controlarCamposPago();
    });

    controlarCamposPago();
});
</script>
<script>
function enfocarSiguienteCampo(actual) {
    const form = document.querySelector("form");

    const campos = Array.from(form.querySelectorAll(
        'input:not([type="hidden"]), select, textarea, button[type="submit"]'
    )).filter(campo => {
        const contenedor = campo.closest(".campo");
        return !campo.disabled && (!contenedor || contenedor.style.display !== "none");
    });

    const index = campos.indexOf(actual);

    if (index >= 0 && index < campos.length - 1) {
        const siguiente = campos[index + 1];

        if ($(siguiente).hasClass("buscador")) {
    $(siguiente).select2("open");
}
    }
}

/* ENTER: va solo a campos vacíos; si no hay vacíos, registra */
document.querySelector("form").addEventListener("keydown", function(e) {
    if (
    e.key === "Enter" &&
    !document.querySelector(".flatpickr-calendar.open") &&
    e.target.id !== "sexo" &&
    e.target.id !== "dni"
    && !$(e.target).hasClass("select2-search__field")
) {
        if (e.target.tagName === "TEXTAREA") {
    return;
}

        if (e.target.type === "submit") {
            return;
        }

        e.preventDefault();

        const form = document.querySelector("form");

        const camposVacios = Array.from(form.querySelectorAll(
            'input[required], select[required], textarea[required]'
        )).filter(campo => {
            const contenedor = campo.closest(".campo");

            if (contenedor && contenedor.style.display === "none") {
                return false;
            }

            return !campo.value || campo.value.trim() === "";
        });

        if (camposVacios.length > 0) {
    let actual = e.target;
    let posicionActual = Array.from(form.elements).indexOf(actual);

    let siguienteVacio = camposVacios.find(campo => {
        return Array.from(form.elements).indexOf(campo) > posicionActual;
    });

    if (!siguienteVacio) {
        siguienteVacio = camposVacios[0];
    }

    if (siguienteVacio.id === "fecha") {
    calendarioFecha.open();

    setTimeout(() => {
        const inputFecha = document.querySelector("#fecha");

        inputFecha.addEventListener("keydown", function handler(ev) {
            if (ev.key === "Enter") {
                ev.preventDefault();
                calendarioFecha.close();
                document.getElementById("sexo").focus();
                inputFecha.removeEventListener("keydown", handler);
            }
        });
    }, 100);
}
    else if ($(siguienteVacio).hasClass("buscador")) {
        $(siguienteVacio).select2("open");
    } else {
        siguienteVacio.focus();
    }
} else {
    document.querySelector('button[type="submit"]').focus();
}
    }
});
const sexoInput = document.getElementById("sexo");

sexoInput.addEventListener("keydown", function(e) {
    if (e.key === "Backspace" || e.key === "Delete") {
        this.dataset.borrando = "1";
    } else {
        this.dataset.borrando = "0";
    }

    if (e.key === "Enter") {
        e.preventDefault();

        let valor = this.value.trim().toLowerCase();

        if (valor === "m" || valor === "masculino") {
            this.value = "Masculino";
        } else if (valor === "f" || valor === "femenino") {
            this.value = "femenino";
        }

        // Ahora va al siguiente campo vacío, no borra el autocompletado
        const form = document.querySelector("form");

        const camposVacios = Array.from(form.querySelectorAll(
            'input[required], select[required], textarea[required]'
        )).filter(campo => {
            const contenedor = campo.closest(".campo");
            if (contenedor && contenedor.style.display === "none") return false;
            return !campo.value || campo.value.trim() === "";
        });

        let siguienteVacio = camposVacios.find(campo => campo !== this);

        if (siguienteVacio) {
            if ($(siguienteVacio).hasClass("buscador")) {
                $(siguienteVacio).select2("open");
            } else {
                siguienteVacio.focus();
            }
        } else {
            document.querySelector('button[type="submit"]').focus();
        }
    }
});

sexoInput.addEventListener("input", function() {
    if (this.dataset.borrando === "1") {
        return;
    }

    let valor = this.value.trim().toLowerCase();

    if (valor === "m") {
        this.value = "Masculino";
    }

    if (valor === "f") {
        this.value = "Femenino";
    }
});

/* Validación visual antes de registrar */
document.querySelector("form").addEventListener("submit", function(e) {
    let faltan = [];

    this.querySelectorAll("[required]").forEach(campo => {
        const contenedor = campo.closest(".campo");

        if (contenedor && contenedor.style.display === "none") {
            return;
        }

        let label = contenedor ? contenedor.querySelector("label") : null;
        let nombreCampo = label ? label.innerText.replace(":", "") : campo.name;

        if (!campo.value || campo.value.trim() === "") {
            faltan.push({
                campo: campo,
                nombre: nombreCampo
            });

            campo.style.border = "2px solid #dc2626";

            if ($(campo).hasClass("buscador")) {
                $(campo).next(".select2-container").find(".select2-selection").css({
                    "border": "2px solid #dc2626"
                });
            }
        } else {
            campo.style.border = "";

            if ($(campo).hasClass("buscador")) {
                $(campo).next(".select2-container").find(".select2-selection").css({
                    "border": ""
                });
            }
        }
    });

    if (faltan.length > 0) {
        e.preventDefault();

        let mensaje = "";

        if (faltan.length === 1) {
            mensaje = "Falta completar el campo: " + faltan[0].nombre;
        } else {
            mensaje = "Faltan completar estos campos: " + faltan.map(item => item.nombre).join(", ");
        }

        Swal.fire({
            icon: "warning",
            title: "Faltan datos",
            text: mensaje,
            confirmButtonColor: "#2563eb"
        }).then(() => {
            let primerCampo = faltan[0].campo;

            if ($(primerCampo).hasClass("buscador")) {
                $(primerCampo).select2("open");
            } else {
                primerCampo.focus();
            }
        });

        return false;
    }

    return true;
});
</script>
<script>
$(document).on('select2:open', function() {
    setTimeout(function() {
        const buscador = document.querySelector('.select2-container--open .select2-search__field');
        if (buscador) {
            buscador.focus();
            buscador.select();
        }
    }, 80);
});

$('.buscador').on('select2:select', function() {
    setTimeout(() => {
        const form = document.querySelector("form");
        const campos = Array.from(form.querySelectorAll(
            'input:not([type="hidden"]), select, textarea'
        )).filter(campo => {
            const contenedor = campo.closest(".campo");
            return !campo.disabled && (!contenedor || contenedor.style.display !== "none");
        });

        const index = campos.indexOf(this);
        const siguiente = campos[index + 1];

        if (siguiente) {
            if ($(siguiente).hasClass("buscador")) {
                $(siguiente).next(".select2-container").find(".select2-selection").focus();
            } else {
                siguiente.focus();
            }
        }
    }, 100);
});
/* Moverse entre casillas con flechas del teclado */
document.addEventListener("keydown", function(e) {
    if (!["ArrowRight", "ArrowLeft", "ArrowUp", "ArrowDown"].includes(e.key)) return;

    if (
        document.querySelector(".select2-container--open") ||
        document.querySelector(".flatpickr-calendar.open")
    ) {
        return;
    }

    e.preventDefault();

    const form = document.querySelector("form");

    const campos = Array.from(form.querySelectorAll(
        'input:not([type="hidden"]), select, textarea'
    )).filter(campo => {
        const contenedor = campo.closest(".campo");
        return !campo.disabled && (!contenedor || contenedor.style.display !== "none");
    });

    let actual = document.activeElement;

    if (actual.classList.contains("select2-selection")) {
        actual = $(actual).closest(".select2-container").prev("select")[0];
    }

    let index = campos.indexOf(actual);
    if (index === -1) return;

    let columnas = 3;
    let nuevoIndex = index;

    if (e.key === "ArrowRight") nuevoIndex = index + 1;
    if (e.key === "ArrowLeft") nuevoIndex = index - 1;
    if (e.key === "ArrowDown") nuevoIndex = index + columnas;
    if (e.key === "ArrowUp") nuevoIndex = index - columnas;

    if (nuevoIndex < 0 || nuevoIndex >= campos.length) return;

    const siguiente = campos[nuevoIndex];

    if ($(siguiente).hasClass("buscador")) {
        $(siguiente).next(".select2-container").find(".select2-selection").focus();
    } else {
        siguiente.focus();
    }
});
/* Diagnóstico: permite usar Enter para saltos de línea.
   Opcional: Ctrl + Enter manda el foco al botón Registrar. */
document.querySelector('textarea[name="diagnostico"]').addEventListener("keydown", function(e) {
    if (e.key === "Enter" && e.ctrlKey) {
        e.preventDefault();
        document.querySelector('button[type="submit"]').focus();
    }
});
document.getElementById("btnLimpiar").addEventListener("click", function() {
    document.querySelector("form").reset();

    $('.buscador').val('').trigger('change');

    calendarioFecha.clear();

    document.getElementById("campo_monto").style.display = "none";
    document.getElementById("campo_boleta").style.display = "none";
    document.getElementById("campo_convenio").style.display = "none";

    document.getElementById("monto").required = false;
    document.getElementById("numero_boleta").required = false;
    document.getElementById("convenio").required = false;

    document.querySelectorAll("input, textarea, select").forEach(campo => {
        campo.style.border = "";
    });

    $('.select2-selection').css("border", "");

    document.querySelector('[name="historia_clinica"]').focus();
});
</script>
<script>

document.getElementById("dni").addEventListener("keyup", function(){

    let dni = this.value;

    if(dni.length >= 8){

        fetch("buscar_paciente.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },

            body: "dni=" + dni

        })

        .then(response => response.json())

        .then(data => {

            if(data.success){

                document.getElementById("historia_clinica").value =
                data.historia_clinica;

                document.getElementById("apellidos").value =
                data.apellidos;

                document.getElementById("nombres").value =
                data.nombres;

                document.getElementById("sexo").value =
                data.sexo;

                // 🔥 IR AL SIGUIENTE CAMPO VACÍO

                setTimeout(() => {

                    const form = document.querySelector("form");

                    const camposVacios = Array.from(
                        form.querySelectorAll(
                            'input[required], select[required], textarea[required]'
                        )
                    ).filter(campo => {

                        const contenedor = campo.closest(".campo");

                        if (contenedor && contenedor.style.display === "none") {
                            return false;
                        }

                        return !campo.value || campo.value.trim() === "";

                    });

                    if (camposVacios.length > 0) {

                        const siguiente = camposVacios[0];

                        if ($(siguiente).hasClass("buscador")) {

                            $(siguiente).select2("open");

                        } else {

                            siguiente.focus();
                        }
                    }

                }, 200);
            }
        });
    }

});

</script>
</body>
</html>
