<?php

header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$sql = "SELECT idInscripcion, idTorneo, idEquipo, fechaInscripcion, estado FROM inscripciones ORDER BY idInscripcion DESC";
$resultado = $conexion->query($sql);

$inscripciones = [];

while ($fila = $resultado->fetch_assoc()) {
    $inscripciones[] = $fila;
}

echo json_encode([
    "success" => true,
    "inscripciones" => $inscripciones
]);

$conexion->close();

?>