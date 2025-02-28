$(document).ready(function () {
  $(document).on("submit", "formularioEvaluador", function (event) {
    event.preventDefault();
    $.ajax({
      url: url + "/Home/enviarFormulario",
      method: "POST",
      data: new FormData(this),
      contentType: false,
      processData: false,
      success: function (data) {
        alert(data);
        $("#formularioEvaluador")[0].reset();
      },
    });
  });
});
