<?php

namespace Mini\Model;

use Mini\Core\Model;
use PDO;
use Exception;

class Home extends Model
{
    // *Función para insertar información de la sección 1 y 2
    public function insertPersonalInfo($data)
    {
        try {
            // Iniciar una transacción
            $this->db->beginTransaction();

            // *Insertar en la tabla de información personal
            $sql1 = "INSERT INTO postulante (
                primer_apellido, segundo_apellido, nombres_completos, tipo_identidad, numero_identidad, 
                pdf_documento_identidad, nacionalidad, fecha_nacimiento, estado_civil, foto_perfil, 
                correo_electronico, codigo_telefono, numero_celular, red_profesional, tipo_direccion, 
                direccion, numero_direccion, pais, region_estado, provincia_municipio, distrito, referencia_domicilio
            ) VALUES (
                :primer_apellido, :segundo_apellido, :nombres_completos, :tipo_identidad, :numero_identidad, 
                :pdf_documento_identidad, :nacionalidad, :fecha_nacimiento, :estado_civil, :foto_perfil, 
                :correo_electronico, :codigo_telefono, :numero_celular, :red_profesional, :tipo_direccion, 
                :direccion, :numero_direccion, :pais, :region_estado, :provincia_municipio, :distrito, :referencia_domicilio
            )";

            $query1 = $this->db->prepare($sql1);
            $query1->execute([
                ':primer_apellido' => $data['apellido1'],
                ':segundo_apellido' => $data['apellido2'],
                ':nombres_completos' => $data['nombresCompletos'],
                ':tipo_identidad' => $data['tipoIdentidad'],
                ':numero_identidad' => $data['numDoc'],
                ':pdf_documento_identidad' => $data['pdfDocumentoIdentidad'],
                ':nacionalidad' => $data['nationality'],
                ':fecha_nacimiento' => $data['fechaNacimiento'],
                ':estado_civil' => $data['estadoCivil'],
                ':foto_perfil' => $data['fotoPerfil'],
                ':correo_electronico' => $data['correoElectronico'],
                ':codigo_telefono' => $data['phoneCode'],
                ':numero_celular' => $data['celular'],
                ':red_profesional' => $data['redProfesional'],
                ':tipo_direccion' => $data['tipoDireccion'],
                ':direccion' => $data['direccion'],
                ':numero_direccion' => $data['numeroDireccion'],
                ':pais' => $data['PaisDatoDominicial'],
                ':region_estado' => $data['PaisDatoDominicialRegion'],
                ':provincia_municipio' => $data['provinciaDatoDominicial'],
                ':distrito' => $data['distritoDatoDominicial'],
                ':referencia_domicilio' => $data['referenciaDomicilio']
            ]);

            // Obtener el ID del postulante recién insertado
            $postulanteId = $this->db->lastInsertId();

            // *Insertar en la tabla de información laboral actual
            $sql2 = "INSERT INTO informacionlaboral (
                postulante_id, nombre_centro_laboral, cargo_actual, tiempo_laboral, pais, ciudad, rubro, 
                nombres_empleador, cargo_empleador, correo_empleador
            ) VALUES (
                :postulante_id, :nombre_centro_laboral, :cargo_actual, :tiempo_laboral, :pais, :ciudad, :rubro, 
                :nombres_empleador, :cargo_empleador, :correo_empleador
            )";

            $query2 = $this->db->prepare($sql2);
            $query2->execute([
                ':postulante_id' => $postulanteId,
                ':nombre_centro_laboral' => $data['centroLaboral'],
                ':cargo_actual' => $data['cargoActual'],
                ':tiempo_laboral' => $data['tiempoLaboral'],
                ':pais' => $data['PaisInformacionLaboral'],
                ':ciudad' => $data['ciudadInformacionLaboral'],
                ':rubro' => $data['rubroInformacionLaboral'],
                ':nombres_empleador' => $data['nombresEmpleador'],
                ':cargo_empleador' => $data['cargoEmpleador'],
                ':correo_empleador' => $data['correoEmpleador']
            ]);


            // *carta de presentación
            $sql3 = "INSERT INTO cartapresentacion (
                postulante_id, carta_presentacion
            ) VALUES (
                :postulante_id, :carta_presentacion
            )";

            $query3 = $this->db->prepare($sql3);
            $query3->execute([
                ':postulante_id' => $postulanteId,
                ':carta_presentacion' => $data['cartaPresentacion']
            ]);

            // Confirmar la transacción
            $this->db->commit();

            return $postulanteId; // Retornar el ID del postulante para usarlo en otras inserciones
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->db->rollBack();
            error_log("Error en insertPersonalInfo: " . $e->getMessage());
            throw new Exception("Error al insertar los datos personales: " . $e->getMessage());
        }
    }

    // *Función para insertar información de la sección 3 (formación académica)
    public function insertFormacionAcademica($data)
    {
        try {
            // Query para insertar en la tabla formacionacademica
            $sql = "INSERT INTO formacionacademica (
            postulante_id, tipo_formacion, pais, ano_graduacion, 
            universidad, nombre_grado, pdf_formacion_academica
        ) VALUES (
            :postulante_id, :tipo_formacion, :pais, :ano_graduacion, 
            :universidad, :nombre_grado, :pdf_formacion_academica
        )";

            // Preparar la consulta
            $query = $this->db->prepare($sql);

            // Ejecutar la consulta con los datos proporcionados
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':tipo_formacion' => $data['tipo_formacion'],
                ':pais' => $data['pais'],
                ':ano_graduacion' => $data['ano_graduacion'],
                ':universidad' => $data['universidad'],
                ':nombre_grado' => $data['nombre_grado'],
                ':pdf_formacion_academica' => $data['pdf_formacion_academica'] ?? '' //! aun no se procesa el pdf de la formacion academica
            ]);

            return true; // Retornar true si la inserción fue exitosa
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertFormacionAcademica: " . $e->getMessage());
            throw new Exception("Error al insertar la formación académica: " . $e->getMessage());
        }
    }

    // *Función para insertar información de la sección 3 (idiomas)
    public function insertIdiomas($data)
    {
        try {
            // Query para insertar en la tabla idiomas
            $sql = "INSERT INTO idiomas (
            postulante_id, idioma, competencia_escrita, competencia_lectora, competencia_oral
        ) VALUES (
            :postulante_id, :idioma, :competencia_escrita, :competencia_lectora, :competencia_oral
        )";

            // Preparar la consulta con los datos proporcionados
            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':idioma' => $data['idioma'],
                ':competencia_escrita' => $data['competencia_escrita'],
                ':competencia_lectora' => $data['competencia_lectora'],
                ':competencia_oral' => $data['competencia_oral']
            ]);

            return true; // Retornar true si la inserción fue exitosa
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertIdiomas: " . $e->getMessage());
            throw new Exception("Error al insertar los idiomas: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 3 (cursos y seminarios - Relacionados a: su campo profesional)
    public function insertCursosSeminariosCampoProfesional($data)
    {
        try {
            // Query para insertar en la tabla cursosseminarios
            $sql = "INSERT INTO cursosseminarioscampoprofesional (
            postulante_id, ano, institucion, nombre_curso_seminario, 
            duracion
        ) VALUES (
            :postulante_id, :ano, :institucion, :nombre_curso_seminario, 
            :duracion
        )";

            // Preparar la consulta con los datos proporcionados
            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':ano' => $data['ano_curso_seminario'],
                ':institucion' => $data['institucion_curso_seminario'],
                ':nombre_curso_seminario' => $data['nombre_curso_seminario'],
                ':duracion' => $data['duracion_curso_seminario']
            ]);

            return true; // Retornar true si la inserción fue exitosa
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertCursosSeminariosCampoProfesional: " . $e->getMessage());
            throw new Exception("Error al insertar los cursos y seminarios relacionados a su campo profesional: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 3 (cursos y seminarios - Relacionados a: ámbito académico)
    public function insertCursosSeminariosAmbitoAcademico($data)
    {
        try {
            // Query para insertar en la tabla cursosseminarios
            $sql = "INSERT INTO cursosseminariosambitoacademico (
            postulante_id, ano, institucion, nombre_curso_seminario, 
            duracion
        ) VALUES (
            :postulante_id, :ano, :institucion, :nombre_curso_seminario, 
            :duracion
        )";

            // Preparar la consulta con los datos proporcionados
            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':ano' => $data['ano_curso_ambito_academico'],
                ':institucion' => $data['institucion_curso_ambito_academico'],
                ':nombre_curso_seminario' => $data['nombre_curso_ambito_academico'],
                ':duracion' => $data['duracion_curso_ambito_academico']
            ]);

            return true; // Retornar true si la inserción fue exitosa
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertCursosSeminariosCampoProfesional: " . $e->getMessage());
            throw new Exception("Error al insertar los cursos y seminarios relacionados a su campo profesional: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 3 (cursos y seminarios - Relacionados a: ámbito de evaluación)
    public function insertCursosSeminariosAmbitoEvaluacion($data)
    {
        try {
            // Query para insertar en la tabla cursosseminarios
            $sql = "INSERT INTO cursosseminariosambitoevaluacion (
            postulante_id, ano, institucion, nombre_curso_seminario, 
            duracion
        ) VALUES (
            :postulante_id, :ano, :institucion, :nombre_curso_seminario, 
            :duracion
        )";

            // Preparar la consulta con los datos proporcionados
            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':ano' => $data['ano_curso_ambito_evaluacion'],
                ':institucion' => $data['institucion_curso_ambito_evaluacion'],
                ':nombre_curso_seminario' => $data['nombre_curso_ambito_evaluacion'],
                ':duracion' => $data['duracion_curso_ambito_evaluacion']
            ]);

            return true; // Retornar true si la inserción fue exitosa
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertCursosSeminariosCampoProfesional: " . $e->getMessage());
            throw new Exception("Error al insertar los cursos y seminarios relacionados a su campo profesional: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 4 (experiencia laboral)
    public function insertExperienciaLaboral($data)
    {
        try {
            $sql = "INSERT INTO experiencialaboral (
            postulante_id, institucion_empresa,cargo_desempenado, 
            fecha_inicio, fecha_retiro, pais, ciudad, pdf_experiencia
        ) 
        VALUES (
            :postulante_id, :institucion_empresa, :cargo_desempenado, 
            :fecha_inicio, :fecha_retiro, :pais, :ciudad, :pdf_experiencia
        )";

            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':institucion_empresa' => $data['institucion_empresa'],
                ':cargo_desempenado' => $data['cargo_desempenado'],
                ':fecha_inicio' => $data['fecha_inicio'],
                ':fecha_retiro' => $data['fecha_retiro'],
                ':pais' => $data['pais'],
                ':ciudad' => $data['ciudad'],
                ':pdf_experiencia' => $data['pdf_experiencia'] ?? '' //! aun no se procesa el pdf de la experiencia laboral
            ]);

            return true;
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertExperienciaLaboral: " . $e->getMessage());
            throw new Exception("Error al insertar la experiencia laboral: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 4 (experiencia laboral - experiencia donante)
    public function insertExperienciaDonante($data)
    {
        try {
            $sql = "INSERT INTO experienciadocente (
            postulante_id, institucion, pais, ciudad, programa_profesional,
            curso_capacitacion_impartido, funciones_principales, fecha_inicio, fecha_retiro, pdf_experiencia_docente
        ) VALUES (
            :postulante_id, :institucion, :pais, :ciudad, :programa_profesional,
            :curso_capacitacion_impartido, :funciones_principales, :fecha_inicio, :fecha_retiro, :pdf_experiencia_docente
        )";

            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':institucion' => $data['institucion'],
                ':pais' => $data['pais'],
                ':ciudad' => $data['ciudad'],
                ':programa_profesional' => $data['programa_profesional'],
                ':curso_capacitacion_impartido' => $data['curso_capacitacion_impartido'],
                ':funciones_principales' => $data['funciones_principales'],
                ':fecha_inicio' => $data['fecha_inicio'],
                ':fecha_retiro' => $data['fecha_retiro'],
                ':pdf_experiencia_docente' => $data['pdf_experiencia_docente'] //! aun no se procesa el pdf de la experiencia docente
            ]);

            return true;
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertExperienciaDonante: " . $e->getMessage());
            throw new Exception("Error al insertar la experiencia docente: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 4 (experiencia laboral - parte de comité de calidad)
    public function insertExperienciaComite($data)
    {
        try {
            $sql = "INSERT INTO experienciacomite (
            postulante_id, institucion, cargo_desempenado, modelos_calidad, fecha_inicio, fecha_retiro
        ) VALUES (
            :postulante_id, :institucion, :cargo_desempenado, :modelos_calidad, :fecha_inicio, :fecha_retiro
        )";

            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':institucion' => $data['institucion'],
                ':cargo_desempenado' => $data['cargo_desempenado'],
                ':modelos_calidad' => $data['modelos_calidad'],
                ':fecha_inicio' => $data['fecha_inicio'],
                ':fecha_retiro' => $data['fecha_retiro']
            ]);

            return true;
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertExperienciaComite: " . $e->getMessage());
            throw new Exception("Error al insertar la experiencia comite: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 4 (experiencia laboral - como par evaluador)
    public function insertExperienciaEvaluador($data)
    {
        try {
            $sql = "INSERT INTO experienciaevaluador (
            postulante_id, agencia_acreditadora, fecha_inicio, fecha_retiro, nombre_entidad, programa,
            cargo, pais, ciudad, fecha_evaluacion
        ) VALUES (
            :postulante_id, :agencia_acreditadora, :fecha_inicio, :fecha_retiro, :nombre_entidad, :programa,
            :cargo, :pais, :ciudad, :fecha_evaluacion
        )";

            $query = $this->db->prepare($sql);
            $query->execute([
                //datos de registro unico
                ':postulante_id' => $data['postulante_id'],
                ':agencia_acreditadora' => $data['agencia_acreditadora'],
                ':fecha_inicio' => $data['fecha_inicio_evaluador'],
                ':fecha_retiro' => $data['fecha_retiro_evaluador'],

                //datos de registro mediante tabla
                ':nombre_entidad' => $data['nombreEntidadEvaluador'],
                ':programa' => $data['programaEvaluador'],
                ':cargo' => $data['cargoEvaluador'],
                ':pais' => $data['paisEvaluador'],
                ':ciudad' => $data['ciudadEvaluador'],
                ':fecha_evaluacion' => $data['fechaEvaluacionEvaluador']
            ]);

            return true;
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción    
            error_log("Error en insertExperienciaEvaluador: " . $e->getMessage());
            throw new Exception("Error al insertar la experiencia evaluador: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 4 (experiencia laboral - membresias)
    public function insertExperienciaMembresia($data)
    {
        try {
            $sql = "INSERT INTO membresias (
            postulante_id, asociacion_profesional, numero_membresia, grado
        ) VALUES (
            :postulante_id, :asociacion_profesional, :numero_membresia, :grado
        )";

            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':asociacion_profesional' => $data['asociacion_profesional'],
                ':numero_membresia' => $data['numero_membresia'],
                ':grado' => $data['grado']
            ]);

            return true;
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertExperienciaMembresia: " . $e->getMessage());
            throw new Exception("Error al insertar la experiencia membresia: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 5 (publicaciones, artículos y revistas)
    public function insertExperienciaInvestigacion($data)
    {
        try {
            $sql = "INSERT INTO investigaciones (
            postulante_id, fecha_publicacion, revista_congreso, base_datos, nombre_investigacion, autores
        ) VALUES (
            :postulante_id, :fecha_publicacion, :revista_congreso, :base_datos, :nombre_investigacion, :autores
        )";

            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':fecha_publicacion' => $data['fecha_publicacion'],
                ':revista_congreso' => $data['revista_congreso'],
                ':base_datos' => $data['base_datos'],
                ':nombre_investigacion' => $data['nombre_investigacion'],
                ':autores' => $data['autores']
            ]);

            return true;
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertExperienciaInvestigacion: " . $e->getMessage());
            throw new Exception("Error al insertar la experiencia investigacion: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 6 (Premios y Reconocimientos)
    public function insertExperienciaPremios($data)
    {
        try {
            $sql = "INSERT INTO premiosreconocimientos (
            postulante_id, ano, institucion_empresa, nombre_reconocimiento, descripcion_reconocimiento
        ) VALUES (
            :postulante_id, :ano, :institucion_empresa, :nombre_reconocimiento, :descripcion_reconocimiento
        )";

            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':ano' => $data['ano_reconocimiento'],
                ':institucion_empresa' => $data['institucion_empresa'],
                ':nombre_reconocimiento' => $data['nombre_reconocimiento'],
                ':descripcion_reconocimiento' => $data['descripcion_reconocimiento']
            ]);

            return true;
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertExperienciaPremios: " . $e->getMessage());
            throw new Exception("Error al insertar la experiencia premios: " . $e->getMessage());
        }
    }

    //* Función para insertar información de la sección 7 (valores eticos y firma) 
    public function insertCondutaEtica($data)
    {
        try {
            $sql = "INSERT INTO conductaetica (
            postulante_id, conducta_etica, informacion_verdadera, firma
        ) VALUES (
            :postulante_id, :conducta_etica, :informacion_verdadera, :firma
        )";

            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':conducta_etica' => $data['conduta_etica'],
                ':informacion_verdadera' => $data['conduta_etica_valores'],
                ':firma' => $data['firma']
            ]);     

            return true;
        } catch (Exception $e) {
            // Registrar el error y lanzar una excepción
            error_log("Error en insertCondutaEtica: " . $e->getMessage());
            throw new Exception("Error al insertar la experiencia valores eticos: " . $e->getMessage());
        }
    }

}
