
function recolectarDatosTablasInvestigaciones(formData, existeRegistro) {
    const esActualizacion = existeRegistro;  

    formData.append('esActualizacion', esActualizacion);

    const nuevosDatos = {
        Investigaciones: [],
    };

    // Objeto para almacenar IDs eliminados
    const eliminados = {
        Investigaciones: [],
    };

    if (esActualizacion) {
        console.log("Identificando registros eliminados...");
        
        // Para formación académica
        const idsInvestigacionesOriginales = registrosTablas.investigaciones.map(item => item.id);
        const idsInvestigacionesActuales = $("#tablaInvestigaciones tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);
        
        eliminados.Investigaciones = idsInvestigacionesOriginales.filter(id => !idsInvestigacionesActuales.includes(id));
        console.log("Formación académica eliminada:", eliminados.Investigaciones);
    }

    // **Recolectar datos de la tabla dinámica de investigaciones
    $("#tablaInvestigaciones tr").each(function (index){
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')) {
            console.log(`Recolectando datos de la fila ${index + 1} de la tabla de investigaciones...`);
            const data = {
                fechaPublicacion: row.find("td:eq(0)").text(),
                revistaCongreso: row.find("td:eq(1)").text(),
                baseDatos: row.find("td:eq(2)").text(),
                nombreInvestigacion: row.find("td:eq(3)").text(),
                autores: row.find("td:eq(4)").text(),
                
            }

            for (const key in data) {
                formData.append(`Investigaciones[${index}][${key}]`, data[key]);
            }
        }
    });

    if (esActualizacion) {
        formData.append('eliminados', JSON.stringify(eliminados));
        console.log("Datos de eliminados preparados:", eliminados);
    }

    formData.append('nuevos', JSON.stringify(nuevosDatos));
    console.log("Datos nuevos preparados:", nuevosDatos);


    console.log("Datos a enviar:", {
        eliminados: eliminados,
        nuevos: nuevosDatos
    });
}

