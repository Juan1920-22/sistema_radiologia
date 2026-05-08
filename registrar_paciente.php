<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar paciente</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:linear-gradient(135deg,#172b4d,#1e3a8a);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px;
}

.contenedor{
    width:100%;
    max-width:650px;
    background:white;
    border-radius:24px;
    padding:35px;
    box-shadow:0 15px 40px rgba(0,0,0,.25);
}

h2{
    text-align:center;
    color:#1e3a8a;
    margin-bottom:30px;
    font-size:32px;
}

.formulario{
    display:grid;
    gap:18px;
}

.campo label{
    display:block;
    margin-bottom:7px;
    font-weight:bold;
    color:#334155;
}

input{
    width:100%;
    padding:14px;
    border:1px solid #cbd5e1;
    border-radius:12px;
    font-size:15px;
    outline:none;
    transition:.25s;
}

input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,.15);
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:15px;
    border-radius:14px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:.25s;
}

button:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.volver{
    display:inline-block;
    margin-bottom:20px;
    text-decoration:none;
    background:#64748b;
    color:white;
    padding:10px 16px;
    border-radius:10px;
    font-weight:bold;
}

.volver:hover{
    background:#475569;
}

</style>
</head>

<body>

<div class="contenedor">

<a href="menu.php" class="volver">← Volver al menú</a>

<h2>Registrar Paciente</h2>

<form action="guardar_paciente.php" method="POST" class="formulario">

    <div class="campo">
        <label>DNI</label>
        <input type="text" name="dni" maxlength="8" required>
    </div>

    <div class="campo">
        <label>Nombres</label>
        <input type="text" name="nombres" required>
    </div>

    <div class="campo">
        <label>Apellidos</label>
        <input type="text" name="apellidos" required>
    </div>

    <div class="campo">
        <label>Edad</label>
        <input type="number" name="edad">
    </div>

    <button type="submit">
        Guardar paciente
    </button>

</form>

</div>

</body>
</html>