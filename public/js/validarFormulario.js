document.addEventListener('DOMContentLoaded', function() {
    // Validar campos requeridos antes de abrir el modal

    // Enviar el formulario al confirmar en el modal
    document.getElementById('confirmarEnvioBtn').addEventListener('click', function () {
        const formId = this.getAttribute('data-form-id');
        const form = document.getElementById(formId);

        if (!form) {
            console.error(`Formulario con ID "${formId}" no encontrado.`);
            return;
        }

        this.disabled = true;
        this.innerHTML = 'Enviando...';

        form.submit();
    });
});

