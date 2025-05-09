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
        const tabla = fila.closest("table");
        const tipo = fila.find("td:first").text();
        
        // Lógica de tipos especiales (de tu función eliminarFila)
        if (tipo === "Pregrado") tipoPregradoAgregado = false;
        if (tipo === "Posdoctorado") tipoPosdoctoradoAgregado = false;

        // Eliminar archivo asociado si existe
        // if (tabla.attr("id") === "tablaFormacion") {
        //     const inputOculto = fila.find("input[type='hidden'][name*='pdf']");
        //     if (inputOculto.length) {
        //         // Eliminar de tu array de anexos
        //         anexosTablasFormacionAcademica = anexosTablasFormacionAcademica.filter(
        //             anexo => anexo.file.name !== inputOculto.val()
        //         );
        //         // Actualizar contador si es necesario
        //         contadorFormacion--;
        //     }
        // }
        
        fila.remove();
        verificarCambios();
        
        // if (tabla.find("tr").length <= 1) {
        //     tabla.find("tbody").html('<tr><td colspan="100%" class="text-center">No hay registros</td></tr>');
        // }
    };

    function cargarDatosIniciales() {
        let promesas = [
            recuperarDatosSeccion5(),
        ];

        Promise.all(promesas)
        .then(resultados => { // 'resultados' será un array de booleanos (true si hay datos, false si no)
            console.log("Resultados de la carga inicial:", resultados);
            existeRegistro = resultados.some(resultado => resultado);
            cambiarBotonGuardarActualizar();
            verificarCambios();
        })
        .catch(error => {
            console.error("Error al cargar datos iniciales:", error);
        });
    }

    function cambiarBotonGuardarActualizar() {
        const btnGuardar = $(".btn-guardar");
        if (existeRegistro || verificarSiHayDatosEnFormulario()) {
            console.log("Cambiando botón a modo actualizar");
            btnGuardar
                .removeClass("btn-success")
                .addClass("btn-warning")
                .html('<i class="fa-solid fa-rotate"></i> Actualizar Cambios')
                .attr("id", "btnActualizar");
        } else {
            console.log("Cambiando botón a modo guardar");
            btnGuardar
                .removeClass("btn-warning")
                .addClass("btn-success")
                .html('<i class="fa-solid fa-save"></i> Guardar Datos')
                .attr("id", "btnGuardar");
        }
        $("#btnActualizar").prop("disabled", !hayCambios);
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
        // Verificar cada tabla para ver si tiene datos
        const tablas = [
            "#tablaInvestigaciones",
        ];
        
        return tablas.some(selector => {
            const tabla = $(selector);
            // Verificar si la tabla existe y tiene filas con datos (excluyendo la fila "No hay registros")
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
            (verificarTablasConDatos() && registrosTablas.investigaciones.length > 0);
    
        $("#btnActualizar").prop("disabled", false); // Siempre habilitado para actualización
        
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
            // if (esActualizacion) {
            //     // obtenerDatosFormulario()
            //     // console.log("Datos que se enviarán para actualización:", obtenerDatosFormulario());
            // }
        });

        $("#confirmarEnvioBtn").click(function() {
            
            // if (!verificarTablasConDatos()) {
            //     alert("Debe agregar al menos un registro en alguna de las tablas.");
            //     $(this).prop("disabled", false);
            //     return;
            // }

            const esActualizacion = $(this).data("es-actualizacion");
            const formData = new FormData($("#formulario_seccion5")[0]);
            
            formData.append('userId', userId);
            formData.append('esActualizacion', esActualizacion);
            
            console.log("Preparando envío de datos...");

            recolectarDatosTablasInvestigaciones(formData, existeRegistro);


            $.ajax({
                url: esActualizacion ? 
                    URL + "Formulario/actualizarSeccion5" : 
                    URL + "Formulario/enviarSeccion5",
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
                            //alert(responseData.message);
                            // if (responseData.debug) {
                            //     console.log("Debug del servidor:", responseData.debug);
                            // }
                            Swal.fire({
                                icon: 'success',
                                title: '¡Guardado!',
                                text: responseData.message || (esActualizacion ? 'Datos actualizados correctamente.' : 'Datos guardados correctamente.'),
                                confirmButtonText: 'Aceptar',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.href = URL + "Admin/HomeFormulario";
                                }
                            });
                            
                        } else {
                            throw new Error(responseData.message || "Error desconocido");
                        }
                    } catch (e) {
                        console.error("Error al procesar respuesta:", e);
                        //alert("Error: " + e.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "Error al procesar la respuesta del servidor.",
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en AJAX:", status, error);
                    console.error("Respuesta del servidor:", xhr.responseText);
                    //alert("Error en la solicitud. Ver consola para detalles.");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "Error en la solicitud. Ver consola para detalles.",
                    });
                },
                complete: () => {
                    $(this).prop("disabled", false)
                        .html(esActualizacion ? "Actualizar Cambios" : "Guardar Datos");
                }
            });
        });
    }

    // Inicialización
    cargarDatosIniciales();
    configurarEventosCambios();
    configurarModalConfirmacion();
});

// FUNCIONES DE RECUPERACIÓN DE DATOS 
function recuperarDatosSeccion5(){
    var userId = $("#userId").val();
    
    if (!userId) {
        alert("El ID de usuario no está definido.");
        return;
    }

    return new Promise(( resolve, reject) => {
        $.ajax({
            url: URL + "DatosRecuperar/recuperarDatosSeccion5",
            type: "POST",
            data: { userId: userId },
            dataType: "json",
            success: function(data) {
                //console.log("Datos recibidos del servidor de investigaciones:", data);
                
                existeRegistro = data && data.length > 0;
                
                if (data.length > 0) {
                    var tablaBody = $("#tablaInvestigaciones");
                    tablaBody.empty();
                    registrosTablas.investigaciones = data || [];
    
                    // Recorre cada registro y crea una fila en la tabla
                    $.each(data, function(index, registro) {
                        //console.log(`Registro formación cargado - ID: ${registro.id}, Tipo: ${registro.fecha_publicacion}`);
                        var fila = `
                            <tr data-id="${registro.id || 'new'}">
                                <td>${registro.fecha_publicacion || 'N/A'}</td>
                                <td>${registro.revista_congreso || 'N/A'}</td>
                                <td>${registro.base_datos || 'N/A'}</td>
                                <td>${registro.nombre_investigacion || 'N/A'}</td>
                                <td>${registro.autores || 'N/A'}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        tablaBody.append(fila);
                    });
                    resolve(data.length > 0); // Resuelve con true si hay datos, false si no
                } else {
                    resolve(false); // Resuelve con false si no hay datos
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar los datos:", error);
                //alert("Error al cargar los datos. Ver consola para más detalles.");
            }
        });
    })
}