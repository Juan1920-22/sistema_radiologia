<h2>Registrar Paciente</h2>

<form action="guardar_paciente.php" method="POST">
    DNI: <input type="text" name="dni" required><br><br>
    Nombres: <input type="text" name="nombres" required><br><br>
    Apellidos: <input type="text" name="apellidos" required><br><br>
    Edad: <input type="number" name="edad"><br><br>

    <button type="submit">Guardar paciente</button>
</form>