<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Sistema de Ecografías</title>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    min-height: 100vh;
    background: url('img/fondo_dashboard.jpg') center center / cover no-repeat fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px;
    position: relative;
}

body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(241, 245, 249, 0.78);
    z-index: -1;
}

.login-wrapper {
    width: 100%;
    max-width: 1080px;
    min-height: 620px;
    display: grid;
    grid-template-columns: 1.05fr .95fr;
    background: rgba(255,255,255,0.92);
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 24px 55px rgba(15,23,42,.22);
    border: 1px solid rgba(203,213,225,.55);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* LADO IZQUIERDO */
.login-left {
    background: linear-gradient(135deg, rgba(219,234,254,.95), rgba(191,219,254,.92));
    padding: 60px 55px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-left h1 {
    font-size: 52px;
    color: #1e3a8a;
    margin-bottom: 18px;
    font-weight: 800;
}

.login-left p {
    font-size: 18px;
    color: #334155;
    line-height: 1.6;
    max-width: 420px;
}

.logo-grande {
    margin-top: 38px;
    display: flex;
    justify-content: center;
}

.logo-grande img {
    width: 260px;
    max-width: 100%;
    background: white;
    padding: 18px;
    border-radius: 22px;
    box-shadow: 0 18px 32px rgba(15,23,42,.12);
}

/* LADO DERECHO */
.login-right {
    background: rgba(255,255,255,.92);
    padding: 58px 55px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.logo-pequeno {
    width: 82px;
    height: 82px;
    background: white;
    border-radius: 50%;
    margin: 0 auto 22px;
    box-shadow: 0 12px 28px rgba(15,23,42,.14);
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-pequeno img {
    width: 68%;
    height: 68%;
    object-fit: contain;
}

.login-right h2 {
    text-align: center;
    color: #1e3a8a;
    font-size: 32px;
    line-height: 1.2;
    margin-bottom: 10px;
    font-weight: 800;
}

.subtitulo {
    text-align: center;
    color: #475569;
    margin-bottom: 34px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.mensaje-error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 12px 14px;
    border-radius: 12px;
    margin-bottom: 18px;
    text-align: center;
    font-weight: bold;
    border: 1px solid #fecaca;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    font-weight: bold;
    color: #1e293b;
    font-size: 14px;
    margin-bottom: 8px;
}

input {
    width: 100%;
    padding: 15px 16px;
    border: 1px solid #cbd5e1;
    border-radius: 14px;
    font-size: 15px;
    outline: none;
    background: #f8fafc;
    transition: .25s;
}

input:focus {
    border-color: #2563eb;
    background: white;
    box-shadow: 0 0 0 3px rgba(37,99,235,.14);
}

.password-container {
    position: relative;
}

.password-container input {
    padding-right: 48px;
}

.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 17px;
    color: #64748b;
    user-select: none;
}

.btn-login {
    width: 100%;
    padding: 16px;
    background: linear-gradient(90deg, #38bdf8, #2563eb);
    color: white;
    border: none;
    border-radius: 15px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: .25s;
    box-shadow: 0 12px 24px rgba(37,99,235,.24);
    margin-top: 5px;
}

.btn-login:hover {
    transform: translateY(-2px);
    filter: brightness(1.03);
}

.footer-login {
    text-align: center;
    margin-top: 24px;
    color: #64748b;
    font-size: 13px;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .login-wrapper {
        grid-template-columns: 1fr;
    }

    .login-left,
    .login-right {
        padding: 40px 30px;
    }

    .login-left {
        text-align: center;
    }

    .login-left p {
        margin: auto;
    }

    .login-left h1 {
        font-size: 40px;
    }

    .logo-grande img {
        width: 210px;
    }

    .login-right h2 {
        font-size: 28px;
    }
}
</style>
</head>

<body>

<div class="login-wrapper">

    <div class="login-left">
        <h1>¡Bienvenido!</h1>
        <p>
            Ingrese sus datos para acceder al sistema de gestión de ecografías
            del Hospital San José de Chincha.
        </p>

        <div class="logo-grande">
            <img src="img/hospital.jpg" alt="Hospital San José de Chincha">
        </div>
    </div>

    <div class="login-right">

        <div class="logo-pequeno">
            <img src="img/hospital.jpg" alt="Hospital">
        </div>

        <h2>Sistema de Gestión de Ecografías</h2>
        <p class="subtitulo">Hospital San José de Chincha</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="mensaje-error">
                Usuario o contraseña incorrectos
            </div>
        <?php endif; ?>

        <form action="validar_login.php" method="POST" autocomplete="off">

            <div class="form-group">
                <label>Usuario</label>
                <input 
                    type="text" 
                    name="usuario" 
                    placeholder="Ingrese su usuario" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Contraseña</label>

                <div class="password-container">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="Ingrese su contraseña" 
                        required 
                        autocomplete="off"
                    >

                    <span class="toggle-password"
                        onmousedown="mostrarPassword()"
                        onmouseup="ocultarPassword()"
                        onmouseleave="ocultarPassword()"
                        ontouchstart="mostrarPassword()"
                        ontouchend="ocultarPassword()">
                        👁
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Ingresar al sistema
            </button>

        </form>

        <div class="footer-login">
            EsSalud · Sistema Institucional
        </div>

    </div>

</div>

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