function recolectarDatosTablas(formData, existeRegistro) {
    const esActualizacion = existeRegistro; // Variable que defines al cargar los datos iniciales
    
    formData.append('esActualizacion', esActualizacion);
    
    const nuevosDatos = {
        formacionAcademica: [],
        idiomas: [],
        cursosProfesionales: [],
        cursosAcademicos: [],
        cursosEvaluacion: []
    };

    // Objeto para almacenar IDs eliminados
    const eliminados = {
        formacionAcademica: [],
        idiomas: [],
        cursosProfesionales: [],
        cursosAcademicos: [],
        cursosEvaluacion: []
    };

    if (esActualizacion) {
        console.log("Identificando registros eliminados...");
        
        // Para formación académica
        const idsFormacionOriginales = registrosTablas.formacion.map(item => item.id);
        const idsFormacionActuales = $("#tablaFormacion tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);
        
        eliminados.formacionAcademica = idsFormacionOriginales.filter(id => !idsFormacionActuales.includes(id));
        console.log("Formación académica eliminada:", eliminados.formacionAcademica);
        
        // para idiomas
        const idsIdiomasOriginales = registrosTablas.idiomas.map(item => item.id);
        const idsIdiomasActuales = $("#tablaIdiomas tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);
        
        eliminados.idiomas = idsIdiomasOriginales.filter(id => !idsIdiomasActuales.includes(id));
        console.log("Formación académica eliminada:", eliminados.formacionAcademica);

        //! Cursos - Campo Profesional
        const idsCursosProfesionalesOriginales = registrosTablas.cursosCampoProfesional.map(item => item.id);
        const idsCursosProfesionalesActuales = $("#tablaCursosAmbitoProfesional tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);

        eliminados.cursosProfesionales = idsCursosProfesionalesOriginales.filter(id => !idsCursosProfesionalesActuales.includes(id));
        console.log("Cursos profesionales eliminados:", eliminados.cursosProfesionales);

        //! Cursos - Campo Académico
        const idsCursosAcademicosOriginales = registrosTablas.cursosAcademicos.map(item => item.id);
        const idsCursosAcademicosActuales = $("#tablaCursosAcademicos tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);

        eliminados.cursosAcademicos = idsCursosAcademicosOriginales.filter(id => !idsCursosAcademicosActuales.includes(id));
        console.log("Cursos académicos eliminados:", eliminados.cursosAcademicos);

        // Cursos - Campo Evaluación
        const idsCursosEvaluacionOriginales = registrosTablas.cursosEvaluacion.map(item => item.id);
        const idsCursosEvaluacionActuales = $("#tablaCursos tr[data-id]").map((i, el) => {
            const id = $(el).data('id');
            return (id && !id.toString().startsWith('new_')) ? id : null;
        }).get().filter(Boolean);

        eliminados.cursosEvaluacion = idsCursosEvaluacionOriginales.filter(id => !idsCursosEvaluacionActuales.includes(id));
        console.log("Cursos evaluación eliminados:", eliminados.cursosEvaluacion);
    }

    //nuevos registros
    console.log("Identificando nuevos registros...");

    // Formación Académica
    $("#tablaFormacion tr").each(function (index) {
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')) {
            const data = {
                tipo_formacion: row.find("td:eq(0)").text(),
                pais: row.find("td:eq(1)").text(),
                ano_graduacion: row.find("td:eq(2)").text(),
                universidad: row.find("td:eq(3)").text(),
                nombre_grado: row.find("td:eq(4)").text()
            };

            for (const key in data) {
                formData.append(`formacionAcademica[${index}][${key}]`, data[key]);
            }

            //! Manejar archivos PDF
            const inputOculto = row.find("input[type='hidden'][name^='formacionAcademica']");
            if (inputOculto.length > 0) {
                const archivoNombre = inputOculto.val();
                const archivo = anexosTablasFormacionAcademica.find(anexo => anexo.file.name === archivoNombre)?.file;
                
                if (archivo) {
                    formData.append(`formacionAcademica[${index}][pdf]`, archivo);
                }
            }

            nuevosDatos.formacionAcademica.push(data);
            console.log("Nuevo registro formación:", data);
        }
    });

    // Idiomas
    $("#tablaIdiomas tr").each(function (index) {
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')) {
            const data = {
                idioma: row.find("td:eq(0)").text(),
                competencia_escrita: row.find("td:eq(1)").text(),
                competencia_lectora: row.find("td:eq(2)").text(),
                competencia_oral: row.find("td:eq(3)").text()
            };
            
            for (const key in data) {
                formData.append(`idiomas[${index}][${key}]`, data[key]);
            }

            
            nuevosDatos.idiomas.push(data);
            console.log("Nuevo registro idioma:", data);
        }

    });

    //! Cursos - Campo Profesional
    $("#tablaCursosAmbitoProfesional tr").each(function (index) {
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')) {
            const data = {
                ano: row.find("td:eq(0)").text(),
                institucion: row.find("td:eq(1)").text(),
                nombre_curso_seminario: row.find("td:eq(2)").text(),
                tipo_seminario: row.find("td:eq(3)").text(),
                duracion: row.find("td:eq(4)").text()
            };
            
            for (const key in data) {
                formData.append(`cursosProfesionales[${index}][${key}]`, data[key]);
            }
            
            nuevosDatos.cursosProfesionales.push(data);
            console.log("Nuevo registro curso profesional:", data);
        }
    });

    //! Cursos - Ámbito Académico
    $("#tablaCursosAmbitoAcademico tr").each(function (index) {
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')) {
            const data = {
                ano: row.find("td:eq(0)").text(),
                institucion: row.find("td:eq(1)").text(),
                nombre_curso_seminario: row.find("td:eq(2)").text(),
                tipo_seminario: row.find("td:eq(3)").text(),
                duracion: row.find("td:eq(4)").text()
            };
            
            for (const key in data) {
                formData.append(`cursosAcademicos[${index}][${key}]`, data[key]);
            }

            nuevosDatos.cursosAcademicos.push(data);
        console.log("Nuevo registro curso académico:", data);
        }

    });

    // Cursos - Ámbito Evaluación
    $("#tablaCursos tr").each(function (index) {
        const row = $(this);
        const rowId = row.attr('data-id');

        if (!rowId || rowId.toString().startsWith('new_')) {
            const data = {
                ano: row.find("td:eq(0)").text(),
                institucion: row.find("td:eq(1)").text(),
                nombre_curso_seminario: row.find("td:eq(2)").text(),
                tipo_seminario: row.find("td:eq(3)").text(),
                duracion: row.find("td:eq(4)").text()
            };
            
            for (const key in data) {
                formData.append(`cursosEvaluacion[${index}][${key}]`, data[key]);
            }

            nuevosDatos.cursosEvaluacion.push(data);
            console.log("Nuevo registro curso evaluación:", data);
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
