$(document).ready(function() {
    console.log("Documento listo - Iniciando script de sección 1");
    // alert("Script de sección 1 cargado correctamente");

    // Variables globales
    var userId = $("#userId").val();
    console.log("ID de usuario obtenido:", userId);
    
    var datosOriginales = {};
    var existeRegistro = false;
    var hayCambios = false;

    if (!userId) {
        alert("Error: El ID de usuario no está definido.");
        console.error("El ID de usuario no está definido");
        return;
    }

    // Cargar datos existentes
    cargarDatosIniciales();

    // Configurar eventos para detectar cambios
    configurarEventosCambios();

    // Configurar el modal de confirmación
    configurarModalConfirmacion();

    // Función para cargar los datos iniciales
    function cargarDatosIniciales() {
        console.log("Iniciando carga de datos iniciales para userId:", userId);
        
        $.ajax({
            url: URL + "DatosRecuperar/recuperarDatosSeccion1",
            dataType: "json",
            method: "POST",
            data: { userId: userId },
            success: function(data) {
                console.log("Datos recibidos del servidor:", data);
                // alert("Datos recibidos del servidor. Ver consola para detalles.");
                
                // Verificar si hay datos existentes
                if (data && data.apellido1) {
                    console.log("Datos existentes encontrados");
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
                    
                    console.log("Datos originales guardados:", datosOriginales);
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
                alert("Error al cargar datos existentes. Ver consola para detalles.");
            }
        });
    }

    // Función para llenar el formulario con datos
    function llenarFormulario(data) {
        console.log("Llenando formulario con datos:", data);
        
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
        
        console.log("Formulario llenado exitosamente");
        // alert("Formulario poblado con datos existentes");
    }

    // Función para cambiar el botón a modo actualización
    function cambiarBotonActualizacion() {
        console.log("Cambiando botón a modo actualización");
        
        $(".btn-guardar")
            .removeClass("btn-success")
            .addClass("btn-warning")
            .html('<i class="fa-solid fa-rotate"></i> Actualizar Cambios')
            .attr("id", "btnActualizar");
            
        console.log("Botón actualizado a modo 'Actualizar Cambios'");
    }

    // Función para configurar eventos de cambios
    function configurarEventosCambios() {
        console.log("Configurando eventos para detectar cambios");
        
        $("input, select, textarea").on("change input", function() {
            console.log("Cambio detectado en campo:", $(this).attr("id"));
            verificarCambios();
        });

        // Para campos de archivo
        $("input[type='file']").on("change", function() {
            console.log("Cambio en archivo:", $(this).attr("id"));
            verificarCambios();
        });
        
        console.log("Eventos de cambio configurados correctamente");
    }

    // Función para verificar cambios
    function verificarCambios() {
        if (!existeRegistro) {
            console.log("No hay registro existente, no se verifican cambios");
            return false;
        }

        console.log("Verificando cambios en el formulario...");
        hayCambios = false;
        
        // Verificar cambios en campos normales
        $("input, select, textarea").not("[type='file']").each(function() {
            const id = $(this).attr("id");
            if (datosOriginales[id] !== undefined) {
                const valorOriginal = datosOriginales[id] || '';
                const valorActual = $(this).val() || '';
                
                // Comparación más robusta que ignora espacios en blanco y convierte números
                if (String(valorOriginal).trim() !== String(valorActual).trim()) {
                    console.log(`Campo modificado: ${id} - Valor original: '${valorOriginal}' - Nuevo valor: '${valorActual}'`);
                    hayCambios = true;
                    $(this).addClass("campo-modificado");
                } else {
                    $(this).removeClass("campo-modificado");
                }
            }
        });

        // Verificar cambios en archivos
        $("input[type='file']").each(function() {
            if ($(this)[0].files.length > 0) {
                console.log(`Archivo nuevo seleccionado en: ${$(this).attr("id")}`);
                hayCambios = true;
            }
        });

        // Habilitar/deshabilitar botón según haya cambios
        if (existeRegistro) {
            console.log(`Hay cambios detectados: ${hayCambios}`);
            $("#btnActualizar").prop("disabled", !hayCambios);
            
            if (hayCambios) {
                console.log("Cambios detectados - Botón de actualización habilitado");
                // alert("Se detectaron cambios en el formulario. Puede proceder a actualizar.");
            } else {
                console.log("No hay cambios detectados - Botón de actualización deshabilitado");
            }
        }

        return hayCambios;
    }

    // Función para configurar el modal de confirmación
    function configurarModalConfirmacion() {
        console.log("Configurando modal de confirmación");
        
        $(document).on("click", ".btn-guardar, #btnActualizar", function() {
            const esActualizacion = $(this).attr("id") === "btnActualizar";
            const mensaje = esActualizacion ? 
                "¿Estás seguro de que deseas actualizar los datos de esta sección?" : 
                "¿Estás seguro de que deseas guardar los datos por primera vez?";
            
            console.log(`Mostrando modal de confirmación para ${esActualizacion ? 'actualización' : 'creación'}`);
            
            $("#modalMessage").text(mensaje);
            $("#confirmarEnvioBtn")
                .text(esActualizacion ? "Actualizar Cambios" : "Guardar Datos")
                .data("es-actualizacion", esActualizacion);
                
            // Mostrar datos que se enviarán
            if (esActualizacion) {
                console.log("Datos que se enviarán para actualización:", obtenerDatosFormulario());
            }
        });

        // Manejar confirmación en el modal
        $("#confirmarEnvioBtn").click(function() {
            const form = $("#formulario_seccion1")[0];
            const formData = new FormData(form);
            const esActualizacion = $(this).data("es-actualizacion");
            
            // Agregar el userId al FormData
            formData.append('userId', userId);
            
            console.log(`Confirmado ${esActualizacion ? 'actualización' : 'creación'} de datos`);
            
            // Deshabilitar botón y mostrar spinner
            $(this).prop("disabled", true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...'
            );

            // Determinar la URL adecuada
            const url = esActualizacion ? URL + "Formulario/actualizarSeccion1" : URL + "Formulario/enviarSeccion1";
            
            // Enviar con AJAX
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        var responseData = typeof response === 'string' ? JSON.parse(response) : response;
                        console.log("Respuesta del servidor al guardar/actualizar:", responseData);
                        
                        if (responseData.status === 'success') {
                            // alert(responseData.message);
                            console.log(responseData.message);
                            location.href = URL + "Admin/HomeFormulario";
                        } else {
                            alert("Error: " + responseData.message);
                            $("#confirmarEnvioBtn").prop("disabled", false).html(
                                esActualizacion ? "Actualizar Cambios" : "Guardar Datos"
                            );
                        }
                    } catch (e) {
                        console.error("Error parsing response:", e.message, e.stack);
                        alert(`Error procesando la respuesta del servidor:\n${e.message}\n${e.stack}`);
                    }
                },
                error: function(xhr, status, error) {
                    alert("Error en la solicitud: " + error);
                    $("#confirmarEnvioBtn").prop("disabled", false).html(
                        esActualizacion ? "Actualizar Cambios" : "Guardar Datos"
                    );
                    console.error("Error en AJAX:", error);
                }
            });
        });
        console.log("Modal de confirmación configurado correctamente");
    }

    // Función auxiliar para obtener datos del formulario
    function obtenerDatosFormulario() {
        const datos = {
            apellido1: $("#apellido1").val(),
            apellido2: $("#apellido2").val(),
            nombresCompletos: $("#nombresCompletos").val(),
            tipoIdentidad: $("#tipoIdentidad").val(),
            numDoc: $("#numDoc").val(),
            nationality: $("#nationality").val(),
            fechaNacimiento: $("#fechaNacimiento").val(),
            estadoCivil: $("#estadoCivil").val(),
            correoElectronico: $("#correoElectronico").val(),
            phoneCode: $("#phoneCode").val(),
            celular: $("#phoneNumber").val(),
            redProfesional: $("#basic-url").val(),
            tipoDireccion: $("#tipoDireccion").val(),
            direccion: $("#direccion").val(),
            numeroDireccion: $("#numeroDireccion").val(),
            PaisDatoDominicial: $("#PaisDatoDominicial").val(),
            PaisDatoDominicialRegion: $("#PaisDatoDominicialRegion").val(),
            provinciaDatoDominicial: $("#provinciaDatoDominicial").val(),
            distritoDatoDominicial: $("#distritoDatoDominicial").val(),
            referenciaDomicilio: $("#referenciaDomicilio").val()
        };
        
        // Agregar información de archivos
        if ($("#fotoPerfil")[0].files.length > 0) {
            datos.fotoPerfil = "Nuevo archivo seleccionado";
        }
        
        if ($("#pdfDocumentoIdentidad")[0].files.length > 0) {
            datos.pdfDocumentoIdentidad = "Nuevo archivo seleccionado";
        }
        
        return datos;
    }
});