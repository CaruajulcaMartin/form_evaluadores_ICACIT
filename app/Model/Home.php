<?php

namespace Mini\Model;

use Mini\Core\Model;
use Exception;

use PDO;
use PDOException;

class Home extends Model
{

    public function verificarEnvio($usuarioId)
    {
        if (!is_numeric($usuarioId) || $usuarioId <= 0) {
            return false;
        }

        try {
            $sql = "SELECT 1 FROM progreso_formulario 
                WHERE usuario_id = :usuario_id 
                AND formulario_id = 1
                AND enviado = 1
                LIMIT 1";

            $query = $this->db->prepare($sql);
            $query->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $query->execute();

            return (bool)$query->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al verificar envío: " . $e->getMessage());
            return false;
        }
    }

    // confirmar envio de formulario
    public function confirmarEnvio($usuario_id)
    {
        // Primero verifica si cumple las condiciones
        $sql_check = "SELECT id FROM progreso_formulario 
                    WHERE usuario_id = ? 
                    AND porcentaje_completado = 100 
                    AND completado = 1";

        $query_check = $this->db->prepare($sql_check);
        $query_check->execute([$usuario_id]);

        if ($query_check->rowCount() == 0) {
            return false;
        }

        // Luego realiza el update
        $sql_update = "UPDATE progreso_formulario 
                    SET enviado = 1, 
                        fecha_envio = NOW()
                    WHERE usuario_id = ?";

        $query_update = $this->db->prepare($sql_update);
        return $query_update->execute([$usuario_id]);
    }

    // elimnar los datos de la seccion 5
    public function deleteSeccion6($id)
    {
        $sql = "DELETE FROM premiosreconocimientos WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    // elimnar los datos de la seccion 5
    public function deleteSeccion5($id)
    {
        $sql = "DELETE FROM investigaciones WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //!eliminar los datos de la seccion 4 - membresias
    public function deleteMembresias($id)
    {
        $sql = "DELETE FROM membresias WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //eliminar los datos de la seccion 4 - experiencia evaluador
    public function deleteExperienciaEvaluador($id)
    {
        $sql = "DELETE FROM experienciaevaluador WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //eliminar los datos de la seccion 4 - experiencia docente
    public function deleteExperienciaDocente($id)
    {
        $sql = "DELETE FROM experienciadocente WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //eliminar los datos de la seccion 4 - experiencia comite
    public function deleteExperienciaComite($id)
    {
        $sql = "DELETE FROM experienciacomite WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //eliminar los datos de la seccion 4 - experiencia laboral
    public function deleteExperienciaLaboral($id)
    {
        $sql = "DELETE FROM experiencialaboral WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //eliminar los datos de la seccion 3 - curso evaluacion
    public function eliminarCursoEvaluacion($id)
    {
        $sql = "DELETE FROM cursosseminariosambitoevaluacion WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //eliminar los datos de la seccion 3 - curso academico
    public function eliminarCursoAcademico($id)
    {
        $sql = "DELETE FROM cursosseminariosambitoacademico WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //eliminar los datos de la seccion 3 - campo profesional
    public function eliminarCampoProfesional($id)
    {
        $sql = "DELETE FROM cursosseminarioscampoprofesional WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //eliminar los datos de la seccion 3 - idiomas
    public function eliminarIdiomas($id)
    {
        $sql = "DELETE FROM idiomas WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    //eliminar los datos de la seccion 3 - formacion academica
    public function eliminarFormacionAcademica($id)
    {
        $sql = "DELETE FROM formacionacademica WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    // insertar los datos de la seccion 8
    public function insertSeccion8($data)
    {
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
    }

    // insertar los datos de la seccion 7
    public function insertCartaPresentacion($data)
    {
        $sql = "INSERT INTO cartapresentacion (
            postulante_id, carta_presentacion
        ) VALUES (
            :postulante_id, :carta_presentacion
        )";

        $query = $this->db->prepare($sql);
        $query->execute([
            ':postulante_id' => $data['postulante_id'],
            ':carta_presentacion' => $data['cartaPresentacion']
        ]);
    }

    // insertar los datos de la seccion 6
    public function insertTablaPremios($data)
    {
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
    }

    // insertar los datos de la seccion 5
    public function insertTablaInvestigaciones($data)
    {
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
    }

    // insertar los datos de la seccion 4
    public function insertExperienciaLaboral($data)
    {
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
            ':pdf_experiencia' => $data['pdf_experiencia'] ?? null
        ]);
    }

    public function insertExperienciaDocente($data)
    {
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
            ':pdf_experiencia_docente' => $data['pdf_experiencia_docente'] ?? null
        ]);
    }

    public function insertExperienciaComite($data)
    {
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
    }

    public function insertExperienciaEvaluador($data)
    {
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
    }

    public function insertExperienciaMembresias($data)
    {
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
    }

    //insertar los datos de la seccion 3
    public function insertFormacionAcademica($data)
    {
        $sql = "INSERT INTO formacionacademica (
        postulante_id, tipo_formacion, pais, ano_graduacion, universidad, nombre_grado, pdf_formacion_academica
        ) VALUES (
            :postulante_id, :tipo_formacion, :pais, :ano_graduacion, :universidad, :nombre_grado, :pdf_formacion_academica
        )";

        $query = $this->db->prepare($sql);
        $query->execute([
            'postulante_id' => $data['postulante_id'],
            'tipo_formacion' => $data['tipo_formacion'],
            'pais' => $data['pais'],
            'ano_graduacion' => $data['ano_graduacion'],
            'universidad' => $data['universidad'],
            'nombre_grado' => $data['nombre_grado'],
            'pdf_formacion_academica' => $data['pdf_formacion_academica'] ?? null
        ]);
    }

    public function insertIdiomas($data)
    {
        $sql = "INSERT INTO idiomas (
        postulante_id, idioma, competencia_escrita, competencia_lectora, competencia_oral
        ) VALUES (
            :postulante_id, :idioma, :competencia_escrita, :competencia_lectora, :competencia_oral
        )";

        $query = $this->db->prepare($sql);
        $query->execute([
            'postulante_id' => $data['postulante_id'],
            'idioma' => $data['idioma'],
            'competencia_escrita' => $data['competencia_escrita'],
            'competencia_lectora' => $data['competencia_lectora'],
            'competencia_oral' => $data['competencia_oral']
        ]);
    }

    public function insertCursosSeminariosCampoProfesional($data)
    {
        $sql = "INSERT INTO cursosseminarioscampoprofesional (
        postulante_id, ano, institucion, nombre_curso_seminario, tipo_seminario, duracion
        ) VALUES (
            :postulante_id, :ano, :institucion, :nombre_curso_seminario, :tipo_seminario, :duracion
        )";

        $query = $this->db->prepare($sql);
        $query->execute([
            'postulante_id' => $data['postulante_id'],
            'ano' => $data['ano'],
            'institucion' => $data['institucion'],
            'nombre_curso_seminario' => $data['nombre_curso_seminario'],
            'tipo_seminario' => $data['tipo_seminario'],
            'duracion' => $data['duracion']
        ]);
    }

    public function insertCursosSeminariosAmbitoAcademico($data)
    {
        $sql = "INSERT INTO cursosseminariosambitoacademico (
        postulante_id, ano, institucion, nombre_curso_seminario, tipo_seminario, duracion
        ) VALUES (
            :postulante_id, :ano, :institucion, :nombre_curso_seminario, :tipo_seminario, :duracion
        )";

        $query = $this->db->prepare($sql);
        $query->execute([
            'postulante_id' => $data['postulante_id'],
            'ano' => $data['ano'],
            'institucion' => $data['institucion'],
            'nombre_curso_seminario' => $data['nombre_curso_seminario'],
            'tipo_seminario' => $data['tipo_seminario'],
            'duracion' => $data['duracion']
        ]);
    }

    public function insertCursosSeminariosAmbitoEvaluacion($data)
    {
        $sql = "INSERT INTO cursosseminariosambitoevaluacion (
        postulante_id, ano, institucion, nombre_curso_seminario, tipo_seminario, duracion
        ) VALUES (
            :postulante_id, :ano, :institucion, :nombre_curso_seminario, :tipo_seminario, :duracion
        )";

        $query = $this->db->prepare($sql);
        $query->execute([
            'postulante_id' => $data['postulante_id'],
            'ano' => $data['ano'],
            'institucion' => $data['institucion'],
            'nombre_curso_seminario' => $data['nombre_curso_seminario'],
            'tipo_seminario' => $data['tipo_seminario'],
            'duracion' => $data['duracion']
        ]);
    }

    // insertar los datos de la seccion 2
    public function insertSeccion2($data)
    {
        try {
            if (empty($data['postulante_id'])) {
                throw new Exception("El postulante_id no puede estar vacío.");
            }

            $sql = "INSERT INTO informacionlaboral (
            postulante_id, nombre_centro_laboral, cargo_actual, tiempo_laboral, pais, ciudad, rubro,
            nombres_empleador, cargo_empleador, correo_empleador
        ) VALUES (
            :postulante_id, :nombre_centro_laboral, :cargo_actual, :tiempo_laboral, :pais, :ciudad, :rubro,
            :nombres_empleador, :cargo_empleador, :correo_empleador
        )";

            $query = $this->db->prepare($sql);
            $query->execute([
                ':postulante_id' => $data['postulante_id'],
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

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error en insertSeccion2: " . $e->getMessage());
            throw new Exception("Error al insertar los datos de la Sección 2: " . $e->getMessage());
        }
    }

    // insertar los datos de la seccion 1
    public function insertSeccion1($data)
    {
        try {
            if (empty($data['usuario_id'])) {
                throw new Exception("El usuario_id no puede estar vacío.");
            }

            $sql = "INSERT INTO postulante (
                usuario_id, primer_apellido, segundo_apellido, nombres_completos, tipo_identidad, numero_identidad, 
                pdf_documento_identidad, nacionalidad, fecha_nacimiento, estado_civil, foto_perfil, 
                correo_electronico, codigo_telefono, numero_celular, red_profesional, tipo_direccion, 
                direccion, numero_direccion, pais, region_estado, provincia_municipio, distrito, referencia_domicilio, fecha_envio, estado
            ) VALUES (
                :usuario_id, :primer_apellido, :segundo_apellido, :nombres_completos, :tipo_identidad, :numero_identidad, 
                :pdf_documento_identidad, :nacionalidad, :fecha_nacimiento, :estado_civil, :foto_perfil, 
                :correo_electronico, :codigo_telefono, :numero_celular, :red_profesional, :tipo_direccion, 
                :direccion, :numero_direccion, :pais, :region_estado, :provincia_municipio, :distrito, :referencia_domicilio, NOW(), :estado
            )";

            $query = $this->db->prepare($sql);
            $query->execute([
                ':usuario_id' => $data['usuario_id'],
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
                ':referencia_domicilio' => $data['referenciaDomicilio'],
                ':estado' => 'completado'
            ]);

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error en insertSeccion1: " . $e->getMessage());
            throw new Exception("Error al insertar los datos de la Sección 1: " . $e->getMessage());
        }
    }
}
