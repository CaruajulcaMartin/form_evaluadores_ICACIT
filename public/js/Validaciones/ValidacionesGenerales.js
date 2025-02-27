document.addEventListener("DOMContentLoaded", function () {
    // Crear un modal para mostrar alertas
    function crearModal() {
        const modal = document.createElement("div");
        modal.className = "modal fade";
        modal.id = "alertModal";
        modal.tabIndex = -1;
        modal.role = "dialog";
        modal.setAttribute("aria-labelledby", "alertModalLabel");
        modal.setAttribute("aria-hidden", "true");
        modal.innerHTML = `
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-danger" id="alertModalLabel">Alerta</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    // Mostrar el modal con el mensaje de alerta
    function mostrarAlerta(mensaje) {
        const modal = document.getElementById("alertModal");
        const modalBody = modal.querySelector(".modal-body");
        modalBody.textContent = mensaje;
        $(modal).modal('show');
    }

    // Crear el modal al cargar la página
    crearModal();

    // Validación de campos de texto (solo letras), excluyendo el campo de número de documento
    const textInputs = document.querySelectorAll('input[type="text"]:not([name="numDoc"])');
    textInputs.forEach(input => {
        input.addEventListener("input", function () {
            // Remover cualquier carácter que no sea letra o espacio
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });
    });

    // Función genérica para validar fechas (no permitir fechas futuras)
    function validarFecha(input, mensajeError) {
        input.addEventListener("change", function () {
            const fecha = new Date(this.value);
            const fechaActual = new Date();
            if (fecha > fechaActual) {
                mostrarAlerta(mensajeError);
                this.value = ""; // Limpiar el campo si la fecha es futura
            }
        });
    }

    // Validación de la fecha de nacimiento
    const fechaNacimientoInput = document.querySelector('input[name="fechaNacimiento"]');
    if (fechaNacimientoInput) {
        validarFecha(fechaNacimientoInput, "La fecha de nacimiento no puede ser futura.");
    }

    // Validación de fechas de inicio y retiro en diferentes secciones
    const fechasInicio = document.querySelectorAll('input[name="fechaInicio"], input[name="fechaInicioDocente"], input[name="fechaInicioComite"]');
    fechasInicio.forEach(input => validarFecha(input, "La fecha de inicio no puede ser futura."));

    const fechasRetiro = document.querySelectorAll('input[name="fechaRetiro"], input[name="fechaRetiroDocente"], input[name="fechaRetiroComite"]');
    fechasRetiro.forEach(input => validarFecha(input, "La fecha de retiro no puede ser futura."));

    // Validación de fecha de evaluación
    const fechaEvaluacionInputs = document.querySelectorAll('input[name="fechaEvaluacion"]');
    fechaEvaluacionInputs.forEach(input => validarFecha(input, "La fecha de evaluación no puede ser futura."));

    // Validación de año de publicación
    const fechaPublicacionInputs = document.querySelectorAll('input[name="fechaPublicacion"]');
    fechaPublicacionInputs.forEach(input => validarFecha(input, "El año de publicación no puede ser futuro."));

    // Validación de email (mejorada para correos institucionales)
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(emailInput => {
        // Crear un elemento para mostrar el mensaje de error
        const errorMessage = document.createElement("div");
        errorMessage.className = "text-danger mt-1";
        emailInput.parentNode.appendChild(errorMessage);

        // Función para validar el correo electrónico
        const validarCorreoElectronico = () => {
            const email = emailInput.value.trim();
            // Expresión regular mejorada para correos institucionales
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?$/;

            if (!emailPattern.test(email)) {
                errorMessage.textContent = "Ingrese un correo electrónico válido.";
                emailInput.setCustomValidity("Ingrese un correo electrónico válido.");
            } else {
                errorMessage.textContent = "";
                emailInput.setCustomValidity("");
            }
        };

        // Aplicar la validación cuando el usuario escribe
        emailInput.addEventListener("input", validarCorreoElectronico);

        // Aplicar la validación cuando el campo pierde el foco
        emailInput.addEventListener("blur", validarCorreoElectronico);
    });

    // Validación de tipo de identidad
    const tipoIdentidadSelect = document.querySelector('select[name="tipoIdentidad"]');
    const numDocInput = document.querySelector('input[name="numDoc"]');

    if (tipoIdentidadSelect && numDocInput) {
        // Crear un elemento para mostrar el mensaje de error
        const errorMessage = document.createElement("div");
        errorMessage.className = "text-danger mt-1";
        numDocInput.parentNode.appendChild(errorMessage);

        // Deshabilitar el campo de número de documento al cargar la página
        numDocInput.disabled = true;

        // Función para validar el número de documento según el tipo de identidad
        const validarNumeroDocumento = () => {
            const tipoIdentidad = tipoIdentidadSelect.value;

            // Limpiar el campo de número de documento cuando cambia el tipo de identidad
            numDocInput.value = "";
            errorMessage.textContent = ""; // Limpiar mensajes de error

            // Habilitar el campo solo si se selecciona un tipo de identidad válido
            if (tipoIdentidad) {
                numDocInput.disabled = false;
            } else {
                numDocInput.disabled = true;
            }

            // Aplicar validaciones según el tipo de identidad seleccionado
            if (tipoIdentidad === "DNI (Documento Nacional de Identidad)") {
                // Validación para DNI: solo números, máximo 8 dígitos
                numDocInput.setAttribute("pattern", "\\d{1,8}");
                numDocInput.setAttribute("title", "El DNI debe tener máximo 8 dígitos.");
            } else if (tipoIdentidad === "Pasaporte") {
                // Validación para Pasaporte: letras y números, máximo 12 caracteres
                numDocInput.removeAttribute("pattern");
                numDocInput.setAttribute("title", "El pasaporte puede contener letras y números, máximo 12 caracteres.");
            } else if (tipoIdentidad === "Carnet de Extranjeria") {
                // Validación para Carnet de Extranjería: solo números, máximo 12 caracteres
                numDocInput.removeAttribute("pattern");
                numDocInput.setAttribute("title", "El carnet de extranjería debe contener solo números, máximo 12 caracteres.");
            } else {
                // Si no se selecciona un tipo de identidad, no aplicar ninguna validación
                numDocInput.removeAttribute("pattern");
                numDocInput.removeAttribute("title");
            }
        };

        // Función para validar la entrada en tiempo real
        const validarEntrada = () => {
            const tipoIdentidad = tipoIdentidadSelect.value;
            const valor = numDocInput.value;

            if (tipoIdentidad === "DNI (Documento Nacional de Identidad)") {
                // Solo números y máximo 8 dígitos
                numDocInput.value = valor.replace(/\D/g, ''); // Eliminar caracteres no numéricos
                if (numDocInput.value.length > 8) {
                    numDocInput.value = numDocInput.value.slice(0, 8); // Limitar a 8 dígitos
                    errorMessage.textContent = "El DNI no puede tener más de 8 dígitos.";
                } else {
                    errorMessage.textContent = "";
                }
            } else if (tipoIdentidad === "Pasaporte") {
                // Letras, números y máximo 12 caracteres
                numDocInput.value = valor.replace(/[^a-zA-Z0-9]/g, ''); // Eliminar caracteres no alfanuméricos
                if (numDocInput.value.length > 12) {
                    numDocInput.value = numDocInput.value.slice(0, 12); // Limitar a 12 caracteres
                    errorMessage.textContent = "El pasaporte no puede tener más de 12 caracteres.";
                } else {
                    errorMessage.textContent = "";
                }
            } else if (tipoIdentidad === "Carnet de Extranjeria") {
                // Solo números y máximo 12 caracteres
                numDocInput.value = valor.replace(/\D/g, ''); // Eliminar caracteres no numéricos
                if (numDocInput.value.length > 12) {
                    numDocInput.value = numDocInput.value.slice(0, 12); // Limitar a 12 dígitos
                    errorMessage.textContent = "El carnet de extranjería no puede tener más de 12 dígitos.";
                } else {
                    errorMessage.textContent = "";
                }
            }
        };

        // Aplicar la validación cuando cambia el tipo de identidad
        tipoIdentidadSelect.addEventListener("change", validarNumeroDocumento);

        // Aplicar la validación en tiempo real cuando el usuario escribe
        numDocInput.addEventListener("input", validarEntrada);

        // Aplicar la validación inicial al cargar la página
        validarNumeroDocumento();
    }
});