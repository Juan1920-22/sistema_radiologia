<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Sistema Radiología</title>

<style>
* {
    box-sizing: border-box;
}
.password-container {
    position: relative;
}

.password-container input {
    width: 100%;
    padding: 13px 45px 13px 14px;
}

.toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    color: #64748b;
}
body {
    margin: 0;
    font-family: Arial, sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #93c5fd, #dbeafe);
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-wrapper {
    width: 90%;
    max-width: 1150px;
    min-height: 620px;
    background: white;
    border-radius: 28px;
    overflow: hidden;
    display: flex;
    box-shadow: 0 25px 60px rgba(0,0,0,.18);
}

.login-left {
    width: 55%;
    background: linear-gradient(135deg, #e0f7fa, #bfdbfe);
    position: relative;
    padding: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-left h1 {
    font-size: 48px;
    color: #1e3a8a;
    margin: 0;
}

.login-left p {
    font-size: 18px;
    color: #475569;
    line-height: 1.5;
    margin-top: 20px;
}

.doctor-img {
    width: 72%;
    max-width: 360px;
    margin: 35px auto 0;
    display: block;
}

.login-right {
    width: 45%;
    padding: 70px 65px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.logo {
    width: 85px;
    height: 85px;
    background: white;
    border-radius: 50%;
    margin: 0 auto 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo img {
    width: 70%;
}

h2 {
    text-align: center;
    color: #1e3a8a;
    font-size: 28px;
    margin: 0;
}

.subtitulo {
    text-align: center;
    color: #475569;
    margin: 10px 0 35px;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase !important; /* 🔥 FORZADO */
}

label {
    font-weight: bold;
    color: #334155;
    font-size: 14px;
    margin-bottom: 8px;
    display: block;
}

input {
    width: 100%;
    padding: 14px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    font-size: 15px;
    margin-bottom: 20px;
    outline: none;
    background: #f8fafc;
}

input:focus {
    border-color: #2563eb;
    background: white;
    box-shadow: 0 0 0 3px rgba(37,99,235,.15);
}

button {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #38bdf8, #2563eb);
    color: white;
    border: none;
    border-radius: 14px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    opacity: .9;
}

.footer {
    text-align: center;
    margin-top: 25px;
    color: #64748b;
    font-size: 13px;
}

@media (max-width: 900px) {
    .login-wrapper {
        flex-direction: column;
    }

    .login-left, .login-right {
        width: 100%;
    }

    .login-left {
        padding: 40px;
    }

    .doctor-img {
        width: 55%;
    }
}
</style>
</head>

<body>

<div class="login-wrapper">

    <div class="login-left">
        <h1>¡Bienvenido!</h1>
        <p>Ingrese sus datos para acceder al sistema de gestión de ecografías.</p>

        <img class="doctor-img" src="img/hospital.jpg" alt="Doctor">
    </div>

    <div class="login-right">

        <div class="logo">
            <img src="img/hospital.jpg" alt="Hospital">
        </div>

        <h2>Sistema Web de Gestión de Ecografías</h2>
        <p class="subtitulo">HOSPITAL SAN JOSÉ DE CHINCHA</p>
        <?php if (isset($_GET['error'])): ?>
    <div style="
        background: #fee2e2;
        color: #b91c1c;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        text-align: center;
        font-weight: bold;">
         Usuario o contraseña incorrectos
    </div>
<?php endif; ?>

        <form action="validar_login.php" method="POST" autocomplete="off">
            <label>Usuario</label>
            <input type="text" name="usuario" placeholder="Ingrese su usuario" required>

            <label>Contraseña</label>

<div class="password-container">
    <input type="password" name="password" id="password" 
           placeholder="Ingrese su contraseña" required autocomplete="off">

    <span class="toggle-password"
      onmousedown="mostrarPassword()"
      onmouseup="ocultarPassword()"
      onmouseleave="ocultarPassword()"
      ontouchstart="mostrarPassword()"
      ontouchend="ocultarPassword()">
    👁
</span>
</div>

<button type="submit">Ingresar al sistema</button>
        </form>

        <div class="footer">
            EsSalud - Sistema Institucional
        </div>

    </div>

</div>
<script>
function togglePassword() {
    const input = document.getElementById("password");

    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>
<script>
function mostrarPassword() {
    document.getElementById("password").type = "text";
}

function ocultarPassword() {
    document.getElementById("password").type = "password";
}
</script>
</body>
</html>