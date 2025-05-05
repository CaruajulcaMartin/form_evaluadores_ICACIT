<header class="header-section">
    <a class="img-header" href="<?php echo URL; ?>Admin/HomeFormulario">
        <img src="<?php echo URL; ?>assets/logo ICACIT.png" alt="logo de ICACIT">
    </a>

    <div class="usuario-header">
        <?php if (isset($data['user'])): ?>
            <h3 class="user-name">
                <?php echo htmlspecialchars($data['user']['nombre'] . ' ' . $data['user']['apellido_paterno']); ?>
            </h3>
            <span><?php echo htmlspecialchars($data['user']['email']); ?></span>
            <input type="hidden" id="userId" value="<?php echo htmlspecialchars($data['user']['id']); ?>">
        <?php else: ?>
            <p>Error: No se encontraron datos del usuario.</p>
        <?php endif; ?>
    </div>
</header>


<section class="container">
    <form class="row g-3 formulario" action="<?php echo URL; ?>Formulario/enviarSeccion7" method="POST" id="formulario_seccion7" enctype="multipart/form-data">
        <!-- seccion 7: Carta de presentación. -->
        <div class="form-section" id="section7">
            <button type="button" class="btn btn-primary mb-4" onclick="location.href='<?php echo URL; ?>Admin/HomeFormulario'">
                <i class="fa-solid fa-arrow-left"></i> Volver al Panel
            </button>
            <button type="button" class="btn-guardar btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#confirmarEnvioModal">
                <i class="fa-regular fa-floppy-disk"></i> Guardar
            </button>
            <h2><b>Sección 07:</b> Carta de Presentación<span class="text-danger">*</span></h2>

            <!-- seccion 7.1:  Carta de presentación -->
            <div class="row g-3 formulario">
                <h5>Describa en 400 palabras su interés por ser parte del Exclusivo Staff Internacional de
                    <b>Evaluadores ICACIT</b>
                </h5>
                <div class="col-12">
                    <!-- <label class="form-label">Carta de Presentación:<span class="text-danger">*</span></label> -->
                    <textarea name="cartaPresentacion" id="descripcionContribucion" class="form-control"
                        rows="6" required placeholder="Ej: Me interesa ser parte del Exclusivo Staff Internacional de Evaluadores ICACIT"></textarea>
                    <small id="contadorPalabras" class="text-muted">Maximo 400 palabras. Palabras actuales:
                        0</small>
                    <div id="mensajeError" class="text-danger mt-1" style="display: none;"><b>Nota:</b> Has excedido
                        el límite de 400 palabras como maximo.</div>
                </div>
            </div>
        </div>
    </form>
</section>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmarEnvioModal" tabindex="-1" aria-labelledby="confirmarEnvioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarEnvioModalLabel">Confirmar Acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalMessage">
                <!-- Mensaje dinámico se insertará aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmarEnvioBtn" data-form-id="formulario_seccion7">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="<?php echo URL; ?>public/js/validarFormulario.js"></script> -->

<!-- Validaciones -->
<script src="<?php echo URL; ?>js/Validaciones/ValidacionesGenerales.js"></script>
<script src="<?php echo URL; ?>js/Validaciones/validaciones.js"></script>


<!-- Variables Globales -->
<script>
    var URL = '<?php echo URL; ?>';
</script>

<!-- Recuperar Datos -->
<script src="<?php echo URL; ?>js/2_recuperar_datos/recuperarDatosSeccion7.js"></script>