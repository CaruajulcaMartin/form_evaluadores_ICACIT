
<header class="container header">
  <img src="<?php echo URL; ?>images/ICACIT_2025.jpg" alt="logo de ICACIT">
  <h1 class="title">Formulario de Inscripción - Evaluador ICACIT</h1>
</header>

<main class="container main">
  <!-- Barra de progreso -->
  <div class="progress mb-4">
    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 14%" aria-valuenow="14"
      aria-valuemin="0" aria-valuemax="100">Paso 1 de 7</div>
  </div>

  <form class="row g-3 formulario" action="<?php echo URL; ?>Home/enviarFormulario/" method="POST" id="formularioEvaluador" enctype="multipart/form-data">
    <!-- seccion 1: informacion personal -->
    <div class="form-section" id="section1">
      <h2><b>Sección 01:</b> Información del Postulante</h2>
      <div class="section-title">
        <i class="fa-regular fa-address-card"></i>
        <h4>Datos Personales</h4>
      </div>

      <div class="row g-3 formulario">
        <div class="col-md-4">
          <label class="form-label">PrimerApellido:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="apellido1" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Segundo Apellido:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="apellido2" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Nombres Completos:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="nombresCompletos" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Tipo de Identidad:<span class="text-danger">*</span></label>
          <select class="form-select" name="tipoIdentidad" id="tipoIdentidad" required>
            <option value="" selected>--Seleccionar--</option>
            <option value="DNI (Documento Nacional de Identidad)">DNI (Documento Nacional de Identidad)
            </option>
            <option value="Pasaporte">Pasaporte</option>
            <option value="Carnet de Extranjeria">Carnet de Extranjería</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Numero de Identidad:<span class="text-danger">*</span></label>
          <input type="text" name="numDoc" id="tipoIdentidad" class="form-control" required disabled>
        </div>

        <!-- archivo de identidad adjunto -->
        <div class="col-md-4">
          <label class="form-label">Adjuntar Documento de Identidad:<span
              class="text-danger">*</span></label>
          <input type="file" class="form-control" name="pdfDocumentoIdentidad" id="pdfDocumentoIdentidad"
            accept="application/pdf" required>
          <small class="form-text text-muted">Solo se permiten archivos PDF con un tamaño máximo de 5
            MB.</small>
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
          <input type="date" name="fechaNacimiento" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Estado Civil:<span class="text-danger">*</span></label>
          <select class="form-select" name="estadoCivil" required>
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
            required>
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
            <input type="url"  name="redProfesional" class="form-control" id="basic-url"
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
            <select class="form-select" name="tipoDireccion" required>
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
            <input type="text" class="form-control" name="direccion" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Número:<span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="numeroDireccion" min="1" step="1" required>
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
          <input type="text" class="form-control" name="provinciaDatoDominicial" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Distrito:</label>
          <input type="text" class="form-control" name="distritoDatoDominicial">
        </div>
        <div class="col-md-12">
          <label class="form-label">Referencia del Domicilio:</label>
          <textarea name="referenciaDomicilio" id="referenciaDomicilio" class="form-control" rows="3"></textarea>
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

      <button type="button" class="btn btn-primary mt-3 next" onclick="nextSection()">Siguiente</button>
    </div>

    <!-- seccion 2: informacion laboral actual -->
    <div class="form-section" id="section2">
      <h2><b>Sección 02:</b> Información Laboral Actual</h2>
      <div class="section-title">
        <i class="fa-solid fa-building-user"></i>
        <h4>Datos del Centro Laboral Actual</h4>
      </div>

      <div class="row g-3 formulario">
        <div class="col-md-4">
          <label class="form-label">Nombre del Centro Laboral:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="centroLaboral" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Cargo Actual:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="cargoActual" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Tiempo en el centro laboral (en años):<span
              class="text-danger">*</span></label>
          <input type="number" class="form-control" name="tiempoLaboral" min="1" step="1" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">País:<span class="text-danger">*</span></label>
          <select class="form-control" name="PaisInformacionLaboral" id="PaisInformacionLaboral" required>
            <option value="">Seleccionar un país</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Ciudad:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="ciudadInformacionLaboral" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Rubro:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="rubroInformacionLaboral" required>
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
          <input type="text" class="form-control" name="nombresEmpleador" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Cargo del Empleador Actual:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="cargoEmpleador" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Correo Electrónico del Empleador Actual:<span
              class="text-danger">*</span></label>
          <input type="email" class="form-control" name="correoEmpleador" required>
        </div>
      </div>

      <button type="button" class="btn btn-secondary mt-3 prev" onclick="prevSection()">Atrás</button>
      <button type="button" class="btn btn-primary mt-3 next" onclick="nextSection()">Siguiente</button>
    </div>

    <!-- seccion 3: informacion academica -->
    <div class="form-section" id="section3">
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
            max="2099" step="1">
        </div>
        <div class="col-md-4">
          <label class="form-label">Universidad:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="institucionEducativa" id="institucionEducativa">
        </div>
        <!-- <div class="col-md-4">
                        <label class="form-label">Especialidad:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="especialidad" id="especialidad">
                    </div> -->
        <div class="col-md-4">
          <label class="form-label">Nombre del Grado:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="nombreGrado" id="nombreGrado">
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
          <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
            data-bs-title="Haz clic aquí para agregar más información sobre tu formación académica"
            onclick="agregarFormacion()">
            Agregar Formación Académica
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
      <table class="table table-striped required">
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
      <div class="alert alert-info mt-3 p-2 d-flex align-items-center" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <div class="small">
          <a class="text-decoration-none" data-bs-toggle="collapse" href="#notaCollapse2" role="button">
            <strong>Nota:</strong> Ver detalles...
          </a>
          <div class="collapse" id="notaCollapse2">
            En caso de contar con más registros de formación académica, favor
            adicionar las filas que sean necesarias.
          </div>
        </div>
      </div>

      <!-- seccion 3.2: idiomas -->
      <div class="section-title">
        <i class="fa-solid fa-language"></i>
        <h4>Idiomas</h4>
      </div>

      <div class="row g-3 formulario">
        <div class="col-md-2">
          <label class="form-label">Idioma:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="idioma" id="idioma">
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
            Agregar Idiomas
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
        <h4>Cursos y Seminarios (últimos 3 años)</h4>
      </div>

      <!-- seccion 3.3.1: relacionados a su campo profesional -->
      <div class="subsection-title row g-3 formulario">
        <!-- Subsección: Campo Profesional -->
        <h5>Relacionados a su campo profesional:</h5>
        <div class="row g-3">
          <div class="col-md-2">
            <label class="form-label">Año:<span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="anoCertificadoCampoProfesional"
              id="anoCertificadoCampoProfesional" min="1900" max="2099" step="1">
          </div>
          <div class="col-md-3">
            <label class="form-label">Institución:<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="institucionCampoProfesional"
              id="institucionCampoProfesional">
          </div>
          <div class="col-md-3">
            <label class="form-label">Nombre del Curso o Seminario:<span
                class="text-danger">*</span></label>
            <input type="text" class="form-control" name="cursoSeminarioCampoProfesional"
              id="cursoSeminarioCampoProfesional">
          </div>
          <div class="col-md-2">
            <label class="form-label">Duración (8 horas mínimo):<span
                class="text-danger">*</span></label>
            <input type="number" class="form-control" name="duracionCampoProfesional"
              id="duracionCampoProfesional" min="8" max="360" step="1">
          </div>
          <!-- Botón para agregar un nuevo registro -->
          <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
              data-bs-placement="top" data-bs-custom-class="custom-tooltip"
              data-bs-title="Haz clic aquí para agregar más información sobre tus cursos y seminarios en relacion a tu campo profesional"
              onclick="agregarCursosAmbitoProfesional()">
              Agregar Cursos
              <i class="fa-solid fa-plus"></i>
            </button>
          </div>
        </div>

        <!-- Tabla de Campo Profesional -->
        <table class="table table-striped required" data-title="Relacionados a su campo profesional:">
          <thead>
            <tr>
              <th scope="col">Año</th>
              <th scope="col">Institución</th>
              <th scope="col">Curso o Seminario</th>
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
        <h5>Relacionados a su ámbito académico:</h5>
        <div class="row g-3 formulario">
          <div class="col-md-2">
            <label class="form-label">Año:<span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="anoCertificadoAmbitoAcademico"
              id="anoCertificadoAmbitoAcademico" min="1900" max="2099" step="1">
          </div>
          <div class="col-md-3">
            <label class="form-label">Institución:<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="institucionAmbitoAcademico"
              id="institucionAmbitoAcademico">
          </div>
          <div class="col-md-3">
            <label class="form-label">Nombre del Curso o Seminario:<span
                class="text-danger">*</span></label>
            <input type="text" class="form-control" name="cursoSeminarioAmbitoAcademico"
              id="cursoSeminarioAmbitoAcademico">
          </div>
          <div class="col-md-2">
            <label class="form-label">Duración (8 horas mínimo):<span
                class="text-danger">*</span></label>
            <input type="number" class="form-control" name="duracionAmbitoAcademico"
              id="duracionAmbitoAcademico" min="8" max="360" step="1">
          </div>

          <!-- Botón para agregar un nuevo registro -->
          <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
              data-bs-placement="top" data-bs-custom-class="custom-tooltip"
              data-bs-title="Haz clic aquí para agregar más información sobre tus cursos y seminarios en relacion a su ámbito académico"
              onclick="agregarCursosAmbitoAcademico()">
              Agregar Cursos
              <i class="fa-solid fa-plus"></i>
            </button>
          </div>
        </div>

        <table class="table table-striped required">
          <thead>
            <tr>
              <th scope="col">Año</th>
              <th scope="col">Institución</th>
              <th scope="col">Curso o Seminario</th>
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
        <h5>Relacionados a su ámbito de evaluación con fines de acreditación:</h5>
        <div class="row g-3 formulario">
          <div class="col-md-2">
            <label class="form-label">Año:<span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="anoCertificado" id="anoCertificado"
              min="1900" max="2099" step="1">
          </div>
          <div class="col-md-3">
            <label class="form-label">Institución:<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="institucion" id="institucion">
          </div>
          <div class="col-md-3">
            <label class="form-label">Nombre del Curso o Seminario:<span
                class="text-danger">*</span></label>
            <input type="text" class="form-control" name="cursoSeminario" id="cursoSeminario">
          </div>
          <div class="col-md-2">
            <label class="form-label">Duración (8 horas mínimo):<span
                class="text-danger">*</span></label>
            <input type="number" class="form-control" name="duracion" id="duracion" min="8" max="360"
              step="1">
          </div>

          <!-- Botón para agregar un nuevo registro -->
          <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
              data-bs-placement="top" data-bs-custom-class="custom-tooltip"
              data-bs-title="Haz clic aquí para agregar más información sobre tus cursos y seminarios en relacion a su ámbito de evaluación con fines de acreditación"
              onclick="agregarCursos()">
              Agregar Cursos
              <i class="fa-solid fa-plus"></i>
            </button>
          </div>
        </div>

        <table class="table table-striped required">
          <thead>
            <tr>
              <th scope="col">Año</th>
              <th scope="col">Institución</th>
              <th scope="col">Curso o Seminario</th>
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

      <button type="button" class="btn btn-secondary mt-3 prev" onclick="prevSection()">Atrás</button>
      <button type="button" class="btn btn-primary mt-3 next" onclick="nextSection()">Siguiente</button>
    </div>

    <!-- seccion 4: información sobre experiencia laboral  -->
    <div class="form-section" id="section4">
      <h2><b>Sección 04:</b> Información Sobre Experiencia Laboral</h2>

      <!-- seccion 4.1: experiencia laboral -->
      <div class="section-title">
        <i class="fa-solid fa-briefcase"></i>
        <h4>Experiencia laboral en su campo profesional (evidenciar mínimo 10 años)</h4>
      </div>

      <div class="row g-3 formulario">
        <div class="col-md-4">
          <label class="form-label">Institución o Empresa:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="institucionEmpresa" id="institucionEmpresa">
        </div>
        <div class="col-md-4">
          <label class="form-label">Cargo desempeñado:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="cargoDesempeñado" id="cargoDesempeñado">
        </div>
        <div class="col-md-2">
          <label class="form-label">Fecha de Inicio:<span class="text-danger">*</span></label>
          <input type="date" class="form-control" name="fechaInicio" id="fechaInicio">
        </div>
        <div class="col-md-2">
          <label class="form-label">Fecha de Retiro:<span class="text-danger">*</span></label>
          <input type="date" class="form-control" name="fechaRetiro" id="fechaRetiro">
        </div>
        <div class="col-md-4">
          <label class="form-label">País:<span class="text-danger">*</span></label>
          <select class="form-control" name="paisEmpresa" id="paisEmpresa">
            <option value="">Seleccionar un país</option>
          </select>
          <!-- <input type="text" class="form-control" name="paisEmpresa" id="paisEmpresa"> -->
        </div>
        <div class="col-md-4">
          <label class="form-label">Ciudad:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="ciudad" id="ciudadEmpresa">
        </div>
        <div class="col-md-4">
          <label class="form-label">Adjuntar PDF:<span class="text-danger">*</span></label>
          <input type="file" class="form-control" name="pdfExperiencia" id="pdfExperiencia"
            accept="application/pdf">
          <small class="form-text text-muted">Solo se permiten archivos PDF con un tamaño máximo de 5
            MB.</small>
        </div>

        <!-- Botón para agregar un nuevo registro -->
        <div class="col-md-2 d-flex align-items-end">
          <button type="button" class="btn btn-success btn-agregar" data-bs-toggle="tooltip"
            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
            data-bs-title="Haz clic aquí para agregar más información sobre su experiencia laboral"
            onclick="agregarExperiencia()">
            Agregar Experiencia
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
          <input type="text" class="form-control" name="institucionDocente" id="institucionDocente">
        </div>
        <div class="col-md-4">
          <label class="form-label">País:<span class="text-danger">*</span></label>
          <select class="form-control" name="paisDocente" id="paisDocente">
            <option value="">Seleccionar un país</option>
          </select>
          <!-- <input type="text" class="form-control" name="paisDocente" id="paisDocente"> -->
        </div>
        <div class="col-md-4">
          <label class="form-label">Ciudad:<span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="ciudadDocente" id="ciudadDocente">
        </div>
        <div class="col-md-6">
          <label class="form-label">Nombre del Programa Profesional o Unidad Funcional de la Empresa:<span
              class="text-danger">*</span></label>
          <input type="text" class="form-control" name="programaProfesional" id="programaProfesional">
        </div>
        <div class="col-md-6">
          <label class="form-label">Curso o Capacitación Impartido:<span
              class="text-danger">*</span></label>
          <input type="text" class="form-control" name="cursosImpartidos" id="cursosImpartidos">
        </div>
        <div class="col-md-12">
          <label class="form-label">Funciones Principales:</label>
          <textarea name="funcionesPrincipales" id="funcionesPrincipales" class="form-control" rows="3"
            placeholder="Ingrese las funciones principales"></textarea>
          <small id="contadorObservacionesPrincipales" class="text-muted">Máximo 150 caracteres.
            caracteres
            actuales: 0</small>
          <div id="errorObservaciones" class="text-danger mt-1" style="display: none;">
            Has excedido el límite de 150 caracteres.
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Fecha de Inicio:<span class="text-danger">*</span></label>
          <input type="date" class="form-control" name="fechaInicioDocente" id="fechaInicioDocente">
        </div>
        <div class="col-md-4">
          <label class="form-label">Fecha de Retiro:<span class="text-danger">*</span></label>
          <input type="date" class="form-control" name="fechaRetiroDocente" id="fechaRetiroDocente">
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
            Agregar Experiencia
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
          <input type="text" class="form-control" name="institucionComite" id="institucionComite">
        </div>
        <div class="col-md-4">
          <label class="form-label">Cargo desempeñado:</label>
          <input type="text" class="form-control" name="cargoComite" id="cargoComite">
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
            Agregar Experiencia
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
          <input type="text" class="form-control" name="agenciaAcreditadora" id="agenciaAcreditadora">
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
          <input type="text" class="form-control" name="nombreEntidad" id="nombreEntidad">
        </div>
        <div class="col-md-4">
          <label class="form-label">Programa:</label>
          <input type="text" class="form-control" name="programaEvaluador" id="programaEvaluador">
        </div>
        <div class="col-md-4">
          <label class="form-label">Cargo:</label>
          <input type="text" class="form-control" name="cargoEvaluador" id="cargoEvaluador">
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
          <input type="text" class="form-control" name="ciudadEvaluador" id="ciudadEvaluador">
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
            Agregar Experiencia
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
      </div>

      <!-- tabla de contenido -->
      <table class="table table-striped">
        <thead>
          <tr>
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
          <input type="text" class="form-control" name="asociacionProfesional" id="asociacionProfesional">
        </div>
        <div class="col-md-3">
          <label class="form-label">Número de la membresía:</label>
          <input type="number" class="form-control" name="numeroMembresia" id="numeroMembresia">
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
            Agregar Experiencia
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

      <button type="button" class="btn btn-secondary mt-3 prev" onclick="prevSection()">Atrás</button>
      <button type="button" class="btn btn-primary mt-3 next" onclick="nextSection()">Siguiente</button>
    </div>

    <!-- seccion5:  Información Sobre Investigación. -->
    <div class="form-section" id="section5">
      <h2><b>Sección 05:</b> Información Sobre Investigación</h2>
      <!-- seccion 5.1:  Publicaciones, Artículos y Revistas -->
      <div class="section-title">
        <i class="fa-brands fa-readme"></i>
        <h4>Publicaciones, Artículos y Revistas</h4>
      </div>

      <div class="row g-3 formulario">
        <div class="col-md-2">
          <label class="form-label">Año:</label>
          <input type="date" class="form-control" name="fechaPublicacion" id="fechaPublicacion">
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
          <input type="text" class="form-control" name="nombreInvestigacion" id="nombreInvestigacion">
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
            Agregar Investigación
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



      <button type="button" class="btn btn-secondary mt-3 prev" onclick="prevSection()">Atrás</button>
      <button type="button" class="btn btn-primary mt-3 next" onclick="nextSection()">Siguiente</button>
    </div>

    <!-- seccion 6:Premios y Reconocimientos. -->
    <div class="form-section" id="section6">
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
            min="1900" max="2099" step="1">
        </div>
        <div class="col-md-5">
          <label class="form-label">Institución / Empresa:</label>
          <input type="text" class="form-control" name="institucionReconocimiento"
            id="institucionReconocimiento">
        </div>
        <div class="col-md-5">
          <label class="form-label">Nombre del Reconocimiento / Premio:</label>
          <input type="text" class="form-control" name="nombreReconocimiento" id="nombreReconocimiento">
        </div>
        <div class="col-md-6">
          <label class="form-label">Descripción Reconocimiento / Premio:</label>
          <textarea name="descripcionReconocimiento" id="descripcionReconocimiento" class="form-control"
            rows="4"></textarea>
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
            Agregar Reconocimientos
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

      <button type="button" class="btn btn-secondary mt-3 prev" onclick="prevSection()">Atrás</button>
      <button type="button" class="btn btn-primary mt-3 next" onclick="nextSection()">Siguiente</button>
    </div>

    <!-- seccion 7: Carta de presentación. -->
    <div class="form-section" id="section7">
      <h2><b>Sección 07:</b> Carta de Presentación<span class="text-danger">*</span></h2>

      <!-- seccion 7.1:  Carta de presentación -->
      <div class="row g-3 formulario">
        <h5>Describa en 400 palabras su interés por ser parte del Exclusivo Staff Internacional de
          <b>Evaluadores ICACIT</b>
        </h5>
        <div class="col-12">
          <!-- <label class="form-label">Carta de Presentación:<span class="text-danger">*</span></label> -->
          <textarea name="descripcionContribucion" id="descripcionContribucion" class="form-control"
            rows="6" required></textarea>
          <small id="contadorPalabras" class="text-muted">Maximo 400 palabras. Palabras actuales:
            0</small>
          <div id="mensajeError" class="text-danger mt-1" style="display: none;"><b>Nota:</b> Has excedido
            el límite de 400 palabras como maximo.</div>
        </div>
      </div>

      <button type="button" class="btn btn-secondary mt-3 prev" onclick="prevSection()">Atrás</button>
      <button type="button" class="btn btn-primary mt-3 next" onclick="nextSection()">Siguiente</button>
    </div>

    <!-- seccion 8: valores eticos -->
    <div class="form-section" id="section8">
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

      <button type="button" class="btn btn-secondary mt-3 prev" onclick="prevSection()">Atrás</button>
      <button type="button" class="btn btn-primary mt-3 next"
        onclick="showPreviewInModal()">Previsualizar</button>
    </div>
    <button type="submit" class="btn btn-success">Enviar Formulario</button>
  </form>
</main>

<!-- modal para la previsualización -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <img src="<?php echo URL; ?>images/ICACIT_2025.jpg" alt="Logo ICACIT" class="modal-logo">
        <h5 class="modal-title" id="previewModalLabel">Previsualización del Formulario de Inscripción -
          Evaluador ICACIT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="previewModalBody">
        <!-- El contenido de la previsualización se inserta aquí -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-info" onclick="downloadPDF()">Descargar PDF</button>
        <!-- <button type="submit" class="btn btn-success" id="enviarFormulario" onclick="">Enviar Formulario</button> -->
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
