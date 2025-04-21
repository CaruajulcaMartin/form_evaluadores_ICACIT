function enviarFormulario() {
  const formData = new FormData(document.getElementById("formularioEvaluador"));

  // Ejecutar scripts de recolección de datos
  recolectarDatosTablas(formData);
  recolectarDatosTablasExperiencia(formData);
  recolectarDatosTablasInvestigaciones(formData);
  recolectarDatosTablasPremios(formData);

  // Deshabilitar el botón para evitar doble envío
  $("#btnEnviarFormulario").prop("disabled", true);

  $.ajax({
    url: url + "/Home/enviarFormulario",
    method: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (data) {
      // alert(data);
      $("#contador").val(data);

      $("#modalMensaje .modal-body").html(`
        <h5 class="text-success">¡Felicidades!</h5>
        <p>Su formulario de aplicación al programa de evaluadores ICACIT se ha enviado con éxito.</p>
        <p>Desde el correo <strong>evaluadores@icacit.org.pe</strong> recibirá la respuesta a su aplicación.</p>
        <p>Tenga buen día.</p>
      `);
      $("#modalMensaje").modal("show");

      // Resetear el formulario
      $("#formularioEvaluador")[0].reset();

      // Cerrar la pestaña
      setTimeout(() => window.close(), 3000);
    },

    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", xhr.responseText);
      $("#modalMensaje .modal-body").html(`
        <h5 class="text-danger">Error</h5>
        <p>Ocurrió un error al enviar el formulario. Por favor, inténtelo de nuevo.</p>
        <p>Detalles: ${xhr.responseText}</p>
      `);
      $("#modalMensaje").modal("show");

      // Habilitar el botón de nuevo
      $("#btnEnviarFormulario").prop("disabled", false);
    },
  });

  // alert(numero_documento);
  // buscarContador();
}

//funcion buscador de contador
// function buscarContador() {
//   var numero_documento = $("#numDoc").val();

//   var params = {
//     numDoc: numero_documento,
//   };

//   $.ajax({
//     url: url + "/Home/buscar_contador",
//     dataType: "html",
//     method: "POST",
//     data: params,
//     success: function (data) {
//       // alert (data);
//       $("#contador").val(data);
//     },
//   });
// }

// Evento para enviar el formulario
$(document).ready(function () {
  $("#btnEnviarFormulario").on("click", function (e) {
    e.preventDefault();
    enviarFormulario();
  });

  // Evento para cerrar el modal y redirigir a la página de inicio
  $("#btnCerrarModal").on("click", function () {
    window.location.href = "https://icacit.org.pe/formacion_evaluadores/";
  });
});
