$(document).ready(function() {
    //console.log("Documento listo - Iniciando script de sección 1");
    // alert("Script de sección 1 cargado correctamente");

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

    // Cargar los datos del usuario
    cargarDatosUsuario();

    // Cargar datos existentes
    cargarDatosIniciales();

    // Configurar eventos para detectar cambios
    configurarEventosCambios();

    // Configurar el modal de confirmación
    configurarModalConfirmacion();

    //*funcion para cargar los datos del usuario
    function cargarDatosUsuario(){
        $.ajax({
            url: URL + "DatosRecuperar/getDatosUsuario", // Nueva ruta en el controlador
            dataType: "json",
            method: "GET", // O POST, según tu configuración
            success: function(response) {
                if (response.status === 'success') {
                    var userData = response.data;
                    // Llenar los campos del formulario con los datos del usuario
                    $("#nombresCompletos").val(userData.nombre);
                    $("#apellido1").val(userData.apellido_paterno);
                    $("#apellido2").val(userData.apellido_materno);
                    $("#correoElectronico").val(userData.email);
                } else {
                    console.error("Error al cargar datos del usuario:", response.message);
                    // Posiblemente mostrar un mensaje al usuario
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", {
                status: textStatus,
                error: errorThrown,
                response: jqXHR.responseText
                });
            }
        });
    }

    // Función para cargar los datos iniciales
    function cargarDatosIniciales() {
        //console.log("Iniciando carga de datos iniciales para userId:", userId);
        $.ajax({
            url: URL + "DatosRecuperar/recuperarDatosSeccion1",
            dataType: "json",
            method: "POST",
            data: { userId: userId },
            success: function(data) {
                //console.log("Datos recibidos del servidor:", data);
                // alert("Datos recibidos del servidor. Ver consola para detalles.");
                
                // Verificar si hay datos existentes
                if (data && data.apellido1) {
                    //console.log("Datos existentes encontrados");
                    existeRegistro = true;
                    
                    // Guardar datos originales para comparación
                    datosOriginales = {
                        apellido1: data.apellido1 || '',
                        apellido2: data.apellido2 || '',
                        nombresCompletos: data.nombresCompletos || '',
                        tipoIdentidad: data.tipoIdentidad || '',
                        numDoc: data.numDoc || '',
                        nationality: data.nationality || '',
                        fechaNacimiento: data.fechaNacimiento || '',
                        estadoCivil: data.estadoCivil || '',
                        correoElectronico: data.correoElectronico || '',
                        phoneCode: data.phoneCode || '',
                        celular: data.celular || '',
                        redProfesional: data.redProfesional || '',
                        tipoDireccion: data.tipoDireccion || '',
                        direccion: data.direccion || '',
                        numeroDireccion: data.numeroDireccion || '',
                        PaisDatoDominicial: data.PaisDatoDominicial || '',
                        PaisDatoDominicialRegion: data.PaisDatoDominicialRegion || '',
                        provinciaDatoDominicial: data.provinciaDatoDominicial || '',
                        distritoDatoDominicial: data.distritoDatoDominicial || '',
                        referenciaDomicilio: data.referenciaDomicilio || ''
                    };
                    
                    //console.log("Datos originales guardados:", datosOriginales);
                    // alert("Datos originales almacenados para comparación");

                    // Llenar el formulario con los datos
                    llenarFormulario(data);
                    
                    // Cambiar el botón a "Actualizar"
                    cambiarBotonActualizacion();
                } else {
                    console.log("No se encontraron datos existentes para este usuario");
                    // alert("No se encontraron datos previos. Modo de creación activado.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", {
                    status: textStatus,
                    error: errorThrown,
                    response: jqXHR.responseText
                });
                //alert("Error al cargar datos existentes. Ver consola para detalles.");
            }
        });
    }

    // Función para llenar el formulario con datos
    function llenarFormulario(data) {
        //console.log("Llenando formulario con datos:", data);
        
        // Datos personales
        $("#apellido1").val(data.apellido1);
        $("#apellido2").val(data.apellido2);
        $("#nombresCompletos").val(data.nombresCompletos);
        $("#tipoIdentidad").val(data.tipoIdentidad);
        $("#numDoc").val(data.numDoc);
        $("#nationality").val(data.nationality);
        $("#fechaNacimiento").val(data.fechaNacimiento);
        $("#estadoCivil").val(data.estadoCivil);
        // Datos de contacto
        $("#correoElectronico").val(data.correoElectronico);
        $("#phoneCode").val(data.phoneCode);
        $("#phoneNumber").val(data.celular);
        $("#basic-url").val(data.redProfesional);
        // Datos domiciliarios
        $("#tipoDireccion").val(data.tipoDireccion);
        $("#direccion").val(data.direccion);
        $("#numeroDireccion").val(data.numeroDireccion);
        $("#PaisDatoDominicial").val(data.PaisDatoDominicial);
        $("#PaisDatoDominicialRegion").val(data.PaisDatoDominicialRegion);
        $("#provinciaDatoDominicial").val(data.provinciaDatoDominicial);
        $("#distritoDatoDominicial").val(data.distritoDatoDominicial);
        $("#referenciaDomicilio").val(data.referenciaDomicilio);
        //console.log("Formulario llenado exitosamente");
        // alert("Formulario poblado exitosamente con datos cargados.");
    }

    // Función para configurar eventos de cambio en los inputs
    function configurarEventosCambios() {
        $('input, select, textarea').on('change', function() {
            hayCambios = true;
            $(this).addClass('campo-modificado');
        });
    }

    // Función para configurar el modal de confirmación
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
            
            // Si existe registro o el formulario está completo para el primer registro, procede con el modal
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
            
            // Aquí se llama a la función para enviar el formulario
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


    // Función para enviar el formulario (guardar/actualizar)
    function enviarFormulario(formId) {
        var formulario = $('#' + formId);
        var formData = new FormData(formulario[0]);

        $.ajax({
            url: formulario.attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                try {
                    var jsonResponse = JSON.parse(response);
                    //console.log("Respuesta del servidor:", jsonResponse);
                    // alert("Respuesta del servidor recibida. Ver consola para detalles.");
                    
                    if (jsonResponse.success) {
                        Swal.fire({
                            icon: 'success',
                            title: existeRegistro ? 'Datos Actualizados' : 'Datos Guardados',
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
                            title: existeRegistro ? 'Error al Actualizar' : 'Error al Guardar',
                            text: jsonResponse.message
                        });
                    }
                } catch (e) {
                    console.error("Error al parsear la respuesta JSON:", e);
                    // alert("Error al procesar la respuesta del servidor.");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Inesperado',
                        text: 'Ocurrió un error inesperado al procesar la respuesta del servidor.'
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", {
                    status: textStatus,
                    error: errorThrown,
                    response: jqXHR.responseText
                });
                // alert("Error al comunicarse con el servidor. Ver consola para detalles.");
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'Ocurrió un error al comunicarse con el servidor. Por favor, intente nuevamente.'
                });
            }
        });
    }

    function cambiarBotonActualizacion() {
        $('.btn-guardar').html('<i class="fa-solid fa-arrows-rotate"></i> Actualizar Datos');
    }
});
