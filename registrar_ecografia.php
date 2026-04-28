<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Ecografía</title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
    </style>
</head>

<body>

<div class="header">
    <h1>Hospital San José de Chincha</h1>
    <p>Sistema Web de Gestión de Ecografías</p>
</div>

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
            <label>Fecha:</label>
            <input type="date" name="fecha" required>
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
                <option value="Asegurado">Asegurado</option>
                <option value="No asegurado">No asegurado</option>
                <option value="Referido">Referido</option>
                <option value="Particular">Particular</option>
            </select>
        </div>

        <div class="campo">
            <label>Servicio solicitante:</label>
            <select class="buscador" name="servicio_solicitante" required>
                <option value="">Seleccione o escriba...</option>
                <option>Cardiología</option>
                <option>Cirugía</option>
                <option>Consultorio externo</option>
                <option>COVID-19</option>
                <option>Dengue</option>
                <option>Emergencia</option>
                <option>Endocrinología</option>
                <option>Gastroenterología</option>
                <option>Ginecología</option>
                <option>Infectología</option>
                <option>Medicina</option>
                <option>Medicina física y rehabilitación</option>
                <option>Nefrología</option>
                <option>Neonatología</option>
                <option>Neumología</option>
                <option>Neurología</option>
                <option>Obstetricia</option>
                <option>Oncología</option>
                <option>Otorrinolaringología</option>
                <option>Pediatría</option>
                <option>Psiquiatría</option>
                <option>Reumatología</option>
                <option>Shock trauma</option>
                <option>Traumatología</option>
                <option>UCIN</option>
                <option>Urología</option>
                <option>UVI</option>
                <option>UVICLIN</option>
                <option>Vésico prostático</option>
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
                <option>Abdominal superior</option>
                <option>Retroperitoneal</option>
                <option>Pélvica</option>
                <option>Transvaginal</option>
                <option>Obstetricia</option>
                <option>Renal y vejiga</option>
                <option>Vejiga y próstata</option>
                <option>Transrectal</option>
                <option>De pulmones</option>
                <option>Ecografía de partes blandas</option>
                <option>Transfontanelar</option>
                <option>Tejidos blandos de cuero cabelludo</option>
                <option>Tejidos blandos de cuello</option>
                <option>De tiroides</option>
                <option>De mamas</option>
                <option>Tejidos blandos de tórax</option>
                <option>Tejidos blandos de abdomen</option>
                <option>Tejidos blandos de pelvis</option>
                <option>Testicular</option>
                <option>Histerosonografía</option>
                <option>De hernia umbilical</option>
                <option>De hernia inguinal</option>
                <option>De hernia inguinal bilateral</option>
                <option>Eventración</option>
                <option>Partes blandas tumoraciones-colecciones</option>
                <option>Doppler carotídeo</option>
                <option>Doppler ABC. SUP</option>
                <option>Doppler renal</option>
                <option>Doppler prostático</option>
                <option>Doppler testicular</option>
                <option>Doppler ginecología</option>
                <option>Doppler obstétrico</option>
                <option>Doppler ART M.SUP</option>
                <option>Doppler ART. M. SUP. BILATERAL</option>
                <option>Doppler ART. M. INF</option>
                <option>Doppler ART. M. INF. BILATERAL</option>
                <option>Doppler venoso M.SUP</option>
                <option>Doppler venoso M. SUP. BILATERAL</option>
                <option>Doppler venoso M. INF</option>
                <option>Doppler venoso M. INF. BILATERAL</option>
            </select>
        </div>

        <div class="campo completo">
            <label>Diagnóstico:</label>
            <textarea name="diagnostico" placeholder="Ingrese el diagnóstico del paciente..."></textarea>
        </div>

        <div class="botones">
            <a class="salir" href="menu.php">Salir</a>
            <button type="submit">Registrar Ecografía</button>
        </div>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.buscador').select2({
            placeholder: "Seleccione o escriba...",
            allowClear: true,
            width: '100%'
        });
    });
</script>

</body>
</html>