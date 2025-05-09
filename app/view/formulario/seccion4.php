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
    <form class="row g-3 formulario" action="<?php echo URL; ?>Formulario/enviarSeccion4" method="POST" id="formulario_seccion4" enctype="multipart/form-data">
        <!-- seccion 4: información sobre experiencia laboral  -->
        <div class="form-section" id="section4">
            <button type="button" class="btn btn-primary mb-4" onclick="location.href='<?php echo URL; ?>Admin/HomeFormulario'">
                <i class="fa-solid fa-arrow-left"></i> Volver al Panel
            </button>
            <button type="button" class="btn-guardar btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#confirmarEnvioModal">
                <i class="fa-regular fa-floppy-disk"></i> Guardar
            </button>
            <h2><b>Sección 04:</b> Información Sobre Experiencia Laboral</h2>

            <!-- seccion 4.1: experiencia laboral -->
            <div class="section-title">
                <i class="fa-solid fa-briefcase"></i>
                <h4>Experiencia laboral en su campo profesional (evidenciar mínimo 10 años)</h4>
            </div>

            <div class="row g-3 formulario">
                <div class="col-md-4">
                    <label class="form-label">Institución o Empresa:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="institucionEmpresaExperienciaLaboral" id="institucionEmpresa"
                        placeholder="Ej: Empresa CBC S.A.C.">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cargo desempeñado:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="cargoDesempeñadoExperienciaLaboral" id="cargoDesempeñado" placeholder="Ej: Analista de Sistemas">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Fecha de Inicio:<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="fechaInicioExperienciaLaboral" id="fechaInicio">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Fecha de Retiro:<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="fechaRetiroExperienciaLaboral" id="fechaRetiro">
                </div>
                <div class="col-md-4">
                    <label class="form-label">País:<span class="text-danger">*</span></label>
                    <select class="form-control" name="paisEmpresaExperienciaLaboral" id="paisEmpresa">
                        <option value="">Seleccionar un país</option>
                    </select>
                    <!-- <input type="text" class="form-control" name="paisEmpresa" id="paisEmpresa"> -->
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ciudad:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="ciudadExperienciaLaboral" id="ciudadEmpresa" placeholder="Ej: Piura">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Adjuntar PDF:<span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="pdfExperienciaLaboral" id="pdfExperiencia" accept="application/pdf">
                    <small class="form-text text-muted">Solo se permiten archivos PDF con un tamaño máximo de 5
                        MB.</small>
                </div>

                <!-- Botón para agregar un nuevo registro -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haz clic aquí para agregar más información sobre su experiencia laboral"
                        onclick="agregarExperiencia()">
                        Registrar Experiencia
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

                <!-- Alerta de Nota -->
                <div class="alert alert-info mt-3 p-2" role="alert">
                    <ul class="list-unstyled small mb-0">
                        <li><i class="fas fa-info-circle me-2"></i> <strong>Nota 1:</strong> Los 10 años no incluyen
                            experiencia profesional docente, ni cargos
                            académicos como: Director de Programa, Decano, entre otros; ni estudios a tiempo
                            completo de
                            posgrado.</li>
                        <li><i class="fas fa-info-circle me-2"></i> <strong>Nota 2:</strong> Se tomará en cuenta
                            experiencia profesional docente o de cargos
                            académicos solamente para profesionales del área de ciencias (de las disciplinas de:
                            Física,
                            Matemáticas o Química).</li>
                    </ul>
                </div>


            </div>

            <!-- Tabla para muestrar los registros agregados -->
            <table class="table table-striped required">
                <thead>
                    <tr>
                        <th scope="col">Institución o Empresa</th>
                        <th scope="col">Cargo desempeñado</th>
                        <th scope="col">Fecha inicio</th>
                        <th scope="col">Fecha fin</th>
                        <th scope="col">País</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col">Anexos</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaExperiencia">
                    <!-- aca se mostraran las filas agregadas -->
                </tbody>
            </table>

            <!-- Alerta de Nota -->
            <div class="alert alert-info mt-3 p-2" role="alert">
                <ul class="list-unstyled small mb-0">
                    <li><i class="fas fa-info-circle me-2"></i><strong>Nota:</strong> En caso contar con más registros de experiencia laboral en su campo profesional, favor
                        adicionar las filas que sean necesarias.</li>
                </ul>
            </div>

            <!-- seccion 4.2: experiencia docente en educación superior o formación continua -->
            <div class="section-title">
                <i class="fa-solid fa-chalkboard-user"></i>
                <h4>Experiencia Docente en Educación Superior o Formación Continua</h4>
            </div>

            <div class="row g-3 formulario">
                <h5>(Dictado de talleres, capacitaciones y/o seminarios)</h5>
                <div class="col-md-4">
                    <label class="form-label">Nombre de la Institución:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="institucionExperienciaDocente" id="institucionDocente" placeholder="Ej: Universidad de Piura">
                </div>
                <div class="col-md-4">
                    <label class="form-label">País:<span class="text-danger">*</span></label>
                    <select class="form-control" name="paisExperienciaDocente" id="paisDocente">
                        <option value="">Seleccionar un país</option>
                    </select>
                    <!-- <input type="text" class="form-control" name="paisDocente" id="paisDocente"> -->
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ciudad:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="ciudadExperienciaDocente" id="ciudadDocente" placeholder="Ej: Piura">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre del Programa Profesional o Unidad Funcional de la Empresa:<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="programaProfesionalExperienciaDocente" id="programaProfesional" placeholder="Ej: Ingeniería de Sistemas">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Curso o Capacitación Impartido:<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="cursoCapacitacionImpartidoExperienciaDocente" id="cursosImpartidos" placeholder="Ej: Sistemas Operativos">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Funciones Principales:</label>
                    <textarea name="funcionesPrincipales" id="funcionesPrincipales" class="form-control" rows="3"
                        placeholder="Ej: Dictado de talleres, capacitaciones y/o seminarios"></textarea>
                    <small id="contadorObservacionesPrincipales" class="text-muted">Máximo 150 caracteres.
                        caracteres
                        actuales: 0</small>
                    <div id="errorObservaciones" class="text-danger mt-1" style="display: none;">
                        Has excedido el límite de 150 caracteres.
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Inicio:<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="fechaInicioExperienciaDocente" id="fechaInicioDocente">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Retiro:<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="fechaRetiroExperienciaDocente" id="fechaRetiroDocente">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Adjuntar PDF:<span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="pdfExperienciaDocente" id="pdfExperienciaDocente"
                        accept="application/pdf">
                    <small class="form-text text-muted">Solo se permiten archivos PDF con un tamaño máximo de 5
                        MB.</small>
                </div>

                <!-- Botón para agregar un nuevo registro -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haz clic aquí para agregar más información sobre su experiencia laboral como docente en educación superior"
                        onclick="agregarExperienciaDocente()">
                        Registrar Experiencia
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- Tabla para muestrar los registros agregados -->
            <table class="table table-striped required">
                <thead>
                    <tr>
                        <th scope="col">Institución</th>
                        <th scope="col">País</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col">Nombre del Programa</th>
                        <th scope="col">Curso o Capacitación</th>
                        <th scope="col">Funciones Principales</th>
                        <th scope="col">Fecha Inicio</th>
                        <th scope="col">Fecha Fin</th>
                        <th scope="col">Anexos</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaExperienciaDocente">
                    <!-- aca se mostraran las filas agregadas -->
                </tbody>
            </table>

            <!-- Alerta de Nota -->
            <div class="alert alert-info mt-3 p-2" role="alert">
                <ul class="list-unstyled small mb-0">
                    <li><i class="fas fa-info-circle me-2"></i><strong>Nota:</strong> En caso de presentar más experiencias en docencia en educación superior y/o formación
                        continua, favor adicionar las celdas que sean necesarias.</li>
                </ul>
            </div>

            <!-- seccion 4.3: experiencia como parte de comité de calidad u oficina de acreditación / calidad -->
            <div class="section-title">
                <i class="fa-solid fa-ranking-star"></i>
                <h4>Experiencia como parte de comité de calidad u oficina de acreditación / calidad</h4>
            </div>

            <div class="row g-3 formulario">
                <div class="col-md-4">
                    <label class="form-label">Institución:</label>
                    <input type="text" class="form-control" name="institucionComite" id="institucionComite" placeholder="Ej: Universidad de los Andes">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cargo desempeñado:</label>
                    <input type="text" class="form-control" name="cargoComite" id="cargoComite" placeholder="Ej: Comité de calidad">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Modelos de Calidad Desarrollados:</label>
                    <input type="text" class="form-control" name="modelosCalidad" id="modelosCalidad"
                        placeholder=" (ICACIT, SINEACE, otros)">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Inicio:</label>
                    <input type="date" class="form-control" name="fechaInicioComite" id="fechaInicioComite">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Retiro:</label>
                    <input type="date" class="form-control" name="fechaRetiroComite" id="fechaRetiroComite">
                </div>

                <!-- Botón para agregar un nuevo registro -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haz clic aquí para agregar más información sobre su experiencia laboral como parte de comité de calidad"
                        onclick="agregarExperienciaComite()">
                        Registrar Experiencia
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- tabla de contenido -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Institución</th>
                        <th scope="col">Cargo Desempeñado</th>
                        <th scope="col">Modelos de Calidad</th>
                        <th scope="col">Fecha inicio</th>
                        <th scope="col">Fecha fin</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaExperienciaComite">
                    <!-- aca se mostraran las filas agregadas -->
                </tbody>
            </table>

            <!-- Alerta de Nota -->
            <div class="alert alert-info mt-3 p-2" role="alert">
                <ul class="list-unstyled small mb-0">
                    <li><i class="fas fa-info-circle me-2"></i><strong>Nota:</strong> En caso de presentar más experiencias, favor adicionar las celdas que sean necesarias.</li>
                </ul>
            </div>

            <!-- seccion 4.4: experiencia como par evaluador -->
            <div class="section-title">
                <i class="fa-solid fa-list-check"></i>
                <h4>Experiencia como par evaluador</h4>
            </div>
            <div class="row g-3 formulario">
                <div class="col-md-4">
                    <label class="form-label">Agencia Acreditadora:</label>
                    <input type="text" class="form-control" name="agenciaAcreditadora" id="agenciaAcreditadora" placeholder="Ej: ICACIT">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Año de Inicio:</label>
                    <select class="form-control" name="fechaInicioEvaluador" id="fechaInicioEvaluador">
                        <!-- Opciones para el año de inicio -->
                        <option value="">--Seleccionar año--</option>
                        <!-- Las opciones se generan dinámicamente con JavaScript -->
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Año de Vigencia:</label>
                    <select class="form-control" name="fechaRetiroEvaluador" id="fechaRetiroEvaluador">
                        <!-- Opciones para el año de vigencia -->
                        <option value="">--Seleccionar año--</option>
                        <!-- Las opciones se generan dinámicamente con JavaScript -->
                    </select>
                </div>
                <!-- estos datos se mostraran en la tabla -->
                <div class="col-md-4">
                    <label class="form-label">Nombre Entidad:</label>
                    <input type="text" class="form-control" name="nombreEntidad" id="nombreEntidad" placeholder="Ej: Universidad de Lima">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Programa:</label>
                    <input type="text" class="form-control" name="programaEvaluador" id="programaEvaluador" placeholder="Ej: Ingeniería de Sistemas">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cargo:</label>
                    <input type="text" class="form-control" name="cargoEvaluador" id="cargoEvaluador" placeholder="Ej: Evaluador">
                </div>
                <div class="col-md-3">
                    <label class="form-label">País:</label>
                    <select class="form-control" name="paisEvaluador" id="paisEvaluador">
                        <option value="">Seleccionar un país</option>
                    </select>
                    <!-- <input type="text" class="form-control" name="paisEvaluador" id="paisEvaluador"> -->
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ciudad:</label>
                    <input type="text" class="form-control" name="ciudadEvaluador" id="ciudadEvaluador" placeholder="Ej: Lima">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha de Evaluación:</label>
                    <input type="date" class="form-control" name="fechaEvaluacion" id="fechaEvaluacion">
                </div>

                <!-- Botón para agregar un nuevo registro -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haz clic aquí para agregar más información sobre su experiencia laboral como par evaluador"
                        onclick="agregarExperienciaEvaluador()">
                        Registrar Experiencia
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- tabla de contenido -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Agencia Acreditadora</th>
                        <th scope="col">Año Inicio</th>
                        <th scope="col">Año Vigencia</th>
                        <th scope="col">Nombre Entidad</th>
                        <th scope="col">Programa</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">País</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col">Fecha de Evaluación</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaExperienciaEvaluador">
                    <!-- aca se mostraran las filas agregadas -->
                </tbody>
            </table>

            <!-- seccion 4.5: membresias en asociaciones profesionales -->
            <div class="section-title">
                <i class="fa-solid fa-handshake-angle"></i>
                <h4>Membresías en Asociaciones Profesionales Vigentes</h4>
            </div>
            <div class="row g-3 formulario">
                <div class="col-md-4">
                    <label class="form-label">Asociación Profesional:</label>
                    <input type="text" class="form-control" name="asociacionProfesional" id="asociacionProfesional" placeholder="Ej: IEEE">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Número de la membresía:</label>
                    <input type="number" class="form-control" name="numeroMembresia" id="numeroMembresia" placeholder="Ej: 123456789">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Grado:</label>
                    <input type="text" class="form-control" name="gradoMembresia" id="gradoMembresia"
                        placeholder="Afiliado o Senior">
                </div>

                <!-- Botón para agregar un nuevo registro -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haz clic aquí para agregar más información sobre sus membresías en asociaciones profesionales "
                        onclick="agregarMembresia()">
                        Registrar Experiencia
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- tabla de contenido -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Asociación Profesional</th>
                        <th scope="col">Número de la Membresía</th>
                        <th scope="col">Grado</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaMembresias">
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
                <button type="button" class="btn btn-success" id="confirmarEnvioBtn" data-form-id="formulario_seccion4">Confirmar</button>
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
<script src="<?php echo URL; ?>js/Validaciones/validarExperiencia.js"></script>
<!-- funciones de tabla -->
<script src="<?php echo URL; ?>js/formFunctions.js"></script>
<!-- Servicios -->
<script src="<?php echo URL; ?>js/services/serviceCountry.js"></script>
<script src="<?php echo URL; ?>js/services/serviceNationalities.js"></script>
<!-- script para procesar los datos de las tablas -->
<script src="<?php echo URL; ?>js/procesar_tablas/recolectarDatosTablasExperiencia.js"></script>

<!-- Variables Globales -->
<script>
    var URL = '<?php echo URL; ?>';
</script>

<!-- Recuperar Datos -->
<script src="<?php echo URL; ?>js/2_recuperar_datos/recuperarDatosSeccion4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>