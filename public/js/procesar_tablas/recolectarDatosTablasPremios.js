$(document).ready(function () {
    $(document).on("#formularioEvaluador", function (event) { //? evento para enviar el formulario
        event.preventDefault();

        // Crear un objeto FormData para tabla de Información Sobre premios
        const formData = new FormData(this);

        // **Recolectar datos de la tabla dinámica de premios
        $("#tablaPremios tr").each(function (index) {
            const row = $(this);
            if (row.find("td").length > 0) {
                formData.append(`premiosReconocimientos[${index}][anoReconocimiento]`, row.find("td:eq(0)").text());
                formData.append(`premiosReconocimientos[${index}][institucionReconocimiento]`, row.find("td:eq(1)").text());
                formData.append(`premiosReconocimientos[${index}][nombreReconocimiento]`, row.find("td:eq(2)").text());
                formData.append(`premiosReconocimientos[${index}][descripcionReconocimiento]`, row.find("td:eq(3)").text());
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
                    alert("Formulario enviado con éxito 140");
                } else {
                    alert("error al enviar el formulario 141");
                    
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("Ocurrió un error al enviar el formulario. Por favor, inténtalo de nuevo.");
            }
        });
    });
});