function enviarFormulario() {
  // Crear un objeto FormData con los datos del formulario
  const formData = new FormData(document.getElementById("formularioEvaluador"));

  // Ejecutar manualmente los scripts de recolección de datos
  recolectarDatosTablas(formData);
  recolectarDatosTablasExperiencia(formData);
  recolectarDatosTablasInvestigaciones(formData);
  recolectarDatosTablasPremios(formData);
  // Agrega aquí las funciones de recolección de datos de otras tablas...

  // Depuración: Imprimir los datos que se están enviando
  console.log("Datos del formulario:");
  for (let [key, value] of formData.entries()) {
    console.log(key, value);
  }

  // Enviar los datos al servidor mediante AJAX
  $.ajax({
    url: url + "/Home/enviarFormulario",
    method: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (data) {
      console.log("Respuesta del servidor:", data); // Depuración
      alert("Formulario enviado con éxito. Respuesta del servidor: " + data); 
      $("#formularioEvaluador")[0].reset();

      // Cerrar el modal después de enviar el formulario
      $("#previewModal").modal("hide");
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", xhr.responseText); // Depuración
      alert(
        "Ocurrió un error al enviar el formulario. Por favor, inténtalo de nuevo. Detalles: " +
          xhr.responseText
      );
    },
  });
}

// Asignar la función al botón dentro del modal
$(document).ready(function () {
  $("#btnEnviarFormulario").on("click", function (e) {
    e.preventDefault(); // Prevenir el comportamiento por defecto del botón
    console.log("Botón de enviar clickeado"); // Depuración
    enviarFormulario()
  });
});