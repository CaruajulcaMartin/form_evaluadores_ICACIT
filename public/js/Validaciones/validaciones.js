document.addEventListener("DOMContentLoaded", function () {
    // Función para validar archivos (foto de perfil)
    function validarArchivo(inputId, errorId, formatosValidos, maxSizeMB) {
        let input = document.getElementById(inputId);
        let errorMensaje = document.getElementById(errorId);
        
        input.addEventListener("change", function () {
            let archivo = this.files[0];
            let maxSizeBytes = maxSizeMB * 1024 * 1024;
            
            if (archivo) {
                // Convertir tipo de archivo a minúsculas para comparación
                let tipoArchivo = archivo.type.toLowerCase();

                // Permitir también archivos con extensión .jpg y .png
                if (!formatosValidos.includes(tipoArchivo) && !(tipoArchivo === "image/jpeg" && archivo.name.toLowerCase().endsWith(".jpg")) && !(tipoArchivo === "image/png" && archivo.name.toLowerCase().endsWith(".png"))) {
                    errorMensaje.textContent = `Formato inválido. Solo se permiten archivos ${formatosValidos.join(", ")}.`;
                    errorMensaje.style.display = "block";
                    this.value = ""; // Limpiar el input
                    return;
                }
                
                if (archivo.size > maxSizeBytes) {
                    errorMensaje.textContent = `El archivo es demasiado grande. Máximo permitido: ${maxSizeMB}MB.`;
                    errorMensaje.style.display = "block";
                    this.value = ""; // Limpiar el input
                    return;
                }
                
                // Si pasa ambas validaciones, ocultar mensaje de error
                errorMensaje.style.display = "none";
            }
        });
    }

    // Función para validar textarea (límite de palabras)
    function validarTextarea(textareaId, contadorId, errorId, maximo, minimo = 0) {
        let textarea = document.getElementById(textareaId);
        // Puedes añadir let contador = document.getElementById(contadorId); y let errorMensaje = document.getElementById(errorId); aquí si también necesitas validarlos.
    
        // Verificar si el textarea existe antes de añadir el listener
        if (textarea) { // <--- Añadir esta verificación
            textarea.addEventListener("input", function () {
                let texto = this.value.trim();
                let totalCaracteres = texto.length;
                let totalPalabras = texto.split(/\s+/).filter(word => word.length > 0).length;
    
                // Obtener contador y errorMensaje aquí dentro del listener
                // Esto asegura que se busquen los elementos solo cuando hay interacción
                let contador = document.getElementById(contadorId);
                let errorMensaje = document.getElementById(errorId);
    
    
                if (maximo === 150) {
                    if (contador) contador.textContent = `Máximo ${maximo} caracteres. Caracteres actuales: ${totalCaracteres}`; // Añadir verificación de contador
                    if (totalCaracteres > maximo) {
                         if (errorMensaje) { // Añadir verificación de errorMensaje
                            errorMensaje.style.display = "block";
                            errorMensaje.textContent = `No puedes escribir más de ${maximo} caracteres.`;
                         }
                        this.value = texto.slice(0, maximo); // Limitar a maximo
                    } else {
                        if (errorMensaje) errorMensaje.style.display = "none"; // Añadir verificación de errorMensaje
                    }
                } else if (maximo === 400) {
                    if (contador) contador.textContent = `Máximo ${maximo} palabras. Palabras actuales: ${totalPalabras}`; // Añadir verificación de contador
                    if (totalPalabras > maximo) {
                        if (errorMensaje) { // Añadir verificación de errorMensaje
                            errorMensaje.style.display = "block";
                            errorMensaje.textContent = `No puedes escribir más de ${maximo} palabras.`;
                        }
                        this.value = texto.split(/\s+/).filter(word => word.length > 0).slice(0, maximo).join(" "); // Limitar a maximo
                    } else if (totalPalabras < minimo) {
                         if (errorMensaje) { // Añadir verificación de errorMensaje
                            errorMensaje.style.display = "block";
                            errorMensaje.textContent = `Debes escribir al menos ${minimo} palabras.`;
                         }
                    } else {
                        if (errorMensaje) errorMensaje.style.display = "none"; // Añadir verificación de errorMensaje
                    }
                }
            });
        } else {
            console.error(`validaciones.js: Elemento con ID "${textareaId}" no encontrado para añadir listener.`);
        }
    }

    // Validación de textarea de sección 1 (referencias domicilio)
    validarTextarea("referenciaDomicilio", "contadorReferenciaDomicilio", "errorReferenciaDomicilio", 150);

    // Validación de textarea de sección 4 (funciones principales)
    validarTextarea("funcionesPrincipales", "contadorObservacionesPrincipales", "errorObservaciones", 150);

    // Validación de textarea de sección 6 (Descripción Reconocimiento / Premio)
    validarTextarea("descripcionReconocimiento", "contadorDescripcionReconocimiento", "errorObservaciones", 150);

    // Validación de textarea de sección 7 (carta de presentación) - mínimo 10 palabras, máximo 400 palabras
    validarTextarea("descripcionContribucion", "contadorPalabras", "mensajeError", 400, 10);

    // Validación de foto de perfil (solo JPG y PNG, máximo 5 MB)
    validarArchivo("fotoPerfil", "errorFotoPerfil", ["image/jpg", "image/png"], 5);
});