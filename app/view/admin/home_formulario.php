<?php
// Obtener el número de la última sección completada
$ultimaSeccionCompletada = $data['progress']['ultima_seccion_completada'] ?? 0;
$habilitadoHasta = $ultimaSeccionCompletada + 1; // Habilitar la siguiente sección

$formularioEnviado = $_SESSION['formulario_enviado'] ?? false;

// Si el formulario fue enviado, deshabilitar todas las secciones excepto la 9
if ($formularioEnviado) {
    $habilitadoHasta = 0; // Deshabilitar todas las secciones
}

// Preparar estados de las secciones
$estadoSecciones = [];
for ($i = 1; $i <= 9; $i++) {
    $estado = 'No iniciado';
    if (isset($data['progress']['estado_secciones'][$i])) {
        if ($data['progress']['estado_secciones'][$i]['completada']) {
            $estado = 'Completado';
        } elseif (!empty($data['progress']['estado_secciones'][$i]['datos_guardados'])) {
            $estado = 'En progreso';
        }
    }
    $estadoSecciones[$i] = $estado;
}
?>


<header class="header-section">
    <div class="img-header">
        <img src="<?php echo URL; ?>assets/logo ICACIT.png" alt="logo de ICACIT">
    </div>

    <div class="usuario-header">
        <?php if (isset($data['user'])): ?>
            <h3 class="user-name">
                <?php echo htmlspecialchars($data['user']['nombre'] . ' ' . $data['user']['apellido_paterno']); ?>
            </h3>
            <span><?php echo htmlspecialchars($data['user']['email']); ?></span>
        <?php else: ?>
            <p>Error: No se encontraron datos del usuario.</p>
        <?php endif; ?>
    </div>
</header>

<section class="container main-section mt-4 mb-4">
    <div class="main-header-titles">
        <?php if ($data['progress']['porcentaje_completado'] > 0): ?>
            <h1 class="mb-3">¡Bienvenido de nuevo, <?php echo htmlspecialchars($data['user']['nombre']); ?>!</h1>
            <p class="lead">Finaliza las 8 secciones para aplicar al Programa de Evaluadores ICACIT 2025</p>
        <?php else: ?>
            <h1 class="mb-3">¡Bienvenido, <?php echo htmlspecialchars($data['user']['nombre']); ?>!</h1>
            <p class="lead">Finaliza las 8 secciones para aplicar al Programa de Evaluadores ICACIT 2025</p>
        <?php endif; ?>
    </div>

    <div class="section-content">
        <!-- Progreso del formulario -->
        <div class="main-left">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="card-progreso">
                        <div class="title-card-progreso">
                            <h2 class="h3 mb-3">Tu Progreso</h2>
                            <!-- <p class="text-muted">Completa las 8 secciones para finalizar tu formulario</p> -->
                        </div>

                        <!-- <a href="#" class="btn-continuar">
                            Continuar donde lo dejaste
                        </a> -->

                    </div>

                    <!-- Progreso de las secciones -->
                    <div class="progreso-secciones">
                        <div class="title-secciones">
                            <h3 class="titulo-progreso">Finalización total</h3>
                            <h4 class="porcentaje-progreso"><?php echo $data['progress']['porcentaje_completado']; ?>%</h4>
                        </div>

                        <!-- Barra de progreso -->
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: <?php echo $data['progress']['porcentaje_completado']; ?>%;"
                                aria-valuenow="<?php echo $data['progress']['porcentaje_completado']; ?>"
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <!-- Secciones del formulario-->
                    <div class="secciones-container">
                        <!-- Sección 01 -->
                        <div class="secciones-formulario">
                            <div class="seccion-titulo">
                                <div class="seccion-icon">
                                    <?php if ($estadoSecciones[1] == 'Completado'): ?>
                                        <i class="fa-regular fa-circle-check text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle text-muted me-2"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="title">
                                    <span class=""><b>Sección 01:</b> Información del Postulante</span>
                                    <span class="text-muted"><?php echo $estadoSecciones[1]; ?></span>
                                    <input type="hidden" id="estadoSeccion1" value="<?php echo $estadoSecciones[1]; ?>">
                                </div>
                            </div>

                            <a href="<?php echo $habilitadoHasta >= 1 ? URL . 'Formulario/Seccion1' : '#'; ?>"
                                class="btn-seccion <?php echo $habilitadoHasta >= 1 ? 'active' : 'disabled'; ?>">
                                <?php echo $habilitadoHasta >= 1 ? 'Ir a esta sección' : 'No disponible'; ?>
                            </a>
                        </div>

                        <!-- Sección 02 -->
                        <div class="secciones-formulario">
                            <div class="seccion-titulo">
                                <div class="seccion-icon">
                                    <?php if ($estadoSecciones[2] == 'Completado'): ?>
                                        <i class="fa-regular fa-circle-check text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle text-muted me-2"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="title">
                                    <span class=""><b>Sección 02:</b> Información Laboral Actual</span>
                                    <span class="text-muted"><?php echo $estadoSecciones[2]; ?></span>
                                    <input type="hidden" id="estadoSeccion2" value="<?php echo $estadoSecciones[2]; ?>">
                                </div>
                            </div>

                            <a href="<?php echo $habilitadoHasta >= 2 ? URL . 'Formulario/Seccion2' : '#'; ?>"
                                class="btn-seccion <?php echo $habilitadoHasta >= 2 ? 'active' : 'disabled'; ?>">
                                <?php echo $habilitadoHasta >= 2 ? 'Ir a esta sección' : 'No disponible'; ?>
                            </a>
                        </div>

                        <!-- Sección 03 -->
                        <div class="secciones-formulario">
                            <div class="seccion-titulo">
                                <div class="seccion-icon">
                                    <?php if ($estadoSecciones[3] == 'Completado'): ?>
                                        <i class="fa-regular fa-circle-check text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle text-muted me-2"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="title">
                                    <span class=""><b>Sección 03:</b> Información de Formación Académica</span>
                                    <span class="text-muted"><?php echo $estadoSecciones[3]; ?></span>
                                    <input type="hidden" id="estadoSeccion3" value="<?php echo $estadoSecciones[3]; ?>">
                                </div>
                            </div>

                            <a href="<?php echo $habilitadoHasta >= 3 ? URL . 'Formulario/Seccion3' : '#'; ?>"
                                class="btn-seccion <?php echo $habilitadoHasta >= 3 ? 'active' : 'disabled'; ?>">
                                <?php echo $habilitadoHasta >= 3 ? 'Ir a esta sección' : 'No disponible'; ?>
                            </a>
                        </div>

                        <!-- Sección 04 -->
                        <div class="secciones-formulario">
                            <div class="seccion-titulo">
                                <div class="seccion-icon">
                                    <?php if ($estadoSecciones[4] == 'Completado'): ?>
                                        <i class="fa-regular fa-circle-check text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle text-muted me-2"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="title">
                                    <span class=""><b>Sección 04:</b> Información Sobre Experiencia Laboral</span>
                                    <span class="text-muted"><?php echo $estadoSecciones[4]; ?></span>
                                    <input type="hidden" id="estadoSeccion4" value="<?php echo $estadoSecciones[4]; ?>">
                                </div>
                            </div>

                            <a href="<?php echo $habilitadoHasta >= 4 ? URL . 'Formulario/Seccion4' : '#'; ?>"
                                class="btn-seccion <?php echo $habilitadoHasta >= 4 ? 'active' : 'disabled'; ?>">
                                <?php echo $habilitadoHasta >= 4 ? 'Ir a esta sección' : 'No disponible'; ?>
                            </a>
                        </div>

                        <!-- Sección 05 -->
                        <div class="secciones-formulario">
                            <div class="seccion-titulo">
                                <div class="seccion-icon">
                                    <?php if ($estadoSecciones[5] == 'Completado'): ?>
                                        <i class="fa-regular fa-circle-check text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle text-muted me-2"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="title">
                                    <span class=""><b>Sección 05:</b> Información Sobre Investigación</span>
                                    <span class="text-muted"><?php echo $estadoSecciones[5]; ?></span>
                                    <input type="hidden" id="estadoSeccion5" value="<?php echo $estadoSecciones[5]; ?>">
                                </div>
                            </div>

                            <a href="<?php echo $habilitadoHasta >= 5 ? URL . 'Formulario/Seccion5' : '#'; ?>"
                                class="btn-seccion <?php echo $habilitadoHasta >= 5 ? 'active' : 'disabled'; ?>">
                                <?php echo $habilitadoHasta >= 5 ? 'Ir a esta sección' : 'No disponible'; ?>
                            </a>
                        </div>

                        <!-- Sección 06 -->
                        <div class="secciones-formulario">
                            <div class="seccion-titulo">
                                <div class="seccion-icon">
                                    <?php if ($estadoSecciones[6] == 'Completado'): ?>
                                        <i class="fa-regular fa-circle-check text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle text-muted me-2"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="title">
                                    <span class=""><b>Sección 06:</b> Premios y Reconocimientos</span>
                                    <span class="text-muted"><?php echo $estadoSecciones[6]; ?></span>
                                    <input type="hidden" id="estadoSeccion6" value="<?php echo $estadoSecciones[6]; ?>">
                                </div>
                            </div>

                            <a href="<?php echo $habilitadoHasta >= 6 ? URL . 'Formulario/Seccion6' : '#'; ?>"
                                class="btn-seccion <?php echo $habilitadoHasta >= 6 ? 'active' : 'disabled'; ?>">
                                <?php echo $habilitadoHasta >= 6 ? 'Ir a esta sección' : 'No disponible'; ?>
                            </a>
                        </div>

                        <!-- Sección 07 -->
                        <div class="secciones-formulario">
                            <div class="seccion-titulo">
                                <div class="seccion-icon">
                                    <?php if ($estadoSecciones[7] == 'Completado'): ?>
                                        <i class="fa-regular fa-circle-check text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle text-muted me-2"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="title">
                                    <span class=""><b>Sección 07:</b> Carta de Presentación</span>
                                    <span class="text-muted"><?php echo $estadoSecciones[7]; ?></span>
                                    <input type="hidden" id="estadoSeccion7" value="<?php echo $estadoSecciones[7]; ?>">
                                </div>
                            </div>

                            <a href="<?php echo $habilitadoHasta >= 7 ? URL . 'Formulario/Seccion7' : '#'; ?>"
                                class="btn-seccion <?php echo $habilitadoHasta >= 7 ? 'active' : 'disabled'; ?>">
                                <?php echo $habilitadoHasta >= 7 ? 'Ir a esta sección' : 'No disponible'; ?>
                            </a>
                        </div>

                        <!-- Sección 08 -->
                        <div class="secciones-formulario">
                            <div class="seccion-titulo">
                                <div class="seccion-icon">
                                    <?php if ($estadoSecciones[8] == 'Completado'): ?>
                                        <i class="fa-regular fa-circle-check text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle text-muted me-2"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="title">
                                    <span class=""><b>Sección 08:</b> Conducta y Valores Éticos</span>
                                    <span class="text-muted"><?php echo $estadoSecciones[8]; ?></span>
                                    <input type="hidden" id="estadoSeccion8" value="<?php echo $estadoSecciones[8]; ?>">
                                </div>
                            </div>

                            <a href="<?php echo $habilitadoHasta >= 8 ? URL . 'Formulario/Seccion8' : '#'; ?>"
                                class="btn-seccion <?php echo $habilitadoHasta >= 8 ? 'active' : 'disabled'; ?>">
                                <?php echo $habilitadoHasta >= 8 ? 'Ir a esta sección' : 'No disponible'; ?>
                            </a>
                        </div>

                        <!-- Sección 09 -->
                        <div class="secciones-formulario">
                            <div class="seccion-titulo">
                                <div class="seccion-icon">
                                    <?php if ($estadoSecciones[9] == 'Completado' || $estadoSecciones[9] == 'Info. Enviado' || $formularioEnviado): ?>
                                        <i class="fa-regular fa-circle-check text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle text-muted me-2"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="title">
                                    <span style="color: #2c3e50;" class=""><b>Sección 09:</b> Previsualizar y Confirmar Información</span>
                                </div>
                            </div>

                            <?php if ($formularioEnviado): ?>
                                <button type="button" class="btn btn-success" id="downloadPDF"><i class="fas fa-file-pdf me-2"></i> Descargar PDF</button>
                            <?php else: ?>
                                <button type="button" style="border: none;"
                                    id="btnPreview"
                                    class="btn-seccion <?php echo $habilitadoHasta >= 9 ? 'active' : 'disabled'; ?>"
                                    <?php echo $habilitadoHasta >= 9 ? 'data-bs-toggle="modal" data-bs-target="#previewModal"' : 'disabled'; ?>>
                                    <?php echo $habilitadoHasta >= 9 ? 'Previsualizar y Confirmar' : 'No disponible'; ?>
                                </button>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Modal para previsualizar y confirmar información -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <img src="<?php echo URL; ?>images/ICACIT_2025.jpg" alt="Logo ICACIT" class="modal-logo">
                <h5 class="modal-title" id="previewModalLabel">Previsualización del formulario de aplicación al Programa de Formación de Evaluadores ICACIT</h5>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <!-- Contenido de previsualización -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="confirmarEnvio">Confirmar Envío</button>
                <button type="button" class="btn btn-success" id="downloadPDF">Descargar PDF</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Envío -->
<div class="modal fade" id="confirmacionEnvioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">¡Envío Exitoso!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                <h4>¡Felicidades!</h4>
                <p>Su formulario de aplicación al programa de evaluadores ICACIT se ha enviado con éxito.</p>
                <p>Desde el correo <strong>evaluadores@icacit.org.pe</strong> recibirá la respuesta a su aplicación.</p>
                <p class="mb-0">Tenga buen día.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- ruta -->
<script>
    const URL = "<?php echo URL; ?>";
</script>

<script src="<?php echo URL; ?>public/js/2_recuperar_datos/mostrarTodosDatos.js"></script>

<!-- Incluir jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<!-- Incluir jsPDF autoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<!-- Incluir pdf-lib -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>