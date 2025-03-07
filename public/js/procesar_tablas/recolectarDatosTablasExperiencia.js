$(document).ready(function () {
    $(document).on("#formularioEvaluador", function (event) { //? evento para enviar el formulario
        event.preventDefault();

        // Crear un objeto FormData para tabla de experiencia laboral
        const formData = new FormData(this);

        // **Recolectar datos de la tabla dinámica de experiencia laboral
        $("#tablaExperiencia tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                formData.append(`experienciaLaboral[${index}][institucionEmpresaExperienciaLaboral]`, row.find("td:eq(0)").text());
                formData.append(`experienciaLaboral[${index}][cargoDesempeñadoExperienciaLaboral]`, row.find("td:eq(1)").text());
                formData.append(`experienciaLaboral[${index}][fechaInicioExperienciaLaboral]`, row.find("td:eq(2)").text());
                formData.append(`experienciaLaboral[${index}][fechaRetiroExperienciaLaboral]`, row.find("td:eq(3)").text());
                formData.append(`experienciaLaboral[${index}][paisEmpresaExperienciaLaboral]`, row.find("td:eq(4)").text());
                formData.append(`experienciaLaboral[${index}][ciudadExperienciaLaboral]`, row.find("td:eq(5)").text());

                // ! no procesa el pdf de la experiencia laboral
                const fileInput = row.find("td:eq(6)").find("input[type='file']");
                if (fileInput.length > 0 && fileInput[0].files.length > 0) {
                    formData.append(`pdfExperienciaLaboral[${index}][pdf]`, fileInput[0].files[0]);
                }

            }
        });

        //* Recolectar datos de la tabla expeencia docente
        $("#tablaExperienciaDocente tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                formData.append(`experienciaDocente[${index}][institucionExperienciaDocente]`, row.find("td:eq(0)").text());
                formData.append(`experienciaDocente[${index}][paisExperienciaDocente]`, row.find("td:eq(1)").text());
                formData.append(`experienciaDocente[${index}][ciudadExperienciaDocente]`, row.find("td:eq(2)").text());
                formData.append(`experienciaDocente[${index}][programaProfesionalExperienciaDocente]`, row.find("td:eq(3)").text());
                formData.append(`experienciaDocente[${index}][cursoCapacitacionImpartidoExperienciaDocente]`, row.find("td:eq(4)").text());
                formData.append(`experienciaDocente[${index}][funcionesPrincipales]`, row.find("td:eq(5)").text());
                formData.append(`experienciaDocente[${index}][fechaInicioExperienciaDocente]`, row.find("td:eq(6)").text());
                formData.append(`experienciaDocente[${index}][fechaRetiroExperienciaDocente]`, row.find("td:eq(7)").text());

                // ! no procesa el pdf de la experiencia docente
                const fileInput = row.find("td:eq(8)").find("input[type='file']");
                if (fileInput.length > 0 && fileInput[0].files.length > 0) {
                    formData.append(`pdfExperienciaDocente[${index}][pdf]`, fileInput[0].files[0]);
                }
            }
        });

        // **Recolectar datos de la tabla dinámica de experiencia comite
        $("#tablaExperienciaComite tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                formData.append(`experienciaComite[${index}][institucionComite]`, row.find("td:eq(0)").text());
                formData.append(`experienciaComite[${index}][cargoComite]`, row.find("td:eq(1)").text());
                formData.append(`experienciaComite[${index}][modelosCalidad]`, row.find("td:eq(2)").text());
                formData.append(`experienciaComite[${index}][fechaInicioComite]`, row.find("td:eq(3)").text());
                formData.append(`experienciaComite[${index}][fechaRetiroComite]`, row.find("td:eq(4)").text());
            }
        });

        // **Recolectar datos de la tabla dinámica de experiencia evaluador
        //! observacion: considerar los datos en tabla dinamica
        $("#tablaExperienciaEvaluador tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                formData.append(`experienciaEvaluador[${index}][agenciaAcreditadora]`, row.find("td:eq(0)").text());
                formData.append(`experienciaEvaluador[${index}][fechaInicioEvaluador]`, row.find("td:eq(1)").text());
                formData.append(`experienciaEvaluador[${index}][fechaRetiroEvaluador]`, row.find("td:eq(2)").text());
                formData.append(`experienciaEvaluador[${index}][nombreEntidad]`, row.find("td:eq(3)").text());
                formData.append(`experienciaEvaluador[${index}][programaEvaluador]`, row.find("td:eq(4)").text());
                formData.append(`experienciaEvaluador[${index}][cargoEvaluador]`, row.find("td:eq(5)").text());
                formData.append(`experienciaEvaluador[${index}][paisEvaluador]`, row.find("td:eq(6)").text());
                formData.append(`experienciaEvaluador[${index}][ciudadEvaluador]`, row.find("td:eq(7)").text());
                formData.append(`experienciaEvaluador[${index}][fechaEvaluacion]`, row.find("td:eq(8)").text());
            }
        });

        // **Recolectar datos de la tabla dinámica de membresias
        $("#tablaMembresias tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                formData.append(`membresias[${index}][asociacionProfesional]`, row.find("td:eq(0)").text());
                formData.append(`membresias[${index}][numeroMembresia]`, row.find("td:eq(1)").text());
                formData.append(`membresias[${index}][gradoMembresia]`, row.find("td:eq(2)").text());
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
                //alert(response);
                
                if (response === "éxito") {
                    alert("Formulario enviado con éxito 120");
                } else {
                    alert("error al enviar el formulario 121");
                    
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("Ocurrió un error al enviar el formulario. Por favor, inténtalo de nuevo.");
            }
        });
    });
});