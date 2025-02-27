<!-- modal para la previsualización -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <img src="<?php echo URL; ?>images/ICACIT_2025.jpg" alt="Logo ICACIT" class="modal-logo">
        <h5 class="modal-title" id="previewModalLabel">Previsualización del Formulario de Inscripción -
          Evaluador ICACIT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="previewModalBody">
        <!-- El contenido de la previsualización se inserta aquí -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-info" onclick="downloadPDF()">Descargar PDF</button>
        <button type="button" class="btn btn-success" onclick="">Enviar Formulario</button>
      </div>
    </div>
  </div>
</div>


<!-- modal de alerta -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-danger" id="alertModalLabel"><i class="fa-solid fa-triangle-exclamation fa-fade"></i> Alerta</h4>
      </div>
      <div class="modal-body" id="alertModalBody">
        <!-- Mensaje de alerta irá aquí -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- index script -->
<script src="<?php echo URL; ?>js/index.js"></script>


<!-- firma y previsualizar -->
<script src="<?php echo URL; ?>js/firmaUsuario.js"></script>
<script src="<?php echo URL; ?>js/previsualizar.js"></script>

<!-- Validaciones -->
<script src="<?php echo URL; ?>js/Validaciones/ValidacionesGenerales.js"></script>
<script src="<?php echo URL; ?>js/Validaciones/validaciones.js"></script>
<script src="<?php echo URL; ?>js/Validaciones/validarExperiencia.js"></script>
<script src="<?php echo URL; ?>js/Validaciones/validarFirma.js"></script>

<!-- funciones de tabla -->
<script src="<?php echo URL; ?>js/formFunctions.js"></script>

<!-- Servicios -->
<script src="<?php echo URL; ?>js/services/serviceCountry.js"></script>
<script src="<?php echo URL; ?>js/services/serviceNationalities.js"></script>
<script src="<?php echo URL; ?>js/services/servicePhone.js"></script>

<!-- Bootstrap 5 -->
<script>
  // Inicializa todos los tooltips en la página
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>

</body>

</html>