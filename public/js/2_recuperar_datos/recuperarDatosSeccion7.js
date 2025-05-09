$(document).ready(function() {
    console.log('Documento listo - Inicializando sección 7');

    // Elementos del DOM
    var $textarea = $('#descripcionContribucion');
    var $contadorPalabras = $('#contadorPalabras');
    var $mensajeError = $('#mensajeError');
    var $btnGuardar = $('.btn-guardar');
    var $modalMessage = $('#modalMessage');
    var $confirmarEnvioBtn = $('#confirmarEnvioBtn');
    
    // Variables de estado
    var userId = $('#userId').val();
    var textoOriginal = '';
    var existeRegistro = false;

    console.log('User ID:', userId);

    // Inicialización
    if (!userId) {
        console.error('ID de usuario no definido - Elemento #userId no encontrado o vacío');
        //alert('Error: No se pudo identificar al usuario');
        return;
    }

    // Cargar datos iniciales
    cargarDatosIniciales();

    // Configurar eventos
    $textarea.on('input', function() {
        //console.log('Texto modificado');
        actualizarContadorPalabras();
        verificarCambios();
    });

    $btnGuardar.on('click', mostrarModalConfirmacion);
    $confirmarEnvioBtn.on('click', enviarFormulario);

    // Funciones principales
    function cargarDatosIniciales() {
        //console.log('Cargando datos iniciales...');
        
        $.ajax({
            url: `${URL}DatosRecuperar/recuperarDatosSeccion7`,
            method: 'POST',
            data: { userId },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta recibida (recuperarDatosSeccion7):', response);
                
                if (response?.cartaPresentacion) {
                    //console.log('Datos de carta de presentación encontrados');
                    existeRegistro = true;
                    textoOriginal = response.cartaPresentacion;
                    $textarea.val(textoOriginal);
                    actualizarContadorPalabras();
                    configurarBotonActualizar();
                } else {
                    console.log('No se encontraron datos de carta de presentación');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar datos:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                alert('Error al cargar los datos existentes. Consulte la consola para más detalles.');
            }
        });
    }

    function actualizarContadorPalabras() {
        const texto = $textarea.val().trim();
        const numPalabras = texto ? texto.split(/\s+/).length : 0;
        
       // console.log('Actualizando contador de palabras:', numPalabras);
        $contadorPalabras.text(`Máximo 400 palabras. Palabras actuales: ${numPalabras}`);
        $mensajeError.toggle(numPalabras > 400);
    }

    function verificarCambios() {
        if (!existeRegistro) return;
        
        const textoActual = $textarea.val().trim();
        const hayCambios = textoActual !== textoOriginal;
        
        //console.log('Verificando cambios:', { textoOriginal, textoActual, hayCambios });
        $textarea.toggleClass('campo-modificado', hayCambios);
        $btnGuardar.prop('disabled', !hayCambios);
    }

    function configurarBotonActualizar() {
        //console.log('Configurando botón como "Actualizar"');
        $btnGuardar
            .removeClass('btn-success')
            .addClass('btn-warning')
            .html('<i class="fa-solid fa-rotate"></i> Actualizar Cambios')
            .attr('id', 'btnActualizar')
            .prop('disabled', true);
    }

    function mostrarModalConfirmacion() {
        const esActualizacion = existeRegistro;
        const mensaje = esActualizacion 
            ? "¿Estás seguro de actualizar tu carta de presentación?"
            : "¿Estás seguro de guardar tu carta de presentación?";
        
        //console.log('Mostrando modal de confirmación:', { esActualizacion, mensaje });
        $modalMessage.text(mensaje);
        $confirmarEnvioBtn
            .text(esActualizacion ? "Actualizar" : "Guardar")
            .data('es-actualizacion', esActualizacion);
    }

    function enviarFormulario() {
        const esActualizacion = $confirmarEnvioBtn.data('es-actualizacion');
        const texto = $textarea.val().trim();
        const numPalabras = texto ? texto.split(/\s+/).length : 0;
        
        // console.log('Enviando formulario:', {
        //     esActualizacion,
        //     texto,
        //     numPalabras
        // });
    
        // Validación
        if (numPalabras > 400) {
            console.warn('Validación fallida: Excede el límite de palabras');
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'La carta no puede exceder las 400 palabras.',
            });
            return;
        }
    
        const $btn = $confirmarEnvioBtn;
        $btn.prop("disabled", true)
            .html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
    
        $.ajax({
            url: esActualizacion ? 
                URL + "Formulario/actualizarSeccion7" : 
                URL + "Formulario/enviarSeccion7",
            // url:
            //     URL + "Formulario/enviarSeccion7",
            method: 'POST',
            data: { 
                userId,
                cartaPresentacion: texto 
            },
            dataType: 'json'
        })
        .done(function(response) {
            console.log('Respuesta exitosa:', response);
            
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Guardado!',
                    text: response.message || (esActualizacion ? 'Carta de presentación actualizada correctamente.' : 'Carta de presentación guardada correctamente.'),
                    confirmButtonText: 'Aceptar',
                }).then((result) =>{
                    if (response.redirect) {
                        // alert(response.message);
                        location.href = response.redirect;
                    } else {
                        textoOriginal = texto;
                        verificarCambios();
                        //alert(response.message);
                    }
                });
            } else {
                throw new Error(response.message || 'Error desconocido');
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Error en la petición:', { status, error, xhr });
            
            let errorMessage = 'Error al procesar la solicitud';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.statusText) {
                errorMessage = `${xhr.status}: ${xhr.statusText}`;
            }
            
            //alert(errorMessage);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
            });
        })
        .always(function() {
            $btn.prop('disabled', false)
                .text(esActualizacion ? "Actualizar" : "Guardar");
        });
    }
});