/*
$(document).ready(function () {
    $(document).on("submit", "#formularioEvaluador", function (event) { //? evento para enviar el formulario
        event.preventDefault();

        // Crear un objeto FormData para tabla de experiencia laboral
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

function recolectarDatosTablasExperiencia(formData) {
    console.log("Recolectando datos de la tabla de experiencia...");
    // **Recolectar datos de la tabla dinámica de experiencia laboral
    $("#tablaExperiencia tr").each(function (index) {
        const row = $(this);

        if (row.find("td").length > 0) {
            console.log(`Recolectando datos de la fila ${index + 1} de la tabla de experiencia laboral`);

            // Recolectar datos de las celdas
            const institucionEmpresaExperienciaLaboral = row.find("td:eq(0)").text();
            const cargoDesempeñadoExperienciaLaboral = row.find("td:eq(1)").text();
            const fechaInicioExperienciaLaboral = row.find("td:eq(2)").text();
            const fechaRetiroExperienciaLaboral = row.find("td:eq(3)").text();
            const paisEmpresaExperienciaLaboral = row.find("td:eq(4)").text();
            const ciudadExperienciaLaboral = row.find("td:eq(5)").text();

            /*
            // Depuración
            console.log(`Institucion: ${institucionEmpresaExperienciaLaboral}`);
            console.log(`Cargo: ${cargoDesempeñadoExperienciaLaboral}`);
            console.log(`Fecha inicio: ${fechaInicioExperienciaLaboral}`);
            console.log(`Fecha retiro: ${fechaRetiroExperienciaLaboral}`);
            console.log(`Pais: ${paisEmpresaExperienciaLaboral}`);
            console.log(`Ciudad: ${ciudadExperienciaLaboral}`);
            */
            
            // Agregar los datos recolectados al FormData
            formData.append(`experienciaLaboral[${index}][institucionEmpresaExperienciaLaboral]`, institucionEmpresaExperienciaLaboral);
            formData.append(`experienciaLaboral[${index}][cargoDesempeñadoExperienciaLaboral]`, cargoDesempeñadoExperienciaLaboral);
            formData.append(`experienciaLaboral[${index}][fechaInicioExperienciaLaboral]`, fechaInicioExperienciaLaboral);
            formData.append(`experienciaLaboral[${index}][fechaRetiroExperienciaLaboral]`, fechaRetiroExperienciaLaboral);
            formData.append(`experienciaLaboral[${index}][paisEmpresaExperienciaLaboral]`, paisEmpresaExperienciaLaboral);
            formData.append(`experienciaLaboral[${index}][ciudadExperienciaLaboral]`, ciudadExperienciaLaboral);

            // Recolectar PDF de experiencia laboral
            const inputOculto = row.find("input[type='hidden'][name^='experienciaLaboral']");
            if (inputOculto.length > 0) {
                const archivoNombre = inputOculto.val(); // Obtener el nombre del archivo desde el valor del input oculto
                console.log(`  pdfExperienciaLaboral (nombre): ${archivoNombre}`);

                if (archivoNombre) {
                    // Buscar el archivo en el arreglo de anexos
                    const archivo = anexosTablasExperienciaLaboral.find(anexo => anexo.file.name === archivoNombre)?.file;
                    if (archivo) {
                        console.log(`  pdf: Archivo encontrado - ${archivo.name}`);
                        formData.append(`experienciaLaboral[${index}][pdfExperienciaLaboral]`, archivo);
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
            console.log("  Arreglo de anexos:", anexosTablasExperienciaLaboral);
            console.log("  Input oculto encontrado:", inputOculto);
            console.log("  Valor del input oculto:", inputOculto.val());
            console.log("  Estructura de la fila:", row.html());
            console.log("  ---------------------------");
            */
        } else {
            console.log(`Fila ${index + 1} ignorada (no contiene datos)`);
        }
    });

    // **Recolectar datos de la tabla de experiencia docente
    $("#tablaExperienciaDocente tr").each(function (index) {
        const row = $(this);

        if (row.find("td").length > 0) {
            console.log(`Recolectando datos de la fila ${index + 1} de la tabla de experiencia docente`);

            // Recolectar datos de las celdas
            const institucionExperienciaDocente = row.find("td:eq(0)").text();
            const paisExperienciaDocente = row.find("td:eq(1)").text();
            const ciudadExperienciaDocente = row.find("td:eq(2)").text();
            const programaProfesionalExperienciaDocente = row.find("td:eq(3)").text();
            const cursoCapacitacionImpartidoExperienciaDocente = row.find("td:eq(4)").text();
            const funcionesPrincipales = row.find("td:eq(5)").text();
            const fechaInicioExperienciaDocente = row.find("td:eq(6)").text();
            const fechaRetiroExperienciaDocente = row.find("td:eq(7)").text();

            /*
            // Depuración
            console.log(`InstitucionExperienciaDocente: ${institucionExperienciaDocente}`);
            console.log(`PaisExperienciaDocente: ${paisExperienciaDocente}`);
            console.log(`CiudadExperienciaDocente: ${ciudadExperienciaDocente}`);
            console.log(`ProgramaProfesionalExperienciaDocente: ${programaProfesionalExperienciaDocente}`);
            console.log(`CursoCapacitacionImpartidoExperienciaDocente: ${cursoCapacitacionImpartidoExperienciaDocente}`);
            console.log(`FuncionesPrincipales: ${funcionesPrincipales}`);
            console.log(`FechaInicioExperienciaDocente: ${fechaInicioExperienciaDocente}`);
            console.log(`FechaRetiroExperienciaDocente: ${fechaRetiroExperienciaDocente}`);
            */

            // Agregar los datos recolectados al FormData
            formData.append(`experienciaDocente[${index}][institucionExperienciaDocente]`, institucionExperienciaDocente);
            formData.append(`experienciaDocente[${index}][paisExperienciaDocente]`, paisExperienciaDocente);
            formData.append(`experienciaDocente[${index}][ciudadExperienciaDocente]`, ciudadExperienciaDocente);
            formData.append(`experienciaDocente[${index}][programaProfesionalExperienciaDocente]`, programaProfesionalExperienciaDocente);
            formData.append(`experienciaDocente[${index}][cursoCapacitacionImpartidoExperienciaDocente]`, cursoCapacitacionImpartidoExperienciaDocente);
            formData.append(`experienciaDocente[${index}][funcionesPrincipales]`, funcionesPrincipales);
            formData.append(`experienciaDocente[${index}][fechaInicioExperienciaDocente]`, fechaInicioExperienciaDocente);
            formData.append(`experienciaDocente[${index}][fechaRetiroExperienciaDocente]`, fechaRetiroExperienciaDocente);

            // Recolectar PDF de experiencia docente
            const inputOculto = row.find("input[type='hidden'][name^='experienciaDocente']");
            if (inputOculto.length > 0) {
                const archivoNombre = inputOculto.val(); // Obtener el nombre del archivo desde el valor del input oculto
                console.log(`  pdfExperienciaDocente (nombre): ${archivoNombre}`);

                if (archivoNombre) {
                    // Buscar el archivo en el arreglo de anexos
                    const archivo = anexosTablasExperienciaDocente.find(anexo => anexo.file.name === archivoNombre)?.file;
                    if (archivo) {
                        console.log(`  pdf: Archivo encontrado - ${archivo.name}`);
                        formData.append(`experienciaDocente[${index}][pdfExperienciaDocente]`, archivo);
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
            console.log("  Arreglo de anexos:", anexosTablasExperienciaDocente);
            console.log("  Input oculto encontrado:", inputOculto);
            console.log("  Valor del input oculto:", inputOculto.val());
            console.log("  Estructura de la fila:", row.html());
            console.log("  ---------------------------");
            */
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
}