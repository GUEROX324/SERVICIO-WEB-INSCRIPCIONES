<?php

header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$idEquipo = $_GET["idEquipo"] ?? null;

if (!$idEquipo || !is_numeric($idEquipo)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El parámetro idEquipo es obligatorio y debe ser numérico"
    ]);
    exit;
}

$sql = "SELECT idInscripcion, idTorneo, idEquipo, fechaInscripcion, estado 
        FROM inscripciones 
        WHERE idEquipo = ?
        ORDER BY fechaInscripcion DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idEquipo);
$stmt->execute();

$resultado = $stmt->get_result();

$inscripciones = [];

while ($fila = $resultado->fetch_assoc()) {
    $inscripciones[] = $fila;
}

echo json_encode([
    "success" => true,
    "idEquipo" => intval($idEquipo),
    "inscripciones" => $inscripciones
]);

$stmt->close();
$conexion->close();

?>