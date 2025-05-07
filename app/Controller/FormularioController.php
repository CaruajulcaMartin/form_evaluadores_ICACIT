<?php

namespace Mini\Controller;

use Mini\Model\Home;
use Mini\Model\Persona;
use Mini\Model\DatosRecuperar;
use Mini\Model\Progreso;

use PDOException;
use Exception;
use Mini\Core\Model;

class FormularioController extends Model
{

    //enviar formulario completo
    public function enviarFormularioCompleto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonError("Método no permitido", 405);
            return;
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonError("Usuario no autenticado", 401);
            return;
        }

        $usuarioId = $_SESSION['user_id'];

        try {
            $homeModel = new Home();
            $progresoModel = new Progreso();
            $this->db->beginTransaction();

            // 1. Verificar y confirmar el envío del formulario
            if (!$homeModel->confirmarEnvio($usuarioId)) {
                $this->db->rollBack();
                $this->sendJsonError("Error al confirmar el envío del formulario", 500);
                return;
            }

            // 2. Actualizar el estado de todas las secciones como completadas
            $progresoId = $progresoModel->obtenerIdProgreso($usuarioId);
            if (!$progresoId) {
                $this->db->rollBack();
                $this->sendJsonError("No se encontró progreso para el usuario", 404);
                return;
            }

            // 3. Actualizar el progreso general
            $seccionActual = "Sección 9";
            $porcentajeCompletado = 100;
            $completado = 1;

            $progresoModel->actualizarProgresoGeneral(
                $progresoId,
                $seccionActual,
                $porcentajeCompletado,
                $completado
            );

            // 4. Actualizar la sesión
            $_SESSION['formulario_enviado'] = true;

            $this->db->commit();

            $this->sendJsonResponse([
                "success" => true,
                "message" => "Formulario enviado con éxito",
                "formulario_enviado" => true,
                "estado" => "completado"
            ]);
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al enviar formulario: " . $e->getMessage());
            $this->sendJsonError("Ocurrió un error interno al procesar tu solicitud: " . $e->getMessage(), 500);
        }
    }

    public function verificarEstadoFormulario()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonError("Usuario no autenticado", 401);
            return;
        }

        try {
            $homeModel = new Home();
            $estado = $homeModel->verificarEnvio($_SESSION['user_id']);

            $this->sendJsonResponse([
                "success" => true,
                "formulario_enviado" => $estado['enviado'],
                "fecha_envio" => $estado['fecha_envio'],
                "porcentaje_completado" => $estado['porcentaje'],
                "seccion_actual" => $estado['seccion_actual']
            ]);
        } catch (Exception $e) {
            $this->sendJsonError("Error al verificar estado del formulario", 500);
        }
    }

    //  ---  Funciones de utilidad (privadas)  ---
    private function sendJsonResponse($data, $httpCode = 200)
    {
        http_response_code($httpCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    private function sendJsonError($message, $httpCode = 500)
    {
        $this->sendJsonResponse(["success" => false, "message" => $message], $httpCode);
    }

    //!Actualizar la seccion 7
    public function actualizarSeccion7()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido", 405);
            }

            session_start();
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Usuario no autenticado", 401);
            }

            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.", 404);
            }

            // Validar que existe el campo
            if (!isset($_POST['cartaPresentacion'])) {
                throw new Exception("El campo 'Carta de Presentación' es requerido.", 400);
            }

            $cartaPresentacion = trim($_POST['cartaPresentacion']);
            $wordCount = str_word_count($cartaPresentacion);

            if ($wordCount > 400) {
                throw new Exception("La carta de presentación excede el límite de 400 palabras.", 400);
            }

            $homeModel = new DatosRecuperar();

            $resultado = $homeModel->updateCartaPresentacion($postulanteId, [
                'cartaPresentacion' => $cartaPresentacion
            ]);

            if ($resultado === 0) {
                // Insertar nuevo registro si no existía
                $resultado = $homeModel->insertCartaPresentacion([
                    'postulante_id' => $postulanteId,
                    'carta_presentacion' => $cartaPresentacion
                ]);

                if (!$resultado) {
                    throw new Exception("Error al guardar la carta de presentación", 500);
                }

                $message = "Carta de presentación guardada correctamente";
            } else {
                $message = "Carta de presentación actualizada correctamente";
            }

            $this->actualizarProgreso($userId, 7, true);

            echo json_encode([
                "status" => "success",
                "message" => $message,
                "redirect" => URL . 'Admin/HomeFormulario'
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage(),
                "code" => $e->getCode() ?: 500
            ]);
        }

        exit;
    }

    // Actualizar la seccion 6
    public function actualizarSeccion6()
    {
        header('Content-Type: application/json');

        try {

            session_start();
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            // Debug: Verificar datos recibidos
            error_log("Datos POST recibidos: " . print_r($_POST, true));
            error_log("Datos FILES recibidos: " . print_r($_FILES, true));

            if (empty($_POST['esActualizacion'])) {
                $this->enviarSeccion5();
                return;
            }

            $formularioModel = new Home();

            // 1. procesar eliminados
            if (!empty($_POST['eliminados'])) {
                $eliminados = json_decode($_POST['eliminados'], true);
                error_log("Eliminados decodificados: " . print_r($eliminados, true));

                if (!empty($eliminados['Premios'])) {
                    foreach ($eliminados['Premios'] as $id) {
                        $formularioModel->deleteSeccion6($id);
                        error_log("Eliminada investigación con ID: $id");
                    }
                }
            }

            // 2. procesar actualizados
            if (!empty($_POST['Premios'])) {
                foreach ($_POST['Premios'] as $premio) {
                    $premioData = [
                        'postulante_id' => $postulanteId,
                        'ano_reconocimiento' => $premio['anoReconocimiento'],
                        'institucion_empresa' => $premio['institucionReconocimiento'],
                        'nombre_reconocimiento' => $premio['nombreReconocimiento'],
                        'descripcion_reconocimiento' => $premio['descripcionReconocimiento'],
                    ];
                    $formularioModel->insertTablaPremios($premioData);
                }
            }

            $progresoController = new FormularioController(); //  ¡Asegúrate de instanciar la clase!
            $progresoController->actualizarProgreso($userId, 6, true);

            echo json_encode([
                "status" => "success",
                "message" => "Datos actualizados correctamente",
                "debug" => [
                    "eliminados" => $eliminados ?? [],
                    "nuevos" => $nuevos ?? []
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }
    }


    // Actualizar la seccion 5
    public function actualizarSeccion5()
    {
        header('Content-Type: application/json');

        try {

            session_start();
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            // Debug: Verificar datos recibidos
            error_log("Datos POST recibidos: " . print_r($_POST, true));
            error_log("Datos FILES recibidos: " . print_r($_FILES, true));

            if (empty($_POST['esActualizacion'])) {
                $this->enviarSeccion5();
                return;
            }

            $formularioModel = new Home();

            // 1. procesar eliminados
            if (!empty($_POST['eliminados'])) {
                $eliminados = json_decode($_POST['eliminados'], true);
                error_log("Eliminados decodificados: " . print_r($eliminados, true));

                if (!empty($eliminados['Investigaciones'])) {
                    foreach ($eliminados['Investigaciones'] as $id) {
                        $formularioModel->deleteSeccion5($id);
                        error_log("Eliminada investigación con ID: $id");
                    }
                }
            }

            // 2. procesar actualizados
            if (!empty($_POST['Investigaciones'])) {
                foreach ($_POST['Investigaciones'] as $investigacion) {
                    $investigacionData = [
                        'postulante_id' => $postulanteId,
                        'fecha_publicacion' => $investigacion['fechaPublicacion'],
                        'revista_congreso' => $investigacion['revistaCongreso'],
                        'base_datos' => $investigacion['baseDatos'],
                        'nombre_investigacion' => $investigacion['nombreInvestigacion'],
                        'autores' => $investigacion['autores'],
                    ];
                    $formularioModel->insertTablaInvestigaciones($investigacionData);
                }
            }

            $progresoController = new FormularioController(); //  ¡Asegúrate de instanciar la clase!
            $progresoController->actualizarProgreso($userId, 5, true);

            echo json_encode([
                "status" => "success",
                "message" => "Datos actualizados correctamente",
                "debug" => [
                    "eliminados" => $eliminados ?? [],
                    "nuevos" => $nuevos ?? []
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }
    }


    //! Actualizar la seccion 4
    public function actualizarSeccion4()
    {
        header('Content-Type: application/json');

        try {

            session_start();
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            // Debug: Verificar datos recibidos
            error_log("Datos POST recibidos: " . print_r($_POST, true));
            error_log("Datos FILES recibidos: " . print_r($_FILES, true));

            if (empty($_POST['esActualizacion'])) {
                $this->enviarSeccion4();
                return;
            }

            $formularioModel = new Home();

            //* 1. procesar eliminados
            if (!empty($_POST['eliminados'])) {
                $eliminados = json_decode($_POST['eliminados'], true);
                error_log("Eliminados decodificados: " . print_r($eliminados, true));

                foreach ($eliminados as $tabla => $ids) {
                    foreach ($ids as $id) {
                        switch ($tabla) {
                            case 'experienciaLaboral':
                                $formularioModel->deleteExperienciaLaboral($id);
                                error_log("Eliminado experiencia laboral con ID: $id");
                                break;
                            case 'experienciaDocente':
                                $formularioModel->deleteExperienciaDocente($id);
                                error_log("Eliminado experiencia docente con ID: $id");
                                break;
                            case 'experienciaComite':
                                $formularioModel->deleteExperienciaComite($id);
                                error_log("Eliminado experiencia comite con ID: $id");
                                break;
                            case 'experienciaEvaluador':
                                $formularioModel->deleteExperienciaEvaluador($id);
                                error_log("Eliminado experiencia evaluador con ID: $id");
                                break;
                            case 'Membresias':
                                $formularioModel->deleteMembresias($id);
                                error_log("Eliminado membresias con ID: $id");
                                break;
                        }
                    }
                }
            }

            // 2. procesar nuevos
            if (!empty($_POST['experienciaLaboral']) && is_array($_POST['experienciaLaboral'])) {
                foreach ($_POST['experienciaLaboral'] as $index => $experiencia) {
                    $pdfPath = '';

                    // Manejo del archivo PDF
                    if (
                        isset($_FILES['experienciaLaboral']['name'][$index]['pdf']) &&
                        $_FILES['experienciaLaboral']['error'][$index]['pdf'] === UPLOAD_ERR_OK
                    ) {

                        $fileInfo = [
                            'name' => $_FILES['experienciaLaboral']['name'][$index]['pdf'],
                            'tmp_name' => $_FILES['experienciaLaboral']['tmp_name'][$index]['pdf'],
                            'size' => $_FILES['experienciaLaboral']['size'][$index]['pdf'],
                            'error' => $_FILES['experienciaLaboral']['error'][$index]['pdf'],
                            'type' => $_FILES['experienciaLaboral']['type'][$index]['pdf']
                        ];

                        // Validar que sea PDF
                        if ($fileInfo['type'] !== 'application/pdf') {
                            throw new Exception("El archivo debe ser un PDF válido.");
                        }

                        //! Mover el archivo
                        $uploadDir = APP . 'view/admin/upload/experiencia_laboral/';
                        $filename = uniqid('exp_laboral_') . '.pdf';
                        $destination = $uploadDir . $filename;

                        if (!move_uploaded_file($fileInfo['tmp_name'], $destination)) {
                            throw new Exception("No se pudo guardar el archivo PDF de experiencia laboral.");
                        }

                        $pdfPath = $filename;
                    }

                    $experienciaData = [
                        'persona_id' => $postulanteId,
                        'institucion_empresa' => $experiencia['institucion_empresa'] ?? null,
                        'cargo_desempenado' => $experiencia['cargo_desempenado'] ?? null,
                        'fecha_inicio' => $experiencia['fecha_inicio'] ?? null,
                        'fecha_retiro' => $experiencia['fecha_retiro'] ?? null,
                        'pais' => $experiencia['pais'] ?? null,
                        'ciudad' => $experiencia['ciudad'] ?? null,
                        'pdf_experiencia' => $pdfPath ?? null
                    ];

                    $formularioModel->insertExperienciaLaboral($experienciaData);
                }
            }

            if (!empty($_POST['experienciaDocente']) && is_array($_POST['experienciaDocente'])) {
                foreach ($_POST['experienciaDocente'] as $index => $experienciaDocente) {

                    $required = ['institucion_empresa', 'cargo_desempenado', 'fecha_inicio', 'pais', 'ciudad'];

                    foreach ($required as $field) {
                        if (empty($experiencia[$field])) {
                            throw new Exception("El campo $field es requerido en la experiencia laboral (fila $index)");
                        }
                    }

                    $pdfPath = '';

                    // Manejo del archivo PDF
                    if (
                        isset($_FILES['experienciaDocente']['name'][$index]['pdf']) &&
                        $_FILES['experienciaDocente']['error'][$index]['pdf'] === UPLOAD_ERR_OK
                    ) {

                        $fileInfo = [
                            'name' => $_FILES['experienciaDocente']['name'][$index]['pdf'],
                            'tmp_name' => $_FILES['experienciaDocente']['tmp_name'][$index]['pdf'],
                            'size' => $_FILES['experienciaDocente']['size'][$index]['pdf'],
                            'error' => $_FILES['experienciaDocente']['error'][$index]['pdf'],
                            'type' => $_FILES['experienciaDocente']['type'][$index]['pdf']
                        ];

                        // Validar que sea PDF
                        if ($fileInfo['type'] !== 'application/pdf') {
                            throw new Exception("El archivo debe ser un PDF válido.");
                        }

                        // Mover el archivo
                        $uploadDir = APP . 'view/admin/upload/experiencia_laboral/experiencia_docente/';
                        $filename = uniqid('exp_docente_') . '.pdf';
                        $destination = $uploadDir . $filename;

                        if (!move_uploaded_file($fileInfo['tmp_name'], $destination)) {
                            throw new Exception("No se pudo guardar el archivo PDF de experiencia docente.");
                        }

                        $pdfPath = $filename;
                    }

                    $experienciaData = [
                        'postulante_id' => $postulanteId,
                        'institucion' => $experienciaDocente['institucion_empresa'],
                        'pais' => $experienciaDocente['pais'],
                        'ciudad' => $experienciaDocente['ciudad'],
                        'programa_profesional' => $experienciaDocente['programa_profesional'],
                        'curso_capacitacion_impartido' => $experienciaDocente['curso_capacitacion_impartido'],
                        'funciones_principales' => $experienciaDocente['funciones_principales'],
                        'fecha_inicio' => $experienciaDocente['fecha_inicio'],
                        'fecha_retiro' => $experienciaDocente['fecha_retiro'],
                        'pdf_experiencia_docente' => $pdfPath
                    ];

                    $formularioModel->insertExperienciaDocente($experienciaData);
                }
            }

            if (!empty($_POST['experienciaComite']) && is_array($_POST['experienciaComite'])) {
                foreach ($_POST['experienciaComite'] as $experienciaComite) {
                    $experienciaComiteData = [
                        'postulante_id' => $postulanteId,
                        'institucion' => $experienciaComite['institucion'],
                        'cargo_desempenado' => $experienciaComite['cargo_desempenado'],
                        'modelos_calidad' => $experienciaComite['modelos_calidad'],
                        'fecha_inicio' => $experienciaComite['fecha_inicio'],
                        'fecha_retiro' => $experienciaComite['fecha_retiro'],
                    ];
                    $formularioModel->insertExperienciaComite($experienciaComiteData);
                }
            }

            if (!empty($_POST['experienciaEvaluador']) && is_array($_POST['experienciaEvaluador'])) {
                foreach ($_POST['experienciaEvaluador'] as $experienciaEvaluador) {
                    $experienciaEvaluadorData = [
                        'postulante_id' => $postulanteId,
                        'agencia_acreditadora' => $experienciaEvaluador['agencia_acreditadora'],
                        'fecha_inicio_evaluador' => $experienciaEvaluador['fecha_inicio_evaluador'],
                        'fecha_retiro_evaluador' => $experienciaEvaluador['fecha_retiro_evaluador'],
                        'nombreEntidadEvaluador' => $experienciaEvaluador['nombreEntidadEvaluador'],
                        'programaEvaluador' => $experienciaEvaluador['programaEvaluador'],
                        'cargoEvaluador' => $experienciaEvaluador['cargoEvaluador'],
                        'paisEvaluador' => $experienciaEvaluador['paisEvaluador'],
                        'ciudadEvaluador' => $experienciaEvaluador['ciudadEvaluador'],
                        'fechaEvaluacionEvaluador' => $experienciaEvaluador['fechaEvaluacionEvaluador'],
                    ];
                    $formularioModel->insertExperienciaEvaluador($experienciaEvaluadorData);
                }
            }

            if (!empty($_POST['experienciaMembresias']) && is_array($_POST['experienciaMembresias'])) {
                foreach ($_POST['experienciaMembresias'] as $membresia) {
                    $membresiaData = [
                        'postulante_id' => $postulanteId,
                        'asociacion_profesional' => $membresia['asociacion_profesional'],
                        'numero_membresia' => $membresia['numero_membresia'],
                        'grado' => $membresia['grado'],
                    ];
                    $formularioModel->insertExperienciaMembresias($membresiaData);
                }
            }

            $progresoController = new FormularioController();
            $progresoController->actualizarProgreso($userId, 4, true);

            echo json_encode([
                "status" => "success",
                "message" => "Datos actualizados correctamente",
                "debug" => [
                    "eliminados" => $eliminados ?? [],
                    "nuevos" => $nuevos ?? []
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error en actualizarSeccion3: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
        }
    }


    //! Actualizar la seccion 3
    public function actualizarSeccion3()
    {
        //cambios 
        //inicio
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Establecer headers primero y suprimir salidas no deseadas
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        //fin
        
        try {
            session_start();
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            // Debug: Verificar datos recibidos
            error_log("Datos POST recibidos: " . print_r($_POST, true));
            error_log("Datos FILES recibidos: " . print_r($_FILES, true));

            if (empty($_POST['esActualizacion'])) {
                $this->enviarSeccion3();
                return;
            }

            $model = new Home();

            // 1. Procesar eliminados
            if (!empty($_POST['eliminados'])) {
                $eliminados = json_decode($_POST['eliminados'], true);
                error_log("Eliminados decodificados: " . print_r($eliminados, true));

                foreach ($eliminados as $tabla => $ids) {
                    foreach ($ids as $id) {
                        $id = (int)$id;
                        switch ($tabla) {
                            case 'formacionAcademica':
                                $model->eliminarFormacionAcademica($id);
                                error_log("Eliminado formación académica ID: $id");
                                break;
                            case 'idiomas':
                                $model->eliminarIdiomas($id);
                                error_log("Eliminado formación idiomas ID: $id");
                                break;
                            case 'cursosProfesionales':
                                $model->eliminarCampoProfesional($id);
                                error_log("Eliminado formación cursos profesionales ID: $id");
                                break;
                            case 'cursosAcademicos':
                                $model->eliminarCursoAcademico($id);
                                error_log("Eliminado formación cursos académicos ID: $id");
                                break;
                            case 'cursosEvaluacion':
                                $model->eliminarCursoEvaluacion($id);
                                error_log("Eliminado formación cursos evaluación ID: $id");
                                break;
                            default:
                                break;
                        }
                    }
                }
            }

            // 2. Procesar nuevos registros
            if (!empty($_POST['nuevos'])) {
                $nuevos = json_decode($_POST['nuevos'], true);
                error_log("Nuevos registros decodificados: " . print_r($nuevos, true));

                // Procesar formación académica
                if (!empty($_POST['formacionAcademica']) && is_array($_POST['formacionAcademica'])) {
                    foreach ($_POST['formacionAcademica'] as $index => $formacion) {
                        $pdfPath = '';

                        // Verificar si hay un archivo para este índice
                        if (
                            isset($_FILES['formacionAcademica']['name'][$index]['pdf']) &&
                            $_FILES['formacionAcademica']['error'][$index]['pdf'] === UPLOAD_ERR_OK
                        ) {
                            $fileInfo = [
                                'name' => $_FILES['formacionAcademica']['name'][$index]['pdf'],
                                'tmp_name' => $_FILES['formacionAcademica']['tmp_name'][$index]['pdf'],
                                'size' => $_FILES['formacionAcademica']['size'][$index]['pdf'],
                                'error' => $_FILES['formacionAcademica']['error'][$index]['pdf'],
                                'type' => $_FILES['formacionAcademica']['type'][$index]['pdf']
                            ];

                            // Validar que sea PDF
                            if ($fileInfo['type'] !== 'application/pdf') {
                                throw new Exception("El archivo debe ser un PDF válido.");
                            }

                            // Mover el archivo
                            $uploadDir = APP . 'view/admin/upload/formacion_academica/';
                            $filename = uniqid('formacion_') . '.pdf';
                            $destination = $uploadDir . $filename;

                            if (!move_uploaded_file($fileInfo['tmp_name'], $destination)) {
                                throw new Exception("No se pudo guardar el archivo PDF.");
                            }

                            $pdfPath = $filename;
                        }

                        $data = [
                            'postulante_id' => $postulanteId,
                            'tipo_formacion' => $formacion['tipo_formacion'] ?? null,
                            'pais' => $formacion['pais'] ?? null,
                            'ano_graduacion' => $formacion['ano_graduacion'] ?? null,
                            'universidad' => $formacion['universidad'] ?? null,
                            'nombre_grado' => $formacion['nombre_grado'] ?? null,
                            'pdf_formacion_academica' => $pdfPath ?? null
                        ];

                        $model->insertFormacionAcademica($data);
                        error_log("Insertado nuevo registro formación: " . print_r($data, true));
                    }
                }

                // Procesar idiomas
                if (!empty($_POST['idiomas'])) {
                    foreach ($_POST['idiomas'] as $idioma) {
                        $idiomaData = [
                            'postulante_id' => $postulanteId,
                            'idioma' => $idioma['idioma'],
                            'competencia_escrita' => $idioma['competencia_escrita'],
                            'competencia_lectora' => $idioma['competencia_lectora'],
                            'competencia_oral' => $idioma['competencia_oral'],
                        ];
                        $model->insertIdiomas($idiomaData);
                        error_log("Insertado nuevo registro idiomas: " . print_r($idiomaData, true));
                    }
                }

                // Cursos - Campo Profesional
                if (!empty($_POST['cursosProfesionales'])) {
                    foreach ($_POST['cursosProfesionales'] as $curso) {
                        $cursoData = [
                            'postulante_id' => $postulanteId,
                            'ano' => $curso['ano'],
                            'institucion' => $curso['institucion'],
                            'nombre_curso_seminario' => $curso['nombre_curso_seminario'],
                            'tipo_seminario' => $curso['tipo_seminario'],
                            'duracion' => $curso['duracion']
                        ];
                        $model->insertCursosSeminariosCampoProfesional($cursoData);
                        error_log("Insertado nuevo registro cursos profesionales: " . print_r($cursoData, true));
                    }
                }

                // Cursos - Ámbito Académico
                if (!empty($_POST['cursosAcademicos'])) {
                    foreach ($_POST['cursosAcademicos'] as $cursoSeminarioAmbitoAcademico) {
                        $cursoSeminarioDataAmbitoAcademico = [
                            'postulante_id' => $postulanteId,
                            'ano' => $cursoSeminarioAmbitoAcademico['ano'],
                            'institucion' => $cursoSeminarioAmbitoAcademico['institucion'],
                            'nombre_curso_seminario' => $cursoSeminarioAmbitoAcademico['nombre_curso_seminario'],
                            'tipo_seminario' => $cursoSeminarioAmbitoAcademico['tipo_seminario'],
                            'duracion' => $cursoSeminarioAmbitoAcademico['duracion']
                        ];
                        $model->insertCursosSeminariosAmbitoAcademico($cursoSeminarioDataAmbitoAcademico);
                        error_log("Insertado nuevo registro cursos académicos: " . print_r($cursoSeminarioDataAmbitoAcademico, true));
                    }
                }

                /// Cursos - Ámbito Evaluación
                if (!empty($_POST['cursosEvaluacion'])) {
                    foreach ($_POST['cursosEvaluacion'] as $cursoSeminarioAmbitoEvaluacion) {
                        $cursoSeminarioDataAmbitoEvaluacion = [
                            'postulante_id' => $postulanteId,
                            'ano' => $cursoSeminarioAmbitoEvaluacion['ano'],
                            'institucion' => $cursoSeminarioAmbitoEvaluacion['institucion'],
                            'nombre_curso_seminario' => $cursoSeminarioAmbitoEvaluacion['nombre_curso_seminario'],
                            'tipo_seminario' => $cursoSeminarioAmbitoEvaluacion['tipo_seminario'],
                            'duracion' => $cursoSeminarioAmbitoEvaluacion['duracion']
                        ];
                        $model->insertCursosSeminariosAmbitoEvaluacion($cursoSeminarioDataAmbitoEvaluacion);
                        error_log("Insertado nuevo registro cursos evaluación: " . print_r($cursoSeminarioDataAmbitoEvaluacion, true));
                    }
                }
            }

            $progresoController = new FormularioController(); //  ¡Asegúrate de instanciar la clase!
            $progresoController->actualizarProgreso($userId, 3, true);

            //$this->actualizarProgreso($_SESSION['user_id'], 3, true);


            echo json_encode([
                "status" => "success",
                "message" => "Datos actualizados correctamente",
                "debug" => [
                    "eliminados" => $eliminados ?? [],
                    "nuevos" => $nuevos ?? []
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error en actualizarSeccion3: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
        }
        exit;
    }


    //* Actualizar la seccion 2
    public function actualizarSeccion2()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.");
            }

            // Obtener datos del formulario
            $data = [
                'centroLaboral' => $_POST['centroLaboral'],
                'cargoActual' => $_POST['cargoActual'],
                'tiempoLaboral' => $_POST['tiempoLaboral'],
                'PaisInformacionLaboral' => $_POST['PaisInformacionLaboral'],
                'ciudadInformacionLaboral' => $_POST['ciudadInformacionLaboral'],
                'rubroInformacionLaboral' => $_POST['rubroInformacionLaboral'],
                'nombresEmpleador' => $_POST['nombresEmpleador'],
                'cargoEmpleador' => $_POST['cargoEmpleador'],
                'correoEmpleador' => $_POST['correoEmpleador']
            ];

            $homeModel = new DatosRecuperar();
            $resultado = $homeModel->updateSeccion2Data($postulanteId, $data);

            echo json_encode(["status" => "success", "message" => "Datos actualizados correctamente"]);
            exit;
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    //*Actualizar la seccion 1
    public function actualizarSeccion1()
    {
        // header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
            return;
        }

        try {
            $userId = $_POST['userId'] ?? $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.");
            }

            // Recoger datos del formulario
            $datos = [
                'apellido1' => $_POST['apellido1'] ?? '',
                'apellido2' => $_POST['apellido2'] ?? '',
                'nombresCompletos' => $_POST['nombresCompletos'] ?? '',
                'tipoIdentidad' => $_POST['tipoIdentidad'] ?? '',
                'numDoc' => $_POST['numDoc'] ?? '',
                'nationality' => $_POST['nationality'] ?? '',
                'fechaNacimiento' => $_POST['fechaNacimiento'] ?? '',
                'estadoCivil' => $_POST['estadoCivil'] ?? '',
                'correoElectronico' => $_POST['correoElectronico'] ?? '',
                'phoneCode' => $_POST['phoneCode'] ?? '',
                'celular' => $_POST['celular'] ?? '',
                'redProfesional' => $_POST['redProfesional'] ?? '',
                'tipoDireccion' => $_POST['tipoDireccion'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'numeroDireccion' => $_POST['numeroDireccion'] ?? '',
                'PaisDatoDominicial' => $_POST['PaisDatoDominicial'] ?? '',
                'PaisDatoDominicialRegion' => $_POST['PaisDatoDominicialRegion'],
                'provinciaDatoDominicial' => $_POST['provinciaDatoDominicial'],
                'distritoDatoDominicial' => $_POST['distritoDatoDominicial'],
                'referenciaDomicilio' => $_POST['referenciaDomicilio']
            ];

            // Procesar archivos si existen
            // if (!empty($_FILES['fotoPerfil']['name'])) {
            // }

            // if (!empty($_FILES['pdfDocumentoIdentidad']['name'])) {
            // }

            // Actualizar en la base de datos
            $homeModel = new DatosRecuperar();
            $resultado = $homeModel->updateSeccion1Data($postulanteId, $datos);

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Datos actualizados correctamente"
                ]);
            } else {
                throw new Exception("No se realizaron cambios en la base de datos");
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Error al actualizar: " . $e->getMessage()
            ]);
        }
    }

    //*enviar la informacion de la seccion 8 a la base de datos
    public function enviarSeccion8()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.");
            }

            // Validar los datos del formulario
            if (empty($_POST['condutaEtica']) || empty($_POST['condutaEticaValores']) || empty($_POST['firma'])) {
                throw new Exception("Todos los campos son obligatorios, incluyendo la firma.");
            }

            // Capturar los datos del formulario
            $condutaEtica = $_POST['condutaEtica'] ? 1 : 0;
            $condutaEticaValores = $_POST['condutaEticaValores'] ? 1 : 0;
            $firmaBase64 = $_POST['firma'];
            $nombreFirma = "firma_" . $postulanteId . ".png";

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
            $homeModel = new Home();
            $condutaEticaData = [
                'postulante_id' => $postulanteId,
                'conduta_etica' => $condutaEtica,
                'conduta_etica_valores' => $condutaEticaValores,
                'firma' => $nombreFirma
            ];

            $homeModel->insertSeccion8($condutaEticaData);

            // Actualizar el progreso del formulario
            // $homeModel->actualizarProgreso($userId, 8, true);
            //$this->actualizarProgreso($postulanteId, 8, true);
            $this->actualizarProgreso($_SESSION['user_id'], 8, true);

            // Redirigir o devolver respuesta exitosa
            // echo json_encode(["status" => "success", "message" => "Datos guardados correctamente"]);
            $homeModel->insertSeccion8($condutaEticaData);
            $this->actualizarProgreso($_SESSION['user_id'], 8, true);

            echo json_encode([
                "status" => "success",
                "message" => "Datos guardados correctamente",
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            exit;
        }
    }

    //*enviar la informacion de la seccion 7 a la base de datos
    public function enviarSeccion7()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido", 405);
            }

            session_start();
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Usuario no autenticado", 401);
            }

            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.", 404);
            }

            // Validar los datos del formulario
            if (empty($_POST['cartaPresentacion'])) {
                throw new Exception("El campo 'Carta de Presentación' no puede estar vacío.", 400);
            }

            $cartaPresentacion = trim($_POST['cartaPresentacion']);
            $wordCount = str_word_count($cartaPresentacion);

            if ($wordCount > 400) {
                throw new Exception("La carta de presentación excede el límite de 400 palabras.", 400);
            }

            // Guardar los datos en la base de datos
            $formularioModel = new Home();
            $formularioModel->insertCartaPresentacion([
                'postulante_id' => $postulanteId,
                'cartaPresentacion' => $cartaPresentacion
            ]);

            // Actualizar el progreso del formulario
            $this->actualizarProgreso($userId, 7, true);

            echo json_encode([
                "status" => "success",
                "message" => "Datos guardados correctamente",
                "redirect" => URL . 'Admin/HomeFormulario'
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage(),
                "code" => $e->getCode() ?: 500
            ]);
        }

        exit;
    }

    //*enviar la informacion de la seccion 6 a la base de datos
    public function enviarSeccion6()
    {
        // Verificar si es una solicitud AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => "Método no permitido"]);
                exit;
            } else {
                header('Location: ' . URL . 'Admin/HomeFormulario');
                exit;
            }
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
                exit;
            } else {
                header('Location: ' . URL . 'Persona/login');
                exit;
            }
        }

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.");
            }

            $formularioModel = new Home();

            if (!empty($_POST['Premios'])) {
                foreach ($_POST['Premios'] as $premio) {
                    $premioData = [
                        'postulante_id' => $postulanteId,
                        'ano_reconocimiento' => $premio['anoReconocimiento'],
                        'institucion_empresa' => $premio['institucionReconocimiento'],
                        'nombre_reconocimiento' => $premio['nombreReconocimiento'],
                        'descripcion_reconocimiento' => $premio['descripcionReconocimiento'],
                    ];
                    $formularioModel->insertTablaPremios($premioData);
                }
            }

            // Actualizar progreso
            // $formularioModel->actualizarProgreso($postulanteId, 6, true);
            //$this->actualizarProgreso($postulanteId, 6, true);
            $this->actualizarProgreso($_SESSION['user_id'], 6, true);

            // header('Location: ' . URL . 'Admin/HomeFormulario');
            echo json_encode(["status" => "success", "message" => "Datos guardados correctamente"]);
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                exit;
            } else {
                header('Location: ' . URL . 'Admin/HomeFormulario?error=1');
                exit;
            }
        }
    }

    //*enviar la informacion de la seccion 5 a la base de datos
    public function enviarSeccion5()
    {
        // Verificar si es una solicitud AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => "Método no permitido"]);
                exit;
            } else {
                header('Location: ' . URL . 'Admin/HomeFormulario');
                exit;
            }
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
                exit;
            } else {
                header('Location: ' . URL . 'Persona/login');
                exit;
            }
        }

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.");
            }

            $formularioModel = new Home();

            if (!empty($_POST['Investigaciones'])) {
                foreach ($_POST['Investigaciones'] as $investigacion) {
                    $investigacionData = [
                        'postulante_id' => $postulanteId,
                        'fecha_publicacion' => $investigacion['fechaPublicacion'],
                        'revista_congreso' => $investigacion['revistaCongreso'],
                        'base_datos' => $investigacion['baseDatos'],
                        'nombre_investigacion' => $investigacion['nombreInvestigacion'],
                        'autores' => $investigacion['autores'],
                    ];
                    $formularioModel->insertTablaInvestigaciones($investigacionData);
                }
            }

            // Actualizar progreso
            // $formularioModel->actualizarProgreso($postulanteId, 5, true);
            //$this->actualizarProgreso($postulanteId, 5, true);
            $this->actualizarProgreso($_SESSION['user_id'], 5, true);

            // header('Location: ' . URL . 'Admin/HomeFormulario');
            echo json_encode(["status" => "success", "message" => "Datos guardados correctamente"]);
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                exit;
            } else {
                header('Location: ' . URL . 'Admin/HomeFormulario?error=1');
                exit;
            }
        }
    }

    //enviar la informacion de la seccion 4 a la base de datos
    public function enviarSeccion4()
    {
        header('Content-Type: application/json');
        // Verificar si es una solicitud AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => "Método no permitido"]);
                exit;
            } else {
                header('Location: ' . URL . 'Admin/HomeFormulario');
                exit;
            }
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
                exit;
            } else {
                header('Location: ' . URL . 'Persona/login');
                exit;
            }
        }

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.");
            }

            $formularioModel = new Home();
            // Procesar experiencia laboral
            if (!empty($_POST['experienciaLaboral']) && is_array($_POST['experienciaLaboral'])) {
                foreach ($_POST['experienciaLaboral'] as $index => $experiencia) {
                    $pdfPath = '';

                    // Manejo del archivo PDF
                    if (
                        isset($_FILES['experienciaLaboral']['name'][$index]['pdf']) &&
                        $_FILES['experienciaLaboral']['error'][$index]['pdf'] === UPLOAD_ERR_OK
                    ) {

                        $fileInfo = [
                            'name' => $_FILES['experienciaLaboral']['name'][$index]['pdf'],
                            'tmp_name' => $_FILES['experienciaLaboral']['tmp_name'][$index]['pdf'],
                            'size' => $_FILES['experienciaLaboral']['size'][$index]['pdf'],
                            'error' => $_FILES['experienciaLaboral']['error'][$index]['pdf'],
                            'type' => $_FILES['experienciaLaboral']['type'][$index]['pdf']
                        ];

                        // Validar que sea PDF
                        if ($fileInfo['type'] !== 'application/pdf') {
                            throw new Exception("El archivo debe ser un PDF válido.");
                        }

                        // Mover el archivo
                        $uploadDir = APP . 'view/admin/upload/experiencia_laboral/';
                        $filename = uniqid('exp_laboral_') . '.pdf';
                        $destination = $uploadDir . $filename;

                        if (!move_uploaded_file($fileInfo['tmp_name'], $destination)) {
                            throw new Exception("No se pudo guardar el archivo PDF de experiencia laboral.");
                        }

                        $pdfPath = $filename;
                    }

                    $experienciaData = [
                        'postulante_id' => $postulanteId,
                        'institucion_empresa' => $experiencia['institucion_empresa'],
                        'cargo_desempenado' => $experiencia['cargo_desempenado'],
                        'fecha_inicio' => $experiencia['fecha_inicio'],
                        'fecha_retiro' => $experiencia['fecha_retiro'],
                        'pais' => $experiencia['pais'],
                        'ciudad' => $experiencia['ciudad'],
                        'pdf_experiencia' => $pdfPath
                    ];

                    $formularioModel->insertExperienciaLaboral($experienciaData);
                }
            }

            if (!empty($_POST['experienciaDocente']) && is_array($_POST['experienciaDocente'])) {
                foreach ($_POST['experienciaDocente'] as $index => $experiencia) {
                    $pdfPath = '';

                    // Manejo del archivo PDF
                    if (
                        isset($_FILES['experienciaDocente']['name'][$index]['pdf']) &&
                        $_FILES['experienciaDocente']['error'][$index]['pdf'] === UPLOAD_ERR_OK
                    ) {

                        $fileInfo = [
                            'name' => $_FILES['experienciaDocente']['name'][$index]['pdf'],
                            'tmp_name' => $_FILES['experienciaDocente']['tmp_name'][$index]['pdf'],
                            'size' => $_FILES['experienciaDocente']['size'][$index]['pdf'],
                            'error' => $_FILES['experienciaDocente']['error'][$index]['pdf'],
                            'type' => $_FILES['experienciaDocente']['type'][$index]['pdf']
                        ];

                        // Validar que sea PDF
                        if ($fileInfo['type'] !== 'application/pdf') {
                            throw new Exception("El archivo debe ser un PDF válido.");
                        }

                        // Mover el archivo
                        $uploadDir = APP . 'view/admin/upload/experiencia_laboral/experiencia_docente/';
                        $filename = uniqid('exp_docente_') . '.pdf';
                        $destination = $uploadDir . $filename;

                        if (!move_uploaded_file($fileInfo['tmp_name'], $destination)) {
                            throw new Exception("No se pudo guardar el archivo PDF de experiencia docente.");
                        }

                        $pdfPath = $filename;
                    }

                    $experienciaData = [
                        'postulante_id' => $postulanteId,
                        'institucion' => $experiencia['institucion_empresa'],
                        'pais' => $experiencia['pais'],
                        'ciudad' => $experiencia['ciudad'],
                        'programa_profesional' => $experiencia['programa_profesional'],
                        'curso_capacitacion_impartido' => $experiencia['curso_capacitacion_impartido'],
                        'funciones_principales' => $experiencia['funciones_principales'],
                        'fecha_inicio' => $experiencia['fecha_inicio'],
                        'fecha_retiro' => $experiencia['fecha_retiro'],
                        'pdf_experiencia_docente' => $pdfPath
                    ];

                    $formularioModel->insertExperienciaDocente($experienciaData);
                }
            }

            if (!empty($_POST['experienciaComite']) && is_array($_POST['experienciaComite'])) {
                foreach ($_POST['experienciaComite'] as $experienciaComite) {
                    $experienciaComiteData = [
                        'postulante_id' => $postulanteId,
                        'institucion' => $experienciaComite['institucion'],
                        'cargo_desempenado' => $experienciaComite['cargo_desempenado'],
                        'modelos_calidad' => $experienciaComite['modelos_calidad'],
                        'fecha_inicio' => $experienciaComite['fecha_inicio'],
                        'fecha_retiro' => $experienciaComite['fecha_retiro'],
                    ];
                    $formularioModel->insertExperienciaComite($experienciaComiteData);
                }
            }

            if (!empty($_POST['experienciaEvaluador']) && is_array($_POST['experienciaEvaluador'])) {
                foreach ($_POST['experienciaEvaluador'] as $experienciaEvaluador) {
                    $experienciaEvaluadorData = [
                        'postulante_id' => $postulanteId,
                        'agencia_acreditadora' => $experienciaEvaluador['agencia_acreditadora'],
                        'fecha_inicio_evaluador' => $experienciaEvaluador['fecha_inicio_evaluador'],
                        'fecha_retiro_evaluador' => $experienciaEvaluador['fecha_retiro_evaluador'],
                        'nombreEntidadEvaluador' => $experienciaEvaluador['nombreEntidadEvaluador'],
                        'programaEvaluador' => $experienciaEvaluador['programaEvaluador'],
                        'cargoEvaluador' => $experienciaEvaluador['cargoEvaluador'],
                        'paisEvaluador' => $experienciaEvaluador['paisEvaluador'],
                        'ciudadEvaluador' => $experienciaEvaluador['ciudadEvaluador'],
                        'fechaEvaluacionEvaluador' => $experienciaEvaluador['fechaEvaluacionEvaluador'],
                    ];
                    $formularioModel->insertExperienciaEvaluador($experienciaEvaluadorData);
                }
            }

            if (!empty($_POST['experienciaMembresias']) && is_array($_POST['experienciaMembresias'])) {
                foreach ($_POST['experienciaMembresias'] as $membresia) {
                    $membresiaData = [
                        'postulante_id' => $postulanteId,
                        'asociacion_profesional' => $membresia['asociacion_profesional'],
                        'numero_membresia' => $membresia['numero_membresia'],
                        'grado' => $membresia['grado'],
                    ];
                    $formularioModel->insertExperienciaMembresias($membresiaData);
                }
            }

            // Actualizar progreso
            // $formularioModel->actualizarProgreso($postulanteId, 4, true);
            //$this->actualizarProgreso($postulanteId, 4, true);
            $this->actualizarProgreso($_SESSION['user_id'], 4, true);

            // echo json_encode(["status" => "success", "message" => "Datos guardados correctamente"]);
            echo json_encode([
                "status" => "success",
                "message" => "Datos de Sección 3 guardados correctamente",
                "postulanteId" => $postulanteId // Opcional: enviar el ID del nuevo registro
            ]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        exit;
    }

    //enviar la informacion de la seccion 3 a la base de datos
    public function enviarSeccion3()
    {
        header('Content-Type: application/json');
        // Verificar si es una solicitud AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => "Método no permitido"]);
                exit;
            } else {
                header('Location: ' . URL . 'Admin/HomeFormulario');
                exit;
            }
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            if ($isAjax) {
                echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
                exit;
            } else {
                header('Location: ' . URL . 'Persona/login');
                exit;
            }
        }

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.");
            }

            $homeModel = new Home();

            if (!empty($_POST['formacionAcademica']) && is_array($_POST['formacionAcademica'])) {
                foreach ($_POST['formacionAcademica'] as $index => $formacion) {
                    $pdfPath = '';

                    // Verificar si hay un archivo para este índice
                    if (
                        isset($_FILES['formacionAcademica']['name'][$index]['pdf']) &&
                        $_FILES['formacionAcademica']['error'][$index]['pdf'] === UPLOAD_ERR_OK
                    ) {

                        $fileInfo = [
                            'name' => $_FILES['formacionAcademica']['name'][$index]['pdf'],
                            'tmp_name' => $_FILES['formacionAcademica']['tmp_name'][$index]['pdf'],
                            'size' => $_FILES['formacionAcademica']['size'][$index]['pdf'],
                            'error' => $_FILES['formacionAcademica']['error'][$index]['pdf'],
                            'type' => $_FILES['formacionAcademica']['type'][$index]['pdf']
                        ];

                        // Validar que sea PDF
                        if ($fileInfo['type'] !== 'application/pdf') {
                            throw new Exception("El archivo debe ser un PDF válido.");
                        }

                        // Mover el archivo
                        $uploadDir = APP . 'view/admin/upload/formacion_academica/';
                        $filename = uniqid('formacion_') . '.pdf';
                        $destination = $uploadDir . $filename;

                        if (!move_uploaded_file($fileInfo['tmp_name'], $destination)) {
                            throw new Exception("No se pudo guardar el archivo PDF.");
                        }

                        $pdfPath = $filename;
                    }

                    $formacionData = [
                        'postulante_id' => $postulanteId,
                        'tipo_formacion' => $formacion['tipo_formacion'],
                        'pais' => $formacion['pais'],
                        'ano_graduacion' => $formacion['ano_graduacion'],
                        'universidad' => $formacion['universidad'],
                        'nombre_grado' => $formacion['nombre_grado'],
                        'pdf_formacion_academica' => $pdfPath
                    ];

                    $homeModel->insertFormacionAcademica($formacionData);
                }
            }


            // Procesar idiomas
            if (!empty($_POST['idiomas'])  && is_array($_POST['idiomas'])) {
                foreach ($_POST['idiomas'] as $idioma) {
                    $idiomaData = [
                        'postulante_id' => $postulanteId,
                        'idioma' => $idioma['idioma'],
                        'competencia_escrita' => $idioma['competencia_escrita'],
                        'competencia_lectora' => $idioma['competencia_lectora'],
                        'competencia_oral' => $idioma['competencia_oral'],
                    ];
                    $homeModel->insertIdiomas($idiomaData);
                }
            }

            // Cursos - Campo Profesional
            if (!empty($_POST['cursosProfesionales']) && is_array($_POST['cursosProfesionales'])) {
                foreach ($_POST['cursosProfesionales'] as $curso) {
                    $cursoData = [
                        'postulante_id' => $postulanteId,
                        'ano' => $curso['ano'],
                        'institucion' => $curso['institucion'],
                        'nombre_curso_seminario' => $curso['nombre_curso_seminario'],
                        'tipo_seminario' => $curso['tipo_seminario'],
                        'duracion' => $curso['duracion']
                    ];
                    $homeModel->insertCursosSeminariosCampoProfesional($cursoData);
                }
            }

            // Cursos - Ámbito Académico
            if (!empty($_POST['cursosAcademicos']) && is_array($_POST['cursosAcademicos'])) {
                foreach ($_POST['cursosAcademicos'] as $cursoSeminarioAmbitoAcademico) {
                    $cursoSeminarioDataAmbitoAcademico = [
                        'postulante_id' => $postulanteId,
                        'ano' => $cursoSeminarioAmbitoAcademico['ano'],
                        'institucion' => $cursoSeminarioAmbitoAcademico['institucion'],
                        'nombre_curso_seminario' => $cursoSeminarioAmbitoAcademico['nombre_curso_seminario'],
                        'tipo_seminario' => $cursoSeminarioAmbitoAcademico['tipo_seminario'],
                        'duracion' => $cursoSeminarioAmbitoAcademico['duracion']
                    ];
                    $homeModel->insertCursosSeminariosAmbitoAcademico($cursoSeminarioDataAmbitoAcademico);
                }
            }

            /// Cursos - Ámbito Evaluación
            if (!empty($_POST['cursosEvaluacion']) && is_array($_POST['cursosEvaluacion'])) {
                foreach ($_POST['cursosEvaluacion'] as $cursoSeminarioAmbitoEvaluacion) {
                    $cursoSeminarioDataAmbitoEvaluacion = [
                        'postulante_id' => $postulanteId,
                        'ano' => $cursoSeminarioAmbitoEvaluacion['ano'],
                        'institucion' => $cursoSeminarioAmbitoEvaluacion['institucion'],
                        'nombre_curso_seminario' => $cursoSeminarioAmbitoEvaluacion['nombre_curso_seminario'],
                        'tipo_seminario' => $cursoSeminarioAmbitoEvaluacion['tipo_seminario'],
                        'duracion' => $cursoSeminarioAmbitoEvaluacion['duracion']
                    ];
                    $homeModel->insertCursosSeminariosAmbitoEvaluacion($cursoSeminarioDataAmbitoEvaluacion);
                }
            }

            // Actualizar progreso
            // $homeModel->actualizarProgreso($postulanteId, 3, true);
            // $this->actualizarProgreso($postulanteId, 3, true);
            $this->actualizarProgreso($_SESSION['user_id'], 3, true);

            echo json_encode([
                "status" => "success",
                "message" => "Datos de Sección 3 guardados correctamente",
                "postulanteId" => $postulanteId // Opcional: enviar el ID del nuevo registro
            ]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        exit;
    }

    //*enviar la informacion de la seccion 2 a la base de datos
    public function enviarSeccion2()
    {
        header('Content-Type: application/json');
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró un postulante asociado al usuario.");
            }

            $data = $_POST;
            $data['postulante_id'] = $postulanteId;

            $requiredFields = [
                'centroLaboral',
                'cargoActual',
                'tiempoLaboral',
                'PaisInformacionLaboral',
                'ciudadInformacionLaboral',
                'rubroInformacionLaboral',
                'nombresEmpleador',
                'cargoEmpleador',
                'correoEmpleador'
            ];

            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo $field no puede estar vacío.");
                }
            }

            $homeModel = new Home();

            $insertedId = $homeModel->insertSeccion2($data);

            if (!$insertedId) {
                throw new Exception("Error al insertar los datos en la base de datos.");
            }

            // Actualizar progreso
            // $homeModel->actualizarProgreso($_SESSION['user_id'], 2, true);
            $this->actualizarProgreso($_SESSION['user_id'], 2, true);

            // header('Location: ' . URL . 'Admin/HomeFormulario');
            echo json_encode([
                "status" => "success",
                "message" => "Datos de Sección 2 guardados correctamente",
                "postulanteId" => $postulanteId // Opcional: enviar el ID del nuevo registro
            ]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }

        exit;
    }

    //*enviar la informacion de la seccion 1 a la base de datos
    public function enviarSeccion1()
    {

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
            return;
        }

        try {
            $data = $_POST;
            $data['usuario_id'] = $_SESSION['user_id'];

            $data['fotoPerfil'] = $this->procesarArchivo($_FILES['fotoPerfil'] ?? null, APP . 'view/admin/upload/fotos_perfil/', "foto_perfil_" . $data['numDoc']);
            $data['pdfDocumentoIdentidad'] = $this->procesarArchivo($_FILES['pdfDocumentoIdentidad'] ?? null, APP . 'view/admin/upload/documentos_identidad/', "pdf_documento_identidad_" . $data['numDoc']);

            $homeModel = new Home();
            $postulanteId = $homeModel->insertSeccion1($data);

            if (!$postulanteId) {
                throw new Exception("Error al guardar los datos de la Sección 1");
            }

            // Actualizar el progreso del formulario
            // $homeModel->actualizarProgreso($_SESSION['user_id'], 1, true);
            $this->actualizarProgreso($_SESSION['user_id'], 1, true);

            echo json_encode([
                "status" => "success",
                "message" => "Datos de Sección 1 guardados correctamente",
                "postulanteId" => $postulanteId // Opcional: enviar el ID del nuevo registro
            ]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        exit;
    }

    // Procesar archivo subido
    private function procesarArchivo($archivo, $directorio, $nombreBase)
    {
        if (!$archivo || empty($archivo['name'])) {
            return '';
        }

        $permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        $tipoArchivo = mime_content_type($archivo['tmp_name']);

        if (!in_array($tipoArchivo, $permitidos)) {
            throw new Exception("Formato de archivo no permitido. Solo se permiten archivos JPEG, PNG y PDF.");
        }

        if ($archivo['size'] > 5 * 1024 * 1024) { // 5MB
            throw new Exception("El archivo es demasiado grande. El tamaño máximo permitido es 5MB.");
        }

        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = $nombreBase . '_' . uniqid() . '.' . $extension;

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $rutaDestino = rtrim($directorio, '/') . '/' . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            throw new Exception("Error al mover el archivo subido.");
        }

        return $nombreArchivo;
    }

    //cargar la vista del formulario
    public function HomeFormulario()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();

        try {
            // Obtener progreso usando el modelo de Progreso
            $progresoModel = new Progreso();
            $progress = $progresoModel->getProgresoFormulario($userId);

            // Inicializar progreso si no existe
            if (empty($progress['estado_secciones'])) {
                $progresoModel = new Progreso();
                $progresoModel->crearProgreso($userId);
                // Inicializar todas las secciones como no completadas
                for ($i = 1; $i <= 8; $i++) {
                    $this->registrarEstadoSeccion(
                        $this->validarYObtenerProgresoId($userId),
                        $i,
                        false
                    );
                }
                $progresoModel = new Progreso();
                $progress = $progresoModel->getProgresoFormulario($userId);
            }

            $data = [
                'user' => $personaModel->getUserById($userId),
                'progress' => $progress
            ];

            require APP . 'view/_templates/header_adm.php';
            require APP . 'view/admin/home_formulario.php';
        } catch (Exception $e) {
            error_log("Error en HomeFormulario: " . $e->getMessage());
            header('Location: ' . URL . 'error?message=' . urlencode($e->getMessage()));
            exit;
        }
    }

    //cargar las vistas de las secciones del formulario
    public function seccion1()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);

        // Obtener progreso desde el modelo de Progreso
        $progresoModel = new Progreso();
        $progress = $progresoModel->getProgresoFormulario($userId);

        $data = [
            'user' => $user,
            'progress' => $progress
        ];

        // Cargar vista de la sección 1
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/formulario/seccion1.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    public function seccion2()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);

        // Obtener progreso desde el modelo de Progreso
        $progresoModel = new Progreso();
        $progress = $progresoModel->getProgresoFormulario($userId);

        $data = [
            'user' => $user,
            'progress' => $progress
        ];

        // Cargar vista de la sección 2
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/formulario/seccion2.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    public function seccion3()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);

        // Obtener progreso desde el modelo de Progreso
        $progresoModel = new Progreso();
        $progress = $progresoModel->getProgresoFormulario($userId);

        $data = [
            'user' => $user,
            'progress' => $progress
        ];

        // Cargar vista de la sección 3
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/formulario/seccion3.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    public function seccion4()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);

        // Obtener progreso desde el modelo de Progreso
        $progresoModel = new Progreso();
        $progress = $progresoModel->getProgresoFormulario($userId);

        $data = [
            'user' => $user,
            'progress' => $progress
        ];

        // Cargar vista de la sección 4
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/formulario/seccion4.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    public function seccion5()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);

        // Obtener progreso desde el modelo de Progreso
        $progresoModel = new Progreso();
        $progress = $progresoModel->getProgresoFormulario($userId);

        $data = [
            'user' => $user,
            'progress' => $progress
        ];
        // Cargar vista de la sección 5
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/formulario/seccion5.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    public function seccion6()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);

        // Obtener progreso desde el modelo de Progreso
        $progresoModel = new Progreso();
        $progress = $progresoModel->getProgresoFormulario($userId);

        $data = [
            'user' => $user,
            'progress' => $progress
        ];

        // Cargar vista de la sección 6
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/formulario/seccion6.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    public function seccion7()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);

        // Obtener progreso desde el modelo de Progreso
        $progresoModel = new Progreso();
        $progress = $progresoModel->getProgresoFormulario($userId);

        $data = [
            'user' => $user,
            'progress' => $progress
        ];

        // Cargar vista de la sección 7
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/formulario/seccion7.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    public function seccion8()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);

        // Obtener progreso desde el modelo de Progreso
        $progresoModel = new Progreso();
        $progress = $progresoModel->getProgresoFormulario($userId);

        $data = [
            'user' => $user,
            'progress' => $progress
        ];

        // Cargar vista de la sección 8
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/formulario/seccion8.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    //cargar la vista de la seccion 9 (se cambiara para abrir un modal)
    public function seccion9()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);
        $progress = $personaModel->getProgressByUserId($userId);

        if (!$user) {
            header('Location: ' . URL . 'login');
            exit;
        }

        // Si no hay progreso registrado, inicializa el progreso
        if (!$progress) {
            $personaModel->initializeProgress($userId);
            $progress = $personaModel->getProgressByUserId($userId);
        }

        $data = [
            'user' => $user,
            'progress' => $progress
        ];

        // Cargar vista de la sección 9
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/formulario/seccion9.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    // Actualizar el progreso del usuario
    public function actualizarProgreso($usuarioId, $seccionActual, $completada)
    {
        try {
            // 1. Validar y obtener progreso existente
            $progresoId = $this->validarYObtenerProgresoId($usuarioId);

            // 2. Registrar estado de la sección actual
            $this->registrarEstadoSeccion($progresoId, $seccionActual, $completada);

            // 3. Habilitar siguiente sección si es necesario
            if ($completada && $seccionActual < 8) {
                $this->habilitarSiguienteSeccionSiNoExiste($progresoId, $seccionActual);
            }

            // 4. Actualizar progreso general
            $this->actualizarProgresoGeneral($progresoId, $seccionActual);

            return true;
        } catch (Exception $e) {
            error_log("Error en actualizarProgreso: " . $e->getMessage());
            throw new Exception("Error al actualizar el progreso del formulario.");
        }
    }

    private function validarYObtenerProgresoId($usuarioId)
    {
        $progresoModel = new Progreso();
        $progresoId = $progresoModel->obtenerIdProgreso($usuarioId);

        if (!$progresoId) {
            $progresoId = $progresoModel->crearProgreso($usuarioId);

            if (!$progresoId) {
                throw new Exception("No se pudo crear el progreso del usuario.");
            }
        }

        return $progresoId;
    }

    private function registrarEstadoSeccion($progresoId, $seccionNumero, $completada)
    {
        $nombreSeccion = $this->formatearNombreSeccion($seccionNumero);

        $progresoModel = new Progreso();
        $progresoModel->insertarEstadoSeccion(
            $progresoId,
            $nombreSeccion,
            $completada
        );
    }

    private function habilitarSiguienteSeccionSiNoExiste($progresoId, $seccionActual)
    {
        $siguienteSeccion = $seccionActual + 1;
        $nombreSiguienteSeccion = $this->formatearNombreSeccion($siguienteSeccion);

        $progresoModel = new Progreso();
        if (!$progresoModel->existeEstadoSeccion($progresoId, $nombreSiguienteSeccion)) {
            $progresoModel->insertarEstadoSeccion(
                $progresoId,
                $nombreSiguienteSeccion,
                false
            );
        }
    }

    private function actualizarProgresoGeneral($progresoId, $seccionActual)
    {
        $nombreSeccion = $this->formatearNombreSeccion($seccionActual);
        $porcentaje = $this->calcularPorcentajeCompletado($progresoId);
        $completado = $porcentaje >= 100;

        $progresoModel = new Progreso();
        $progresoModel->actualizarProgresoGeneral(
            $progresoId,
            $nombreSeccion,
            $porcentaje,
            $completado
        );
    }

    private function calcularPorcentajeCompletado($progresoId)
    {
        $progresoModel = new Progreso();
        $seccionesCompletadas = $progresoModel->contarSeccionesCompletadas($progresoId);
        return $seccionesCompletadas * 12.5;
    }

    private function formatearNombreSeccion($numeroSeccion)
    {
        return "Sección " . $numeroSeccion;
    }

    public function obtenerPostulanteId()
    {
        session_start();
        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

        header('Content-Type: application/json');
        echo json_encode(['postulanteId' => $postulanteId]);
    }
}
