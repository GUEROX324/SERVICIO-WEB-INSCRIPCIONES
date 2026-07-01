<?php

header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$idInscripcion = $_POST["idInscripcion"] ?? $_GET["idInscripcion"] ?? null;
$estado = $_POST["estado"] ?? $_GET["estado"] ?? null;

$estadosPermitidos = ["pendiente", "aceptada", "rechazada", "cancelada"];

if (!$idInscripcion || !$estado) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Faltan parámetros: idInscripcion y estado son obligatorios"
    ]);
    exit;
}

if (!is_numeric($idInscripcion)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "idInscripcion debe ser numérico"
    ]);
    exit;
}

if (!in_array($estado, $estadosPermitidos)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Estado no válido. Use: pendiente, aceptada, rechazada o cancelada"
    ]);
    exit;
}

$sql = "UPDATE inscripciones SET estado = ? WHERE idInscripcion = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("si", $estado, $idInscripcion);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "success" => true,
            "mensaje" => "Estado de inscripción actualizado correctamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "mensaje" => "No se encontró la inscripción o no hubo cambios"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "Error al actualizar la inscripción",
        "error" => $conexion->error
    ]);
}

$stmt->close();
$conexion->close();

?>