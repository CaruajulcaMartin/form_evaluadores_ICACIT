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
    <form class="row g-3 formulario" action="<?php echo URL; ?>Formulario/enviarSeccion8" method="POST" id="formulario_seccion8" enctype="multipart/form-data">
        <!-- seccion 8: valores eticos -->
        <div class="form-section" id="section8">
        <button type="button" class="btn btn-primary mb-4" onclick="location.href='<?php echo URL; ?>Admin/HomeFormulario'">
                <i class="fa-solid fa-arrow-left"></i> Volver al Panel
            </button>
            <button type="button" class="btn-guardar btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#confirmarEnvioModal">
                <i class="fa-regular fa-floppy-disk"></i> Guardar
            </button>
            <h2><b>Sección 08:</b> Conducta y Valores Éticos</h2>
            <!-- seccion 8.1:  valores eticos -->
            <div class="row g-3 formulario">
                <div class="col-12">
                    <input type="checkbox" name="condutaEtica" id="condutaEtica">
                    <label for="condutaEtica">Declaro tener una conducta intachable y valores éticos.</label>
                </div>
                <div class="col-12">
                    <input type="checkbox" name="condutaEticaValores" id="condutaEticaValores">
                    <label for="condutaEticaValores">Declaro que la información que he consignado en este formulario
                        es verdadera.</label>
                </div>
            </div>

            <!-- aca ira la firma-->
            <div class="col-md-6">
                <label class="form-label">Firma:</label>
                <canvas id="firmaCanvas"></canvas>
                <button type="button" id="limpiarFirma" class="btn btn-danger">Limpiar Firma</button>
                <input type="hidden" id="firmaInput" name="firma">
            </div>

            <!-- <button type="button" class="btn btn-secondary mt-3 prev" onclick="prevSection()">Atrás</button> -->
            <!-- <button type="button" class="btn btn-primary mt-3 next" onclick="showPreviewInModal()">Previsualizar</button> -->
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
                <button type="button" class="btn btn-success" id="confirmarEnvioBtn" data-form-id="formulario_seccion1">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="<?php echo URL; ?>public/js/validarFormulario.js"></script> -->

<!-- Validaciones -->
<script src="<?php echo URL; ?>js/Validaciones/ValidacionesGenerales.js"></script>
<script src="<?php echo URL; ?>js/Validaciones/validaciones.js"></script>
<script src="<?php echo URL; ?>js/Validaciones/validarFirma.js"></script>
<!-- firma y previsualizar -->
<script src="<?php echo URL; ?>js/firmaUsuario.js"></script>
<!-- <script src="<?php echo URL; ?>js/previsualizar.js"></script> -->

<!-- Variables Globales -->
<script>
    var URL = '<?php echo URL; ?>';
</script>

<!-- scrip para enviar formulario -->
<script src="<?php echo URL; ?>js/2_recuperar_datos/recuperarDatosSeccion8.js"></script>