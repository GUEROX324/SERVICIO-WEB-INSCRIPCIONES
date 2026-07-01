<?php

header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$idTorneo = $_GET["idTorneo"] ?? null;

if (!$idTorneo || !is_numeric($idTorneo)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El parámetro idTorneo es obligatorio y debe ser numérico"
    ]);
    exit;
}

$sql = "SELECT idInscripcion, idTorneo, idEquipo, fechaInscripcion, estado 
        FROM inscripciones 
        WHERE idTorneo = ?
        ORDER BY fechaInscripcion DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idTorneo);
$stmt->execute();

$resultado = $stmt->get_result();

$inscripciones = [];

while ($fila = $resultado->fetch_assoc()) {
    $inscripciones[] = $fila;
}

echo json_encode([
    "success" => true,
    "idTorneo" => intval($idTorneo),
    "inscripciones" => $inscripciones
]);

$stmt->close();
$conexion->close();

?>