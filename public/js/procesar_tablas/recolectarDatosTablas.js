/*
$(document).ready(function () {
    $(document).on("submit", "#formularioEvaluador", function (event) { //? evento para enviar el formulario
        event.preventDefault();

        // Crear un objeto FormData para tabla de formación académica
        const formData = new FormData(this);

        

        
        // Enviar los datos al servidor mediante AJAX
        $.ajax({
            url: url + "Home/enviarFormulario/",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {

                //? falta agregar codigo para que resete el formulario
                //alert(response);
                
                if (response === "éxito") {
                    alert("Formulario enviado con éxito 130");
                } else {
                    alert("error al enviar el formulario 131");
                    
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("Ocurrió un error al enviar el formulario. Por favor, inténtalo de nuevo.");
            }
        });
        
    });
});
*/

function recolectarDatosTablas(formData) {
    
    $("#tablaFormacion tr").each(function (index) {
        const row = $(this);

        // Verificar si la fila contiene datos (ignorar la fila de encabezado)
        if (row.find("td").length > 0) {
            console.log(`Recolectando datos de la fila ${index + 1} de la tabla de formación académica`);
    
            // Recolectar datos de las celdas
            const tipoFormacion = row.find("td:eq(0)").text();
            const paisFormacion = row.find("td:eq(1)").text();
            const anoGraduacion = row.find("td:eq(2)").text();
            const institucionEducativa = row.find("td:eq(3)").text();
            const nombreGrado = row.find("td:eq(4)").text();
    
            /*
            // Depuración
            console.log(`  tipoFormacion: ${tipoFormacion}`);
            console.log(`  paisFormacion: ${paisFormacion}`);
            console.log(`  anoGraduacion: ${anoGraduacion}`);
            console.log(`  institucionEducativa: ${institucionEducativa}`);
            console.log(`  nombreGrado: ${nombreGrado}`);
            */

            // Agregar datos al FormData
            formData.append(`formacionAcademica[${index}][tipoFormacion]`, tipoFormacion);
            formData.append(`formacionAcademica[${index}][paisFormacion]`, paisFormacion);
            formData.append(`formacionAcademica[${index}][anoGraduacion]`, anoGraduacion);
            formData.append(`formacionAcademica[${index}][institucionEducativa]`, institucionEducativa);
            formData.append(`formacionAcademica[${index}][nombreGrado]`, nombreGrado);
    
            // Obtener el input oculto que contiene el nombre del archivo PDF
            const inputOculto = row.find("input[type='hidden'][name^='formacionAcademica']");
            if (inputOculto.length > 0) {
                const archivoNombre = inputOculto.val(); // Obtener el nombre del archivo desde el valor del input oculto
                console.log(`  pdfFormacionAcademica (nombre): ${archivoNombre}`);
    
                if (archivoNombre) {
                    // Buscar el archivo en el arreglo de anexos
                    const archivo = anexosTablasFormacionAcademica.find(anexo => anexo.file.name === archivoNombre)?.file;
                    if (archivo) {
                        console.log(`  pdf: Archivo encontrado - ${archivo.name}`);
                        formData.append(`formacionAcademica[${index}][pdfFormacionAcademica]`, archivo);
                    } else {
                        console.log(`  pdf: No se encontró el archivo en el arreglo de anexos`);
                    }
                } else {
                    console.log(`  pdf: El nombre del archivo está vacío`);
                }
            } else {
                console.log("  pdf: No se encontró el input oculto en la fila");
            }
    
            /*
            // Depuración adicional
            console.log("  --- Depuración adicional ---");
            console.log("  Arreglo de anexos:", anexosTablasFormacionAcademica);
            console.log("  Input oculto encontrado:", inputOculto);
            console.log("  Valor del input oculto:", inputOculto.val());
            console.log("  Estructura de la fila:", row.html());
            console.log("  ---------------------------");
            */
        }
    });


    /*
    // Depuración: Imprimir el contenido de FormData
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    */

    // **Recolectar datos de la tabla dinámica de idiomas
    $("#tablaIdiomas tr").each(function (index) {
        const row = $(this);
        if (row.find("td").length > 0) {
            formData.append(`idiomas[${index}][idioma]`, row.find("td:eq(0)").text());
            formData.append(`idiomas[${index}][competenciaEscrita]`, row.find("td:eq(1)").text());
            formData.append(`idiomas[${index}][competenciaLectora]`, row.find("td:eq(2)").text());
            formData.append(`idiomas[${index}][competenciaOral]`, row.find("td:eq(3)").text());
        }
    });

    //** Recolectar datos de la tabla dinámica de cursos y seminarios - Relacionados a: su campo profesional
    $("#tablaCursosAmbitoProfesional tr").each(function (index) {
        const row = $(this);
        if (row.find("td").length > 0) {
            formData.append(`SeminariosCampoProfesional[${index}][anoCertificadoCampoProfesional]`, row.find("td:eq(0)").text());
            formData.append(`SeminariosCampoProfesional[${index}][institucionCampoProfesional]`, row.find("td:eq(1)").text());
            formData.append(`SeminariosCampoProfesional[${index}][cursoSeminarioCampoProfesional]`, row.find("td:eq(2)").text());
            formData.append(`SeminariosCampoProfesional[${index}][tipoCursoSeminarioCampoProfesional]`, row.find("td:eq(3)").text());
            formData.append(`SeminariosCampoProfesional[${index}][duracionCampoProfesional]`, row.find("td:eq(4)").text());
        }
    });


    //** Recolectar datos de la tabla dinámica de cursos y seminarios - Relacionados a: ámbito académico
    $("#tablaCursosAmbitoAcademico tr").each(function (index) {
        const row = $(this);
        if (row.find("td").length > 0) {
            formData.append(`SeminariosAmbitoAcademico[${index}][anoCertificadoAmbitoAcademico]`, row.find("td:eq(0)").text());
            formData.append(`SeminariosAmbitoAcademico[${index}][institucionAmbitoAcademico]`, row.find("td:eq(1)").text());
            formData.append(`SeminariosAmbitoAcademico[${index}][cursoSeminarioAmbitoAcademico]`, row.find("td:eq(2)").text());
            formData.append(`SeminariosAmbitoAcademico[${index}][tipoCursoSeminarioAmbitoAcademico]`, row.find("td:eq(3)").text());
            formData.append(`SeminariosAmbitoAcademico[${index}][duracionAmbitoAcademico]`, row.find("td:eq(4)").text());
        }
    });

    //**  Recolectar datos de la tabla dinámica de cursos y seminarios - Relacionados a: ámbito de evaluación
    $("#tablaCursos tr").each(function (index) {
        const row = $(this);
        if (row.find("td").length > 0) {
            formData.append(`SeminariosAmbitoEvaluacion[${index}][anoCertificadoAmbitoEvaluacion]`, row.find("td:eq(0)").text());
            formData.append(`SeminariosAmbitoEvaluacion[${index}][institucionAmbitoEvaluacion]`, row.find("td:eq(1)").text());
            formData.append(`SeminariosAmbitoEvaluacion[${index}][cursoSeminarioAmbitoEvaluacion]`, row.find("td:eq(2)").text());
            formData.append(`SeminariosAmbitoEvaluacion[${index}][tipoCursoSeminarioAmbitoEvaluacion]`, row.find("td:eq(3)").text());
            formData.append(`SeminariosAmbitoEvaluacion[${index}][duracionAmbitoEvaluacion]`, row.find("td:eq(4)").text());
        }
    });
}