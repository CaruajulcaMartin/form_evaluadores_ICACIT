$(document).ready(function() {
    console.log("Documento listo - Iniciando script de sección 2");
    // alert("Script de sección 2 cargado correctamente");

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


    function cargarDatosIniciales() {
        console.log("Cargando datos iniciales para la sección 2");

        $.ajax({
            url: URL + "DatosRecuperar/recuperarDatosSeccion2",
            dataType: "json",
            method: "POST",
            data: { userId: userId },
            success: function(data) {
                console.log("Datos recuperados:", data);

                if(data && data.centroLaboral){
                    console.log("Datos existentes encontrados");
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

                    console.log("Datos originales guardados:", datosOriginales);
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
                alert("Error de conexión con el servidor.");
            }
        });
    }

    
    function llenarCamposFormulario(data) {
        console.log("Llenando campos del formulario con datos recuperados" + data);
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

    function cambiarBotonGuardarActualizar() {
        console.log("Cambiando botón a modo actualización");
        
        $(".btn-guardar")
            .removeClass("btn-success")
            .addClass("btn-warning")
            .html('<i class="fa-solid fa-rotate"></i> Actualizar Cambios')
            .attr("id", "btnActualizar");
            
        console.log("Botón actualizado a modo 'Actualizar Cambios'");
    }

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
                obtenerDatosFormulario()
                // console.log("Datos que se enviarán para actualización:", obtenerDatosFormulario());
            }
        });

        // Manejar confirmación en el modal
        $("#confirmarEnvioBtn").click(function() {
            const form = $("#formulario_seccion2")[0];
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
            const url = esActualizacion ? URL + "Formulario/actualizarSeccion2" : URL + "Formulario/enviarSeccion2";
            
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

    function obtenerDatosFormulario() {
        const datos = {
            centroLaboral: $("#centroLaboral").val(),
            cargoActual: $("#cargoActual").val(),
            tiempoLaboral: $("#tiempoLaboral").val(),
            PaisInformacionLaboral: $("#PaisInformacionLaboral").val(),
            ciudadInformacionLaboral: $("#ciudadInformacionLaboral").val(),
            rubroInformacionLaboral: $("#rubroInformacionLaboral").val(),
            nombresEmpleador: $("#nombresEmpleador").val(),
            cargoEmpleador: $("#cargoEmpleador").val(),
            correoEmpleador: $("#correoEmpleador").val()
        };
        console.log("Datos del formulario obtenidos:", datos);
        return datos;
    }


});