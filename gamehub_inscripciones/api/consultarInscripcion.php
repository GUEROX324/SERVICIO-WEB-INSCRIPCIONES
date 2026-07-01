<?php

header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$idInscripcion = $_GET["idInscripcion"] ?? null;

if (!$idInscripcion || !is_numeric($idInscripcion)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El parámetro idInscripcion es obligatorio y debe ser numérico"
    ]);
    exit;
}

$sql = "SELECT idInscripcion, idTorneo, idEquipo, fechaInscripcion, estado 
        FROM inscripciones 
        WHERE idInscripcion = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idInscripcion);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo json_encode([
        "success" => true,
        "inscripcion" => $resultado->fetch_assoc()
    ]);
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "No se encontró la inscripción"
    ]);
}

$stmt->close();
$conexion->close();

?>