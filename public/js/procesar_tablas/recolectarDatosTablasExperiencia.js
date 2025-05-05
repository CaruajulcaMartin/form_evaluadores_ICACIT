
function recolectarDatosTablasExperiencia(formData, existeRegistro) {
    const esActualizacion = existeRegistro;

    formData.append('esActualizacion', esActualizacion);

    const nuevosDatos = {
        experienciaLaboral: [],
        experienciaDocente: [],
        experienciaComite: [],
        experienciaEvaluador: [],
        membresias: [],
    };

    // Objeto para almacenar IDs eliminados
    const eliminados = {
        experienciaLaboral: [],
        experienciaDocente: [],
        experienciaComite: [],
        experienciaEvaluador: [],
        membresias: [],
    };

    if (esActualizacion){
        console.log("Identificando registros eliminados...");

        // Para experiencia laboral
        const idsExperienciaLaboralOriginales = (registrosTablas.experienciaLaboral || []).map(item => item.id);
        const idsExperienciaLaboralActuales = $("#tablaExperiencia tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);
        eliminados.experienciaLaboral = idsExperienciaLaboralOriginales.filter(id => !idsExperienciaLaboralActuales.includes(id));
        console.log("Experiencia laboral eliminada:", eliminados.experienciaLaboral);

        // Para experiencia docente
        const idsExperienciaDocenteOriginales = (registrosTablas.experienciaDocente || []).map(item => item.id);
        const idsExperienciaDocenteActuales = $("#tablaExperienciaDocente tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);
        eliminados.experienciaDocente = idsExperienciaDocenteOriginales.filter(id => !idsExperienciaDocenteActuales.includes(id));
        console.log("Experiencia docente eliminada:", eliminados.experienciaDocente);

        //? Para experiencia comite
        const idsExperienciaComiteOriginales = (registrosTablas.experienciaComite || []).map(item => item.id);
        const idsExperienciaComiteActuales = $("#tablaExperienciaComite tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);
        eliminados.experienciaComite = idsExperienciaComiteOriginales.filter(id => !idsExperienciaComiteActuales.includes(id));
        console.log("Experiencia comite eliminada:", eliminados.experienciaComite);

        // Para experiencia evaluador
        const idsExperienciaEvaluadorOriginales = (registrosTablas.experienciaEvaluador || []).map(item => item.id);
        const idsExperienciaEvaluadorActuales = $("#tablaExperienciaEvaluador tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);
        eliminados.experienciaEvaluador = idsExperienciaEvaluadorOriginales.filter(id => !idsExperienciaEvaluadorActuales.includes(id));
        console.log("Experiencia evaluador eliminada:", eliminados.experienciaEvaluador);

        // Para membresias
        const idsMembresiasOriginales = (registrosTablas.membresias || []).map(item => item.id);
        const idsMembresiasActuales = $("#tablaMembresias tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);
        eliminados.membresias = idsMembresiasOriginales.filter(id => !idsMembresiasActuales.includes(id));
        console.log("Membresias eliminada:", eliminados.membresias);
    }

    //nuevos registros
    console.log("Identificando nuevos registros...");

    // **Recolectar datos de la tabla dinámica de experiencia laboral
    $("#tablaExperiencia tr").each(function (index) {
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')){
            // console.log(`Recolectando datos de la fila ${index + 1} de la tabla de experiencia comite`);
            const data = {
                institucion_empresa: row.find("td:eq(0)").text() || '',
                cargo_desempenado: row.find("td:eq(1)").text() || '',
                fecha_inicio: row.find("td:eq(2)").text() || '',
                fecha_retiro: row.find("td:eq(3)").text() || '',
                pais: row.find("td:eq(4)").text() || '',
                ciudad: row.find("td:eq(5)").text() || '',
            };
    
            for (const key in data) {
                formData.append(`experienciaLaboral[${index}][${key}]`, data[key]);
            }

            const inputOculto = row.find("input[type='hidden'][name^='experienciaLaboral']");
            if (inputOculto.length > 0) {
                const archivoNombre = inputOculto.val();
                const archivo = anexosTablasExperienciaLaboral.find(anexo => anexo.file.name === archivoNombre)?.file;
                
                if (archivo) {
                    formData.append(`experienciaLaboral[${index}][pdf]`, archivo);
                }
            }

            nuevosDatos.experienciaLaboral.push(data);
            console.log("Nuevo registro formación:", data);
        }
    });


    // **Recolectar datos de la tabla de experiencia docente
    $("#tablaExperienciaDocente tr").each(function (index) {
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')){
            console.log(`Recolectando datos de la fila ${index + 1} de la tabla de experiencia docente`);
    
            // Recolectar datos de las celdas
            const data = {
                institucion_empresa: row.find("td:eq(0)").text().trim(),
                pais: row.find("td:eq(1)").text().trim(),
                ciudad: row.find("td:eq(2)").text().trim(),
                programa_profesional: row.find("td:eq(3)").text().trim(),
                curso_capacitacion_impartido: row.find("td:eq(4)").text().trim(),
                funciones_principales: row.find("td:eq(5)").text().trim(),
                fecha_inicio: row.find("td:eq(6)").text().trim(),
                fecha_retiro: row.find("td:eq(7)").text().trim(),
            };
    
            for (const key in data) {
                formData.append(`experienciaDocente[${index}][${key}]`, data[key]);
            }
    
            // Recolectar PDF desde el input oculto
            const inputOculto = row.find("input[type='hidden'][name^='experienciaDocente']");
            if (inputOculto.length > 0) {
                const archivoNombre = inputOculto.val();
                const archivo = anexosTablasExperienciaDocente.find(anexo => anexo.file.name === archivoNombre)?.file;
                
                if (archivo) {
                    formData.append(`experienciaDocente[${index}][pdf]`, archivo);
                }
            }

            nuevosDatos.experienciaDocente.push(data);
            console.log("Nuevo registro formación:", data);
        }
    });


    // **Recolectar datos de la tabla dinámica de experiencia comite
    $("#tablaExperienciaComite tr").each(function (index) {
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')){
            console.log(`Recolectando datos de la fila ${index + 1} de la tabla de experiencia comite`);
            const data = {
                institucion: row.find("td:eq(0)").text(),
                cargo_desempenado: row.find("td:eq(1)").text(),
                modelos_calidad: row.find("td:eq(2)").text(),
                fecha_inicio: row.find("td:eq(3)").text(),
                fecha_retiro: row.find("td:eq(4)").text(),
            }

            for (const key in data) {
                formData.append(`experienciaComite[${index}][${key}]`, data[key]);
            }

            nuevosDatos.experienciaComite.push(data);
            console.log("Nuevo registro formación:", data);
        }
    });

    // **Recolectar datos de la tabla dinámica de experiencia evaluador
    $("#tablaExperienciaEvaluador tr").each(function (index){
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')){
            console.log(`Recolectando datos de la fila ${index + 1} de la tabla de experiencia evaluador`);
            const data = {
                agencia_acreditadora: row.find("td:eq(0)").text(),
                fecha_inicio_evaluador: row.find("td:eq(1)").text(),
                fecha_retiro_evaluador: row.find("td:eq(2)").text(),
                nombreEntidadEvaluador: row.find("td:eq(3)").text(),
                programaEvaluador: row.find("td:eq(4)").text(),
                cargoEvaluador: row.find("td:eq(5)").text(),
                paisEvaluador: row.find("td:eq(6)").text(),
                ciudadEvaluador: row.find("td:eq(7)").text(),
                fechaEvaluacionEvaluador: row.find("td:eq(8)").text(),
            }

            for (const key in data) {
                formData.append(`experienciaEvaluador[${index}][${key}]`, data[key]);
            }

            nuevosDatos.experienciaEvaluador.push(data);
            console.log("Nuevo registro formación:", data);
        }
    });

    // **Recolectar datos de la tabla dinámica de membresias
    $("#tablaMembresias tr").each(function (index){
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')){
            console.log(`Recolectando datos de la fila ${index + 1} de la tabla de experiencia membresias`);
            const data = {
                asociacion_profesional: row.find("td:eq(0)").text(),
                numero_membresia: row.find("td:eq(1)").text(),
                grado: row.find("td:eq(2)").text(),
            }

            for (const key in data) {
                formData.append(`experienciaMembresias[${index}][${key}]`, data[key]);
            }

            nuevosDatos.experienciaMembresias.push(data);
            console.log("Nuevo registro formación:", data);
        }
    });

    // Agregar datos al formData
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