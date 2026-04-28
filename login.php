<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema Radiología</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
                        url('img/hospital.1.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 400px;
            background: rgba(255, 255, 255, .96);
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,.35);
        }

        /* LOGO MEJORADO */
        .logo {
            width: 85px;
            height: 85px;
            background: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,.2);
        }

        .logo img {
            width: 65%;
        }

        h2 {
            text-align: center;
            color: #1e3a8a;
            margin: 0;
            font-size: 28px;
        }

        .subtitulo {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin: 8px 0 28px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #334155;
            margin-bottom: 7px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 15px;
            margin-bottom: 18px;
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
            background: #2563eb;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #1d4ed8;
            transform: scale(1.02);
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #64748b;
            margin-top: 22px;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <!-- LOGO -->
        <div class="logo">
            <img src="img/hospital.jpg" alt="EsSalud">
        </div>

        <h2>Sistema Web de Gestión de Ecografías</h2>
        <p class="subtitulo">Hospital San José de Chincha</p>

        <form action="validar_login.php" method="POST">
            <label>Usuario</label>
            <input type="text" name="usuario" placeholder="Ingrese su usuario" required>

            <label>Contraseña</label>
            <input type="password" name="password" placeholder="Ingrese su contraseña" required>

            <button type="submit">Ingresar al sistema</button>
        </form>

        <div class="footer">
            EsSalud - Sistema Institucional
        </div>
    </div>

</body>
</html>