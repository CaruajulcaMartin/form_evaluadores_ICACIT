let tipoPregradoAgregado = false;
let tipoPosdoctoradoAgregado = false;
let experienciaEvaluadorAgregada = 0;
let fechasRegistradas = [];
let anexosTablasFormacionAcademica = [];
let anexosTablasExperienciaLaboral = [];

// Función para mostrar el modal de alerta
function mostrarAlerta(mensaje) {
    document.getElementById("alertModalBody").textContent = mensaje;
    $('#alertModal').modal('show');
}

// Función genérica para validar campos
function validarCampos(campos) {
    for (let campo of campos) {
        if (campo.value === "" || campo.value === "--Seleccionar--") {
            mostrarAlerta("Por favor, completar todos los campos.");
            return false;
        }
    }
    return true;
}

// Función genérica para validar archivos PDF
function validarPDF(pdfInput, maxSize = 5 * 1024 * 1024) {
    if (pdfInput.files.length === 0) {
        mostrarAlerta("Por favor, adjuntar un PDF.");
        return false;
    }

    let pdfFile = pdfInput.files[0];
    if (pdfFile.type !== "application/pdf") {
        mostrarAlerta("Por favor, adjuntar un archivo PDF válido.");
        return false;
    }

    if (pdfFile.size > maxSize) {
        mostrarAlerta("El archivo PDF no puede exceder los 5 MB.");
        return false;
    }

    return pdfFile;
}

// Función genérica para crear una fila en la tabla
function crearFila(tablaId, valores, incluirPDF = false) {
    let tabla = document.getElementById(tablaId);
    let fila = document.createElement("tr");

    // Verificar si las fechas ya están registradas
    if (tablaId === "tablaExperiencia" || tablaId === "tablaExperienciaDocente" || tablaId === "tablaExperienciaComite") {
        let fechaInicio = valores[2]; // Asumiendo que la fecha de inicio está en la posición 2
        let fechaFin = valores[3]; // Asumiendo que la fecha de fin está en la posición 3

        if (fechasRegistradas.includes(fechaInicio) || fechasRegistradas.includes(fechaFin)) {
            mostrarAlerta("Las fechas ya han sido registradas.");
            return;
        }

        // Agregar las fechas al arreglo de fechas registradas
        fechasRegistradas.push(fechaInicio);
        fechasRegistradas.push(fechaFin);
    }

    // Crear celdas con los valores proporcionados
    valores.forEach((valor) => {
        let celda = document.createElement("td");
        celda.textContent = valor;
        fila.appendChild(celda);
    });

    // Si hay un PDF adjunto, agregar la celda de anexo con la clase "pdf-icon"
    if (incluirPDF) {
        let pdfUrl = URL.createObjectURL(incluirPDF);
        let pdfIcon = `<a href="${pdfUrl}" target="_blank"><i class="fa-regular fa-file-pdf pdf-icon" style="color: red; font-size: 1.5em;"></i></a>`;
        let celdaAnexo = document.createElement("td");
        celdaAnexo.innerHTML = pdfIcon;
        fila.appendChild(celdaAnexo);

        // Agregar el PDF al arreglo de anexos
        /*if (tablaId === "tablaFormacion");
        if (tablaId === "tablaExperiencia");
        if (tablaId === "tablaExperienciaDocente");
        
        anexosTablas.push({file: incluirPDF});*/

        if (tablaId === "tablaFormacion") {
            anexosTablasFormacionAcademica.push({ file: incluirPDF });
        } else if (tablaId === "tablaExperiencia" || tablaId === "tablaExperienciaComite") {
            anexosTablasExperienciaLaboral.push({ file: incluirPDF });
        }
    }

    // Agregar la celda de acción (botón para eliminar la fila)
    let celdaAccion = document.createElement("td");
    celdaAccion.innerHTML = `<button type="button" class="btn btn-danger" onclick="eliminarFila(this)"><i class="fa-solid fa-trash"></i></button>`;
    fila.appendChild(celdaAccion);

    // Agregar la fila a la tabla
    tabla.appendChild(fila);
}

// Función genérica para limpiar campos
function limpiarCampos(campos) {
    campos.forEach(campo => {
        if (campo.tagName.toLowerCase() === 'select') {
            campo.selectedIndex = 0;
        } else {
            campo.value = "";
        }
    });
}

// Función para eliminar filas
function eliminarFila(boton) {
    let fila = boton.parentElement.parentElement;
    let tipo = fila.cells[0].innerHTML;

    if (tipo === "Pregrado") tipoPregradoAgregado = false;
    if (tipo === "Posdoctorado") tipoPosdoctoradoAgregado = false;

    // Eliminar las fechas del arreglo de fechas registradas
    if (fila.parentElement.id === "tablaExperiencia" || fila.parentElement.id === "tablaExperienciaDocente" || fila.parentElement.id === "tablaExperienciaComite") {
        let fechaInicio = fila.cells[2].innerHTML; // Asumiendo que la fecha de inicio está en la posición 2
        let fechaFin = fila.cells[3].innerHTML; // Asumiendo que la fecha de fin está en la posición 3

        fechasRegistradas = fechasRegistradas.filter(fecha => fecha !== fechaInicio && fecha !== fechaFin);
    }

    fila.remove();
}

//funcion para validar año
function validarAno(ano) {
    const anoActual = new Date().getFullYear();
    return !isNaN(ano) && ano >= 1900 && ano <= anoActual;
}

// Función para agregar formación
function agregarFormacion() {
    let campos = [
        document.getElementById("tipoFormacion"),
        document.getElementById("paisFormacion"),
        document.getElementById("anoGraduacion"),
        document.getElementById("institucionEducativa"),
        document.getElementById("nombreGrado")
    ];

    let pdfInput = document.getElementById("pdfFormacionAcademica");

    if (!validarCampos(campos) || !validarPDF(pdfInput)) return;

    let anoCertificado = parseInt(campos[2].value);
    if (!validarAno(anoCertificado)){
        mostrarAlerta("Por favor ingresar un año de graduación válido.");
        return;
    }

    let tipo = campos[0].value;
    if (tipo === "Pregrado" && tipoPregradoAgregado) {
        mostrarAlerta("Ya se ha agregado una formación de Pregrado.");
        return;
    }
    if (tipo === "Posdoctorado" && tipoPosdoctoradoAgregado) {
        mostrarAlerta("Ya se ha agregado una formación de Posdoctorado.");
        return;
    }

    if (tipo === "Pregrado") tipoPregradoAgregado = true;
    if (tipo === "Posdoctorado") tipoPosdoctoradoAgregado = true;

    let valores = campos.map(campo => campo.value);
    crearFila("tablaFormacion", valores, pdfInput.files[0]);
    limpiarCampos([...campos, pdfInput]);
}

// Función para agregar idioma
function agregarIdioma() {
    let campos = [
        document.getElementById("idioma"),
        document.getElementById("competenciaEscrita"),
        document.getElementById("competenciaLectora"),
        document.getElementById("competenciaOral")
    ];

    if (!validarCampos(campos)) return;

    let valores = campos.map(campo => campo.value);
    crearFila("tablaIdiomas", valores);
    limpiarCampos(campos);
}

// Función para campo profesional 
function agregarCursosAmbitoProfesional(){
    let campos = [
        document.getElementById("anoCertificadoCampoProfesional"),
        document.getElementById("institucionCampoProfesional"),
        document.getElementById("cursoSeminarioCampoProfesional"),
        document.getElementById("duracionCampoProfesional"),
    ];

    if (!validarCampos(campos)) return;

    let anoCertificado = parseInt(campos[0].value);
    if (!validarAno(anoCertificado)){
        mostrarAlerta("Por favor ingresar un año válido (entre 1900 y año actual).");
        return;
    }

    let duracion = parseInt(campos[3].value);
    if (duracion < 8) {
        mostrarAlerta("La duración mínima debe ser de 8 horas.");
        return;
    }

    let valores = campos.map(campo => campo.value);
    crearFila("tablaCursosAmbitoProfesional", valores);
    limpiarCampos(campos);
}

//funcion para ambito academico
function agregarCursosAmbitoAcademico(){
    let campos = [
        document.getElementById("anoCertificadoAmbitoAcademico"),
        document.getElementById("institucionAmbitoAcademico"),
        document.getElementById("cursoSeminarioAmbitoAcademico"),
        document.getElementById("duracionAmbitoAcademico"),
    ];

    if (!validarCampos(campos)) return;

    let anoCertificado = parseInt(campos[0].value);
    if (!validarAno(anoCertificado)){
        mostrarAlerta("Por favor ingresar un año válido (entre 1900 y año actual).");
        return;
    }

    let duracion = parseInt(campos[3].value);
    if (duracion < 8) {
        mostrarAlerta("La duración mínima debe ser de 8 horas.");
        return;
    }

    let valores = campos.map(campo => campo.value);
    crearFila("tablaCursosAmbitoAcademico", valores);
    limpiarCampos(campos);
}

//funcion en ambito de evaluacion
function agregarCursos() {
    let campos = [
        document.getElementById("anoCertificado"),
        document.getElementById("institucion"),
        document.getElementById("cursoSeminario"),
        document.getElementById("duracion")
    ];

    if (!validarCampos(campos)) return;

    let anoCertificado = parseInt(campos[0].value);
    if (!validarAno(anoCertificado)){
        mostrarAlerta("Por favor ingresar un año válido (entre 1900 y año actual).");
        return;
    }

    let duracion = parseInt(campos[3].value);
    if (duracion < 8) {
        mostrarAlerta("La duración mínima debe ser de 8 horas.");
        return;
    }

    let valores = campos.map(campo => campo.value);
    crearFila("tablaCursos", valores);
    limpiarCampos(campos);
}

// Función para agregar experiencia laboral
function agregarExperiencia() {
    let campos = [
        document.getElementById("institucionEmpresa"),
        document.getElementById("cargoDesempeñado"),
        document.getElementById("fechaInicio"),
        document.getElementById("fechaRetiro"),
        document.getElementById("paisEmpresa"),
        document.getElementById("ciudadEmpresa")
    ];

    let pdfInput = document.getElementById("pdfExperiencia");

    if (!validarCampos(campos) || !validarPDF(pdfInput)) return;

    let valores = campos.map(campo => campo.value);
    crearFila("tablaExperiencia", valores, pdfInput.files[0]);
    limpiarCampos([...campos, pdfInput]);
}

// Función para agregar experiencia docente
function agregarExperienciaDocente() {
    let campos = [
        document.getElementById("institucionDocente"),
        document.getElementById("paisDocente"),
        document.getElementById("ciudadDocente"),
        document.getElementById("programaProfesional"),
        document.getElementById("cursosImpartidos"),
        document.getElementById("fechaInicioDocente"),
        document.getElementById("fechaRetiroDocente")
    ];

    let funcionesPrincipales = document.getElementById("funcionesPrincipales");

    let pdfInput = document.getElementById("pdfExperienciaDocente");

    if (!validarCampos(campos) || !validarPDF(pdfInput)) return;

    let fechaInicio = new Date(campos[5].value);
    let fechaFin = new Date(campos[6].value);
    let tiempo = fechaFin.getFullYear() - fechaInicio.getFullYear();

    if (tiempo < 1) {
        mostrarAlerta("El tiempo de experiencia debe ser de al menos un año.");
        return;
    }

    let valores = campos.map(campo => campo.value);
    crearFila("tablaExperienciaDocente", valores, pdfInput.files[0]);
    limpiarCampos([...campos, funcionesPrincipales, pdfInput]);
}

// Funcion para agregar experiencia comite
function agregarExperienciaComite() {
    let campos = [
        document.getElementById("institucionComite"),
        document.getElementById("cargoComite"),
        document.getElementById("modelosCalidad"),
        document.getElementById("fechaInicioComite"),
        document.getElementById("fechaRetiroComite")
    ];

    if (!validarCampos(campos)) return;

    let valores = campos.map(campo => campo.value);
    crearFila("tablaExperienciaComite", valores);
    limpiarCampos(campos);
}

//Funcion para agregar como par evaluador
function agregarExperienciaEvaluador(){
    let campos = [
        document.getElementById("nombreEntidad"),
        document.getElementById("programaEvaluador"),
        document.getElementById("cargoEvaluador"),
        document.getElementById("paisEvaluador"),
        document.getElementById("ciudadEvaluador"),
        document.getElementById("fechaEvaluacion")
    ];

    if (!validarCampos(campos)) return;

    let valores = campos.map(campo => campo.value);
    crearFila("tablaExperienciaEvaluador", valores);
    limpiarCampos(campos);
}

// Función para agregar membresías
function agregarMembresia() {
    let campos = [
        document.getElementById("asociacionProfesional"),
        document.getElementById("numeroMembresia"),
        document.getElementById("gradoMembresia")
    ];

    if (!validarCampos(campos)) return;

    let valores = campos.map(campo => campo.value);
    crearFila("tablaMembresias", valores);
    limpiarCampos(campos);
}

// Función para agregar investigaciones
function agregarInvestigacion() {
    let campos = [
        document.getElementById("fechaPublicacion"),
        document.getElementById("revistaCongreso"),
        document.getElementById("baseDatos"),
        document.getElementById("nombreInvestigacion"),
        document.getElementById("autores")
    ];

    if (!validarCampos(campos)) return;

    let valores = campos.map(campo => campo.value);
    crearFila("tablaInvestigaciones", valores);
    limpiarCampos(campos);
}

// Función para agregar premios
function agregarPremio() {
    let campos = [
        document.getElementById("anoReconocimiento"),
        document.getElementById("institucionReconocimiento"),
        document.getElementById("nombreReconocimiento"),
        document.getElementById("descripcionReconocimiento")
    ];

    if (!validarCampos(campos)) return;

    let anoCertificado = parseInt(campos[0].value);
    if (!validarAno(anoCertificado)){
        mostrarAlerta("Por favor ingresar un año válido (entre 1900 y año actual).");
        return;
    }

    let valores = campos.map(campo => campo.value);
    crearFila("tablaPremios", valores);
    limpiarCampos(campos);
}

// Función para generar años en un select
function generarOpcionesAnios(selectId, rangoInicio, rangoFin) {
    const select = document.getElementById(selectId);
    for (let año = rangoInicio; año <= rangoFin; año++) {
        const option = document.createElement("option");
        option.value = año;
        option.text = año;
        select.appendChild(option);
    }
}

// Generar opciones para los años
generarOpcionesAnios("fechaInicioEvaluador", 1990, 2025);
generarOpcionesAnios("fechaRetiroEvaluador", 1990, 2025);