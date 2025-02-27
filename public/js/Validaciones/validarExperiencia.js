// Agregar el modal al documento
document.addEventListener('DOMContentLoaded', (event) => {
    const modalHTML = `
        <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="alertModalLabel">Alerta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="alertModalBody">
                        <!-- Mensaje de alerta irá aquí -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
});

// Función para mostrar el modal de alerta
function mostrarAlerta(mensaje) {
    document.getElementById("alertModalBody").textContent = mensaje;
    $('#alertModal').modal('show');
}

function validarExperienciaLaboral() {
    const tablaExperiencia = document.getElementById("tablaExperiencia");
    if (!tablaExperiencia) {
        mostrarAlerta("Error: La tabla de experiencia laboral no fue encontrada.");
        return false;
    }

    const filas = tablaExperiencia.querySelectorAll("tbody tr");
    console.log(`Número de filas detectadas: ${filas.length}`);

    if (filas.length === 0) {
        mostrarAlerta("Debes agregar al menos una experiencia laboral.");
        return false;
    }

    let totalAnios = 0;

    filas.forEach(fila => {
        const fechaInicioTexto = fila.cells[2]?.textContent.trim();
        const fechaRetiroTexto = fila.cells[3]?.textContent.trim();

        if (!fechaInicioTexto || !fechaRetiroTexto) {
            mostrarAlerta("Error: Hay fechas vacías en la tabla de experiencia laboral.");
            return false;
        }

        const fechaInicio = new Date(fechaInicioTexto);
        const fechaRetiro = new Date(fechaRetiroTexto);

        console.log(`Fecha inicio: ${fechaInicio}, Fecha retiro: ${fechaRetiro}`);

        if (isNaN(fechaInicio.getTime()) || isNaN(fechaRetiro.getTime())) {
            mostrarAlerta("Error: Las fechas ingresadas no son válidas.");
            return false;
        }

        // Calcular la diferencia en años
        const anios = fechaRetiro.getFullYear() - fechaInicio.getFullYear();
        totalAnios += anios;
    });

    console.log(`Total años calculados: ${totalAnios}`);

    if (totalAnios < 10) {
        mostrarAlerta(`Debes tener al menos 10 años de experiencia laboral. Actualmente tienes ${totalAnios} años.`);
        return false;
    }

    return true;
}
