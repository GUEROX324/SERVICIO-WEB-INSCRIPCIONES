const API_INSCRIPCIONES = "/gamehub_inscripciones/api/";

document.addEventListener("DOMContentLoaded", () => {
    listarInscripciones();

    const form = document.getElementById("formInscripcion");

    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const idTorneo = document.getElementById("idTorneo").value;
            const idEquipo = document.getElementById("idEquipo").value;

            registrarInscripcion(idTorneo, idEquipo);
        });
    }
});

async function registrarInscripcion(idTorneo, idEquipo) {
    const url = `${API_INSCRIPCIONES}registrarInscripcion.php?idTorneo=${idTorneo}&idEquipo=${idEquipo}`;

    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();

        alert(datos.mensaje);

        if (datos.success) {
            const modalElement = document.getElementById("modalInscripcion");
            const modal = bootstrap.Modal.getInstance(modalElement);

            if (modal) {
                modal.hide();
            }

            document.getElementById("formInscripcion").reset();
            listarInscripciones();
        }
    } catch (error) {
        alert("Error al registrar la inscripción");
        console.error(error);
    }
}

async function listarInscripciones() {
    const tabla = document.getElementById("tablaInscripciones");

    try {
        const respuesta = await fetch(`${API_INSCRIPCIONES}listarInscripciones.php`);
        const datos = await respuesta.json();

        console.log("Respuesta listarInscripciones:", datos);

        tabla.innerHTML = "";

        const inscripciones = datos.inscripciones || [];

        if (!datos.success || inscripciones.length === 0) {
            tabla.innerHTML = `
                <tr>
                    <td colspan="6">
                        <div class="empty-arcade">
                            <h1>GAME OVER</h1>
                            <p>NO EXISTEN INSCRIPCIONES REGISTRADAS</p>
                            <p class="blink">INSERT COIN / PRESS START</p>
                            <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#modalInscripcion" onclick="nuevaInscripcion()">
                                ▶ NUEVA INSCRIPCIÓN
                            </button>
                        </div>
                    </td>
                </tr>
            `;

            actualizarEstadisticas([]);
            document.getElementById("contadorInscripciones").innerText = "Mostrando 0 inscripciones";
            return;
        }

        inscripciones.forEach(inscripcion => {
            tabla.innerHTML += `
                <tr>
                    <td><b>${inscripcion.idInscripcion}</b></td>
                    <td>${inscripcion.idTorneo}</td>
                    <td>${inscripcion.idEquipo}</td>
                    <td>${inscripcion.fechaInscripcion}</td>
                    <td>${crearBadgeEstado(inscripcion.estado)}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="verDetalle(${inscripcion.idInscripcion})">
                            VER
                        </button>

                        <button class="btn btn-success btn-sm" onclick="actualizarEstado(${inscripcion.idInscripcion}, 'aceptada')">
                            ACEPTAR
                        </button>

                        <button class="btn btn-warning btn-sm" onclick="actualizarEstado(${inscripcion.idInscripcion}, 'rechazada')">
                            RECHAZAR
                        </button>

                        <button class="btn btn-secondary btn-sm" onclick="actualizarEstado(${inscripcion.idInscripcion}, 'cancelada')">
                            CANCELAR
                        </button>

                        <button class="btn btn-danger btn-sm" onclick="eliminarInscripcion(${inscripcion.idInscripcion})">
                            ELIMINAR
                        </button>
                    </td>
                </tr>
            `;
        });

        actualizarEstadisticas(inscripciones);

        document.getElementById("contadorInscripciones").innerText =
            "Mostrando " + inscripciones.length + " inscripciones";

    } catch (error) {
        tabla.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-danger">
                    Error al cargar inscripciones
                </td>
            </tr>
        `;
        console.error(error);
    }
}

async function verDetalle(idInscripcion) {
    try {
        const respuesta = await fetch(`${API_INSCRIPCIONES}consultarInscripcion.php?idInscripcion=${idInscripcion}`);
        const datos = await respuesta.json();

        document.getElementById("detalleInscripcion").textContent =
            JSON.stringify(datos, null, 2);

        const modal = new bootstrap.Modal(document.getElementById("modalDetalle"));
        modal.show();

    } catch (error) {
        alert("Error al consultar la inscripción");
        console.error(error);
    }
}

async function actualizarEstado(idInscripcion, estado) {
    if (!confirm(`¿Seguro que deseas cambiar la inscripción a "${estado}"?`)) {
        return;
    }

    try {
        const respuesta = await fetch(`${API_INSCRIPCIONES}actualizarInscripcion.php?idInscripcion=${idInscripcion}&estado=${estado}`);
        const datos = await respuesta.json();

        alert(datos.mensaje);

        if (datos.success) {
            listarInscripciones();
        }
    } catch (error) {
        alert("Error al actualizar la inscripción");
        console.error(error);
    }
}

async function eliminarInscripcion(idInscripcion) {
    if (!confirm("¿Seguro que deseas eliminar esta inscripción?")) {
        return;
    }

    try {
        const respuesta = await fetch(`${API_INSCRIPCIONES}eliminarInscripcion.php?idInscripcion=${idInscripcion}`);
        const datos = await respuesta.json();

        alert(datos.mensaje);

        if (datos.success) {
            listarInscripciones();
        }
    } catch (error) {
        alert("Error al eliminar la inscripción");
        console.error(error);
    }
}

function nuevaInscripcion() {
    const form = document.getElementById("formInscripcion");

    if (form) {
        form.reset();
    }
}

function actualizarEstadisticas(inscripciones) {
    const total = inscripciones.length;

    const pendientes = inscripciones.filter(i => String(i.estado).toLowerCase() === "pendiente").length;
    const aceptadas = inscripciones.filter(i => String(i.estado).toLowerCase() === "aceptada").length;

    const torneos = [...new Set(inscripciones.map(i => i.idTorneo))];

    document.getElementById("totalInscripciones").innerText = total;
    document.getElementById("totalPendientes").innerText = pendientes;
    document.getElementById("totalAceptadas").innerText = aceptadas;
    document.getElementById("totalTorneos").innerText = torneos.length;
}

function crearBadgeEstado(estado) {
    const estadoNormalizado = String(estado).toLowerCase();

    let clase = "bg-secondary";

    if (estadoNormalizado === "pendiente") {
        clase = "bg-warning text-dark";
    } else if (estadoNormalizado === "aceptada") {
        clase = "bg-success text-dark";
    } else if (estadoNormalizado === "rechazada") {
        clase = "bg-danger";
    } else if (estadoNormalizado === "cancelada") {
        clase = "bg-secondary";
    }

    return `<span class="badge ${clase}">${estado}</span>`;
}

function filtrarTabla() {
    const filtro = document.getElementById("buscador").value.trim().toLowerCase();
    const filas = document.querySelectorAll("#tablaInscripciones tr");

    let visibles = 0;

    filas.forEach(fila => {
        const celdas = fila.querySelectorAll("td");

        if (celdas.length < 5) {
            fila.style.display = "";
            return;
        }

        const idInscripcion = celdas[0].innerText.trim().toLowerCase();
        const idTorneo = celdas[1].innerText.trim().toLowerCase();
        const idEquipo = celdas[2].innerText.trim().toLowerCase();
        const estado = celdas[4].innerText.trim().toLowerCase();

        let coincide = true;

        if (filtro !== "") {
            coincide =
                idInscripcion === filtro ||
                idTorneo === filtro ||
                idEquipo === filtro ||
                estado.includes(filtro);
        }

        fila.style.display = coincide ? "" : "none";

        if (coincide) {
            visibles++;
        }
    });

    const contador = document.getElementById("contadorInscripciones");

    if (filtro === "") {
        contador.innerText = "Mostrando " + visibles + " inscripciones";
    } else {
        contador.innerText = "Mostrando " + visibles + " inscripciones encontradas";
    }
}