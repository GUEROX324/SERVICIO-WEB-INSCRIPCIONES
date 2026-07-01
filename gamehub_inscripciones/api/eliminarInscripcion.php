<?php

header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$idInscripcion = $_POST["idInscripcion"] ?? $_GET["idInscripcion"] ?? null;

if (!$idInscripcion || !is_numeric($idInscripcion)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El parámetro idInscripcion es obligatorio y debe ser numérico"
    ]);
    exit;
}

$sql = "DELETE FROM inscripciones WHERE idInscripcion = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idInscripcion);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "success" => true,
            "mensaje" => "Inscripción eliminada correctamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "mensaje" => "No se encontró la inscripción"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "Error al eliminar la inscripción",
        "error" => $conexion->error
    ]);
}

$stmt->close();
$conexion->close();

?>