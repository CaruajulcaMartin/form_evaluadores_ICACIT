
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

<!-- script para procesar los datos de las tablas -->
<script src="<?php echo URL; ?>js/procesar_tablas/recolectarDatosTablas.js"></script>
<script src="<?php echo URL; ?>js/procesar_tablas/recolectarDatosTablasExperiencia.js"></script>
<script src="<?php echo URL; ?>js/procesar_tablas/recolectarDatosTablasInvestigaciones.js"></script>
<script src="<?php echo URL; ?>js/procesar_tablas/recolectarDatosTablasPremios.js"></script>

<!-- enviar formulario script -->
<script src="<?php echo URL; ?>js/modal_firma.js"></script>

<!-- Bootstrap 5 -->
<script>
  // Inicializa todos los tooltips en la página
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>

<script> var url = "<?php echo URL; ?>"; </script>

</body>

</html>