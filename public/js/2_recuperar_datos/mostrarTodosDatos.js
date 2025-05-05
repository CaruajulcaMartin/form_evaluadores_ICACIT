
    const previewStyles = `
        <style id="preview-style">
            .preview-container { font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 15px; }
            .preview-header { display: flex; align-items: flex-start; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
            .preview-header-photo { margin-right: 20px; }
            .preview-header-photo img { max-width: 100px; height: auto; border-radius: 50%; border: 2px solid #ddd; }
            .preview-header-title h3 { color: #0056b3; margin-bottom: 5px; font-size: 1.4rem; }
            .preview-header-title p { color: #6c757d; font-size: 1rem; margin: 0; }

            .preview-section { margin-bottom: 30px; }
            .preview-section-title { color: #007bff; background-color: #f8f9fa; padding: 10px 15px; border-radius: 5px; margin-bottom: 15px; font-size: 1.2rem; font-weight: bold; border-left: 5px solid #007bff; }

            .preview-subsection { margin-bottom: 20px; padding-left: 15px; border-left: 3px solid #28a745; }
            .preview-subsection-title { color: #28a745; margin-bottom: 10px; font-size: 1.1rem; font-weight: bold; }
            .preview-tertiary-title { color: #17a2b8; margin: 15px 0 10px 10px; font-size: 1rem; font-weight: bold; font-style: italic; }

            /* Contenedor de dos columnas para datos simples */
            .preview-data-columns { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 5px 10px; /* Menos gap vertical */ margin-bottom: 15px; }
            .preview-data-item { display: contents; /* Permite que los hijos directos se posicionen en la grid */ }
            .preview-data-label { font-weight: bold; color: #495057; padding: 5px 0; }
            .preview-data-value { padding: 5px 0; border-bottom: 1px dotted #eee; }
             /* Ocupar toda la fila si es necesario (ej. textarea) */
            .preview-data-item.full-width .preview-data-value { grid-column: 1 / -1; }

             /* Estilos para tablas */
            .preview-table-container { overflow-x: auto; /* Para tablas anchas */ margin-bottom: 15px; }
            .preview-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
            .preview-table th, .preview-table td { border: 1px solid #dee2e6; padding: 8px 10px; text-align: left; vertical-align: top; }
            .preview-table th { background-color: #f8f9fa; font-weight: bold; }
            .preview-table tbody tr:nth-child(odd) { background-color: #f9f9f9; }
            .preview-table td a { color: #007bff; text-decoration: none; }
            .preview-table td a:hover { text-decoration: underline; }
            .preview-table .pdf-icon { color: #dc3545; margin-right: 5px; font-size: 1.1em; }

            .preview-signature-container { margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; text-align: center; }
            .preview-signature-label { font-weight: bold; display: block; margin-bottom: 10px; color: #495057; }
            .preview-signature-image { max-width: 250px; height: auto; border: 1px solid #ccc; padding: 5px; background: #fff; }
            .no-data { color: #6c757d; font-style: italic; padding: 10px 0; }
        </style>
    `;

    // --- Mapeo de Títulos (Clave Backend -> Título Visible) ---
    const nombresSecciones = {
        'seccion1': 'Sección 01: Información del Postulante',
        'seccion2': 'Sección 02: Información Laboral Actual',
        'seccion3': 'Sección 03: Información de Formación Académica',
        'seccion4': 'Sección 04: Información Sobre Experiencia Laboral',
        'seccion5': 'Sección 05: Información Sobre Investigación',
        'seccion6': 'Sección 06: Premios y Reconocimientos',
        'seccion7': 'Sección 07: Carta de Presentación',
        'seccion8': 'Sección 08: Conducta y Valores Éticos',
        // Fallback (si las claves no coinciden exactamente)
        'datos_personales': 'Sección 01: Información del Postulante',
        'informacion_laboral': 'Sección 02: Información Laboral Actual',
        'formacion': 'Sección 03: Información de Formación Académica',
        'experiencia': 'Sección 04: Información Sobre Experiencia Laboral',
        'investigacion': 'Sección 05: Información Sobre Investigación',
        'premios': 'Sección 06: Premios y Reconocimientos',
        'carta': 'Sección 07: Carta de Presentación',
        'conducta': 'Sección 08: Conducta y Valores Éticos',
    };

    const nombresSubsecciones = {
        // Sección 1 (Agrupaciones lógicas, no tablas separadas)
        'datos_personales': 'Datos Personales',
        'datos_contacto': 'Datos de Contacto',
        'datos_domiciliarios': 'Datos Domiciliarios',
        // Sección 2
        'datos_centro_laboral': 'Datos del Centro Laboral Actual',
        'datos_empleador': 'Datos del Empleador Actual',
        // Sección 3
        'formacion_academica': 'Formación Académica',
        'idiomas': 'Idiomas',
        'cursos_seminarios': 'Cursos y Seminarios (últimos 3 años)', // Título general para el grupo
        // Sección 4
        'experiencia_profesional': 'Experiencia laboral en su campo profesional',
        'experiencia_docente': 'Experiencia Docente en Educación Superior o Formación Continua',
        'experiencia_comite': 'Experiencia como parte de comité de calidad u oficina de acreditación / calidad',
        'experiencia_par': 'Experiencia como par evaluador',
        'membresias': 'Membresías en Asociaciones Profesionales Vigentes',
        // Sección 5
        'publicaciones': 'Publicaciones, Artículos y Revistas',
        // Sección 6
        'premios_reconocimientos': 'Premios y Reconocimientos',
        // Sección 7 (Probablemente un solo campo de texto largo)
        // Sección 8 (Probablemente campos simples o checkboxes)
    };

    const nombresTerciarios = {
        // Para Cursos y Seminarios en Sección 3
        'profesional': 'Relacionados a su campo profesional:',
        'academico': 'Relacionados a su ámbito académico:',
        'evaluacion': 'Relacionados a su ámbito de evaluación con fines de acreditación:',
    };

    const nombresCampos = {
        // ... (Mapeos existentes) ...
        'primer_nombre': 'Primer Nombre', 'segundo_nombre': 'Segundo Nombre',
        'apellido_paterno': 'Apellido Paterno', 'apellido_materno': 'Apellido Materno',
        'nombres_completos': 'Nombres Completos', 'tipo_documento': 'Tipo Doc.', 'numero_documento': 'Nro. Doc.',
        'fecha_nacimiento': 'Fecha Nac.', 'nacionalidad': 'Nacionalidad', 'estado_civil': 'Estado Civil',
        'pdf_documento_identidad': 'Documento Identidad',
        'email_principal': 'Email Principal', 'email_secundario': 'Email Secundario', 'telefono_fijo': 'Teléfono Fijo',
        'telefono_movil': 'Teléfono Móvil', 'direccion': 'Dirección', 'distrito': 'Distrito', 'provincia': 'Provincia', 'departamento': 'Departamento',
        'nombre_centro_laboral': 'Centro Laboral', 'ruc_centro_laboral': 'RUC Centro', 'direccion_centro_laboral': 'Dirección Centro',
        'cargo_actual': 'Cargo Actual', 'fecha_inicio_laboral': 'Fecha Inicio', 'nombre_empleador': 'Nombre Empleador', 'cargo_empleador': 'Cargo Empleador',
        'email_empleador': 'Email Empleador', 'telefono_empleador': 'Teléfono Empleador',
        'nivel_academico': 'Nivel Académico', 'titulo_obtenido': 'Título Obtenido', 'institucion': 'Institución', 'fecha_graduacion': 'Fecha Graduación',
        'ruta_archivo_formacion': 'Adjunto',
        'idioma': 'Idioma', 'nivel_oral': 'Nivel Oral', 'nivel_escrito': 'Nivel Escrito', 'nivel_lectura': 'Nivel Lectura',
        'ruta_archivo_idioma': 'Adjunto',
        'nombre_curso': 'Nombre Curso/Seminario', 'tipo_curso': 'Tipo', 'horas': 'Horas', 'fecha_curso': 'Fecha', 'institucion_curso': 'Institución',
        'ruta_archivo_curso': 'Adjunto',
        'empresa': 'Empresa/Institución', 'cargo': 'Cargo', 'fecha_inicio': 'Fecha Inicio', 'fecha_fin': 'Fecha Fin', 'descripcion_funciones': 'Funciones',
        'ruta_archivo_experiencia': 'Adjunto',
        'institucion_docente': 'Institución', 'curso_materia': 'Curso/Materia', 'periodo': 'Periodo', 'horas_semanales': 'Horas Sem.',
        'ruta_archivo_docencia': 'Adjunto',
        'nombre_comite': 'Nombre Comité/Oficina', 'rol': 'Rol', 'periodo_comite': 'Periodo',
        'ruta_archivo_comite': 'Adjunto',
        'evento_evaluacion': 'Evento/Institución Evaluada', 'rol_evaluador': 'Rol', 'fecha_evaluacion': 'Fecha',
        'ruta_archivo_par': 'Adjunto',
        'nombre_asociacion': 'Asociación', 'numero_registro': 'Nro. Registro', 'fecha_incorporacion': 'Fecha Incorp.', 'vigente': 'Vigente',
        'ruta_archivo_membresia': 'Adjunto',
        'tipo_publicacion': 'Tipo', 'titulo': 'Título', 'revista_medio': 'Revista/Medio', 'anio': 'Año', 'doi_url': 'DOI/URL',
        'ruta_archivo_publicacion': 'Adjunto',
        'nombre_premio': 'Nombre Premio/Reconocimiento', 'otorgado_por': 'Otorgado por', 'fecha_premio': 'Fecha',
        'ruta_archivo_premio': 'Adjunto',
        'carta_presentacion': 'Carta de Presentación',

        // --- Sección 8 ---
        'conducta_etica': 'Declaro tener una conducta intachable y valores éticos.',
        'informacion_verdadera': 'Declaro que la información que he consignado en este formulario es verdadera.',
        'foto_perfil': 'Foto de Perfil', // Campo esperado para la foto
    };

    // --- Campos a Excluir SIEMPRE ---
    const camposExcluirSiempre = ['id', 'postulante_id', 'usuario_id', 'fecha_envio', 'estado', 'created_at', 'updated_at'];

    function formatearNombre(nombre, mapaNombres) {
        return mapaNombres[nombre] || nombre.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    const basePath = {
        fotos_perfil: URL + 'app/view/admin/upload/fotos_perfil/',
        firmas: URL + 'app/view/admin/upload/firmas/',
        documentos_identidad: URL + 'app/view/admin/upload/documentos_identidad/',
        formacion_academica: URL + 'app/view/admin/upload/formacion_academica/',
        experiencia_laboral: URL + 'app/view/admin/upload/experiencia_laboral/',
        experiencia_docente: URL + 'app/view/admin/upload/experiencia_laboral/experiencia_docente/',
    };
    
    const placeholderImg = URL + 'path/to/your/placeholder/image.png';

    // Función formatearNombre movida a scope global
    function formatearNombre(nombre, mapaNombres) {
        return mapaNombres[nombre] || nombre.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    // Función para leer archivos
    async function readFileAsArrayBuffer(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
            reader.readAsArrayBuffer(file);
        });
    }

document.addEventListener('DOMContentLoaded', function() {


    function mostrarDatosEnModal(datos) {
        console.log("Datos recibidos para modal:", datos);
        const previewContent = document.getElementById('previewContent');
        previewContent.innerHTML = '';

        if (!document.getElementById('preview-style')) {
            const styleWrapper = document.createElement('div');
            styleWrapper.innerHTML = previewStyles;
            document.head.appendChild(styleWrapper.firstElementChild);
        }

        const container = document.createElement('div');
        container.className = 'preview-container';

        // --- Mostrar solo la foto de perfil ---
        const fotoFilename = datos.seccion1?.foto_perfil;
        if (fotoFilename) {
            const photoContainer = document.createElement('div');
            photoContainer.className = 'text-center mb-4';
            
            const img = document.createElement('img');
            img.className = 'img-thumbnail rounded-circle';
            img.style.width = '150px';
            img.style.height = '150px';
            img.alt = 'Foto de perfil';
            
            // Verificar si es una imagen en base64
            if (typeof fotoFilename === 'string' && fotoFilename.startsWith('data:image')) {
                img.src = fotoFilename;
            } else {
                // Construir URL correctamente codificada
                const encodedFilename = encodeURIComponent(fotoFilename);
                img.src = basePath.fotos_perfil + encodedFilename + '?t=' + Date.now();
                
                // Manejo mejorado de errores
                img.onerror = function() {
                    console.error("Error al cargar foto:", this.src);
                    this.onerror = null;
                    this.src = placeholderImg;
                    this.alt = 'Foto no disponible';
                    
                    // Opcional: Mostrar mensaje de error en la interfaz
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'text-danger small mt-2';
                    errorMsg.textContent = 'No se pudo cargar la foto de perfil';
                    photoContainer.appendChild(errorMsg);
                };
                
                // Forzar la carga como imagen
                img.setAttribute('crossorigin', 'anonymous');
                img.setAttribute('accept', 'image/*');
            }
            
            photoContainer.appendChild(img);
            // container.appendChild(photoContainer);
        }

        //mostrar ruta foto
        console.log('Intentando cargar foto desde:', basePath.fotos_perfil + fotoFilename);

        const sectionOrder = [
            'seccion1', 'seccion2', 'seccion3', 'seccion4',
            'seccion5', 'seccion6', 'seccion7', 'seccion8'
        ];

        // --- Procesar Secciones ---
        sectionOrder.forEach(seccionKey => {
            if (datos[seccionKey]) {
                container.appendChild(crearSeccion(seccionKey, datos[seccionKey]));
            } else {
                console.warn(`No se encontraron datos para la sección: ${seccionKey}`);
            }
        });

        // --- Mostrar Firma ---
        const firmaFilename = datos.seccion8?.firma || datos.seccion8?.firma_digital;
        if (firmaFilename) {
            const firmaContainer = document.createElement('div');
            firmaContainer.className = 'preview-signature-container text-center mt-4'; // Centrado con margen superior
            
            const firmaLabel = document.createElement('span');
            firmaLabel.className = 'preview-signature-label';
            firmaLabel.textContent = 'Firma del Postulante:';
            
            const firmaImg = document.createElement('img');
            firmaImg.className = 'preview-signature-image img-fluid'; // Responsive
            firmaImg.style.maxWidth = '250px';
            firmaImg.alt = 'Firma';
            
            // Usar el nombre de archivo de la BD para buscar en la carpeta
            firmaImg.src = basePath.firmas + firmaFilename;
            
            firmaImg.onerror = function() {
                this.onerror = null;
                this.src = placeholderImg;
                this.alt = 'Firma no disponible';
            };
            
            firmaContainer.appendChild(firmaLabel);
            firmaContainer.appendChild(firmaImg);
            // container.appendChild(firmaContainer);
        }

        previewContent.appendChild(container);
        
        // Mostrar el modal
        const previewModalElement = document.getElementById('previewModal');
        if (previewModalElement) {
            const previewModal = bootstrap.Modal.getInstance(previewModalElement) || new bootstrap.Modal(previewModalElement);
            previewModal.show();
        }
    }

    function crearSeccion(seccionKey, seccionData) {
        const sectionDiv = document.createElement('div');
        // Agregar clase específica para buscarla luego (ej. para la firma)
        sectionDiv.className = `preview-section section-${seccionKey}`;

        const sectionTitle = document.createElement('h4');
        sectionTitle.className = 'preview-section-title';
        sectionTitle.textContent = formatearNombre(seccionKey, nombresSecciones);
        sectionDiv.appendChild(sectionTitle);

        if (!seccionData || (typeof seccionData === 'object' && Object.keys(seccionData).length === 0) || (Array.isArray(seccionData) && seccionData.length === 0)) {
            const noDataDiv = document.createElement('p');
            noDataDiv.className = 'no-data';
            noDataDiv.textContent = 'No hay información registrada para esta sección.';
            sectionDiv.appendChild(noDataDiv);
            return sectionDiv; // Termina si no hay datos
        }

        // --- Lógica para manejar la estructura interna de las secciones ---
        if (typeof seccionData === 'object' && !Array.isArray(seccionData)) {
            // Caso 1: La sección es un objeto (puede contener datos simples o subsecciones)
            const subseccionContainer = document.createElement('div');
            let tieneDatosSimples = false;

            // Creamos un contenedor para los datos simples que irán en columnas
            const dataColumnsDiv = document.createElement('div');
            dataColumnsDiv.className = 'preview-data-columns';

            for (const [key, value] of Object.entries(seccionData)) {
                if (camposExcluirSiempre.includes(key.toLowerCase())) continue; // Excluir siempre
                // Excluir foto y firma aquí porque se manejan por separado
                if (key.toLowerCase() === 'foto_perfil' || key.toLowerCase() === 'firma_digital') continue;

                if (typeof value === 'object' && value !== null) {
                     // Si es un array, es una tabla (subsección)
                    if (Array.isArray(value) && value.length > 0) {
                        subseccionContainer.appendChild(crearSubSeccion(key, value, nombresSubsecciones));
                    }
                    // Si es un objeto y contiene arrays (caso Cursos y Seminarios)
                    else if (!Array.isArray(value) && Object.values(value).some(subval => Array.isArray(subval) && subval.length > 0) ) {
                        subseccionContainer.appendChild(crearGrupoSubseccion(key, value, nombresSubsecciones, nombresTerciarios));
                    }
                    // Podría ser un objeto de datos simples agrupados (ej. Domicilio) - tratar como datos simples
                    else if (!Array.isArray(value) && Object.keys(value).length > 0) {
                        const subTitulo = document.createElement('h5');
                        subTitulo.className = 'preview-subsection-title'; // O un estilo diferente
                        subTitulo.textContent = formatearNombre(key, nombresSubsecciones);
                        subseccionContainer.appendChild(subTitulo);
                        const subDataColumnsDiv = crearVistaColumnas(value); // Crear columnas para este subgrupo
                        subseccionContainer.appendChild(subDataColumnsDiv);
                    }
                } else if (value !== null && value !== '') {
                    // Es un dato simple (string, number, boolean) para la sección principal
                    agregarDatoColumna(dataColumnsDiv, key, value);
                    tieneDatosSimples = true;
                }
            }
             // Añadir el contenedor de columnas sólo si tenía datos simples
            if (tieneDatosSimples) {
                sectionDiv.appendChild(dataColumnsDiv);
            }
            // Añadir el contenedor de subsecciones/tablas
            sectionDiv.appendChild(subseccionContainer);

        } else if (Array.isArray(seccionData) && seccionData.length > 0) {
            // Caso 2: La sección es directamente un array (ej. Publicaciones, Premios si no están anidados)
            sectionDiv.appendChild(crearTablaHTML(seccionData, seccionKey));
        }
        // Otros casos (string simple, etc.) - menos común para una sección entera
        else if (seccionData){
            const valueDiv = document.createElement('div');
            valueDiv.textContent = seccionData;
            sectionDiv.appendChild(valueDiv);
        }

        return sectionDiv;
    }

    // --- Nueva función para manejar subsecciones que son directamente tablas ---
    function crearSubSeccion(subseccionKey, dataArray, titulosMap, parentSectionKey) {
        const subsectionDiv = document.createElement('div');
        subsectionDiv.className = 'preview-subsection';

        const subsectionTitle = document.createElement('h5');
        subsectionTitle.className = 'preview-subsection-title';
        subsectionTitle.textContent = formatearNombre(subseccionKey, titulosMap);
        subsectionDiv.appendChild(subsectionTitle);

        if (dataArray && dataArray.length > 0) {
            subsectionDiv.appendChild(crearTablaHTML(dataArray, subseccionKey));
        } else {
            const noDataDiv = document.createElement('p');
            noDataDiv.className = 'no-data';
            noDataDiv.textContent = 'No hay información registrada.';
            subsectionDiv.appendChild(noDataDiv);
        }
        return subsectionDiv;
    }

     // --- Nueva función para manejar grupos de subsecciones (como Cursos) ---
    function crearGrupoSubseccion(grupoKey, grupoData, titulosSubMap, titulosTerMap, parentSectionKey) {
        const grupoDiv = document.createElement('div');
        grupoDiv.className = 'preview-subsection';

        const grupoTitle = document.createElement('h5');
        grupoTitle.className = 'preview-subsection-title';
        grupoTitle.textContent = formatearNombre(grupoKey, titulosSubMap);
        grupoDiv.appendChild(grupoTitle);

        let contenidoMostrado = false;
        for (const [terciarioKey, dataArray] of Object.entries(grupoData)) {
            if (Array.isArray(dataArray) && dataArray.length > 0) {
                const terciarioTitle = document.createElement('h6');
                terciarioTitle.className = 'preview-tertiary-title';
                terciarioTitle.textContent = formatearNombre(terciarioKey, titulosTerMap);
                grupoDiv.appendChild(terciarioTitle);
                grupoDiv.appendChild(crearTablaHTML(dataArray, grupoKey));
                contenidoMostrado = true;
            }
        }

        if (!contenidoMostrado) {
            // ... (manejo sin datos sin cambios) ...
            const noDataDiv = document.createElement('p');
            noDataDiv.className = 'no-data';
            noDataDiv.textContent = 'No hay información registrada para este grupo.';
            grupoDiv.appendChild(noDataDiv);
        }

        return grupoDiv;
    }

    // --- Función para crear la vista de columnas para datos simples ---
    function crearVistaColumnas(datos, parentSectionKey) {
        const dataColumnsDiv = document.createElement('div');
        dataColumnsDiv.className = 'preview-data-columns';
        let hasData = false;
        for (const [key, value] of Object.entries(datos)) {
            if (camposExcluirSiempre.includes(key.toLowerCase())) continue;
            if (key.toLowerCase() === 'foto_perfil' || key.toLowerCase() === 'firma') continue;

            if (value !== null && value !== '' && typeof value !== 'object') {
                agregarDatoColumna(dataColumnsDiv, key, value, parentSectionKey);
                hasData = true;
            }
        }
        return hasData ? dataColumnsDiv : null;
    }

    // --- Función auxiliar para añadir un par label-valor a la vista de columnas ---
    function agregarDatoColumna(container, key, value, parentSectionKey) {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'preview-data-item';
    
        if (typeof value === 'string' && value.length > 100) {
           itemDiv.classList.add('full-width');
        }
    
        const labelDiv = document.createElement('div');
        labelDiv.className = 'preview-data-label';
        labelDiv.textContent = formatearNombre(key, nombresCampos);
    
        const valueDiv = document.createElement('div');
        valueDiv.className = 'preview-data-value';
    
        // 1. Sección 8: Textos específicos para conducta e información
        if (parentSectionKey === 'seccion8') {
            if (key === 'conducta_etica' && (value === 1 || value === '1' || value === true)) {
                labelDiv.style.display = 'none';
                valueDiv.textContent = nombresCampos['conducta_etica'] + ': Sí';
                itemDiv.appendChild(labelDiv);
                itemDiv.appendChild(valueDiv);
                itemDiv.classList.add('full-width');
                container.appendChild(itemDiv);
                return;
            } else if (key === 'informacion_verdadera' && (value === 1 || value === '1' || value === true)) {
                labelDiv.style.display = 'none';
                valueDiv.textContent = nombresCampos['informacion_verdadera'] + ': Sí';
                itemDiv.appendChild(labelDiv);
                itemDiv.appendChild(valueDiv);
                itemDiv.classList.add('full-width');
                container.appendChild(itemDiv);
                return;
            }
        }
    
        // 2. Sección 1: Enlace para PDF Documento Identidad
        if (parentSectionKey === 'seccion1' && key === 'pdf_documento_identidad' && value) {
            const fileName = String(value).split(/[\\/]/).pop();
            const link = document.createElement('a');
            link.href = basePath.documentos_identidad + value;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.textContent = 'Anexo';
            valueDiv.appendChild(link);
        }
        else if (typeof value === 'boolean') { 
            valueDiv.textContent = value ? 'Sí' : 'No';
        } else if (key.toLowerCase().includes('email')) { 
            valueDiv.innerHTML = `<a href="mailto:${value}">${value}</a>`;
        } else if (key.toLowerCase().includes('url') || key.toLowerCase().includes('doi')) {
            valueDiv.innerHTML = `<a href="${value}" target="_blank" rel="noopener noreferrer">${value}</a>`;
        } else {
            valueDiv.innerHTML = String(value).replace(/\\n/g, '<br>');
        }
    
        if (!itemDiv.contains(labelDiv)) itemDiv.appendChild(labelDiv); 
        if (!itemDiv.contains(valueDiv)) itemDiv.appendChild(valueDiv); 
        if (!container.contains(itemDiv)) container.appendChild(itemDiv); 
    }
    
    function crearTablaHTML(items, tableKey) {
        if (!items || items.length === 0) {
            const noDataDiv = document.createElement('p');
            noDataDiv.className = 'no-data';
            noDataDiv.textContent = 'No hay registros.';
            return noDataDiv;
        }
    
        const container = document.createElement('div');
        container.className = 'preview-table-container';
    
        const table = document.createElement('table');
        table.className = 'preview-table table table-striped table-bordered table-hover';
    
        const thead = table.createTHead();
        const headerRow = thead.insertRow();
    
        const headers = Object.keys(items[0]).filter(key => !camposExcluirSiempre.includes(key.toLowerCase()));
        const fileKey = headers.find(key => key.toLowerCase().startsWith('ruta_archivo_'));
    
        // Determinar la ruta base según el tipo de tabla
        let currentBasePath = '';
        switch(tableKey) {
            case 'formacion_academica':
            case 'pdf_formacion_academica':
                currentBasePath = basePath.formacion_academica;
                break;
            case 'experiencia_profesional':
            case 'pdf_experiencia':
                currentBasePath = basePath.experiencia_laboral;
                break;
            case 'experiencia_docente':
            case 'pdf_experiencia_docente':
                currentBasePath = basePath.experiencia_docente;
                break;
            default:
                console.warn(`Ruta base no definida para tabla: ${tableKey}`);
                currentBasePath = URL + 'view/admin/upload/default/';
        }
    
        headers.forEach(key => {
            const th = document.createElement('th');
            th.textContent = formatearNombre(key, nombresCampos);
            headerRow.appendChild(th);
        });
    
        const tbody = table.createTBody();
        items.forEach(item => {
            const row = tbody.insertRow();
            headers.forEach(key => {
                const cell = row.insertCell();
                const value = item[key];
    
                // Manejo de archivos adjuntos
                if (fileKey && key === fileKey && value) {
                    const fileName = String(value).split(/[\\/]/).pop();
                    if (currentBasePath) {
                        const link = document.createElement('a');
                        link.href = currentBasePath + value;
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                        link.innerHTML = `<i class="fas fa-file-pdf pdf-icon" aria-hidden="true"></i> ${fileName}`;
                        cell.appendChild(link);
                    } else {
                        cell.textContent = fileName;
                    }
                }
                else if (typeof value === 'boolean') {
                    cell.textContent = value ? 'Sí' : 'No';
                } else if (value instanceof Date) {
                    cell.textContent = value.toLocaleDateString();
                } else if (key.toLowerCase().includes('url') || key.toLowerCase().includes('doi')) {
                    cell.innerHTML = `<a href="${value}" target="_blank" rel="noopener noreferrer">${value}</a>`;
                }
                else if (value !== null && value !== undefined) {
                    cell.innerHTML = String(value).replace(/\\n/g, '<br>');
                } else {
                    cell.textContent = '-';
                }
            });
        });
    
        container.appendChild(table);
        return container;
    }


    // --- Nueva función para crear Tablas HTML ---
    function crearTablaHTML(items, tableKey) {
        if (!items || items.length === 0) {
            const noDataDiv = document.createElement('p');
            noDataDiv.className = 'no-data';
            noDataDiv.textContent = 'No hay registros.';
            return noDataDiv;
        }

        const container = document.createElement('div');
        container.className = 'preview-table-container';

        const table = document.createElement('table');
        table.className = 'preview-table table table-striped table-bordered table-hover';

        const thead = table.createTHead();
        const headerRow = thead.insertRow();

        // Determinar cabeceras y clave del archivo adjunto
        const headers = Object.keys(items[0]).filter(key => !camposExcluirSiempre.includes(key.toLowerCase()));
        // *** Buscar la clave que contiene el nombre del archivo (asumiendo un patrón) ***
        const fileKey = headers.find(key => key.toLowerCase().startsWith('ruta_archivo_'));

        headers.forEach(key => {
            const th = document.createElement('th');
            th.textContent = formatearNombre(key, nombresCampos); // Usar mapeo para cabeceras
            headerRow.appendChild(th);
        });

        const tbody = table.createTBody();
        items.forEach(item => {
            const row = tbody.insertRow();
            headers.forEach(key => {
                const cell = row.insertCell();
                const value = item[key];

                // --- *** Lógica MODIFICADA para Adjuntos PDF *** ---
                if (fileKey && key === fileKey && value) { // Si esta es la columna del archivo y tiene valor
                    const fileName = String(value).split(/[\\/]/).pop(); // Nombre de archivo limpio
                    let currentBasePath = '';
                    switch(tableKey) {
                        case 'pdf_formacion_academica':
                            currentBasePath = basePath.formacion_academica;
                            break;
                        case 'pdf_experiencia': // Incluye experiencia laboral general
                            currentBasePath = basePath.experiencia_laboral;
                            break;
                        case 'pdf_experiencia_docente':
                            currentBasePath = basePath.experiencia_docente;
                            break;
                        default:
                            console.warn(`Ruta base no definida para tabla: ${tableKey}`);
                            currentBasePath = URL + 'view/admin/upload/default/'; // Ruta por defecto o error
                    }

                    if (currentBasePath) {
                        const link = document.createElement('a');
                        link.href = currentBasePath + value; // Construir URL completa
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                        link.innerHTML = `<i class="fas fa-file-pdf pdf-icon" aria-hidden="true"></i> ${fileName}`;
                        cell.appendChild(link);
                    } else {
                        cell.textContent = fileName; // Mostrar solo nombre si no hay ruta base
                    }
                }
                // --- Otros tipos de datos (sin cambios) ---
                 else if (typeof value === 'boolean') {
                    cell.textContent = value ? 'Sí' : 'No';
                } else if (value instanceof Date) { // Puede que no funcione si viene como string
                    cell.textContent = value.toLocaleDateString();
                } else if (key.toLowerCase().includes('url') || key.toLowerCase().includes('doi')) { // Enlaces en tablas
                     cell.innerHTML = `<a href="${value}" target="_blank" rel="noopener noreferrer">${value}</a>`;
                }
                 else if (value !== null && value !== undefined) {
                    // Mostrar texto, reemplazar \n si es necesario (aunque en tablas es menos común)
                    cell.innerHTML = String(value).replace(/\\n/g, '<br>');
                } else {
                    cell.textContent = '-'; // Para valores nulos o indefinidos
                }
            });
        });

        container.appendChild(table);
        return container;
    }


    function cargarTodasLasSecciones() {
        const previewContent = document.getElementById('previewContent');
        previewContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3">Cargando datos del formulario...</p>
            </div>
        `;
        // Mostrar modal ANTES de la llamada AJAX para que el spinner sea visible
        const previewModalElement = document.getElementById('previewModal');
        if (previewModalElement) {
            const previewModal = bootstrap.Modal.getInstance(previewModalElement) || new bootstrap.Modal(previewModalElement);
            previewModal.show();
        }


        $.ajax({
            // Asegúrate que esta URL es correcta y apunta a tu método PHP
            url: URL + 'DatosRecuperar/recuperarDatosSecciones',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    mostrarDatosEnModal(response.data);
                } else {
                    console.error("Error al recuperar datos:", response.message);
                    previewContent.innerHTML = `
                        <div class="alert alert-danger">
                            <h5>Error al cargar los datos</h5>
                            <p>${response.message || 'Ocurrió un error al recuperar los datos del formulario.'}</p>
                            <button class="btn btn-sm btn-secondary" onclick="cargarTodasLasSecciones()">Reintentar</button>
                        </div>`;
                }
            },
            error: function(xhr, status, error) {
                console.error("Error AJAX:", status, error);
                previewContent.innerHTML = `
                    <div class="alert alert-danger">
                        <h5>Error de conexión</h5>
                        <p>No se pudo conectar con el servidor (${status}). Por favor verifica tu conexión e intenta nuevamente.</p>
                        <button class="btn btn-sm btn-secondary" onclick="cargarTodasLasSecciones()">Reintentar</button>
                    </div>`;
            }
        });
    }

    // Delegación de eventos para abrir el modal
    document.body.addEventListener('click', function(e) { // Escuchar en body es más seguro
        if (e.target.closest('[data-bs-toggle="modal"][data-bs-target="#previewModal"]')) {
             e.preventDefault(); // Prevenir comportamiento default si es un botón o link
            cargarTodasLasSecciones();
        }
    });

    function actualizarInterfazFormulario(estadoEnviado) {
    // Deshabilitar botones de secciones 1-8
    for (let i = 1; i <= 8; i++) {
        const btnSeccion = document.querySelector(`.btn-seccion-${i}`);
        if (btnSeccion) {
            btnSeccion.disabled = estadoEnviado;
            btnSeccion.classList.toggle('btn-secondary', estadoEnviado);
            btnSeccion.classList.toggle('btn-primary', !estadoEnviado);
        }
    }

    // Cambiar botón de la sección 9
    const btnSeccion9 = document.querySelector('.btn-seccion-9');
    if (btnSeccion9) {
        if (estadoEnviado) {
            btnSeccion9.innerHTML = '<i class="fas fa-file-pdf"></i> Descargar PDF';
            btnSeccion9.onclick = function() { downloadPDF(); };
        } else {
            btnSeccion9.innerHTML = '<i class="fas fa-eye"></i> Previsualizar y Confirmar';
            btnSeccion9.onclick = function() { cargarTodasLasSecciones(); };
        }
    }

    // Ocultar botón de descarga en el modal si ya está en la sección 9
    const downloadBtn = document.getElementById('downloadPDF');
    if (downloadBtn) {
        downloadBtn.style.display = estadoEnviado ? 'none' : 'block';
    }
}

    // nuevo cambio
    function actualizarInterfazFormulario(estadoEnviado) {
        // Deshabilitar botones de secciones 1-8
        for (let i = 1; i <= 8; i++) {
            const btnSeccion = document.querySelector(`.btn-seccion-${i}`);
            if (btnSeccion) {
                btnSeccion.disabled = estadoEnviado;
                btnSeccion.classList.toggle('btn-secondary', estadoEnviado);
                btnSeccion.classList.toggle('btn-primary', !estadoEnviado);
            }
        }
    
        // Cambiar botón de la sección 9
        const btnSeccion9 = document.querySelector('.btn-seccion-9');
        if (btnSeccion9) {
            if (estadoEnviado) {
                btnSeccion9.innerHTML = '<i class="fas fa-file-pdf"></i> Descargar PDF';
                btnSeccion9.onclick = function() { downloadPDF(); };
            } else {
                btnSeccion9.innerHTML = '<i class="fas fa-eye"></i> Previsualizar y Confirmar';
                btnSeccion9.onclick = function() { cargarTodasLasSecciones(); };
            }
        }
    
        // Ocultar botón de descarga en el modal si ya está en la sección 9
        const downloadBtn = document.getElementById('downloadPDF');
        if (downloadBtn) {
            downloadBtn.style.display = estadoEnviado ? 'none' : 'block';
        }
    }
    
    // Verificar estado al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si el formulario ya fue enviado
        $.ajax({
            url: URL + 'Formulario/verificarEstadoFormulario',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.formulario_enviado) {
                    actualizarInterfazFormulario(true);
                }
            },
            error: function() {
                console.log('No se pudo verificar el estado del formulario');
            }
        });
    });


    // Función para actualizar la interfaz después del envío
    function actualizarInterfazPostEnvio() {
        // Deshabilitar todas las secciones (1-8)
        for (let i = 1; i <= 8; i++) {
            const btnSeccion = document.querySelector(`.secciones-formulario:nth-child(${i}) .btn-seccion`);
            if (btnSeccion) {
                btnSeccion.classList.remove('active');
                btnSeccion.classList.add('disabled');
                btnSeccion.textContent = 'No disponible';
                btnSeccion.href = '#';
                btnSeccion.onclick = null; // Eliminar cualquier evento anterior
            }
        }
    
        // Cambiar el botón de la sección 9 a Descargar PDF y asignar la función
        const btnSeccion9 = document.querySelector('.secciones-formulario:nth-child(9) .btn-seccion');
        if (btnSeccion9) {
            btnSeccion9.innerHTML = '<i class="fas fa-file-pdf me-2"></i> Descargar PDF';
            btnSeccion9.href = '#';
            btnSeccion9.onclick = async function(e) {
                e.preventDefault();
                await downloadPDF();
            };
        }
    }

    // Delegación para botón Confirmar Envío
    document.body.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'confirmarEnvio') {
            if (!confirm('¿Estás seguro de que deseas enviar el formulario? No podrás modificar los datos después del envío.')) {
                return;
            }

            const modalFooter = document.querySelector('#previewModal .modal-footer');
            const originalFooterContent = modalFooter.innerHTML;
            modalFooter.innerHTML = `
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status"></span> Enviando...
                </button>
            `;

            $.ajax({
                url: URL + 'Formulario/enviarFormularioCompleto',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Cerrar el modal de previsualización
                        const previewModalInstance = bootstrap.Modal.getInstance(document.getElementById('previewModal'));
                        if(previewModalInstance) previewModalInstance.hide();

                        // Mostrar modal de confirmación
                        const confirmModalElement = document.getElementById('confirmacionEnvioModal');
                        if(confirmModalElement) {
                            const confirmModal = bootstrap.Modal.getInstance(confirmModalElement) || new bootstrap.Modal(confirmModalElement);
                            confirmModal.show();
                            
                            // Actualizar la interfaz inmediatamente
                            actualizarInterfazPostEnvio();
                            
                            // Configurar el evento para cuando se cierre el modal de confirmación
                            confirmModalElement.addEventListener('hidden.bs.modal', function() {
                                // Recargar la página para asegurar consistencia
                                location.reload();
                            });
                        } else {
                            // Fallback si no hay modal de confirmación
                            actualizarInterfazPostEnvio();
                            location.reload();
                        }
                    } else {
                        alert('Error al enviar el formulario: ' + (response.message || 'Error desconocido'));
                        modalFooter.innerHTML = originalFooterContent;
                    }
                },
                error: function() {
                    alert('Error de conexión al enviar el formulario');
                    modalFooter.innerHTML = originalFooterContent;
                }
            });
        }
    });

    // Verificar estado al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si el formulario ya fue enviado
        $.ajax({
            url: URL + 'Formulario/verificarEstadoFormulario',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.formulario_enviado) {
                    actualizarInterfazPostEnvio();
                }
            },
            error: function() {
                console.log('No se pudo verificar el estado del formulario');
            }
        });
    });

    // Hacer la función accesible globalmente si se llama desde HTML (como en el botón Reintentar)
    window.cargarTodasLasSecciones = cargarTodasLasSecciones;


    
    async function readFileAsArrayBuffer(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
            reader.readAsArrayBuffer(file);
        });
    }


    // Función para descargar el PDF
    async function downloadPDF() {
        // Verificar que las librerías estén cargadas
        if (typeof PDFLib === 'undefined' || typeof jspdf === 'undefined') {
            alert('Error: Las librerías PDF no se cargaron correctamente. Recarga la página.');
            return;
        }

        const { PDFDocument } = PDFLib;
        const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
        
        // Configuración inicial
        let margin = 15;
        let lineHeight = 10;
        let currentY = margin;
        const pageWidth = pdf.internal.pageSize.getWidth() - margin * 2;

        // Mostrar mensaje de carga
        const previewContent = document.getElementById('previewContent');
        const originalContent = previewContent.innerHTML;
        previewContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3">Generando PDF, por favor espere...</p>
            </div>
        `;

        try {
            // Cargar logo
            const logoImg = new Image();
            logoImg.crossOrigin = 'Anonymous';
            logoImg.src = URL + 'assets/ICACIT_2025.jpg';

            // Esperar a que el logo cargue
            await new Promise((resolve) => {
                logoImg.onload = resolve;
                logoImg.onerror = resolve; // Continuar incluso si falla
            });

            // Función para agregar encabezado
            const addHeader = () => {
                if (logoImg.complete && logoImg.naturalHeight !== 0) {
                    const logoHeight = (logoImg.height * 25) / logoImg.width;
                    pdf.addImage(logoImg, 'PNG', margin, margin, 25, logoHeight);
                    currentY = margin + logoHeight + 10;
                } else {
                    currentY = margin + 10;
                }
            };

            // Agregar primera página
            addHeader();

            // Obtener datos del formulario
            const response = await $.ajax({
                url: URL + 'DatosRecuperar/recuperarDatosSecciones',
                type: 'POST',
                dataType: 'json'
            });

            if (!response.success) {
                throw new Error(response.message || 'Error al obtener datos del formulario');
            }

            const datos = response.data;

            // Agregar título principal
            pdf.setFontSize(24);
            pdf.setFont('helvetica', 'bold');
            const titleText = 'Perfil De Candidato Para Evaluador ICACIT';
            const titleX = pdf.internal.pageSize.getWidth() / 2;
            const titleY = currentY + 15;
            pdf.text(titleText, titleX, titleY, { align: 'center' });

            // Agregar subtítulo
            pdf.setFontSize(14);
            pdf.setFont('helvetica', 'italic');

            const responseId = await fetch(URL + 'Formulario/obtenerPostulanteId'); 
            if (!responseId.ok) {
                throw new Error('Error al obtener el ID del postulante.');
            }
            const data = await responseId.json();
            const postulanteId = data.postulanteId;

            //cambiar el contador por el id del postulante
            const contador = postulanteId || '0000';
            const subtitleText = 'PFEV2025-' + contador;
            // const contador = $('#contador').val() || '0000';
            // const subtitleText = 'PFEV2025-' + contador;

            const subtitleY = titleY + 10;
            pdf.text(subtitleText, titleX, subtitleY, { align: 'center' });

            // Agregar fecha
            pdf.setFontSize(10);
            const fechaRegistro = new Date().toLocaleDateString();
            const fechaY = subtitleY + 5;
            pdf.text(`Fecha de Registro: ${fechaRegistro}`, titleX, fechaY, { align: 'center' });

            currentY = fechaY + 15;

            // Función para agregar texto
            const addText = (text, fontSize = 12, isBold = false, align = 'left', color = [0, 0, 0]) => {
                if (currentY + lineHeight > pdf.internal.pageSize.getHeight() - margin) {
                    pdf.addPage();
                    addHeader();
                    currentY = margin + 10;
                }
                
                pdf.setFontSize(fontSize);
                pdf.setFont('helvetica', isBold ? 'bold' : 'normal');
                pdf.setTextColor(...color);
                
                const textLines = pdf.splitTextToSize(text, pageWidth);
                pdf.text(textLines, margin, currentY, { align });
                currentY += lineHeight * textLines.length;
            };

            // Función para agregar sección
            const addSection = (title, level = 1) => {
                if (currentY + 20 > pdf.internal.pageSize.getHeight() - margin) {
                    pdf.addPage();
                    addHeader();
                }
                
                const fontSize = level === 1 ? 18 : (level === 2 ? 16 : 14);
                const color = level === 1 ? [0, 51, 102] : (level === 2 ? [0, 102, 153] : [23, 162, 184]);
                
                addText(title, fontSize, true, 'left', color);
                currentY += 5;
            };

            // Función para agregar tabla
            const addTable = (headers, rows) => {
                if (currentY + 50 > pdf.internal.pageSize.getHeight() - margin) {
                    pdf.addPage();
                    addHeader();
                }
                
                pdf.autoTable({
                    startY: currentY,
                    head: [headers.map(header => formatearNombre(header, nombresCampos))],
                    body: rows.map(row => headers.map(header => {
                        const value = row[header];
                        if (typeof value === 'boolean') return value ? 'Sí' : 'No';
                        if (value === null || value === undefined) return '-';
                        return String(value).replace(/\\n/g, ' ');
                    })),
                    margin: { left: margin },
                    headStyles: { fillColor: [44, 62, 80] },
                    alternateRowStyles: { fillColor: [245, 245, 245] },
                });
                
                currentY = pdf.autoTable.previous.finalY + 10;
            };

            // Función para agregar imagen
            const addImage = async (imgSrc, width, height, description = '') => {
                if (currentY + height + 20 > pdf.internal.pageSize.getHeight() - margin) {
                    pdf.addPage();
                    addHeader();
                }
                
                const img = new Image();
                img.crossOrigin = 'Anonymous';
                img.src = imgSrc;
                
                await new Promise((resolve) => {
                    img.onload = resolve;
                    img.onerror = resolve; // Continuar incluso si falla
                });
                
                if (img.complete && img.naturalHeight !== 0) {
                    const x = margin;
                    pdf.addImage(img, 'JPEG', x, currentY, width, height);
                    
                    if (description) {
                        pdf.setFontSize(10);
                        pdf.text(description, x, currentY + height + 5);
                    }
                    
                    currentY += height + (description ? 10 : 5);
                } else {
                    addText(`[Imagen no disponible: ${description}]`, 10, false, 'left', [150, 150, 150]);
                }
            };

            // Procesar cada sección
            const sectionOrder = [
                'seccion1', 'seccion2', 'seccion3', 'seccion4',
                'seccion5', 'seccion6', 'seccion7', 'seccion8'
            ];

            for (const seccionKey of sectionOrder) {
                if (!datos[seccionKey]) continue;

                addSection(formatearNombre(seccionKey, nombresSecciones), 1);
                
                const seccionData = datos[seccionKey];
                
                // Procesar foto de perfil
                if (seccionKey === 'seccion1' && seccionData.foto_perfil) {
                    const fotoSrc = typeof seccionData.foto_perfil === 'string' && seccionData.foto_perfil.startsWith('data:image') ? 
                        seccionData.foto_perfil : 
                        basePath.fotos_perfil + seccionData.foto_perfil;
                    
                    await addImage(fotoSrc, 30, 30, 'Foto de perfil');
                }

                // Procesar datos simples
                if (typeof seccionData === 'object' && !Array.isArray(seccionData)) {
                    for (const [key, value] of Object.entries(seccionData)) {
                        if (camposExcluirSiempre.includes(key.toLowerCase())) continue;
                        if (key === 'foto_perfil' || key === 'firma_digital') continue;
                        
                        if (typeof value !== 'object' && value !== null && value !== '') {
                            const label = formatearNombre(key, nombresCampos);
                            let displayValue = value;
                            
                            if (typeof value === 'boolean') {
                                displayValue = value ? 'Sí' : 'No';
                            } else if (key === 'pdf_documento_identidad') {
                                displayValue = 'Documento adjunto (ver anexos)';
                            }
                            
                            addText(`${label}: ${displayValue}`);
                        }
                    }
                }

                // Procesar subsecciones
                if (typeof seccionData === 'object' && !Array.isArray(seccionData)) {
                    for (const [subseccionKey, subseccionData] of Object.entries(seccionData)) {
                        if (typeof subseccionData === 'object' && subseccionData !== null) {
                            // Agregar título de subsección
                            if (nombresSubsecciones[subseccionKey]) {
                                addSection(formatearNombre(subseccionKey, nombresSubsecciones), 2);
                            }
                            
                            // Procesar arrays (tablas)
                            if (Array.isArray(subseccionData) && subseccionData.length > 0) {
                                const headers = Object.keys(subseccionData[0]).filter(
                                    key => !camposExcluirSiempre.includes(key.toLowerCase())
                                );
                                addTable(headers, subseccionData);
                            }
                            // Procesar grupos de subsecciones
                            else if (!Array.isArray(subseccionData)) {
                                for (const [terciarioKey, terciarioData] of Object.entries(subseccionData)) {
                                    if (Array.isArray(terciarioData) && terciarioData.length > 0) {
                                        if (nombresTerciarios[terciarioKey]) {
                                            addSection(formatearNombre(terciarioKey, nombresTerciarios), 3);
                                        }
                                        
                                        const headers = Object.keys(terciarioData[0]).filter(
                                            key => !camposExcluirSiempre.includes(key.toLowerCase())
                                        );
                                        addTable(headers, terciarioData);
                                    }
                                }
                            }
                        }
                    }
                }
                
                // Procesar firma
                if (seccionKey === 'seccion8' && seccionData.firma_digital) {
                    const firmaSrc = typeof seccionData.firma_digital === 'string' && seccionData.firma_digital.startsWith('data:image') ? 
                        seccionData.firma_digital : 
                        basePath.firmas + seccionData.firma_digital;
                    
                    await addImage(firmaSrc, 80, 30, 'Firma del postulante');
                }
            }

            // Crear PDF final con anexos
            const finalPdf = await PDFDocument.create();
            const jsPDFBuffer = pdf.output('arraybuffer');
            const jsPDFDoc = await PDFDocument.load(jsPDFBuffer);
            const jsPDFPages = await finalPdf.copyPages(jsPDFDoc, jsPDFDoc.getPageIndices());
            jsPDFPages.forEach(page => finalPdf.addPage(page));

            // Función para agregar anexos
            const addAnexo = async (file, titulo) => {
                if (!file) return;
                
                try {
                    // Página de título
                    const tituloPagina = finalPdf.addPage([600, 400]);
                    const helveticaBoldFont = await finalPdf.embedFont(PDFLib.StandardFonts.HelveticaBold);
                    
                    tituloPagina.drawText(titulo, {
                        x: 50,
                        y: 200,
                        size: 28,
                        font: helveticaBoldFont,
                    });
                    
                    // Agregar páginas del PDF
                    const arrayBuffer = await readFileAsArrayBuffer(file);
                    const pdfDoc = await PDFDocument.load(arrayBuffer);
                    const pages = await finalPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
                    pages.forEach(page => finalPdf.addPage(page));
                } catch (error) {
                    console.error(`Error al agregar anexo ${titulo}:`, error);
                }
            };

            // Agregar documento de identidad si existe
            if (datos.seccion1?.pdf_documento_identidad) {
                const fileInput = document.getElementById('pdfDocumentoIdentidad');
                if (fileInput?.files?.[0]) {
                    await addAnexo(fileInput.files[0], 'Anexo: Documento de Identidad');
                }
            }

            // Descargar el PDF final
            const finalPdfBytes = await finalPdf.save();
            const blob = new Blob([finalPdfBytes], { type: 'application/pdf' });
            
            let pdfUrl;
            try {
                // Intentar con el método estándar primero
                if (typeof URL.createObjectURL === 'function') {
                    pdfUrl = URL.createObjectURL(blob);
                } else {
                    // Fallback para navegadores antiguos
                    throw new Error('URL.createObjectURL no disponible');
                }
            } catch (e) {
                console.warn('Error con createObjectURL, usando alternativa:', e);
                // Alternativa usando FileReader
                pdfUrl = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = (event) => resolve(event.target.result);
                    reader.readAsDataURL(blob);
                });
            }

            // Crear y disparar el enlace de descarga
            const link = document.createElement('a');
            link.href = pdfUrl;
            link.download = `Formulario_Inscripcion_ICACIT_2025_${contador}.pdf`;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            
            // Limpieza
            setTimeout(() => {
                document.body.removeChild(link);
                if (typeof URL.revokeObjectURL === 'function' && pdfUrl.startsWith('blob:')) {
                    URL.revokeObjectURL(pdfUrl);
                }
            }, 100);

        } catch (error) {
            console.error('Error al generar PDF:', error);
            alert('Error al generar el PDF: ' + error.message);
            
            // Mostrar detalles técnicos en consola
            console.group('Detalles del error al generar PDF');
            console.log('PDFLib disponible:', typeof PDFLib !== 'undefined');
            console.log('jspdf disponible:', typeof jspdf !== 'undefined');
            console.log('URL disponible:', typeof URL !== 'undefined');
            console.log('createObjectURL disponible:', typeof URL.createObjectURL === 'function');
            console.log('Blob disponible:', typeof Blob !== 'undefined');
            console.groupEnd();
        } finally {
            previewContent.innerHTML = originalContent;
        }
    }

    // Agregar evento al botón de descarga
    document.getElementById('downloadPDF').addEventListener('click', function(e) {
        e.preventDefault();
        downloadPDF();
    });

});

