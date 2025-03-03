$(document).ready(function () {
    $(document).on("submit", "#formularioEvaluador", function (event) {
        event.preventDefault();

        // Recolectar datos de las tablas dinámicas
        const formacionAcademica = [];
        $("#tablaFormacion tr").each(function () {
            const row = $(this);
            if (row.find("td").length > 0) {
                formacionAcademica.push({
                    tipo_formacion: row.find("td:eq(0)").text(),
                    pais: row.find("td:eq(1)").text(),
                    ano_graduacion: row.find("td:eq(2)").text(),
                    universidad: row.find("td:eq(3)").text(),
                    nombre_grado: row.find("td:eq(4)").text(),
                    pdf_formacion_academica: row.find("td:eq(5)").text(),
                });
            }
        });

        // Crear un objeto con todos los datos
        const datosFormulario = {
            formacionAcademica: formacionAcademica
            // Agregar otros datos del formulario si es necesario
        };

        // Enviar los datos al servidor mediante AJAX
        $.ajax({
            url: url + "/Home/enviarFormulario",
            method: "POST",
            data: JSON.stringify(datosFormulario),
            contentType: "application/json",
            success: function (response) {
                alert(response.message);
                if (response.status === "success") {
                    $("#formularioEvaluador")[0].reset(); // Resetear el formulario
                    // Limpiar las tablas dinámicas
                    $("#tablaFormacion").empty();
                }
            },
            error: function (xhr, status, error) {
                alert("Ocurrió un error al enviar el formulario.");
            }
        });
    });
});