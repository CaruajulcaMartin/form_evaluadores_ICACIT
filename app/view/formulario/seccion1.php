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
    <form class="row g-3 formulario" action="<?php echo URL; ?>Formulario/enviarSeccion1" method="POST" id="formulario_seccion1" enctype="multipart/form-data">
        <!-- seccion 1: informacion personal -->
        <div class="form-section" id="section1">
            <!-- Reemplaza el botón de guardar existente por estos dos botones -->
            <button type="button" class="btn btn-primary mb-4" onclick="location.href='<?php echo URL; ?>Admin/HomeFormulario'">
                <i class="fa-solid fa-arrow-left"></i> Volver al Panel
            </button>
            <button type="button" class="btn-guardar btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#confirmarEnvioModal">
                <i class="fa-regular fa-floppy-disk"></i> Guardar
            </button>

            <h2><b>Sección 01:</b> Información del Postulante</h2>
            <div class="section-title">
                <i class="fa-regular fa-address-card"></i>
                <h4>Datos Personales</h4>
            </div>

            <div class="row g-3 formulario">
                <div class="col-md-4">
                    <label class="form-label">Primer Apellido:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="apellido1" id="apellido1" placeholder="Ej: Perez" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Segundo Apellido:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="apellido2" id="apellido2" placeholder="Ej: Lopez" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nombres Completos:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nombresCompletos" id="nombresCompletos" placeholder="Ej: Juan" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo de Identidad:<span class="text-danger">*</span></label>
                    <select class="form-select" name="tipoIdentidad" id="tipoIdentidad" required>
                        <option value="" selected>--Seleccionar--</option>
                        <option value="DNI (Documento Nacional de Identidad)">DNI (Documento Nacional de Identidad)</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="Carnet de Extranjeria">Carnet de Extranjería</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Numero de Identidad:<span class="text-danger">*</span></label>
                    <input type="text" name="numDoc" id="numDoc" class="form-control" placeholder="Ej: 12345678" required disabled>
                </div>

                <!-- archivo de identidad adjunto -->
                <div class="col-md-4">
                    <label class="form-label">Adjuntar Copia de Documento de Identidad:<span
                            class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="pdfDocumentoIdentidad" id="pdfDocumentoIdentidad"
                        accept="application/pdf" required>
                    <small class="form-text text-muted">Solo se permiten archivos PDF con un tamaño máximo de 5MB.</small>
                    <input type="hidden" id="hiddenPdfIdentidad" name="pdfIdentidadHidden">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="nationality">Nacionalidad:<span
                            class="text-danger">*</span></label>
                    <select class="form-control" name="nationality" id="nationality" required>
                        <option value="">Seleccionar Nacionalidad</option>
                    </select>
                    <!-- <input type="text" name="nacionalidad" class="form-control" required> -->
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Nacimiento:<span class="text-danger">*</span></label>
                    <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado Civil:<span class="text-danger">*</span></label>
                    <select class="form-select" name="estadoCivil" id="estadoCivil" required>
                        <option selected>--Seleccionar--</option>
                        <option>Soltero/a</option>
                        <option>Casado/a</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Foto de Perfil: <span class="text-danger">*</span></label>
                    <input type="file" name="fotoPerfil" id="fotoPerfil" accept="image/png, image/jpeg"
                        class="form-control" required>
                    <small class="form-text text-muted">Solo se permiten archivos JPG o PNG con un tamaño máximo de
                        5 MB.</small>
                    <div id="errorFotoPerfil" class="text-danger mt-1" style="display: none;">Formato inválido. Solo
                        JPG o PNG.</div>
                </div>

            </div>

            <!-- datos de contacto -->
            <div class="section-title">
                <i class="fa-regular fa-address-book"></i>
                <h4>Datos de Contacto </h4>
            </div>

            <div class="row g-3 formulario">
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico:<span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="correoElectronico" id="correoElectronico"
                        placeholder="Ej: minombre@example.com" required>
                </div>
                <!-- <div class="col-md-4">
                        <label class="form-label">Correo Electrónico Secundario:<span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="correoElectronicoSecundario" required>
                    </div> -->
                <div class="col-md-6">
                    <label class="form-label">Número de Celular:<span class="text-danger">*</span></label>
                    <!-- <input type="tel" class="form-control" name="celular" placeholder="Numero de Whatsapp" required> -->
                    <div class="input-group" id="phoneInput">
                        <select id="phoneCode" name="phoneCode" class="form-control" style="width: 120px;" required>
                            <option value="" selected disabled>--Código--</option>
                        </select>
                        <input type="tel" id="phoneNumber" class="form-control" name="celular"
                            placeholder="Número de Whatsapp" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="basic-url" class="form-label">Red Profesional:<span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon3">https://example.com/</span>
                        <input type="url" name="redProfesional" class="form-control" id="basic-url"
                            placeholder="LinkedIn, red interna de la universidad o empresa"
                            aria-describedby="basic-addon3 basic-addon4" required>
                    </div>
                </div>
            </div>

            <!-- datos domiciliarios -->
            <div class="section-title">
                <i class="fa-solid fa-location-dot"></i>
                <h4>Datos Domiciliarios</h4>
            </div>

            <div class="row g-3 formulario">
                <div class="input-group grupo">
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Dirección:<span class="text-danger">*</span></label>
                        <select class="form-select" name="tipoDireccion" id="tipoDireccion" required>
                            <option selected>--Seleccionar--</option>
                            <option>Avenida</option>
                            <option>Calle</option>
                            <option>Jirón</option>
                            <option>Pasaje</option>
                            <option>otro</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Dirección:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Ej: Av. Siempreviva" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Número:<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="numeroDireccion" id="numeroDireccion" placeholder="Ej: 123" min="1" step="1" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">País:<span class="text-danger">*</span></label>
                    <select class="form-control" name="PaisDatoDominicial" id="PaisDatoDominicial" required>
                        <option value="">--Seleccionar País--</option>
                    </select>
                    <!-- <input type="text" class="form-control" name="pais" required> -->
                </div>
                <div class="col-md-3">
                    <label class="form-label">Región / Estado:<span class="text-danger">*</span></label>
                    <select class="form-control" name="PaisDatoDominicialRegion" id="PaisDatoDominicialRegion"
                        required>
                        <option value="">--Seleccionar Región / Estado--</option>
                    </select>
                    <!-- <input type="text" class="form-control" name="region" required> -->
                </div>
                <div class="col-md-3">
                    <label class="form-label">Provincia / Municipio:<span class="text-danger">*</span></label>
                    <!-- <select class="form-control" name="PaisDatoDominicialProvincia" id="PaisDatoDominicialProvincia" required>
                            <option value="">--Seleccionar Provincia / Municipio--</option>
                        </select> -->
                    <input type="text" class="form-control" name="provinciaDatoDominicial" id="provinciaDatoDominicial" placeholder="Ej: Lima" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Distrito:</label>
                    <input type="text" class="form-control" name="distritoDatoDominicial" id="distritoDatoDominicial" placeholder="Ej: Miraflores">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Referencia del Domicilio:</label>
                    <textarea name="referenciaDomicilio" id="referenciaDomicilio" class="form-control" rows="3" placeholder="Ej: Cerca de la escuela"></textarea>
                    <small id="contadorReferenciaDomicilio" class="text-muted">Máximo 150 caracteres. Caracteres actuales:
                        0</small>
                    <div id="errorReferenciaDomicilio" class="text-danger mt-1" style="display: none;">
                        Has excedido el límite de 150 caracteres.
                    </div>
                </div>

                <!-- Alerta de Nota -->
                <div class="alert alert-info mt-3 p-2" role="alert">
                    <ul class="list-unstyled small mb-0">
                        <li><i class="fas fa-info-circle me-2"></i><strong>Nota:</strong> En caso su país no contemple la estructura geográfica de:
                            <strong>País / Región / Provincia / Distrito</strong>, favor de actualizar o añadir los
                            campos de la tabla para adecuarla a su estructura geográfica.
                        </li>
                    </ul>
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
                <button type="button" class="btn btn-success" id="confirmarEnvioBtn" data-form-id="formulario_seccion1">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="<?php echo URL; ?>public/js/validarFormulario.js"></script> -->

<!-- Validaciones -->
<script src="<?php echo URL; ?>js/Validaciones/ValidacionesGenerales.js"></script>
<script src="<?php echo URL; ?>js/Validaciones/validaciones.js"></script>
<!-- Servicios -->
<script src="<?php echo URL; ?>js/services/serviceCountry.js"></script>
<script src="<?php echo URL; ?>js/services/serviceNationalities.js"></script>
<script src="<?php echo URL; ?>js/services/servicePhone.js"></script>

<script>
    var URL = '<?php echo URL; ?>';
</script>

<!-- Recuperar Datos -->
<script src="<?php echo URL; ?>js/2_recuperar_datos/recuperarDatosSeccion1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- pora mejora -->
<!-- <style>
    .campo-modificado {
        border-left: 3px solid #ffc107 !important;
        background-color: rgba(255, 193, 7, 0.05);
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }

    #btnActualizar:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .spinner-border {
        vertical-align: middle;
        margin-right: 5px;
    }
</style> -->