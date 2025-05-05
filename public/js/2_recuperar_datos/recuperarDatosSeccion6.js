$(document).ready(function() {
    var userId = $("#userId").val();
    var datosOriginales = {};
    var existeRegistro = false;
    var hayCambios = false;

    // Inicialización
    if (!userId) {
        alert("Error: El ID de usuario no está definido.");
        return;
    }

    window.eliminarFila = function(boton) {
        const fila = $(boton).closest("tr");
        fila.remove();
        verificarCambios();
    };

    function cambiarBotonGuardarActualizar() {
        console.log("Cambiando botón a modo actualización");
        $(".btn-guardar")
            .removeClass("btn-success")
            .addClass("btn-warning")
            .html('<i class="fa-solid fa-rotate"></i> Actualizar Cambios')
            .attr("id", "btnActualizar");
    }

    function configurarEventosCambios() {
        console.log("Configurando eventos para detectar cambios");
        $("input, select, textarea").on("change input", function() {
            verificarCambios();
        });

        $("input[type='file']").on("change", function() {
            verificarCambios();
        });
    }

    function verificarTablasConDatos() {
        const tablas = ["#tablaPremios"];
        return tablas.some(selector => {
            const tabla = $(selector);
            return tabla.length && tabla.find("tr").length > 1 || 
                (tabla.find("tr").length === 1 && !tabla.find("tr td").hasClass("text-center"));
        });
    }

    function verificarCambios() {
        if (!existeRegistro) {
            hayCambios = verificarTablasConDatos();
            $("#btnGuardar").prop("disabled", !hayCambios);
            return hayCambios;
        }
    
        hayCambios = false;
        
        // Verificar cambios en campos normales
        $("input, select, textarea").not("[type='file']").each(function() {
            const id = $(this).attr("id");
            if (datosOriginales[id] !== undefined) {
                const valorOriginal = datosOriginales[id] || '';
                const valorActual = $(this).val() || '';
                
                if (String(valorOriginal).trim() !== String(valorActual).trim()) {
                    hayCambios = true;
                    $(this).addClass("campo-modificado");
                } else {
                    $(this).removeClass("campo-modificado");
                }
            }
        });
    
        // Siempre habilitar el botón si es actualización
        // pero marcar cambios si hay archivos nuevos o diferencias en tablas
        hayCambios = hayCambios || 
            $("input[type='file']").toArray().some(input => input.files.length > 0) ||
            (verificarTablasConDatos() && registrosTablas.premios.length > 0);
    
        $("#btnActualizar").prop("disabled", false); // Siempre habilitado para actualización
        
        return hayCambios;
    }

    function configurarModalConfirmacion() {
        $(document).on("click", ".btn-guardar, #btnActualizar", function() {
            const esActualizacion = $(this).attr("id") === "btnActualizar";
            $("#modalMessage").text(esActualizacion ? 
                "¿Estás seguro de que deseas actualizar los datos de esta sección?" : 
                "¿Estás seguro de que deseas guardar los datos por primera vez?");
            $("#confirmarEnvioBtn")
                .text(esActualizacion ? "Actualizar Cambios" : "Guardar Datos")
                .data("es-actualizacion", esActualizacion);
        });

        $("#confirmarEnvioBtn").click(function() {
            const esActualizacion = $(this).data("es-actualizacion");
            const formData = new FormData($("#formulario_seccion6")[0]);
            
            formData.append('userId', userId);
            formData.append('esActualizacion', esActualizacion);
            
            console.log("Preparando envío de datos...");
            recolectarDatosTablasPremios(formData, existeRegistro);

            $.ajax({
                url: esActualizacion ? 
                    URL + "Formulario/actualizarSeccion6" : 
                    URL + "Formulario/enviarSeccion6",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    console.log("Enviando datos al servidor...");
                    $(this).prop("disabled", true)
                        .html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
                },
                success: function(response) {
                    console.log("Respuesta del servidor:", response);
                    try {
                        const responseData = typeof response === 'string' ? 
                            JSON.parse(response) : response;
                        
                        if (responseData.status === 'success') {
                            // alert(responseData.message);
                            location.href = URL + "Admin/HomeFormulario";
                        } else {
                            throw new Error(responseData.message || "Error desconocido");
                        }
                    } catch (e) {
                        console.error("Error al procesar respuesta:", e);
                        alert("Error: " + e.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en AJAX:", status, error);
                    alert("Error en la solicitud. Ver consola para detalles.");
                },
                complete: () => {
                    $(this).prop("disabled", false)
                        .html(esActualizacion ? "Actualizar Cambios" : "Guardar Datos");
                }
            });
        });
    }

    // Función principal para cargar datos
    function cargarDatosIniciales() {
        console.log("Cargando datos iniciales...");
        
        return new Promise((resolve) => {
            $.ajax({
                url: URL + "DatosRecuperar/recuperarDatosSeccion6",
                type: "POST",
                data: { userId: userId },
                dataType: "json",
                success: function(data) {
                    // Determinar si existen registros
                    existeRegistro = data && data.length > 0;
                    
                    var tablaBody = $("#tablaPremios");
                    tablaBody.empty();

                    if (existeRegistro) {
                        console.log("Existen registros previos, cargando datos...");
                        registrosTablas.premios = data || [];

                        $.each(data, function(index, registro) {
                            var fila = `
                                <tr data-id="${registro.id || 'new'}">
                                    <td>${registro.ano || 'N/A'}</td>
                                    <td>${registro.institucion_empresa || 'N/A'}</td>
                                    <td>${registro.nombre_reconocimiento || 'N/A'}</td>
                                    <td>${registro.descripcion_reconocimiento || 'N/A'}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            tablaBody.append(fila);
                        });
                        
                        cambiarBotonGuardarActualizar();
                    }
                    
                    verificarCambios();
                    resolve();
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar los datos:", error);
                    alert("Error al cargar los datos. Ver consola para más detalles.");
                    resolve();
                }
            });
        });
    }

    // Inicialización
    cargarDatosIniciales().then(() => {
        configurarEventosCambios();
        configurarModalConfirmacion();
    });
});