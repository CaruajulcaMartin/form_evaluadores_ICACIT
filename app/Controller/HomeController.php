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
            $fotoPerfil = $this->procesarArchivo($_FILES['fotoPerfil'] ?? null, APP . 'view/admin/upload/foto_perfil/', "foto_perfil_$numDoc");
            $pdfDocumento = $this->procesarArchivo($_FILES['pdfDocumentoIdentidad'] ?? null, APP . 'view/admin/upload/documentos/', "pdf_documento_identidad_$numDoc");
            // $pdfFormacion = $this->procesarArchivo($_FILES['pdfFormacionAcademica'] ?? null, APP . 'view/admin/upload/documentos/formacion-academica/', "pdf_formacion_academica_$numDoc");

            // *Informacion Personal - Insertar datos personales
            $postulante['fotoPerfil'] = $fotoPerfil;
            $postulante['pdfDocumentoIdentidad'] = $pdfDocumento;
            $postulanteId = $this->model->insertPersonalInfo($postulante);

            if (!$postulanteId) {
                throw new Exception("Error al guardar los datos personales");
            }

            //!* Formación Académica
            // Obtener y procesar los datos de formación académica
            if (!empty($_POST['formacionAcademica'])) {
                foreach ($_POST['formacionAcademica'] as $formacion) {
                    // ! no procesa el pdf
                    $pdfFormacion = $this->procesarArchivo($_FILES['pdfFormacionAcademica'] ?? null, APP . 'view/admin/upload/documentos/formacion-academica/', "pdf_formacion_academica_$numDoc");

                    $formacionData = [
                        'postulante_id' => $postulanteId,
                        'tipo_formacion' => $formacion['tipoFormacion'],
                        'pais' => $formacion['paisFormacion'],
                        'ano_graduacion' => $formacion['anoGraduacion'],
                        'universidad' => $formacion['institucionEducativa'],
                        'nombre_grado' => $formacion['nombreGrado'],
                        'pdf_formacion_academica' => $pdfFormacion
                    ];

                    if (!$this->model->insertFormacionAcademica($formacionData)) {
                        throw new Exception("Error al guardar la formación académica");
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
                        'duracion_curso_ambito_evaluacion' => $cursoSeminarioAmbitoEvaluacion['duracionAmbitoEvaluacion']
                    ];

                    if (!$this->model->insertCursosSeminariosAmbitoEvaluacion($cursoSeminarioDataAmbitoEvaluacion)) {
                        throw new Exception("Error al guardar los cursos y seminarios relacionados a su campo profesional");
                    }
                }
            }

            //!* experiencia laboral
            if (!empty($_POST['experienciaLaboral'])) {
                foreach ($_POST['experienciaLaboral'] as $experienciaLaboral) {
                    // ! no procesa el pdf de la experiencia laboral
                    $pdfExperienciaLaboral = $this->procesarArchivo($_FILES['pdfExperienciaLaboral'] ?? null, APP . 'view/admin/upload/documentos/experiencia_laboral/', "pdf_experiencia_$numDoc");
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
                foreach ($_POST['experienciaDocente'] as $experienciaDocente) {
                    // ! no procesa el pdf de la experiencia docente
                    $pdfExperienciaDocente = $this->procesarArchivo($_FILES['pdfExperienciaDocente'] ?? null, APP . 'view/admin/upload/documentos/experiencia_docente/', "pdf_experiencia_$numDoc");
                    $experienciaDocenteData = [
                        'postulante_id' => $postulanteId,
                        'institucion' => $experienciaDocente['institucionExperienciaDocente'],
                        'pais' => $experienciaDocente['paisExperienciaDocente'],
                        'ciudad' => $experienciaDocente['ciudadExperienciaDocente'],
                        'programa_profesional' => $experienciaDocente['programaProfesionalExperienciaDocente'],
                        'curso_capacitacion_impartido' => $experienciaDocente['cursoCapacitacionImpartidoExperienciaDocente'],
                        'funciones_principales' => $experienciaDocente['funcionesPrincipales'] ?? null,  //! falta mejorar el procesamiento de las funciones principales
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
                $nombreFirma = "firma_" . $numDoc. ".png"; // Nombre de la firma

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

            echo "éxito";
        } catch (Exception $e) {
            echo "Error";
        }
    }

    private function procesarArchivo($archivo, $directorio, $nombreBase)
    {
        if (!$archivo || empty($archivo['name'])) {
            return '';
        }

        $permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        $tipoArchivo = mime_content_type($archivo['tmp_name']);

        if (!in_array($tipoArchivo, $permitidos)) {
            throw new Exception("Formato de archivo no permitido");
        }

        if ($archivo['size'] > 5 * 1024 * 1024) { // Máximo 5MB
            throw new Exception("El archivo es demasiado grande");
        }

        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = $nombreBase . '.' . $extension;
        $rutaDestino = rtrim($directorio, '/') . '/' . $nombreArchivo;

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            throw new Exception("Error al subir el archivo");
        }

        return $nombreArchivo; // Guardamos solo el nombre del archivo en la base de datos
    }
}
