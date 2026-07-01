<?php

header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";
include "configServicios.php";

$idTorneo = $_GET["idTorneo"] ?? null;
$idEquipo = $_GET["idEquipo"] ?? null;

if (!$idTorneo || !$idEquipo) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Faltan parámetros: idTorneo e idEquipo son obligatorios"
    ]);
    exit;
}

if (!is_numeric($idTorneo) || !is_numeric($idEquipo)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "idTorneo e idEquipo deben ser valores numéricos"
    ]);
    exit;
}

// Contexto para evitar la advertencia de ngrok al consumir otros servicios
$contextoNgrok = stream_context_create([
    "http" => [
        "method" => "GET",
        "header" => "ngrok-skip-browser-warning: true\r\n" .
                    "User-Agent: PHP\r\n"
    ]
]);

// --------------------------------------------------
// VALIDAR EQUIPO
// --------------------------------------------------

$urlEquipo = $URL_EQUIPOS . "consultarEquipo.php?idEquipo=" . $idEquipo;

$respuestaEquipo = @file_get_contents($urlEquipo, false, $contextoNgrok);

if ($respuestaEquipo === false) {
    echo json_encode([
        "success" => false,
        "mensaje" => "No se pudo conectar con el servicio de equipos",
        "url_consultada" => $urlEquipo
    ]);
    exit;
}

$datosEquipo = json_decode($respuestaEquipo, true);

if (!$datosEquipo) {
    echo json_encode([
        "success" => false,
        "mensaje" => "La respuesta del servicio de equipos no es JSON válido",
        "url_consultada" => $urlEquipo,
        "respuesta_recibida" => $respuestaEquipo
    ]);
    exit;
}

if (
    !isset($datosEquipo["success"]) ||
    $datosEquipo["success"] != true ||
    !isset($datosEquipo["equipo"])
) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El equipo no existe o la respuesta del servicio de equipos no es válida",
        "url_consultada" => $urlEquipo,
        "respuesta_recibida" => $datosEquipo
    ]);
    exit;
}

$equipo = $datosEquipo["equipo"];

// Validar que el equipo esté activo
$estadoEquipo = strtolower(trim($equipo["estado"] ?? ""));

if ($estadoEquipo !== "activo") {
    echo json_encode([
        "success" => false,
        "mensaje" => "No se puede registrar la inscripción porque el equipo no está activo",
        "estadoActualEquipo" => $equipo["estado"] ?? null,
        "equipo" => $equipo
    ]);
    exit;
}

// --------------------------------------------------
// VALIDAR TORNEO
// --------------------------------------------------

$urlTorneo = $URL_TORNEOS . "consultarTorneo.php?id=" . $idTorneo;

$respuestaTorneo = @file_get_contents($urlTorneo, false, $contextoNgrok);

if ($respuestaTorneo === false) {
    echo json_encode([
        "success" => false,
        "mensaje" => "No se pudo conectar con el servicio de torneos",
        "url_consultada" => $urlTorneo
    ]);
    exit;
}

$datosTorneo = json_decode($respuestaTorneo, true);

if (!$datosTorneo) {
    echo json_encode([
        "success" => false,
        "mensaje" => "La respuesta del servicio de torneos no es JSON válido",
        "url_consultada" => $urlTorneo,
        "respuesta_recibida" => $respuestaTorneo
    ]);
    exit;
}

if (
    !isset($datosTorneo["estado"]) ||
    strtolower($datosTorneo["estado"]) !== "ok" ||
    !isset($datosTorneo["datos"])
) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El torneo no existe o la respuesta del servicio de torneos no es válida",
        "url_consultada" => $urlTorneo,
        "respuesta_recibida" => $datosTorneo
    ]);
    exit;
}

$torneo = $datosTorneo["datos"];

// Estados posibles del torneo:
// Proximo   -> No permite inscripción todavía
// Abierto   -> Sí permite inscripción
// En curso  -> Ya inició, no permite nuevas inscripciones
// Finalizado -> Estado final, bloquea futuras modificaciones
// Cancelado -> No permite inscripción

$estadoTorneo = strtolower(trim($torneo["estado"] ?? ""));

if ($estadoTorneo !== "abierto") {
    echo json_encode([
        "success" => false,
        "mensaje" => "No se puede registrar la inscripción porque el torneo no está abierto",
        "estadoActualTorneo" => $torneo["estado"] ?? null,
        "estadosPermitidosParaInscripcion" => ["Abierto"],
        "torneo" => $torneo
    ]);
    exit;
}

// --------------------------------------------------
// REGISTRAR INSCRIPCIÓN
// --------------------------------------------------

$sql = "INSERT INTO inscripciones (idTorneo, idEquipo, estado) VALUES (?, ?, 'pendiente')";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $idTorneo, $idEquipo);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "mensaje" => "Inscripción registrada correctamente",
        "inscripcion" => [
            "idInscripcion" => $conexion->insert_id,
            "idTorneo" => intval($idTorneo),
            "idEquipo" => intval($idEquipo),
            "estado" => "pendiente"
        ],
        "equipoValidado" => [
            "idEquipo" => $equipo["idEquipo"] ?? intval($idEquipo),
            "nombreEquipo" => $equipo["nombreEquipo"] ?? null,
            "categoria" => $equipo["categoria"] ?? null,
            "estado" => $equipo["estado"] ?? null
        ],
        "torneoValidado" => [
            "idTorneo" => $torneo["idTorneo"] ?? intval($idTorneo),
            "nombreTorneo" => $torneo["nombreTorneo"] ?? null,
            "videojuego" => $torneo["videojuego"] ?? null,
            "modalidad" => $torneo["modalidad"] ?? null,
            "fechaInicio" => $torneo["fechaInicio"] ?? null,
            "cupoMaximo" => $torneo["cupoMaximo"] ?? null,
            "estado" => $torneo["estado"] ?? null
        ]
    ]);
} else {
    if ($conexion->errno == 1062) {
        echo json_encode([
            "success" => false,
            "mensaje" => "Este equipo ya está inscrito en este torneo"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "mensaje" => "Error al registrar la inscripción",
            "error" => $conexion->error
        ]);
    }
}

$stmt->close();
$conexion->close();

?>