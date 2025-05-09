$(document).ready(function() {
    //console.log("Documento listo - Iniciando script de sección 2");
    // alert("Script de sección 2 cargado correctamente");

    // Variables globales
    var userId = $("#userId").val();
    //console.log("ID de usuario obtenido:", userId);
    
    var datosOriginales = {};
    var existeRegistro = false;
    var hayCambios = false;

    if (!userId) {
        //alert("Error: El ID de usuario no está definido.");
        console.error("El ID de usuario no está definido");
        return;
    }

    // Cargar datos existentes
    cargarDatosIniciales();

    // Configurar eventos para detectar cambios
    configurarEventosCambios();

    // Configurar el modal de confirmación
    configurarModalConfirmacion();


    function cargarDatosIniciales() {
        console.log("Cargando datos iniciales para la sección 2");

        $.ajax({
            url: URL + "DatosRecuperar/recuperarDatosSeccion2",
            dataType: "json",
            method: "POST",
            data: { userId: userId },
            success: function(data) {
                //console.log("Datos recuperados:", data);

                if(data && data.centroLaboral){
                    //console.log("Datos existentes encontrados");
                    existeRegistro = true;
                    // Guardar los datos originales para comparación
                    datosOriginales = {
                        centroLaboral: data.centroLaboral,
                        cargoActual: data.cargoActual,
                        tiempoLaboral: data.tiempoLaboral,
                        PaisInformacionLaboral: data.PaisInformacionLaboral,
                        ciudadInformacionLaboral: data.ciudadInformacionLaboral,
                        rubroInformacionLaboral: data.rubroInformacionLaboral,
                        nombresEmpleador: data.nombresEmpleador,
                        cargoEmpleador: data.cargoEmpleador,
                        correoEmpleador: data.correoEmpleador
                    };
                    //console.log("Datos originales guardados:", datosOriginales);
                    // Llenar los campos del formulario con los datos recuperados
                    llenarCamposFormulario(data);
                    //cambiar el btn de guardar por actualizar
                    cambiarBotonGuardarActualizar();
                }else{
                    console.log("No se encontraron datos existentes para este usuario");
                    // alert("No se encontraron datos previos. Modo de creación activado.")
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                //alert("Error al cargar datos existentes. Ver consola para detalles.");
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Error al cargar datos existentes. Por favor, intente nuevamente.",
                });
            }
        });
    }

    function llenarCamposFormulario(data) {
        $("#centroLaboral").val(data.centroLaboral);
        $("#cargoActual").val(data.cargoActual);
        $("#tiempoLaboral").val(data.tiempoLaboral);
        $("#PaisInformacionLaboral").val(data.PaisInformacionLaboral);
        $("#ciudadInformacionLaboral").val(data.ciudadInformacionLaboral);
        $("#rubroInformacionLaboral").val(data.rubroInformacionLaboral);
        $("#nombresEmpleador").val(data.nombresEmpleador);
        $("#cargoEmpleador").val(data.cargoEmpleador);
        $("#correoEmpleador").val(data.correoEmpleador);
    }

    function configurarEventosCambios() {
        $('input, select, textarea').on('change', function() {
            hayCambios = true;
            $(this).addClass('campo-modificado');
        });
    }

    function configurarModalConfirmacion() {
        $('#confirmarEnvioModal').on('show.bs.modal', function(e) {
            if (!existeRegistro) {
                if (!validarFormularioCompleto()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Formulario Incompleto',
                        text: 'Por favor, complete todos los campos obligatorios antes de guardar.',
                    });
                    return;
                }
            }
            var modal = $(this);
            modal.find('.modal-title').text(existeRegistro ? 'Confirmar Actualización' : 'Confirmar Registro');
            modal.find('.modal-body').html(
                (existeRegistro ?
                    '¿Está seguro de que desea actualizar los datos ingresados?' :
                    '¿Está seguro de que desea registrar los datos ingresados?'
                ) +
                '<br><br>' +
                '<div class="alert alert-warning" role="alert">' +
                '<strong>Importante:</strong> Una vez guardados, los datos no podrán ser modificados directamente.  Si necesita realizar cambios, deberá contactar al administrador del sistema.' +
                '</div>'
            );
            modal.find('#confirmarEnvioBtn').text(existeRegistro ? 'Actualizar Datos' : 'Guardar Datos');
        });

        $('#confirmarEnvioBtn').on('click', function() {
            var formId = $(this).data('form-id');
            //console.log("Enviando formulario:", formId);
            // alert("Formulario enviado para guardar/actualizar.");
            enviarFormulario(formId);
        });
    }

    function validarFormularioCompleto() {
        var camposRequeridos = $('[required]');
        var formularioCompleto = true;

        camposRequeridos.each(function() {
            if (!$(this).val()) {
                formularioCompleto = false;
                return false; // Sale del bucle .each
            }
        });

        return formularioCompleto;
    }


    function enviarFormulario(formId) {
        var formulario = $('#' + formId);
        var formData = new FormData(formulario[0]);

        var esActualizacion = existeRegistro;
        var btnGuardar = $("#confirmarEnvioBtn");


        $.ajax({
            url: formulario.attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                btnGuardar.prop("disabled", true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...'
                );
            },
            success: function(response) {
                try {
                    var jsonResponse = JSON.parse(response);
                    //console.log("Respuesta del servidor:", jsonResponse);
                    // alert("Respuesta del servidor recibida. Ver consola para detalles.");

                    if (jsonResponse.success) {
                        Swal.fire({
                            icon: 'success',
                            title: esActualizacion ? 'Datos Actualizados' : 'Datos Guardados',
                            text: jsonResponse.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(function() {
                            // Redirigir o recargar la página
                            window.location.href = URL + 'Admin/HomeFormulario';
                            // location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: esActualizacion ? 'Error al Actualizar' : 'Error al Guardar',
                            text: jsonResponse.message,
                        });
                    }
                } catch (error) {
                    console.error("Error al parsear la respuesta JSON:", error);
                    // alert("Error al procesar la respuesta del servidor.");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Inesperado',
                        text: 'Ocurrió un error inesperado al procesar la respuesta del servidor.'
                    });
                }
            },
            error: function(xhr, status, error) {
                //alert("Error en la solicitud: " + error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Error en la solicitud: " + error,
                });
                btnGuardar.prop("disabled", false).html(
                    esActualizacion ? "Actualizar Cambios" : "Guardar Datos"
                )
                console.error("Error en AJAX:", error);
            }
        });
    }

    function cambiarBotonGuardarActualizar() {
        $('.btn-guardar').html('<i class="fa-solid fa-arrows-rotate"></i> Actualizar Datos');
    }
});
