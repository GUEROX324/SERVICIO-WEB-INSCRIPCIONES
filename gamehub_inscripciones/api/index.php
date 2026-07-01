<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>API Inscripciones - GameHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

        body {
            margin: 0;
            background: #050505;
            color: #ffffff;
            font-family: Arial, sans-serif;
            background-image:
                radial-gradient(circle at top, #27104e 0%, transparent 35%),
                linear-gradient(180deg, #050505 0%, #111827 100%);
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: auto;
            padding: 30px 0;
        }

        .title {
            font-family: 'Press Start 2P', monospace;
            color: #ffcc00;
            text-shadow: 3px 3px #ff00cc;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #39ff14;
            margin-bottom: 30px;
        }

        .box {
            background: #111827;
            border: 3px solid #ffffff;
            box-shadow: 8px 8px 0 #ff00cc;
            padding: 20px;
            margin-bottom: 25px;
        }

        .endpoint {
            background: #000;
            border: 2px solid #00ccff;
            margin-bottom: 20px;
            padding: 15px;
        }

        .method {
            display: inline-block;
            padding: 6px 10px;
            font-weight: bold;
            border: 2px solid white;
            margin-right: 10px;
        }

        .get {
            background: #00ccff;
            color: #000;
        }

        .post {
            background: #39ff14;
            color: #000;
        }

        code, pre {
            background: #050505;
            color: #39ff14;
            padding: 10px;
            display: block;
            overflow-x: auto;
            border: 1px solid #ff00cc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        th {
            background: #ff00cc;
            color: #fff;
            padding: 10px;
            border: 1px solid #fff;
        }

        td {
            padding: 10px;
            border: 1px solid #555;
        }

        .note {
            color: #ffcc00;
        }

        .status {
            color: #39ff14;
            font-weight: bold;
        }

        a {
            color: #00ccff;
        }

        .footer {
            text-align: center;
            color: #999;
            margin-top: 40px;
            font-size: 13px;
        }

        ul li {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1 class="title">GAMEHUB API INSCRIPCIONES</h1>
    <p class="subtitle">Documentación para consumo del Web Service de Inscripciones</p>

    <div class="box">
        <h2>Información general</h2>
        <p><b>Servicio:</b> Web Service de Inscripciones</p>
        <p><b>Estado:</b> <span class="status">Activo</span></p>
        <p><b>Formato de respuesta:</b> JSON</p>
        <p><b>URL Base:</b></p>

        <code>https://enquirer-rubbed-drench.ngrok-free.dev/gamehub_inscripciones/api/</code>

        <p class="note">
            Nota: La URL de ngrok puede cambiar si se reinicia la sesión.
        </p>
    </div>

    <div class="box">
        <h2>Datos que proporciona este servicio</h2>

        <h3>Inscripciones</h3>
        <table>
            <tr>
                <th>Campo</th>
                <th>Descripción</th>
            </tr>
            <tr>
                <td>idInscripcion</td>
                <td>Identificador único de la inscripción</td>
            </tr>
            <tr>
                <td>idTorneo</td>
                <td>Identificador del torneo obtenido desde el Web Service de Torneos</td>
            </tr>
            <tr>
                <td>idEquipo</td>
                <td>Identificador del equipo obtenido desde el Web Service de Equipos</td>
            </tr>
            <tr>
                <td>fechaInscripcion</td>
                <td>Fecha y hora en que se registró la inscripción</td>
            </tr>
            <tr>
                <td>estado</td>
                <td>Estado actual de la inscripción</td>
            </tr>
        </table>

        <h3>Estados de inscripción</h3>
        <table>
            <tr>
                <th>Estado</th>
                <th>Descripción</th>
            </tr>
            <tr>
                <td>pendiente</td>
                <td>Estado inicial al registrar una inscripción</td>
            </tr>
            <tr>
                <td>aceptada</td>
                <td>Inscripción aprobada para participar en el torneo</td>
            </tr>
            <tr>
                <td>rechazada</td>
                <td>Inscripción no aprobada</td>
            </tr>
            <tr>
                <td>cancelada</td>
                <td>Inscripción cancelada</td>
            </tr>
        </table>
    </div>

    <div class="box">
        <h2>Endpoints disponibles</h2>

        <div class="endpoint">
            <h3><span class="method get">GET</span> Registrar inscripción</h3>
            <p><b>URL:</b></p>
            <code>registrarInscripcion.php?idTorneo=1&idEquipo=2</code>

            <table>
                <tr>
                    <th>Parámetro</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td>idTorneo</td>
                    <td>INT</td>
                    <td>ID del torneo al que se desea inscribir el equipo</td>
                </tr>
                <tr>
                    <td>idEquipo</td>
                    <td>INT</td>
                    <td>ID del equipo que será inscrito</td>
                </tr>
            </table>

            <p><b>Ejemplo completo:</b></p>
            <code>https://enquirer-rubbed-drench.ngrok-free.dev/gamehub_inscripciones/api/registrarInscripcion.php?idTorneo=1&idEquipo=2</code>

            <p><b>Respuesta esperada:</b></p>
            <pre>{
  "success": true,
  "mensaje": "Inscripción registrada correctamente",
  "inscripcion": {
    "idInscripcion": 1,
    "idTorneo": 1,
    "idEquipo": 2,
    "estado": "pendiente"
  },
  "equipoValidado": {
    "idEquipo": "2",
    "nombreEquipo": "los Alpha",
    "categoria": "Intermedio",
    "estado": "Activo"
  },
  "torneoValidado": {
    "idTorneo": 1,
    "nombreTorneo": "Copa Gamer 2025",
    "videojuego": "Valorant",
    "modalidad": "Individual",
    "fechaInicio": "2025-08-01",
    "cupoMaximo": 16,
    "estado": "Abierto"
  }
}</pre>
        </div>

        <div class="endpoint">
            <h3><span class="method get">GET</span> Listar inscripciones</h3>
            <p><b>URL:</b></p>
            <code>listarInscripciones.php</code>

            <p><b>Parámetros:</b> No requiere.</p>

            <p><b>Ejemplo completo:</b></p>
            <code>https://enquirer-rubbed-drench.ngrok-free.dev/gamehub_inscripciones/api/listarInscripciones.php</code>

            <p><b>Respuesta esperada:</b></p>
            <pre>{
  "success": true,
  "inscripciones": [
    {
      "idInscripcion": "1",
      "idTorneo": "1",
      "idEquipo": "2",
      "fechaInscripcion": "2026-07-01 10:30:00",
      "estado": "pendiente"
    }
  ]
}</pre>
        </div>

        <div class="endpoint">
            <h3><span class="method get">GET</span> Consultar inscripción</h3>
            <p><b>URL:</b></p>
            <code>consultarInscripcion.php?idInscripcion=1</code>

            <table>
                <tr>
                    <th>Parámetro</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td>idInscripcion</td>
                    <td>INT</td>
                    <td>ID de la inscripción a consultar</td>
                </tr>
            </table>

            <p><b>Ejemplo completo:</b></p>
            <code>https://enquirer-rubbed-drench.ngrok-free.dev/gamehub_inscripciones/api/consultarInscripcion.php?idInscripcion=1</code>

            <p><b>Respuesta esperada:</b></p>
            <pre>{
  "success": true,
  "inscripcion": {
    "idInscripcion": "1",
    "idTorneo": "1",
    "idEquipo": "2",
    "fechaInscripcion": "2026-07-01 10:30:00",
    "estado": "pendiente"
  }
}</pre>
        </div>

        <div class="endpoint">
            <h3><span class="method get">GET</span> Consultar inscripciones por torneo</h3>
            <p><b>URL:</b></p>
            <code>consultarPorTorneo.php?idTorneo=1</code>

            <table>
                <tr>
                    <th>Parámetro</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td>idTorneo</td>
                    <td>INT</td>
                    <td>ID del torneo del cual se desean consultar inscripciones</td>
                </tr>
            </table>

            <p><b>Ejemplo completo:</b></p>
            <code>https://enquirer-rubbed-drench.ngrok-free.dev/gamehub_inscripciones/api/consultarPorTorneo.php?idTorneo=1</code>

            <p><b>Respuesta esperada:</b></p>
            <pre>{
  "success": true,
  "idTorneo": 1,
  "inscripciones": [
    {
      "idInscripcion": "1",
      "idTorneo": "1",
      "idEquipo": "2",
      "fechaInscripcion": "2026-07-01 10:30:00",
      "estado": "pendiente"
    }
  ]
}</pre>
        </div>

        <div class="endpoint">
            <h3><span class="method get">GET</span> Consultar inscripciones por equipo</h3>
            <p><b>URL:</b></p>
            <code>consultarPorEquipo.php?idEquipo=2</code>

            <table>
                <tr>
                    <th>Parámetro</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td>idEquipo</td>
                    <td>INT</td>
                    <td>ID del equipo del cual se desean consultar inscripciones</td>
                </tr>
            </table>

            <p><b>Ejemplo completo:</b></p>
            <code>https://enquirer-rubbed-drench.ngrok-free.dev/gamehub_inscripciones/api/consultarPorEquipo.php?idEquipo=2</code>

            <p><b>Respuesta esperada:</b></p>
            <pre>{
  "success": true,
  "idEquipo": 2,
  "inscripciones": [
    {
      "idInscripcion": "1",
      "idTorneo": "1",
      "idEquipo": "2",
      "fechaInscripcion": "2026-07-01 10:30:00",
      "estado": "pendiente"
    }
  ]
}</pre>
        </div>

        <div class="endpoint">
            <h3><span class="method post">POST</span> Actualizar inscripción</h3>
            <p><b>URL:</b></p>
            <code>actualizarInscripcion.php</code>

            <table>
                <tr>
                    <th>Parámetro</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td>idInscripcion</td>
                    <td>INT</td>
                    <td>ID de la inscripción que será actualizada</td>
                </tr>
                <tr>
                    <td>estado</td>
                    <td>VARCHAR</td>
                    <td>Nuevo estado de la inscripción</td>
                </tr>
            </table>

            <p><b>Estados permitidos:</b></p>
            <code>pendiente, aceptada, rechazada, cancelada</code>

            <p><b>Ejemplo completo con GET para prueba:</b></p>
            <code>https://enquirer-rubbed-drench.ngrok-free.dev/gamehub_inscripciones/api/actualizarInscripcion.php?idInscripcion=1&estado=aceptada</code>

            <p><b>Respuesta esperada:</b></p>
            <pre>{
  "success": true,
  "mensaje": "Estado de inscripción actualizado correctamente"
}</pre>
        </div>

        <div class="endpoint">
            <h3><span class="method post">POST</span> Eliminar inscripción</h3>
            <p><b>URL:</b></p>
            <code>eliminarInscripcion.php</code>

            <table>
                <tr>
                    <th>Parámetro</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td>idInscripcion</td>
                    <td>INT</td>
                    <td>ID de la inscripción que será eliminada</td>
                </tr>
            </table>

            <p><b>Ejemplo completo con GET para prueba:</b></p>
            <code>https://enquirer-rubbed-drench.ngrok-free.dev/gamehub_inscripciones/api/eliminarInscripcion.php?idInscripcion=1</code>

            <p><b>Respuesta esperada:</b></p>
            <pre>{
  "success": true,
  "mensaje": "Inscripción eliminada correctamente"
}</pre>
        </div>
    </div>

    <div class="box">
        <h2>Reglas de validación</h2>
        <ul>
            <li>No se permiten parámetros vacíos.</li>
            <li>idTorneo e idEquipo deben ser valores numéricos.</li>
            <li>No se permite inscribir dos veces el mismo equipo al mismo torneo.</li>
            <li>El equipo se valida consumiendo el Web Service de Equipos.</li>
            <li>El equipo debe tener estado <b>Activo</b>.</li>
            <li>El torneo se valida consumiendo el Web Service de Torneos.</li>
            <li>El torneo debe tener estado <b>Abierto</b> para permitir inscripciones.</li>
            <li>No se accede directamente a bases de datos de otros equipos.</li>
        </ul>
    </div>

    <div class="box">
        <h2>Estados del torneo considerados</h2>
        <table>
            <tr>
                <th>Estado</th>
                <th>Permite inscripción</th>
                <th>Descripción</th>
            </tr>
            <tr>
                <td>Proximo</td>
                <td>No</td>
                <td>Estado por defecto al crear un torneo. Todavía no permite inscripciones.</td>
            </tr>
            <tr>
                <td>Abierto</td>
                <td>Sí</td>
                <td>Estado válido para registrar inscripciones.</td>
            </tr>
            <tr>
                <td>En curso</td>
                <td>No</td>
                <td>El torneo ya inició, no permite nuevas inscripciones.</td>
            </tr>
            <tr>
                <td>Finalizado</td>
                <td>No</td>
                <td>Estado final. Bloquea futuras modificaciones.</td>
            </tr>
            <tr>
                <td>Cancelado</td>
                <td>No</td>
                <td>El torneo fue cancelado y no permite inscripciones.</td>
            </tr>
        </table>
    </div>

    <div class="box">
        <h2>Integración con otros Web Services</h2>

        <p>
            Este Web Service consume principalmente el Web Service de Equipos y el Web Service de Torneos.
        </p>

        <h3>Servicio de Equipos</h3>
        <p>
            Se utiliza para validar que el equipo exista y esté activo antes de registrar una inscripción.
        </p>
        <p><b>Endpoint consumido:</b></p>
        <code>https://episode-possibly-maroon.ngrok-free.dev/gamehub_equipos/api/consultarEquipo.php?idEquipo=6</code>

        <h3>Servicio de Torneos</h3>
        <p>
            Se utiliza para validar que el torneo exista y tenga estado Abierto antes de registrar una inscripción.
        </p>
        <p><b>Endpoint consumido:</b></p>
        <code>https://renounceable-nonmelodious-rocco.ngrok-free.dev/torneos/consultarTorneo.php?id=5</code>

        <h3>Servicio de Jugadores</h3>
        <p>
            Este Web Service no consume directamente el servicio de Jugadores. La validación de jugadores se realiza desde el Web Service de Equipos.
        </p>
    </div>

    <div class="footer">
        GameHub Inscripciones API - Servicios Web - 2026
    </div>

</div>

</body>
</html>