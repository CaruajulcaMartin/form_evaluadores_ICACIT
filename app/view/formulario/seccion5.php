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
    <form class="row g-3 formulario" action="<?php echo URL; ?>Formulario/enviarSeccion5" method="POST" id="formulario_seccion5" enctype="multipart/form-data">
        <!-- seccion5:  Información Sobre Investigación. -->
        <div class="form-section" id="section5">
            <button type="button" class="btn btn-primary mb-4" onclick="location.href='<?php echo URL; ?>Admin/HomeFormulario'">
                <i class="fa-solid fa-arrow-left"></i> Volver al Panel
            </button>
            <button type="button" class="btn-guardar btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#confirmarEnvioModal">
                <i class="fa-regular fa-floppy-disk"></i> Guardar
            </button>
            <h2><b>Sección 05:</b> Registro de investigaciones realizadas</h2>
            <!-- seccion 5.1:  Publicaciones, Artículos y Revistas -->
            <div class="section-title">
                <i class="fa-brands fa-readme"></i>
                <h4>Publicaciones, Artículos y Revistas</h4>
            </div>

            <div class="row g-3 formulario">
                <!-- <div class="col-md-2">
                <label class="form-label">Año:</label>
                <input type="number" class="form-control" name="fechaPublicacion" id="fechaPublicacion
                    min="1900" max="2099" step="1">
                </div> -->
                <div class="col-md-2">
                    <label class="form-label">Año:</label>
                    <input type="date" class="form-control" name="fechaPublicacion" id="fechaPublicacion"
                        min="1900-01-01" max="2099-12-31" step="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Revista o Congreso:</label>
                    <input type="text" class="form-control" name="revistaCongreso" id="revistaCongreso"
                        placeholder="Revista (volumen, número, pág.) o Congreso (nombre, organización, ciudad, país).">
                    <small class="form-text text-muted"></small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Base de Datos:</label>
                    <input type="text" class="form-control" name="baseDatos" id="baseDatos"
                        placeholder="Indexado en IEEE Xplore, SCOPUS">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre de la investigación:</label>
                    <input type="text" class="form-control" name="nombreInvestigacion" id="nombreInvestigacion" placeholder="Ej: Investigación de la inteligencia artificial en la educación">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Información de Autor(es):</label>
                    <input type="text" class="form-control" name="autores" id="autores"
                        placeholder="Ej: Autor1, Autor2">
                    <small class="form-text text-muted">Separar los autores con una coma (,).</small>
                </div>

                <!-- Botón para agregar un nuevo registro -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haz clic aquí para agregar más información sobre sus investigaciones"
                        onclick="agregarInvestigacion()">
                        Registrar Investigación
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- tabla de contenido -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Año de publicación</th>
                        <th scope="col">Revista o Congreso</th>
                        <th scope="col">Base</th>
                        <th scope="col">Titulo de investigación</th>
                        <th scope="col">Autor(es)</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaInvestigaciones">
                    <!-- aca se mostraran las filas agregadas -->
                </tbody>
            </table>

            <!-- Alerta de Nota -->
            <div class="alert alert-info mt-3 p-2" role="alert">
                <ul class="list-unstyled small mb-0">
                    <li><i class="fas fa-info-circle me-2"></i><strong>Nota:</strong> En caso de haber publicado más libros o escrito más artículos, favor adicionar las filas que
                        sean necesarias..</li>
                </ul>
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
                <button type="button" class="btn btn-success" id="confirmarEnvioBtn" data-form-id="formulario_seccion5">Confirmar</button>
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
<!-- script para procesar los datos de las tablas -->
<script src="<?php echo URL; ?>js/procesar_tablas/recolectarDatosTablasInvestigaciones.js"></script>


<!-- Variables Globales -->
<script>
    var URL = '<?php echo URL; ?>';
</script>

<!-- Recuperar Datos -->
<script src="<?php echo URL; ?>js/2_recuperar_datos/recuperarDatosSeccion5.js"></script>