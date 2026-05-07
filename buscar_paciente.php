<?php

include("conexion.php");

if(isset($_POST['dni'])){

    $dni = $_POST['dni'];

    $stmt = $conexion->prepare("
        SELECT historia_clinica,
               apellidos,
               nombres,
               sexo
        FROM ecografias
        WHERE dni = ?
        ORDER BY id_ecografia DESC
        LIMIT 1
    ");

    $stmt->bind_param("s", $dni);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if($fila = $resultado->fetch_assoc()){

        echo json_encode([
            "success" => true,
            "historia_clinica" => $fila['historia_clinica'],
            "apellidos" => $fila['apellidos'],
            "nombres" => $fila['nombres'],
            "sexo" => $fila['sexo']
        ]);

    }else{

        echo json_encode([
            "success" => false
        ]);
    }
}
?>