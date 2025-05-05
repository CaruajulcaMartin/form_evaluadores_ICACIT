let tipoPregradoAgregado = false;
let tipoPosdoctoradoAgregado = false;
let experienciaEvaluadorAgregada = 0;
let fechasRegistradas = [];

let anexosTablasFormacionAcademica = [];
let anexosTablasExperienciaLaboral = [];
let anexosTablasExperienciaDocente = [];

let contadorFormacion = 0; // Contador global para el índice de formación académica
let contadorExperiencia = 0; // Contador global para el índice de experiencia laboral
let contadorExperienciaDocente = 0; // Contador global para el índice de experiencia docente


let registrosTablas = {
    formacion: [],
    idiomas: [],
    cursosCampoProfesional: [],
    cursosAcademicos: [],
    cursosEvaluacion: []
};


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
    fila.setAttribute('data-tabla', tablaId.replace('tabla', '').toLowerCase());

    // Verificar si hay mensaje "No hay registros" y eliminarlo
    // if (tabla.querySelector('tr td.text-center')) {
    //     tabla.innerHTML = '';
    // }

    // Verificación de fechas (si aplica)
    if (tablaId === "tablaExperiencia" || tablaId === "tablaExperienciaDocente" || tablaId === "tablaExperienciaComite") {
        let fechaInicio = valores[2];
        let fechaFin = valores[3];

        if (fechasRegistradas.includes(fechaInicio) || fechasRegistradas.includes(fechaFin)) {
            mostrarAlerta("Las fechas ya han sido registradas.");
            return;
        }
        fechasRegistradas.push(fechaInicio, fechaFin);
    }

    // Crear celdas con los valores
    valores.forEach((valor) => {
        let celda = document.createElement("td");
        celda.textContent = valor || 'N/A';
        fila.appendChild(celda);
    });

    // Mostrar ícono de PDF si existe
    if (incluirPDF) {
        let celdaAnexo = document.createElement("td");
        celdaAnexo.innerHTML = `<i class="fa-regular fa-file-pdf pdf-icon" style="color: red; font-size: 1.5em;"></i>`;
        fila.appendChild(celdaAnexo);

        // Input oculto para el nombre del archivo
        let nombreInputOculto;
        if (tablaId === "tablaFormacion") {
            nombreInputOculto = `formacionAcademica[${contadorFormacion}][pdfFormacionAcademica]`;
            contadorFormacion++;
        } else if (tablaId === "tablaExperiencia") {
            nombreInputOculto = `experienciaLaboral[${contadorExperiencia}][pdfExperienciaLaboral]`;
            contadorExperiencia++;
        } else if (tablaId === "tablaExperienciaDocente") {
            nombreInputOculto = `experienciaDocente[${contadorExperienciaDocente}][pdfExperienciaDocente]`;
            contadorExperienciaDocente++;
        }

        let inputOculto = document.createElement("input");
        inputOculto.type = "hidden";
        inputOculto.name = nombreInputOculto;
        inputOculto.value = incluirPDF.name;
        fila.appendChild(inputOculto);

        // fila.setAttribute('data-pdf-file', JSON.stringify(pdfFile));

        console.log(`PDF agregado al input oculto en la fila de la tabla ${tablaId}`);

        // Agregar a arreglo de anexos
        if (tablaId === "tablaFormacion") {
            anexosTablasFormacionAcademica.push({ file: incluirPDF });
        } else if (tablaId === "tablaExperiencia") {
            anexosTablasExperienciaLaboral.push({ file: incluirPDF });
        } else if (tablaId === "tablaExperienciaDocente") {
            anexosTablasExperienciaDocente.push({ file: incluirPDF });
        }
    }

    // Botón de eliminar
    let celdaAccion = document.createElement("td");
    celdaAccion.classList.add("accion-celda");
    celdaAccion.innerHTML = `<button type="button" class="btn btn-danger" onclick="eliminarFila(this)"><i class="fa-solid fa-trash"></i></button>`;
    fila.appendChild(celdaAccion);

    tabla.appendChild(fila);
    return fila;
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
    let tablaId = fila.getAttribute('data-tabla');

    // Lógica para tipos especiales (mantener tu código actual)
    if (tipo === "Pregrado") tipoPregradoAgregado = false;
    if (tipo === "Posdoctorado") tipoPosdoctoradoAgregado = false;

    // Eliminar de registrosTablas si tiene ID
    const dataId = fila.getAttribute('data-id');
    if (dataId) {
        registrosTablas[tablaId] = registrosTablas[tablaId].filter(item => item.id !== dataId);
    }

    fila.remove();

    // Mostrar "No hay registros" si la tabla queda vacía
    // const tabla = document.getElementById(`tabla${tablaId.charAt(0).toUpperCase() + tablaId.slice(1)}`);
    // if (tabla.rows.length === 0) {
    //     const tr = document.createElement('tr');
    //     tr.innerHTML = '<td colspan="100%" class="text-center">No hay registros</td>';
    //     tabla.appendChild(tr);
    // }
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
    let fila = crearFila("tablaFormacion", valores, pdfInput.files[0]);
    fila.setAttribute('data-id', 'new_' + Date.now());
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
        document.getElementById("tipoCursoSeminarioCampoProfesional"),
        document.getElementById("duracionCampoProfesional"),
    ];

    if (!validarCampos(campos)) return;

    let anoCertificado = parseInt(campos[0].value);
    if (!validarAno(anoCertificado)){
        mostrarAlerta("Por favor ingresar un año válido (entre 1900 y año actual).");
        return;
    }

    let duracion = parseInt(campos[4].value);
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
        document.getElementById("tipoCursoSeminarioAmbitoAcademico"),
        document.getElementById("duracionAmbitoAcademico"),
    ];

    if (!validarCampos(campos)) return;

    let anoCertificado = parseInt(campos[0].value);
    if (!validarAno(anoCertificado)){
        mostrarAlerta("Por favor ingresar un año válido (entre 1900 y año actual).");
        return;
    }

    let duracion = parseInt(campos[4].value);
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
        document.getElementById("tipoCursoSeminario"),
        document.getElementById("cursoSeminario"),
        document.getElementById("duracion")
    ];

    if (!validarCampos(campos)) return;

    let anoCertificado = parseInt(campos[0].value);
    if (!validarAno(anoCertificado)){
        mostrarAlerta("Por favor ingresar un año válido (entre 1900 y año actual).");
        return;
    }

    let duracion = parseInt(campos[4].value);
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

    let funcionesPrincipales = document.getElementById("funcionesPrincipales"); // Campo no obligatorio
    let pdfInput = document.getElementById("pdfExperienciaDocente");

    // Validar los campos obligatorios y el PDF
    if (!validarCampos(campos) || !validarPDF(pdfInput)) return;

    // Validar que el tiempo de experiencia sea de al menos un año
    let fechaInicio = new Date(campos[5].value);
    let fechaFin = new Date(campos[6].value);
    let tiempo = fechaFin.getFullYear() - fechaInicio.getFullYear();

    if (tiempo < 1) {
        mostrarAlerta("El tiempo de experiencia debe ser de al menos un año.");
        return;
    }

    // Mapear los valores de los campos obligatorios
    let valores = campos.map(campo => campo.value);

    // Agregar el valor de "funciones principales" (puede ser vacío)
    valores.splice(5, 0, funcionesPrincipales.value || ""); // Insertar en la posición correspondiente

    // Crear la fila en la tabla
    crearFila("tablaExperienciaDocente", valores, pdfInput.files[0]);

    // Limpiar los campos
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
        document.getElementById("agenciaAcreditadora"), // funciona correctamente en pestaña incognita
        document.getElementById("fechaInicioEvaluador"), // funciona correctamente en pestaña incognita
        document.getElementById("fechaRetiroEvaluador"), // funciona correctamente en pestaña incognita
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