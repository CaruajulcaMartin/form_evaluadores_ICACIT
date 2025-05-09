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
    <form class="row g-3 formulario" action="<?php echo URL; ?>Formulario/enviarSeccion6" method="POST" id="formulario_seccion6" enctype="multipart/form-data">
        <!-- seccion 6:Premios y Reconocimientos. -->
        <div class="form-section" id="section6">
            <button type="button" class="btn btn-primary mb-4" onclick="location.href='<?php echo URL; ?>Admin/HomeFormulario'">
                <i class="fa-solid fa-arrow-left"></i> Volver al Panel
            </button>
            <button type="button" class="btn-guardar btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#confirmarEnvioModal">
                <i class="fa-regular fa-floppy-disk"></i> Guardar
            </button>
            <h2><b>Sección 06:</b> Premios y Reconocimientos</h2>
            <!-- seccion 6.1:  Premios y Reconocimientos -->
            <div class="section-title">
                <i class="fa-solid fa-award"></i>
                <h4>Premios y Reconocimientos</h4>
            </div>
            <div class="row g-3 formulario">
                <div class="col-md-2">
                    <label class="form-label">Año:</label>
                    <input type="number" class="form-control" name="anoReconocimiento" id="anoReconocimiento"
                        min="1900" max="2099" step="1" placeholder="Ej: 2023">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Institución / Empresa:</label>
                    <input type="text" class="form-control" name="institucionReconocimiento"
                        id="institucionReconocimiento" placeholder="Ej: Universidad de Piura">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Nombre del Reconocimiento / Premio:</label>
                    <input type="text" class="form-control" name="nombreReconocimiento" id="nombreReconocimiento"
                        placeholder="Ej: Mejor Docente del Año">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Descripción Reconocimiento / Premio:</label>
                    <textarea name="descripcionReconocimiento" id="descripcionReconocimiento" class="form-control"
                        rows="4" placeholder="Ej: Mejor Docente del Año 2023"></textarea>
                    <small id="contadorDescripcionReconocimiento" class="text-muted">Máximo 150 palabras. Palabras actuales:
                        0</small>
                    <div id="errorDescripcion" class="text-danger mt-1" style="display: none;">
                        Has excedido el límite de 150 palabras.
                    </div>
                </div>


                <!-- Botón para agregar un nuevo registro -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haz clic aquí para agregar más información sobre sus premios y reconocimientos"
                        onclick="agregarPremio()">
                        Registrar Reconocimiento
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- tabla de contenido -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Año</th>
                        <th scope="col">Institución / Empresa</th>
                        <th scope="col">Nombre del Reconocimiento / Premio</th>
                        <th scope="col">Descripción Reconocimiento / Premio</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaPremios">
                    <!-- aca se mostraran las filas agregadas -->
                </tbody>
            </table>
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
                <button type="button" class="btn btn-success" id="confirmarEnvioBtn" data-form-id="formulario_seccion6">Confirmar</button>
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

<!-- <script src="<?php echo URL; ?>public/js/validarFormulario.js"></script> -->

<!-- Validaciones -->
<script src="<?php echo URL; ?>js/Validaciones/ValidacionesGenerales.js"></script>
<script src="<?php echo URL; ?>js/Validaciones/validaciones.js"></script>
<!-- funciones de tabla -->
<script src="<?php echo URL; ?>js/formFunctions.js"></script>
<!-- Servicios -->
<script src="<?php echo URL; ?>js/services/serviceCountry.js"></script>
<script src="<?php echo URL; ?>js/services/serviceNationalities.js"></script>
<script src="<?php echo URL; ?>js/services/servicePhone.js"></script>
<!-- script para procesar los datos de las tablas -->
<script src="<?php echo URL; ?>js/procesar_tablas/recolectarDatosTablasPremios.js"></script>


<!-- Variables Globales -->
<script>
    var URL = '<?php echo URL; ?>';
</script>

<!-- Recuperar Datos -->
<script src="<?php echo URL; ?>js/2_recuperar_datos/recuperarDatosSeccion6.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>