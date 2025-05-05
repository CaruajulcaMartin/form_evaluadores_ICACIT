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
    <form class="row g-3 formulario" action="<?php echo URL; ?>Formulario/enviarSeccion2" method="POST" id="formulario_seccion2" enctype="multipart/form-data">
        <!-- seccion 2: informacion laboral actual -->
        <div class="form-section" id="section2">
            <button type="button" class="btn btn-primary mb-4" onclick="location.href='<?php echo URL; ?>Admin/HomeFormulario'">
                <i class="fa-solid fa-arrow-left"></i> Volver al Panel
            </button>
            <button type="button" class="btn-guardar btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#confirmarEnvioModal">
                <i class="fa-regular fa-floppy-disk"></i> Guardar
            </button>

            <h2><b>Sección 02:</b> Información Laboral Actual</h2>
            <div class="section-title">
                <i class="fa-solid fa-building-user"></i>
                <h4>Datos del Centro Laboral Actual</h4>
            </div>

            <div class="row g-3 formulario">
                <div class="col-md-4">
                    <label class="form-label">Nombre del Centro Laboral:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="centroLaboral" id="centroLaboral" placeholder="Ej: Empresa ABC" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cargo Actual:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="cargoActual" id="cargoActual" placeholder="Ej: Web Developer" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tiempo en el centro laboral (en años):<span
                            class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="tiempoLaboral" id="tiempoLaboral" min="1" step="1" placeholder="Ej: 2" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">País:<span class="text-danger">*</span></label>
                    <select class="form-control" name="PaisInformacionLaboral" id="PaisInformacionLaboral" required>
                        <option value="">Seleccionar un país</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ciudad:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="ciudadInformacionLaboral" id="ciudadInformacionLaboral" placeholder="Ej: Lima" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rubro:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="rubroInformacionLaboral" id="rubroInformacionLaboral" placeholder="Ej: Comercio" required>
                </div>
            </div>

            <div class="section-title">
                <i class="fa-solid fa-user-tie"></i>
                <h4>Datos del Empleador Actual (jefe o superior inmediato)</h4>
            </div>

            <div class="row g-3 formulario">
                <!-- <div class="col-md-4">
                        <label class="form-label">Nombre del Centro Laboral:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="centroLaboralEmpleador" required>
                    </div> -->
                <div class="col-md-4">
                    <label class="form-label">Nombres y Apellidos:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nombresEmpleador" id="nombresEmpleador" placeholder="Ej: Juan Perez" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cargo del Empleador Actual:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="cargoEmpleador" id="cargoEmpleador" placeholder="Ej: Gerente" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Correo Electrónico del Empleador Actual:<span
                            class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="correoEmpleador" id="correoEmpleador" placeholder="Ej: gerente@example.com" required>
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
                <button type="button" class="btn btn-success" id="confirmarEnvioBtn" data-form-id="formulario_seccion2">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="<?php echo URL; ?>public/js/validarFormulario.js"></script> -->

<!-- Validaciones -->
<script src="<?php echo URL; ?>js/Validaciones/ValidacionesGenerales.js"></script>
<!-- <script src="<?php echo URL; ?>js/Validaciones/validaciones.js"></script> -->
<!-- Servicios -->
<script src="<?php echo URL; ?>js/services/serviceCountry.js"></script>
<script src="<?php echo URL; ?>js/services/serviceNationalities.js"></script>

<!-- Variables Globales -->
<script>
    var URL = '<?php echo URL; ?>';
</script>

<!-- Recuperar Datos -->
<script src="<?php echo URL; ?>js/2_recuperar_datos/recuperarDatosSeccion2.js"></script>