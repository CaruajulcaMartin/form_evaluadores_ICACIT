<?php

namespace Mini\Controller;

use Mini\Model\Home;
use Mini\Model\Persona;
use Mini\Model\DatosRecuperar;
use Exception;

class DatosRecuperarController
{
    /* Recuperar los datos para las secciones de manera individual */
    // Mostrar la sección 7
    public function recuperarDatosSeccion7()
    {
        header('Content-Type: application/json');

        try {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Usuario no autenticado", 401);
            }

            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            if (!$postulanteId) {
                throw new Exception("No se encontró postulante asociado", 404);
            }

            $dataRecoveryModel = new DatosRecuperar();
            $seccion7Data = $dataRecoveryModel->getSeccion7Data($postulanteId);

            // Verificar si hay datos y manejarlo adecuadamente
            if (!$seccion7Data || !is_array($seccion7Data)) {
                echo json_encode(['cartaPresentacion' => '']);
                return;
            }

            $output = [
                'cartaPresentacion' => $seccion7Data['carta_presentacion'] ?? ''
            ];

            echo json_encode($output);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500
            ]);
        }

        exit;
    }

    // Mostrar la sección 6
    public function recuperarDatosSeccion6()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion6Data = $dataRecoveryModel->getSeccion6Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'ano' => $registro['ano'],
                    'institucion_empresa' => $registro['institucion_empresa'],
                    'nombre_reconocimiento' => $registro['nombre_reconocimiento'],
                    'descripcion_reconocimiento' => $registro['descripcion_reconocimiento'],
                ];
            }, $seccion6Data);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    // Mostrar la sección 5
    public function recuperarDatosSeccion5()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion5Data = $dataRecoveryModel->getSeccion5Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'fecha_publicacion' => $registro['fecha_publicacion'],
                    'revista_congreso' => $registro['revista_congreso'],
                    'base_datos' => $registro['base_datos'],
                    'nombre_investigacion' => $registro['nombre_investigacion'],
                    'autores' => $registro['autores'],
                ];
            }, $seccion5Data);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    // seccion 4 - como par evaluador
    public function recuperarDatosSeccion4Evaluador()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion4Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'agencia_acreditadora' => $registro['agencia_acreditadora'],
                    'fecha_inicio' => $registro['fecha_inicio'],
                    'fecha_retiro' => $registro['fecha_retiro'],
                    'nombre_entidad' => $registro['nombre_entidad'],
                    'programa' => $registro['programa'],
                    'cargo' => $registro['cargo'],
                    'pais' => $registro['pais'],
                    'ciudad' => $registro['ciudad'],
                    'fecha_evaluacion' => $registro['fecha_evaluacion'],
                ];
            }, $seccion3Data['experiencia_evaluador']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    // seccion 4 - Membresías
    public function recuperarDatosSeccion4Membresias()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion4Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'asociacion_profesional' => $registro['asociacion_profesional'],
                    'numero_membresia' => $registro['numero_membresia'],
                    'grado' => $registro['grado'],
                ];
            }, $seccion3Data['membresias']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    // seccion 4 - parte de comité
    public function recuperarDatosSeccion4Comite()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion4Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'institucion' => $registro['institucion'],
                    'cargo_desempenado' => $registro['cargo_desempenado'],
                    'modelos_calidad' => $registro['modelos_calidad'],
                    'fecha_inicio' => $registro['fecha_inicio'],
                    'fecha_retiro' => $registro['fecha_retiro'],
                ];
            }, $seccion3Data['experiencia_comite']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    // seccion 4 - experiencia docente
    public function recuperarDatosSeccion4Docente()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion4Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'institucion' => $registro['institucion'],
                    'pais' => $registro['pais'],
                    'ciudad' => $registro['ciudad'],
                    'programa_profesional' => $registro['programa_profesional'],
                    'curso_capacitacion_impartido' => $registro['curso_capacitacion_impartido'],
                    'funciones_principales' => $registro['funciones_principales'],
                    'fecha_inicio' => $registro['fecha_inicio'],
                    'fecha_retiro' => $registro['fecha_retiro'],
                    'pdf_experiencia_docente' => $registro['pdf_experiencia_docente'],
                ];
            }, $seccion3Data['experiencia_docente']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    //mostrar seccion 4
    public function recuperarDatosSeccion4()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion4Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'institucion_empresa' => $registro['institucion_empresa'],
                    'cargo_desempenado' => $registro['cargo_desempenado'],
                    'fecha_inicio' => $registro['fecha_inicio'],
                    'fecha_retiro' => $registro['fecha_retiro'],
                    'pais' => $registro['pais'],
                    'ciudad' => $registro['ciudad'],
                    'pdf_experiencia' => $registro['pdf_experiencia'],
                ];
            }, $seccion3Data['experiencia_laboral']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    //seccion 3 cursos-campo profesional
    public function recuperarDatosSeccion3CursosCampoProfesional()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion3Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'ano' => $registro['ano'],
                    'institucion' => $registro['institucion'],
                    'nombre_curso_seminario' => $registro['nombre_curso_seminario'],
                    'tipo_seminario' => $registro['tipo_seminario'],
                    'duracion' => $registro['duracion'],
                ];
            }, $seccion3Data['cursos_profesionales']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    //seccion 3 cursos-ámbito académico
    public function recuperarDatosSeccion3CursosAmbitoAcademico()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion3Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'ano' => $registro['ano'],
                    'institucion' => $registro['institucion'],
                    'nombre_curso_seminario' => $registro['nombre_curso_seminario'],
                    'tipo_seminario' => $registro['tipo_seminario'],
                    'duracion' => $registro['duracion'],
                ];
            }, $seccion3Data['cursos_academicos']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    //seccion 3 cursos-ámbito de evaluación
    public function recuperarDatosSeccion3CursosAmbitoEvaluacion()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion3Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'ano' => $registro['ano'],
                    'institucion' => $registro['institucion'],
                    'nombre_curso_seminario' => $registro['nombre_curso_seminario'],
                    'tipo_seminario' => $registro['tipo_seminario'],
                    'duracion' => $registro['duracion'],
                ];
            }, $seccion3Data['cursos_evaluacion']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    //mostrar la sección 3 - idiomas
    public function recuperarDatosSeccion3Idiomas()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion3Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'idioma' => $registro['idioma'],
                    'competencia_escrita' => $registro['competencia_escrita'],
                    'competencia_lectora' => $registro['competencia_lectora'],
                    'competencia_oral' => $registro['competencia_oral'],
                ];
            }, $seccion3Data['idiomas']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    // Mostrar la sección 3
    public function recuperarDatosSeccion3()
    {
        header('Content-Type: application/json');
        session_start();

        try {
            $userId = $_SESSION['user_id'];
            $personaModel = new Persona();
            $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

            error_log("DEBUG_RECUPERAR_S3: postulanteId obtenido en el controlador = " . $postulanteId . " para userId = " . $userId);

            $dataRecoveryModel = new DatosRecuperar();
            $seccion3Data = $dataRecoveryModel->getSeccion3Data($postulanteId);

            // Devuelve todos los registros, no solo el primero
            $registros = array_map(function ($registro) {
                return [
                    'id' => $registro['id'],
                    'tipo_formacion' => $registro['tipo_formacion'],
                    'pais' => $registro['pais'],
                    'ano_graduacion' => $registro['ano_graduacion'],
                    'universidad' => $registro['universidad'],
                    'nombre_grado' => $registro['nombre_grado'],
                    'pdf_formacion_academica' => $registro['pdf_formacion_academica'],
                ];
            }, $seccion3Data['formacion_academica']);

            echo json_encode($registros);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    //Mostrar la sección 2
    public function recuperarDatosSeccion2()
    {
        header('Content-Type: application/json');
        session_start();

        // Asegúrate de que el usuario esté autenticado antes de continuar
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
            exit;
        }

        $userId = $_SESSION['user_id'];

        $personaModel = new Persona();
        $postulanteId = $personaModel->getPostulanteIdByUserId($userId);

        try {

            $dataRecoveryModel = new DatosRecuperar();
            $seccion2Data = $dataRecoveryModel->getSeccion2Data($postulanteId);

            $output = array();

            if ($seccion2Data && is_array($seccion2Data)) {
                $output["centroLaboral"] = $seccion2Data['nombre_centro_laboral'];
                $output["cargoActual"] = $seccion2Data['cargo_actual'];
                $output["tiempoLaboral"] = $seccion2Data['tiempo_laboral'];
                $output["PaisInformacionLaboral"] = $seccion2Data['pais'];
                $output["ciudadInformacionLaboral"] = $seccion2Data['ciudad'];
                $output["rubroInformacionLaboral"] = $seccion2Data['rubro'];
                $output["nombresEmpleador"] = $seccion2Data['nombres_empleador'];
                $output["cargoEmpleador"] = $seccion2Data['cargo_empleador'];
                $output["correoEmpleador"] = $seccion2Data['correo_empleador'];

                // Añadir un indicador de éxito si se encontraron datos
                $output['status'] = 'success';
                $output['message'] = 'Datos recuperados correctamente';
            } else {
                // En caso contrario, establecer un indicador de error
                $output['status'] = 'error';
                $output['message'] = 'No se encontraron datos para la sección 2';

                $output["centroLaboral"] = null;
                $output["cargoActual"] = null;
                $output["tiempoLaboral"] = null;
                $output["PaisInformacionLaboral"] = null;
                $output["ciudadInformacionLaboral"] = null;
                $output["rubroInformacionLaboral"] = null;
                $output["nombresEmpleador"] = null;
                $output["cargoEmpleador"] = null;
                $output["correoEmpleador"] = null;
            }
            echo json_encode($output);
        } catch (Exception $e) {
            // En caso de una excepción, asegúrate de devolver un JSON de error
            error_log('Error en recuperarDatosSeccion1: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            http_response_code(500); // Establecer un código de estado HTTP adecuado
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
        }
        exit;
    }

    // Mostrar la sección 1
    public function recuperarDatosSeccion1()
    {
        header('Content-Type: application/json');
        session_start();

        // Asegúrate de que el usuario esté autenticado antes de continuar
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
            exit;
        }

        $usuarioId = $_SESSION['user_id'];
        error_log('Solicitando datos completos para usuario ID: ' . $usuarioId);

        // Llamar al modelo para obtener los datos
        try {
            $dataRecoveryModel = new DatosRecuperar();
            $seccion1Data = $dataRecoveryModel->getSeccion1Data($usuarioId);

            $output = array();

            // Verificar si se encontraron datos y si es un array
            if ($seccion1Data && is_array($seccion1Data)) {
                $output["apellido1"] = $seccion1Data['primer_apellido'] ?? null;
                $output["apellido2"] = $seccion1Data['segundo_apellido'] ?? null;
                $output["nombresCompletos"] = $seccion1Data['nombres_completos'] ?? null;
                $output["tipoIdentidad"] = $seccion1Data['tipo_identidad'] ?? null;
                $output["numDoc"] = $seccion1Data['numero_identidad'] ?? null;
                $output["nationality"] = $seccion1Data['nacionalidad'] ?? null;
                $output["fechaNacimiento"] = $seccion1Data['fecha_nacimiento'] ?? null;
                $output["estadoCivil"] = $seccion1Data['estado_civil'] ?? null;
                $output["correoElectronico"] = $seccion1Data['correo_electronico'] ?? null;
                $output["phoneCode"] = $seccion1Data['codigo_telefono'] ?? null;
                $output["celular"] = $seccion1Data['numero_celular'] ?? null;
                $output["redProfesional"] = $seccion1Data['red_profesional'] ?? null;
                $output["tipoDireccion"] = $seccion1Data['tipo_direccion'] ?? null;
                $output["direccion"] = $seccion1Data['direccion'] ?? null;
                $output["numeroDireccion"] = $seccion1Data['numero_direccion'] ?? null;
                $output["PaisDatoDominicial"] = $seccion1Data['pais'] ?? null;
                $output["PaisDatoDominicialRegion"] = $seccion1Data['region_estado'] ?? null;
                $output["provinciaDatoDominicial"] = $seccion1Data['provincia_municipio'] ?? null;
                $output["distritoDatoDominicial"] = $seccion1Data['distrito'] ?? null;
                $output["referenciaDomicilio"] = $seccion1Data['referencia_domicilio'] ?? null;

                // Añadir un indicador de éxito si se encontraron datos
                $output['status'] = 'success';
                $output['message'] = 'Datos recuperados correctamente';
            } else {
                // Si no se encontraron datos, devolver un JSON vacío o con un estado específico
                $output['status'] = 'no_data';
                $output['message'] = 'No se encontraron datos previos para esta sección.';
                // Opcionalmente, puedes devolver un array vacío para todos los campos:
                $output["apellido1"] = null;
                $output["apellido2"] = null;
                $output["nombresCompletos"] = null;
                $output["tipoIdentidad"] = null;
                $output["numDoc"] = null;
                $output["nationality"] = null;
                $output["fechaNacimiento"] = null;
                $output["estadoCivil"] = null;
                $output["correoElectronico"] = null;
                $output["phoneCode"] = null;
                $output["celular"] = null;
                $output["redProfesional"] = null;
                $output["tipoDireccion"] = null;
                $output["direccion"] = null;
                $output["numeroDireccion"] = null;
                $output["PaisDatoDominicial"] = null;
                $output["PaisDatoDominicialRegion"] = null;
                $output["provinciaDatoDominicial"] = null;
                $output["distritoDatoDominicial"] = null;
                $output["referenciaDomicilio"] = null;
            }

            echo json_encode($output);
        } catch (Exception $e) {
            // En caso de una excepción, asegúrate de devolver un JSON de error
            error_log('Error en recuperarDatosSeccion1: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            http_response_code(500); // Establecer un código de estado HTTP adecuado
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
        }
        exit;
    }


    /* Recuperar los datos de las secciones 1 al 8 para la previsualización */
    //obtener datos de las secciones 1 al 8
    public function recuperarDatosSecciones()
    {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['user_id'])) {
            error_log('Intento de acceso no autenticado a recuperarDatosSecciones');
            echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
            exit;
        }

        $usuarioId = $_SESSION['user_id'];
        error_log('Solicitando datos completos para usuario ID: ' . $usuarioId);

        try {
            $dataRecoveryModel = new DatosRecuperar();

            // Obtener datos de la sección 1 primero
            $seccion1Data = $dataRecoveryModel->getSeccion1Data($usuarioId);
            error_log('Datos de sección 1 obtenidos: ' . print_r($seccion1Data, true));

            if (!$seccion1Data) {
                error_log('No se encontraron datos de postulante para el usuario: ' . $usuarioId);
                echo json_encode(['success' => false, 'message' => 'No se encontraron datos del postulante']);
                exit;
            }

            $postulanteId = $seccion1Data['id'];
            error_log('ID de postulante encontrado: ' . $postulanteId);

            // Obtener datos de todas las secciones
            $secciones = [
                'seccion2' => $dataRecoveryModel->getSeccion2Data($postulanteId),
                'seccion3' => $dataRecoveryModel->getSeccion3Data($postulanteId),
                'seccion4' => $dataRecoveryModel->getSeccion4Data($postulanteId),
                'seccion5' => $dataRecoveryModel->getSeccion5Data($postulanteId),
                'seccion6' => $dataRecoveryModel->getSeccion6Data($postulanteId),
                'seccion7' => $dataRecoveryModel->getSeccion7Data($postulanteId),
                'seccion8' => $dataRecoveryModel->getSeccion8Data($postulanteId),
            ];

            // Procesar archivos y generar URLs completas
            $this->procesarArchivosSeccion($seccion1Data, 'seccion1');

            foreach ($secciones as $seccionKey => $seccionData) {
                if ($seccionData) {
                    $this->procesarArchivosSeccion($seccionData, $seccionKey);
                }
            }

            $allData = array_merge(['seccion1' => $seccion1Data], $secciones);

            echo json_encode(['success' => true, 'data' => $allData]);
        } catch (Exception $e) {
            error_log('Error en recuperarDatosSecciones: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
        }
        exit;
    }

    private function procesarArchivosSeccion(&$seccionData, $seccionKey) {
        $basePaths = [
            'fotos_perfil' => 'view/admin/upload/fotos_perfil/',
            'firmas' => 'app/view/admin/upload/firmas/',
            'documentos_identidad' => 'app/view/admin/upload/documentos_identidad/',
            'formacion_academica' => 'app/view/admin/upload/formacion_academica/',
            'experiencia_laboral' => 'app/view/admin/upload/experiencia_laboral/',
            'experiencia_docente' => 'app/view/admin/upload/experiencia_laboral/experiencia_docente/'
        ];
    
        // Procesar foto de perfil (Sección 1)
        if ($seccionKey === 'seccion1' && isset($seccionData['foto_perfil'])) {
            $seccionData['foto_perfil_url'] = $this->generarUrlArchivo(
                $basePaths['fotos_perfil'], 
                $seccionData['foto_perfil']
            );
        }
    
        // Procesar firma digital (Sección 8)
        if ($seccionKey === 'seccion8' && isset($seccionData['firma_digital'])) {
            $seccionData['firma_digital_url'] = $this->generarUrlArchivo(
                $basePaths['firmas'], 
                $seccionData['firma_digital']
            );
        }
    
        // Procesar PDFs de documentos
        $pdfFields = [
            'pdf_documento_identidad' => $basePaths['documentos_identidad'],
            'ruta_archivo_formacion' => $basePaths['formacion_academica'],
            'ruta_archivo_experiencia' => $basePaths['experiencia_laboral'],
            'ruta_archivo_docencia' => $basePaths['experiencia_docente']
        ];
    
        foreach ($pdfFields as $field => $path) {
            if (isset($seccionData[$field])) {
                $seccionData[$field . '_url'] = $this->generarUrlArchivo($path, $seccionData[$field]);
            }
        }
    
        // Procesar arrays de datos que puedan contener archivos
        if (is_array($seccionData)) {
            foreach ($seccionData as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        if (is_array($subValue)) {
                            foreach ($subValue as $item) {
                                if (isset($item['ruta_archivo'])) {
                                    $tipo = $this->determinarTipoArchivo($key, $subKey);
                                    if ($tipo && isset($basePaths[$tipo])) {
                                        $item['ruta_archivo_url'] = $this->generarUrlArchivo(
                                            $basePaths[$tipo], 
                                            $item['ruta_archivo']
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    private function generarUrlArchivo($relativePath, $filename) {
        if (empty($filename)) {
            return null;
        }
    
        // Verificar si ya es una URL completa o un data URI
        if (filter_var($filename, FILTER_VALIDATE_URL) || strpos($filename, 'data:') === 0) {
            return $filename;
        }
    
        // Verificar si el archivo existe físicamente
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . trim($relativePath, '/') . '/' . $filename;
        if (!file_exists($fullPath)) {
            error_log("Archivo no encontrado: $fullPath");
            return null;
        }
    
        // Generar URL completa
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
                    "://$_SERVER[HTTP_HOST]";
        
        return $baseUrl . '/' . trim($relativePath, '/') . '/' . rawurlencode($filename);
    }
    
    private function determinarTipoArchivo($seccion, $subseccion) {
        $map = [
            'formacion_academica' => 'formacion_academica',
            'idiomas' => 'formacion_academica',
            'cursos_seminarios' => 'formacion_academica',
            'experiencia_profesional' => 'experiencia_laboral',
            'experiencia_docente' => 'experiencia_docente',
            'experiencia_comite' => 'experiencia_laboral',
            'experiencia_par' => 'experiencia_laboral',
            'membresias' => 'experiencia_laboral',
            'publicaciones' => 'formacion_academica',
            'premios_reconocimientos' => 'formacion_academica'
        ];
    
        return $map[$subseccion] ?? $map[$seccion] ?? null;
    }
}
