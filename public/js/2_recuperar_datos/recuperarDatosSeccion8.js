document.addEventListener('DOMContentLoaded', function() {
    // Configurar eventos
    document.getElementById('confirmarEnvioBtn').addEventListener('click', enviarSeccion8);
    document.querySelector('.btn-guardar').addEventListener('click', mostrarModalConfirmacion);
});

function mostrarModalConfirmacion(e) {
    e.preventDefault();
    
    if (validarFormulario()) {
        const modalMessage = document.getElementById('modalMessage');
        modalMessage.innerHTML = `
            <p>¿Está seguro que desea guardar los datos de la Sección 8?</p>
            <div class="alert alert-info mt-2">
                <strong>Declaraciones:</strong><br>
                - Conducta intachable y valores éticos: Aceptado<br>
                - Veracidad de la información: Aceptado
            </div>
        `;
    }
}

function validarFormulario() {
    const condutaEtica = document.getElementById('condutaEtica');
    const condutaEticaValores = document.getElementById('condutaEticaValores');
    const firmaInput = document.getElementById('firmaInput');
    
    // Validar checkboxes
    if (!condutaEtica.checked || !condutaEticaValores.checked) {
        return false;
    }
    
    // Validar firma
    if (!firmaInput.value) {
        return false;
    }
    
    return true;
}

async function enviarSeccion8() {
    if (!validarFormulario()) return;
    
    const form = document.getElementById('formulario_seccion8');
    const confirmarBtn = document.getElementById('confirmarEnvioBtn');
    
    try {
        confirmarBtn.disabled = true;
        confirmarBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';
        
        // Crear FormData y asegurar que los checkboxes sean 1 (ya que siempre deben ser 1)
        const formData = new FormData(form);
        formData.set('condutaEtica', '1');
        formData.set('condutaEticaValores', '1');
        
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: '¡Guardado!',
                text: result.message || 'Los datos se han guardado correctamente.',
                confirmButtonText: 'Aceptar',
            }).then(() => {
                location.href = URL + "Admin/HomeFormulario";
            });
        } else {
            throw new Error(result.message || 'Error al guardar los datos');
        }
    } catch (error) {
        console.error('Error:', error.message);
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: error.message || 'Hubo un problema al guardar los datos.',
        });
    } finally {
        confirmarBtn.disabled = false;
        confirmarBtn.innerHTML = 'Confirmar';
    }
}