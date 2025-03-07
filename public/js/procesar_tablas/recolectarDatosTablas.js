$(document).ready(function () {
    $(document).on("submit", "#formularioEvaluador", function (event) { //? evento para enviar el formulario
        event.preventDefault();

        // Crear un objeto FormData para tabla de formación académica
        const formData = new FormData(this);

        // **Recolectar datos de la tabla dinámica de formación académica
        $("#tablaFormacion tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                console.log(`Recolectando datos de la fila ${index+1} de la tabla de formación académica`);
                console.log(`  tipoFormacion: ${row.find("td:eq(0)").text()}`);
                console.log(`  paisFormacion: ${row.find("td:eq(1)").text()}`);
                console.log(`  anoGraduacion: ${row.find("td:eq(2)").text()}`);
                console.log(`  institucionEducativa: ${row.find("td:eq(3)").text()}`);
                console.log(`  nombreGrado: ${row.find("td:eq(4)").text()}`);

                formData.append(`formacionAcademica[${index}][tipoFormacion]`, row.find("td:eq(0)").text());
                formData.append(`formacionAcademica[${index}][paisFormacion]`, row.find("td:eq(1)").text());
                formData.append(`formacionAcademica[${index}][anoGraduacion]`, row.find("td:eq(2)").text());
                formData.append(`formacionAcademica[${index}][institucionEducativa]`, row.find("td:eq(3)").text());
                formData.append(`formacionAcademica[${index}][nombreGrado]`, row.find("td:eq(4)").text());

                // ! no procesa el pdf de la formacion academica falta mejorar el recorrido en tabla
                const fileInput = row.find("td:eq(5)").find("input[type='file']");
                if (fileInput.length > 0 && fileInput[0].files.length > 0) {
                    console.log(`  pdf: ${fileInput[0].files[0].name}`);
                    formData.append(`formacionAcademica[${index}][pdf]`, fileInput[0].files[0]);
                } else {
                    console.log(`  pdf: No hay archivo seleccionado`);
                }
            }
        });

        // Depuración: Imprimir el contenido de FormData
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

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
                formData.append(`SeminariosCampoProfesional[${index}][duracionCampoProfesional]`, row.find("td:eq(3)").text());
            }
        });


        //** Recolectar datos de la tabla dinámica de cursos y seminarios - Relacionados a: ámbito académico
        $("#tablaCursosAmbitoAcademico tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                formData.append(`SeminariosAmbitoAcademico[${index}][anoCertificadoAmbitoAcademico]`, row.find("td:eq(0)").text());
                formData.append(`SeminariosAmbitoAcademico[${index}][institucionAmbitoAcademico]`, row.find("td:eq(1)").text());
                formData.append(`SeminariosAmbitoAcademico[${index}][cursoSeminarioAmbitoAcademico]`, row.find("td:eq(2)").text());
                formData.append(`SeminariosAmbitoAcademico[${index}][duracionAmbitoAcademico]`, row.find("td:eq(3)").text());
            }
        });

        //**  Recolectar datos de la tabla dinámica de cursos y seminarios - Relacionados a: ámbito de evaluación
        $("#tablaCursos tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                formData.append(`SeminariosAmbitoEvaluacion[${index}][anoCertificadoAmbitoEvaluacion]`, row.find("td:eq(0)").text());
                formData.append(`SeminariosAmbitoEvaluacion[${index}][institucionAmbitoEvaluacion]`, row.find("td:eq(1)").text());
                formData.append(`SeminariosAmbitoEvaluacion[${index}][cursoSeminarioAmbitoEvaluacion]`, row.find("td:eq(2)").text());
                formData.append(`SeminariosAmbitoEvaluacion[${index}][duracionAmbitoEvaluacion]`, row.find("td:eq(3)").text());
            }
        });

        // Enviar los datos al servidor mediante AJAX
        $.ajax({
            url: url + "Home/enviarFormulario/",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {

                //? falta agregar codigo para que resete el formulario
                // alert(response);
                
                if (response === "éxito") {
                    alert("Formulario enviado con éxito 110");
                } else {
                    alert("error al enviar el formulario 111");
                    
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("Ocurrió un error al enviar el formulario. Por favor, inténtalo de nuevo.");
            }
        });
    });
});