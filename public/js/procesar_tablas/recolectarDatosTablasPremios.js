
function recolectarDatosTablasPremios(formData, existeRegistro) {
    const esActualizacion = existeRegistro;  

    formData.append('esActualizacion', esActualizacion);

    const nuevosDatos = {
        Premios: [],
    };

    // Objeto para almacenar IDs eliminados
    const eliminados = {
        Premios: [],
    };

    if (esActualizacion) {
        console.log("Identificando registros eliminados...");
        
        // Para formación académica
        const idsPremiosOriginales = registrosTablas.premios.map(item => item.id);
        const idsPremiosActuales = $("#tablaPremios tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);
        
        eliminados.Premios = idsPremiosOriginales.filter(id => !idsPremiosActuales.includes(id));
        console.log("Formación académica eliminada:", eliminados.Premios);
    }

    // **Recolectar datos de la tabla dinámica de premios
    $("#tablaPremios tr").each(function (index) {
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')) {
            console.log(`Recolectando datos de la fila ${index + 1} de la tabla de premios...`);
            const data = {
                anoReconocimiento: row.find("td:eq(0)").text(),
                institucionReconocimiento: row.find("td:eq(1)").text(),
                nombreReconocimiento: row.find("td:eq(2)").text(),
                descripcionReconocimiento: row.find("td:eq(3)").text(),
            }

            for (const key in data) {
                formData.append(`Premios[${index}][${key}]`, data[key]);
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

