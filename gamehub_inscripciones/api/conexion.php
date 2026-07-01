<?php

$servidor = "localhost";
$usuario = "root";
$password = "";
$baseDatos = "gamehub_inscripciones";

$conexion = new mysqli($servidor, $usuario, $password, $baseDatos);

if ($conexion->connect_error) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Error de conexión a la base de datos",
        "error" => $conexion->connect_error
    ]);
    exit;
}

$conexion->set_charset("utf8");

?>