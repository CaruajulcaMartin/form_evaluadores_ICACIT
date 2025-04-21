<?php

namespace Mini\Controller;

use Mini\Model\Home;
use Exception;

class HomeController
{
    private $model;

    public function __construct()
    {
        $this->model = new Home();
    }

    public function index()
    {

        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/home/index.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    public function enviarFormulario()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        try {
            $postulante = $_POST;
            $numDoc = $_POST["numDoc"];

            if (empty($numDoc)) {
                throw new Exception("Número de documento requerido");
            }

            // Procesar archivos subidos
            $fotoPerfil = $this->procesarArchivo($_FILES['fotoPerfil'] ?? null, APP . 'view/admin/upload/fotos_perfil/', "foto_perfil_$numDoc");
            $pdfDocumento = $this->procesarArchivo($_FILES['pdfDocumentoIdentidad'] ?? null, APP . 'view/admin/upload/documentos_identidad/', "pdf_documento_identidad_$numDoc");

            // *Informacion Personal - Insertar datos personales
            $postulante['fotoPerfil'] = $fotoPerfil;
            $postulante['pdfDocumentoIdentidad'] = $pdfDocumento;
            $postulanteId = $this->model->insertPersonalInfo($postulante);

            if (!$postulanteId) {
                throw new Exception("Error al guardar los datos personales");
            }

            //!* Formación Académica
            if (!empty($_POST['formacionAcademica'])) {
                foreach ($_POST['formacionAcademica'] as $index => $formacion) {
                    // Inicializar la variable para el archivo PDF
                    $pdfFormacion = '';
            
                    // Verificar si se envió un archivo PDF para esta fila
                    if (!empty($_FILES['formacionAcademica']['tmp_name'][$index]['pdfFormacionAcademica'])) {
                        // Preparar los datos del archivo para procesarArchivo
                        $archivo = [
                            'name' => $_FILES['formacionAcademica']['name'][$index]['pdfFormacionAcademica'],
                            'tmp_name' => $_FILES['formacionAcademica']['tmp_name'][$index]['pdfFormacionAcademica'],
                            'size' => $_FILES['formacionAcademica']['size'][$index]['pdfFormacionAcademica'],
                            'error' => $_FILES['formacionAcademica']['error'][$index]['pdfFormacionAcademica']
                        ];
            
                        // Procesar el archivo PDF
                        try {
                            $pdfFormacion = $this->procesarArchivo(
                                $archivo, // Datos del archivo
                                APP . 'view/admin/upload/formacion_academica/', // Ruta de almacenamiento
                                "pdf_formacion_academica_$numDoc _$index " // Nombre base del archivo
                            );
                        } catch (Exception $e) {
                            error_log("Error al procesar el archivo PDF en la fila $index: " . $e->getMessage());
                            throw new Exception("Error al procesar el archivo PDF en la fila $index: " . $e->getMessage());
                        }
                    } else {
                        error_log("No se encontró un archivo PDF para la fila $index.");
                    }
            
                    // Preparar los datos de formación académica
                    $formacionData = [
                        'postulante_id' => $postulanteId,
                        'tipo_formacion' => $formacion['tipoFormacion'] ?? null,
                        'pais' => $formacion['paisFormacion'] ?? null,
                        'ano_graduacion' => $formacion['anoGraduacion'] ?? null,
                        'universidad' => $formacion['institucionEducativa'] ?? null,
                        'nombre_grado' => $formacion['nombreGrado'] ?? null,
                        'pdf_formacion_academica' => $pdfFormacion // Puede ser una cadena vacía si no se envió un archivo
                    ];
            
                    // Registrar los datos en el log para depuración
                    error_log("Datos de formación académica (fila $index): " . print_r($formacionData, true));
            
                    // Insertar los datos en la base de datos
                    if (!$this->model->insertFormacionAcademica($formacionData)) {
                        error_log("Error al guardar la formación académica en la fila $index.");
                        throw new Exception("Error al guardar la formación académica en la fila $index.");
                    }
                }
            }

            // * Idiomas
            if (!empty($_POST['idiomas'])) {
                foreach ($_POST['idiomas'] as $idioma) {
                    $idiomaData = [
                        'postulante_id' => $postulanteId,
                        'idioma' => $idioma['idioma'],
                        'competencia_escrita' => $idioma['competenciaEscrita'],
                        'competencia_lectora' => $idioma['competenciaLectora'],
                        'competencia_oral' => $idioma['competenciaOral']
                    ];

                    if (!$this->model->insertIdiomas($idiomaData)) {
                        throw new Exception("Error al guardar los idiomas");
                    }
                }
            }

            // * Cursos y Seminarios - Relacionados a: su campo profesional
            if (!empty($_POST['SeminariosCampoProfesional'])) {
                foreach ($_POST['SeminariosCampoProfesional'] as $cursoSeminarioCampoProfesional) {
                    $cursoSeminarioDataCampoProfesional = [
                        'postulante_id' => $postulanteId,
                        'ano_curso_seminario' => $cursoSeminarioCampoProfesional['anoCertificadoCampoProfesional'],
                        'institucion_curso_seminario' => $cursoSeminarioCampoProfesional['institucionCampoProfesional'],
                        'nombre_curso_seminario' => $cursoSeminarioCampoProfesional['cursoSeminarioCampoProfesional'],
                        'tipo_seminario' => $cursoSeminarioCampoProfesional['tipoCursoSeminarioCampoProfesional'],
                        'duracion_curso_seminario' => $cursoSeminarioCampoProfesional['duracionCampoProfesional']
                    ];

                    if (!$this->model->insertCursosSeminariosCampoProfesional($cursoSeminarioDataCampoProfesional)) {
                        throw new Exception("Error al guardar los cursos y seminarios relacionados a su campo profesional");
                    }
                }
            }

            // * Cursos y Seminarios - Relacionados a: ámbito académico
            if (!empty($_POST['SeminariosAmbitoAcademico'])) {
                foreach ($_POST['SeminariosAmbitoAcademico'] as $cursoSeminarioAmbitoAcademico) {
                    $cursoSeminarioDataAmbitoAcademico = [
                        'postulante_id' => $postulanteId,
                        'ano_curso_ambito_academico' => $cursoSeminarioAmbitoAcademico['anoCertificadoAmbitoAcademico'],
                        'institucion_curso_ambito_academico' => $cursoSeminarioAmbitoAcademico['institucionAmbitoAcademico'],
                        'nombre_curso_ambito_academico' => $cursoSeminarioAmbitoAcademico['cursoSeminarioAmbitoAcademico'],
                        'tipo_seminario' => $cursoSeminarioAmbitoAcademico['tipoCursoSeminarioAmbitoAcademico'],
                        'duracion_curso_ambito_academico' => $cursoSeminarioAmbitoAcademico['duracionAmbitoAcademico']
                    ];

                    if (!$this->model->insertCursosSeminariosAmbitoAcademico($cursoSeminarioDataAmbitoAcademico)) {
                        throw new Exception("Error al guardar los cursos y seminarios relacionados a su campo profesional");
                    }
                }
            }

            //* Cursos y Seminarios - Relacionados a: ámbito de evaluación
            if (!empty($_POST['SeminariosAmbitoEvaluacion'])) {
                foreach ($_POST['SeminariosAmbitoEvaluacion'] as $cursoSeminarioAmbitoEvaluacion) {
                    $cursoSeminarioDataAmbitoEvaluacion = [
                        'postulante_id' => $postulanteId,
                        'ano_curso_ambito_evaluacion' => $cursoSeminarioAmbitoEvaluacion['anoCertificadoAmbitoEvaluacion'],
                        'institucion_curso_ambito_evaluacion' => $cursoSeminarioAmbitoEvaluacion['institucionAmbitoEvaluacion'],
                        'nombre_curso_ambito_evaluacion' => $cursoSeminarioAmbitoEvaluacion['cursoSeminarioAmbitoEvaluacion'],
                        'tipo_seminario' => $cursoSeminarioAmbitoEvaluacion['tipoCursoSeminarioAmbitoEvaluacion'],
                        'duracion_curso_ambito_evaluacion' => $cursoSeminarioAmbitoEvaluacion['duracionAmbitoEvaluacion']
                    ];

                    if (!$this->model->insertCursosSeminariosAmbitoEvaluacion($cursoSeminarioDataAmbitoEvaluacion)) {
                        throw new Exception("Error al guardar los cursos y seminarios relacionados a su campo profesional");
                    }
                }
            }

            //!* experiencia laboral
            if (!empty($_POST['experienciaLaboral'])) {
                foreach ($_POST['experienciaLaboral'] as $index => $experienciaLaboral) {
                    // Inicializar la variable para el archivo PDF
                    $pdfExperienciaLaboral = '';
            
                    // Verificar si se envió un archivo PDF para esta fila
                    if (!empty($_FILES['experienciaLaboral']['tmp_name'][$index]['pdfExperienciaLaboral'])) {
                        // Preparar los datos del archivo para procesarArchivo
                        $archivo = [
                            'name' => $_FILES['experienciaLaboral']['name'][$index]['pdfExperienciaLaboral'],
                            'tmp_name' => $_FILES['experienciaLaboral']['tmp_name'][$index]['pdfExperienciaLaboral'],
                            'size' => $_FILES['experienciaLaboral']['size'][$index]['pdfExperienciaLaboral'],
                            'error' => $_FILES['experienciaLaboral']['error'][$index]['pdfExperienciaLaboral']
                        ];
            
                        // Procesar el archivo PDF
                        try {
                            $pdfExperienciaLaboral = $this->procesarArchivo(
                                $archivo, // Datos del archivo
                                APP . 'view/admin/upload/experiencia_laboral/', // Ruta de almacenamiento
                                "pdf_experiencia_laboral_$numDoc _$index" // Nombre base del archivo
                            );
                        } catch (Exception $e) {
                            error_log("Error al procesar el archivo PDF en la fila $index: " . $e->getMessage());
                            throw new Exception("Error al procesar el archivo PDF en la fila $index: " . $e->getMessage());
                        }
                    } else {
                        error_log("No se encontró un archivo PDF para la fila $index.");
                    }
            
                    // Guardar los datos de la experiencia laboral
                    $experienciaLaboralData = [
                        'postulante_id' => $postulanteId,
                        'institucion_empresa' => $experienciaLaboral['institucionEmpresaExperienciaLaboral'],
                        'cargo_desempenado' => $experienciaLaboral['cargoDesempeñadoExperienciaLaboral'],
                        'fecha_inicio' => $experienciaLaboral['fechaInicioExperienciaLaboral'],
                        'fecha_retiro' => $experienciaLaboral['fechaRetiroExperienciaLaboral'],
                        'pais' => $experienciaLaboral['paisEmpresaExperienciaLaboral'],
                        'ciudad' => $experienciaLaboral['ciudadExperienciaLaboral'],
                        'pdf_experiencia' => $pdfExperienciaLaboral
                    ];

                    if (!$this->model->insertExperienciaLaboral($experienciaLaboralData)) {
                        throw new Exception("Error al guardar la experiencia laboral");
                    }
                }
            }

            //!* experencia docente
            if (!empty($_POST['experienciaDocente'])) {
                foreach ($_POST['experienciaDocente'] as $index => $experienciaDocente) {
                    // Inicializar la variable para el archivo PDF
                    $pdfExperienciaDocente = '';
            
                    // Verificar si se envió un archivo PDF para esta fila
                    if (!empty($_FILES['experienciaDocente']['tmp_name'][$index]['pdfExperienciaDocente'])) {
                        // Preparar los datos del archivo para procesarArchivo
                        $archivo = [
                            'name' => $_FILES['experienciaDocente']['name'][$index]['pdfExperienciaDocente'],
                            'tmp_name' => $_FILES['experienciaDocente']['tmp_name'][$index]['pdfExperienciaDocente'],
                            'size' => $_FILES['experienciaDocente']['size'][$index]['pdfExperienciaDocente'],
                            'error' => $_FILES['experienciaDocente']['error'][$index]['pdfExperienciaDocente']
                        ];
            
                        // Procesar el archivo PDF
                        try {
                            $pdfExperienciaDocente = $this->procesarArchivo(
                                $archivo, // Datos del archivo
                                APP . 'view/admin/upload/experiencia_laboral/experiencia_docente/', // Ruta de almacenamiento
                                "pdf_experiencia_docente_$numDoc _$index" // Nombre base del archivo
                            );
                        } catch (Exception $e) {
                            error_log("Error al procesar el archivo PDF en la fila $index: " . $e->getMessage());
                            throw new Exception("Error al procesar el archivo PDF en la fila $index: " . $e->getMessage());
                        }
                    } else {
                        error_log("No se encontró un archivo PDF para la fila $index.");
                    }
            
                    // Guardar los datos de la experiencia laboral
                    $experienciaDocenteData = [
                        'postulante_id' => $postulanteId,
                        'institucion' => $experienciaDocente['institucionExperienciaDocente'],
                        'pais' => $experienciaDocente['paisExperienciaDocente'],
                        'ciudad' => $experienciaDocente['ciudadExperienciaDocente'],
                        'programa_profesional' => $experienciaDocente['programaProfesionalExperienciaDocente'],
                        'curso_capacitacion_impartido' => $experienciaDocente['cursoCapacitacionImpartidoExperienciaDocente'],
                        'funciones_principales' => $experienciaDocente['funcionesPrincipales'],
                        'fecha_inicio' => $experienciaDocente['fechaInicioExperienciaDocente'],
                        'fecha_retiro' => $experienciaDocente['fechaRetiroExperienciaDocente'],
                        'pdf_experiencia_docente' => $pdfExperienciaDocente
                    ];

                    if (!$this->model->insertExperienciaDonante($experienciaDocenteData)) {
                        throw new Exception("Error al guardar la experiencia docente");
                    }
                }
            }

            //* experiencia comite
            if (!empty($_POST['experienciaComite'])) {
                foreach ($_POST['experienciaComite'] as $experienciaComite) {
                    $experienciaComiteData = [
                        'postulante_id' => $postulanteId,
                        'institucion' => $experienciaComite['institucionComite'],
                        'cargo_desempenado' => $experienciaComite['cargoComite'],
                        'modelos_calidad' => $experienciaComite['modelosCalidad'],
                        'fecha_inicio' => $experienciaComite['fechaInicioComite'],
                        'fecha_retiro' => $experienciaComite['fechaRetiroComite'],
                    ];

                    if (!$this->model->insertExperienciaComite($experienciaComiteData)) {
                        throw new Exception("Error al guardar la experiencia comite");
                    }
                }
            }

            //* experiencia evaluador
            if (!empty($_POST['experienciaEvaluador'])) {
                foreach ($_POST['experienciaEvaluador'] as $experienciaEvaluador) {
                    $experienciaEvaluadorData = [
                        'postulante_id' => $postulanteId,
                        'agencia_acreditadora' => $experienciaEvaluador['agenciaAcreditadora'],
                        'fecha_inicio_evaluador' => $experienciaEvaluador['fechaInicioEvaluador'],
                        'fecha_retiro_evaluador' => $experienciaEvaluador['fechaRetiroEvaluador'],
                        'nombreEntidadEvaluador' => $experienciaEvaluador['nombreEntidad'],
                        'programaEvaluador' => $experienciaEvaluador['programaEvaluador'],
                        'cargoEvaluador' => $experienciaEvaluador['cargoEvaluador'],
                        'paisEvaluador' => $experienciaEvaluador['paisEvaluador'],
                        'ciudadEvaluador' => $experienciaEvaluador['ciudadEvaluador'],
                        'fechaEvaluacionEvaluador' => $experienciaEvaluador['fechaEvaluacion'],
                    ];

                    if (!$this->model->insertExperienciaEvaluador($experienciaEvaluadorData)) {
                        throw new Exception("Error al guardar la experiencia evaluador");
                    }
                }
            }

            //* membresias
            if (!empty($_POST['membresias'])) {
                foreach ($_POST['membresias'] as $membresia) {
                    $membresiaData = [
                        'postulante_id' => $postulanteId,
                        'asociacion_profesional' => $membresia['asociacionProfesional'],
                        'numero_membresia' => $membresia['numeroMembresia'],
                        'grado' => $membresia['gradoMembresia'],
                    ];

                    if (!$this->model->insertExperienciaMembresia($membresiaData)) {
                        throw new Exception("Error al guardar la experiencia membresia");
                    }
                }
            }

            //* investigacion
            if (!empty($_POST['investigaciones'])) {
                foreach ($_POST['investigaciones'] as $investigacion) {
                    $investigacionData = [
                        'postulante_id' => $postulanteId,
                        'fecha_publicacion' => $investigacion['fechaPublicacion'],
                        'revista_congreso' => $investigacion['revistaCongreso'],
                        'base_datos' => $investigacion['baseDatos'],
                        'nombre_investigacion' => $investigacion['nombreInvestigacion'],
                        'autores' => $investigacion['autores'],
                    ];

                    if (!$this->model->insertExperienciaInvestigacion($investigacionData)) {
                        throw new Exception("Error al guardar la experiencia investigacion");
                    }
                }
            }

            //* premios y reconocimientos
            if (!empty($_POST['premiosReconocimientos'])) {
                foreach ($_POST['premiosReconocimientos'] as $premio) {
                    $premioData = [
                        'postulante_id' => $postulanteId,
                        'ano_reconocimiento' => $premio['anoReconocimiento'],
                        'institucion_empresa' => $premio['institucionReconocimiento'],
                        'nombre_reconocimiento' => $premio['nombreReconocimiento'],
                        'descripcion_reconocimiento' => $premio['descripcionReconocimiento'],
                    ];

                    if (!$this->model->insertExperienciaPremios($premioData)) {
                        throw new Exception("Error al guardar la experiencia premios");
                    }
                }
            }

            //* valores eticos y firma
            if (!empty($_POST['condutaEtica']) && !empty($_POST['condutaEticaValores']) && !empty($_POST['firma'])) {
                // Capturar los datos del formulario
                $condutaEtica = $_POST['condutaEtica'] ? 1 : 0; // Convertir a entero (1 para verdadero, 0 para falso)
                $condutaEticaValores = $_POST['condutaEticaValores'] ? 1 : 0; // Convertir a entero (1 para verdadero, 0 para falso)
                $firmaBase64 = $_POST['firma'];
                $nombreFirma = "firma_" . $numDoc . ".png"; // Nombre de la firma

                // Validar si existe la firma
                if (empty($firmaBase64)) {
                    throw new Exception("Debe proporcionar una firma válida.");
                }

                // Guardar la firma como imagen en el servidor
                $directorio = APP . 'view/admin/upload/firmas/';
                $rutaCompleta = $directorio . $nombreFirma;

                // Crear el directorio si no existe
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }

                // Decodificar y guardar la firma en la carpeta
                $firmaImagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $firmaBase64));

                if (file_put_contents($rutaCompleta, $firmaImagen) === false) {
                    throw new Exception("Error al guardar la firma en el servidor.");
                }

                // Insertar los datos en la base de datos
                $condutaEticaData = [
                    'postulante_id' => $postulanteId,
                    'conduta_etica' => $condutaEtica,
                    'conduta_etica_valores' => $condutaEticaValores,
                    'firma' => $nombreFirma // Solo guardamos el nombre del archivo
                ];

                if (!$this->model->insertCondutaEtica($condutaEticaData)) {
                    throw new Exception("Error al guardar la información de conducta ética.");
                }
            }

            // echo "éxito";

            // $numDoc = $_POST['numDoc'];
            // $Home = new Home();
            $contador = $this->model->contador($numDoc);
            // echo $numDoc;

            if ($contador != false) {
                //condicion para el contador un digito agregar 00
                if ($contador->id < 10) {
                    echo '00'.$contador->id;

                }else if ($contador->id < 100) { 
                    echo '0'.$contador->id;

                }else if ($contador->id < 1000) {
                    echo $contador->id;
                }
            }else{
                echo 'ID NO EXISTE';
            }

        } catch (Exception $e) {
            echo "Error";
        }
    }

    //? Función para procesar y guardar un archivo
    private function procesarArchivo($archivo, $directorio, $nombreBase)
    {
        // Si no se proporciona un archivo, retornar una cadena vacía
        if (!$archivo || empty($archivo['name'])) {
            return '';
        }

        // Tipos de archivo permitidos (solo imágenes y PDF)
        $permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        $tipoArchivo = mime_content_type($archivo['tmp_name']);

        // Validar el tipo de archivo
        if (!in_array($tipoArchivo, $permitidos)) {
            throw new Exception("Formato de archivo no permitido. Solo se permiten archivos JPEG, PNG y PDF.");
        }

        // Validar el tamaño del archivo (máximo 5MB)
        if ($archivo['size'] > 5 * 1024 * 1024) { // 5MB
            throw new Exception("El archivo es demasiado grande. El tamaño máximo permitido es 5MB.");
        }

        // Obtener la extensión del archivo
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);

        // Generar un nombre único para el archivo
        $nombreArchivo = $nombreBase . '_' . uniqid() . '.' . $extension;

        // Asegurarse de que el directorio de destino exista
        if (!is_dir($directorio)) {
            if (!mkdir($directorio, 0777, true)) {
                throw new Exception("No se pudo crear el directorio de destino.");
            }
        }

        // Ruta completa del archivo de destino
        $rutaDestino = rtrim($directorio, '/') . '/' . $nombreArchivo;

        // Mover el archivo subido al directorio de destino
        if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            throw new Exception("Error al mover el archivo subido. Verifique los permisos del directorio.");
        }

        // Retornar solo el nombre del archivo para almacenarlo en la base de datos
        return $nombreArchivo;
    }

    // public function buscar_contador(){
    //     $numDoc = $_POST['numDoc'];
    //     $Home = new Home();
    //     $contador = $Home->contador($numDoc);
    //     // echo $numDoc;

    //     if ($contador != false) {
    //         //condicion para el contador un digito agregar 00
    //         if ($contador->id < 10) {
    //             echo '00'.$contador->id;

    //         }else if ($contador->id < 100) { 
    //             echo '0'.$contador->id;

    //         }else if ($contador->id < 1000) {
    //             echo $contador->id;
    //         }
    //     }else{
    //         echo 'ID NO EXISTE';
    //     }
    // }
}
