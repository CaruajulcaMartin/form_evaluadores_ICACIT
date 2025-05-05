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
    <form class="row g-3 formulario" action="<?php echo URL; ?>Formulario/enviarSeccion3" method="POST" id="formulario_seccion3" enctype="multipart/form-data">
        <!-- seccion 3: informacion academica -->
        <div class="form-section" id="section3">
            <button type="button" class="btn btn-primary mb-4" onclick="location.href='<?php echo URL; ?>Admin/HomeFormulario'">
                <i class="fa-solid fa-arrow-left"></i> Volver al Panel
            </button>
            <button type="button" class="btn-guardar btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#confirmarEnvioModal">
                <i class="fa-regular fa-floppy-disk"></i> Guardar
            </button>

            <h2><b>Sección 03:</b> Información de Formación Académica</h2>

            <!-- seccion 3.1: formacion academica -->
            <div class="section-title">
                <i class="fa-solid fa-graduation-cap"></i>
                <h4>Formación Académica</h4>
            </div>

            <div class="row g-3 formulario">
                <div class="col-md-4">
                    <label class="form-label">Tipo de Formación:<span class="text-danger">*</span></label>
                    <select class="form-select" name="tipoFormacion" id="tipoFormacion">
                        <option selected>--Seleccionar--</option>
                        <option>Pregrado</option>
                        <option>Maestria</option>
                        <option>Doctorado</option>
                        <option>Posdoctorado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">País:<span class="text-danger">*</span></label>
                    <select class="form-control" name="paisFormacion" id="paisFormacion">
                        <option value="">Seleccionar un país</option>
                    </select>
                    <!-- <input type="text" class="form-control" name="paisFormacion" id="paisFormacion"> -->
                </div>
                <div class="col-md-4">
                    <label class="form-label">Año de Graduación:<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="anoGraduacion" id="anoGraduacion" min="1900"
                        max="2099" step="1" placeholder="Ej: 2020">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Universidad:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="institucionEducativa" id="institucionEducativa"
                        placeholder="Ej: Universidad Nacional de San Marcos">
                </div>
                <!-- <div class="col-md-4">
                        <label class="form-label">Especialidad:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="especialidad" id="especialidad">
                    </div> -->
                <div class="col-md-4">
                    <label class="form-label">Nombre del Grado:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nombreGrado" id="nombreGrado" placeholder="Ej: Ingeniero de Sistemas">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Adjuntar PDF:<span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="pdfFormacionAcademica" id="pdfFormacionAcademica"
                        accept="application/pdf">
                    <small class="form-text text-muted">Solo se permiten archivos PDF con un tamaño máximo de 5
                        MB.</small>
                </div>
                <!-- Botón para agregar un nuevo registro -->
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-agregar" id="btnAgregarFormacion" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haz clic aquí para agregar más información sobre tu formación académica"
                        onclick="agregarFormacion()">
                        Registrar Formación Académica
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

                <!-- Alerta de Nota -->
                <div class="alert alert-info mt-3 p-2" role="alert">
                    <ul class="list-unstyled small mb-0">
                        <li><i class="fas fa-info-circle me-2"></i><strong>Nota:</strong> Los grados de bachiller, maestría y/o doctorado deben ser en las
                            áreas de Ingeniería, Ciencias de la Computación o afines, Arquitectura y/o Ciencias (de las disciplinas de: Física, Matemáticas o Química).</li>
                    </ul>
                </div>
            </div>

            <!-- Tabla para mostrar los registros agregados -->
            <table class="table table-striped required" id="tablaFormacionAcademica">
                <thead>
                    <tr>
                        <th scope="col">Tipo</th>
                        <th scope="col">País</th>
                        <th scope="col">Año de Graduación</th>
                        <th scope="col">Universidad</th>
                        <th scope="col">Grado</th>
                        <th scope="col">Anexos</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaFormacion">
                    <!-- Aquí se mostrarán las filas agregadas -->
                </tbody>
            </table>

            <!-- Alerta de Nota -->
            <div class="alert alert-info mt-3 p-2" role="alert">
                <ul class="list-unstyled small mb-0">
                    <li><i class="fas fa-info-circle me-2"></i><strong>Nota:</strong> En caso de contar con más registros de formación académica, favor
                        adicionar las filas que sean necesarias.</li>
                </ul>
            </div>

            <!-- seccion 3.2: idiomas -->
            <div class="section-title">
                <i class="fa-solid fa-language"></i>
                <h4>Idiomas</h4>
            </div>

            <div class="row g-3 formulario">
                <div class="col-md-2">
                    <label class="form-label">Idioma:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="idioma" id="idioma" placeholder="Ej: Inglés">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Competencia Escrita:<span class="text-danger">*</span></label>
                    <select class="form-select" name="competenciaEscrita" id="competenciaEscrita">
                        <option selected>--Seleccionar--</option>
                        <option>Básico</option>
                        <option>Intermedio</option>
                        <option>Avanzado</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Competencia Lectora:<span class="text-danger">*</span></label>
                    <select class="form-select" name="competenciaLectora" id="competenciaLectora">
                        <option selected>--Seleccionar--</option>
                        <option>Básico</option>
                        <option>Intermedio</option>
                        <option>Avanzado</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Competencia Oral:<span class="text-danger">*</span></label>
                    <select class="form-select" name="competenciaOral" id="competenciaOral">
                        <option selected>--Seleccionar--</option>
                        <option>Básico</option>
                        <option>Intermedio</option>
                        <option>Avanzado</option>
                    </select>
                </div>

                <!-- Botón para agregar un nuevo registro -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haz clic aquí para agregar más información sobre tus idiomas que domines"
                        onclick="agregarIdioma()">
                        Registrar Idioma
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <table class="table table-striped required">
                <thead>
                    <tr>
                        <th scope="col">Idioma</th>
                        <th scope="col">Competencia Escrita</th>
                        <th scope="col">Competencia Lectora</th>
                        <th scope="col">Competencia Oral</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaIdiomas">
                    <!-- aca se mostraran las filas agregadas -->
                </tbody>
            </table>

            <!-- Alerta de Nota -->
            <div class="alert alert-info mt-3 p-2" role="alert">
                <ul class="list-unstyled small mb-0">
                    <li><i class="fas fa-info-circle me-2"></i><strong>Nota:</strong> En caso de contar con más registros de idiomas, favor adicionar las filas
                        que sean necesarias.</li>
                </ul>
            </div>

            <!-- seccion 3.3: cursos y seminarios -->
            <div class="section-title">
                <i class="fa-solid fa-user-graduate"></i>
                <h4>Participación de capacitaciones (últimos 3 años)</h4>
            </div>

            <!-- seccion 3.3.1: relacionados a su campo profesional -->
            <div class="subsection-title row g-3 formulario">
                <!-- Subsección: Campo Profesional -->
                <h5>a) Registre capacitaciones relacionadas a su campo profesional:</h5>
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Año:</label>
                        <input type="number" class="form-control" name="anoCertificadoCampoProfesional"
                            id="anoCertificadoCampoProfesional" min="1900" max="2099" step="1" placeholder="Ej: 2020">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Institución Educativa:</label>
                        <input type="text" class="form-control" name="institucionCampoProfesional"
                            id="institucionCampoProfesional" placeholder="Ej: Universidad Nacional de San Marcos">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre de la Capacitación:<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cursoSeminarioCampoProfesional"
                            id="cursoSeminarioCampoProfesional" placeholder="Ej: Introducción a la programación en Python">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo de Capacitación:</label>
                        <select class="form-select" name="tipoCursoSeminarioCampoProfesional" id="tipoCursoSeminarioCampoProfesional">
                            <option selected>--Seleccionar--</option>
                            <option>Diplomado</option>
                            <option>Curso</option>
                            <option>Taller</option>
                            <option>Seminario</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Duración <small>(8 horas mínimo)</small>:</label>
                        <input type="number" class="form-control" name="duracionCampoProfesional"
                            id="duracionCampoProfesional" min="8" max="360" step="1" placeholder="Ej: 20">
                    </div>
                    <!-- Botón para agregar un nuevo registro -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                            data-bs-title="Haz clic aquí para agregar más información sobre tus cursos y seminarios en relacion a tu campo profesional"
                            onclick="agregarCursosAmbitoProfesional()">
                            Registrar Curso
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Tabla de Campo Profesional -->
                <table class="table table-striped" data-title="Relacionados a su campo profesional:">
                    <thead>
                        <tr>
                            <th scope="col">Año</th>
                            <th scope="col">Institución</th>
                            <th scope="col">Curso o Seminario</th>
                            <th scope="col">Tipo de Capacitación</th>
                            <th scope="col">Duración (en horas)</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaCursosAmbitoProfesional">
                        <!-- Aquí se mostrarán las filas agregadas -->
                    </tbody>
                </table>
            </div>

            <div class="border-bottom border-secondary my-4"></div>

            <!-- seccion 3.3.2: relacionados a su ambito academico -->
            <div class="subsection-title row g-3 formulario">
                <h5>b) Registre capacitaciones relacionadas a su ámbito académico:</h5>
                <div class="row g-3 formulario">
                    <div class="col-md-2">
                        <label class="form-label">Año:</label>
                        <input type="number" class="form-control" name="anoCertificadoAmbitoAcademico"
                            id="anoCertificadoAmbitoAcademico" min="1900" max="2099" step="1" placeholder="Ej: 2022">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Institución Educativa:</label>
                        <input type="text" class="form-control" name="institucionAmbitoAcademico"
                            id="institucionAmbitoAcademico" placeholder="Ej: Universidad Nacional de Cajamarca">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre de la Capacitación:</label>
                        <input type="text" class="form-control" name="cursoSeminarioAmbitoAcademico"
                            id="cursoSeminarioAmbitoAcademico" placeholder="Ej: Taller de Excel Avanzado">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo de Capacitación:</label>
                        <select class="form-select" name="tipoCursoSeminarioAmbitoAcademico" id="tipoCursoSeminarioAmbitoAcademico">
                            <option selected>--Seleccionar--</option>
                            <option>Diplomado</option>
                            <option>Curso</option>
                            <option>Taller</option>
                            <option>Seminario</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Duración <small>(8 horas mínimo)</small>:</label>
                        <input type="number" class="form-control" name="duracionAmbitoAcademico"
                            id="duracionAmbitoAcademico" min="8" max="360" step="1" placeholder="Ej: 15">
                    </div>

                    <!-- Botón para agregar un nuevo registro -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                            data-bs-title="Haz clic aquí para agregar más información sobre tus cursos y seminarios en relacion a su ámbito académico"
                            onclick="agregarCursosAmbitoAcademico()">
                            Registrar Curso
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Año</th>
                            <th scope="col">Institución</th>
                            <th scope="col">Curso o Seminario</th>
                            <th scope="col">Tipo de Capacitación</th>
                            <th scope="col">Duración (en horas)</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaCursosAmbitoAcademico">
                        <!-- aca se mostraran las filas agregadas -->
                    </tbody>
                </table>
            </div>

            <div class="border-bottom border-secondary my-4"></div>

            <!-- seccion 3.3.3: relacionados a su ambito de evaluacion con fines de acreditacion -->
            <div class="subsection-title row g-3 formulario">
                <h5>c) Registre capacitaciones relacionadas a su ámbito de evaluación con fines de acreditación:</h5>
                <div class="row g-3 formulario">
                    <div class="col-md-2">
                        <label class="form-label">Año:</label>
                        <input type="number" class="form-control" name="anoCertificadoAmbitoEvaluacion" id="anoCertificado"
                            min="1900" max="2099" step="1" placeholder="Ej: 2021">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Institución Educativa:</label>
                        <input type="text" class="form-control" name="institucionAmbitoEvaluacion" id="institucion" placeholder="Ej: Universidad Nacional de Piura">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre de la Capacitación:</label>
                        <input type="text" class="form-control" name="cursoSeminarioAmbitoEvaluacion" id="cursoSeminario" placeholder="Ej: Taller de Programación Avanzada">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo de Capacitación:</label>
                        <select class="form-select" name="tipoCursoSeminarioAmbitoEvaluacion" id="tipoCursoSeminario">
                            <option selected>--Seleccionar--</option>
                            <option>Diplomado</option>
                            <option>Curso</option>
                            <option>Taller</option>
                            <option>Seminario</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Duración <small>(8 horas mínimo)</small>:</label>
                        <input type="number" class="form-control" name="duracionAmbitoEvaluacion" id="duracion" min="8" max="360"
                            step="1" placeholder="Ej: 10">
                    </div>

                    <!-- Botón para agregar un nuevo registro -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                            data-bs-title="Haz clic aquí para agregar más información sobre tus cursos y seminarios en relacion a su ámbito de evaluación con fines de acreditación"
                            onclick="agregarCursos()">
                            Registrar Curso
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Año</th>
                            <th scope="col">Institución</th>
                            <th scope="col">Curso o Seminario</th>
                            <th scope="col">Tipo de Capacitación</th>
                            <th scope="col">Duración (en horas)</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaCursos">
                        <!-- aca se mostraran las filas agregadas -->
                    </tbody>
                </table>
            </div>

            <!-- Alerta de Nota -->
            <div class="alert alert-info mt-3 p-2" role="alert">
                <ul class="list-unstyled small mb-0">
                    <li><i class="fas fa-info-circle me-2"></i><strong>Nota:</strong> En caso de haber recibido más cursos o seminarios, favor adicionar las filas que sean
                        necesarias.</li>
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
                <button type="button" class="btn btn-success" id="confirmarEnvioBtn" data-form-id="formulario_seccion3">Confirmar</button>
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
<script src="<?php echo URL; ?>public/js/Validaciones/ValidacionesGenerales.js"></script>
<script src="<?php echo URL; ?>public/js/Validaciones/validaciones.js"></script>

<!-- funciones de tabla -->
<script src="<?php echo URL; ?>public/js/formFunctions.js"></script>

<!-- script para procesar los datos de las tablas -->
<script src="<?php echo URL; ?>public/js/procesar_tablas/recolectarDatosTablas.js"></script>


<!-- Servicios -->
<script src="<?php echo URL; ?>public/js/services/serviceCountry.js"></script>
<script src="<?php echo URL; ?>public/js/services/serviceNationalities.js"></script>

<!-- Variables Globales -->
<script>
    var URL = '<?php echo URL; ?>';
</script>

<!-- Recuperar Datos -->
<script src="<?php echo URL; ?>js/2_recuperar_datos/recuperarDatosSeccion3.js"></script>