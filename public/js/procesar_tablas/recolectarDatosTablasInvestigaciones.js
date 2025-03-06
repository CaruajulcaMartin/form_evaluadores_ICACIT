$(document).ready(function () {
    $(document).on("submit", "#formularioEvaluador", function (event) { //? evento para enviar el formulario
        event.preventDefault();

        // Crear un objeto FormData para tabla de Información Sobre Investigación
        const formData = new FormData(this);

        // **Recolectar datos de la tabla dinámica de investigaciones
        $("#tablaInvestigaciones tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                formData.append(`investigaciones[${index}][fechaPublicacion]`, row.find("td:eq(0)").text());
                formData.append(`investigaciones[${index}][revistaCongreso]`, row.find("td:eq(1)").text());
                formData.append(`investigaciones[${index}][baseDatos]`, row.find("td:eq(2)").text());
                formData.append(`investigaciones[${index}][nombreInvestigacion]`, row.find("td:eq(3)").text());
                formData.append(`investigaciones[${index}][autores]`, row.find("td:eq(4)").text());
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