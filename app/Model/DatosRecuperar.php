<?php

namespace Mini\Model;

use Mini\Core\Model;
use PDO;
use PDOException;
use Exception;


class DatosRecuperar extends Model
{

    public function insertCartaPresentacion($data)
    {
        try {
            $sql = "INSERT INTO cartapresentacion (postulante_id, carta_presentacion) 
                VALUES (:postulante_id, :carta_presentacion)";
            $query = $this->db->prepare($sql);
            return $query->execute([
                ':postulante_id' => $data['postulante_id'],
                ':carta_presentacion' => $data['carta_presentacion']
            ]);
        } catch (PDOException $e) {
            error_log("Error en insertCartaPresentacion: " . $e->getMessage());
            return false;
        }
    }

    // modificar los datos de la seccion 7
    public function updateCartaPresentacion($postulanteId, $data)
    {
        try {
            $sql = "UPDATE cartapresentacion SET carta_presentacion = :carta_presentacion WHERE postulante_id = :postulante_id";
            $query = $this->db->prepare($sql);
            $query->execute([
                ':carta' => $data['cartaPresentacion'],
                ':id' => $postulanteId
            ]);

            // Verificar si se actualizó alguna fila
            return $query->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error en updateCartaPresentacion: " . $e->getMessage());
            return false;
        }
    }

    //modificar los datos de la seccion2  funciona correctamente
    public function updateSeccion2Data($postulanteId, $data)
    {
        $sql = "UPDATE informacionlaboral SET 
                nombre_centro_laboral = :nombre_centro_laboral,
                cargo_actual = :cargo_actual,
                tiempo_laboral = :tiempo_laboral,
                pais = :pais,
                ciudad = :ciudad,
                rubro = :rubro,
                nombres_empleador = :nombres_empleador,
                cargo_empleador = :cargo_empleador,
                correo_empleador = :correo_empleador
                WHERE postulante_id = :postulante_id";

        $params = [
            ':postulante_id' => $postulanteId,
            ':nombre_centro_laboral' => $data['centroLaboral'] ?? null,
            ':cargo_actual' => $data['cargoActual'] ?? null,
            ':tiempo_laboral' => $data['tiempoLaboral'] ?? null,
            ':pais' => $data['PaisInformacionLaboral'] ?? null,
            ':ciudad' => $data['ciudadInformacionLaboral'] ?? null,
            ':rubro' => $data['rubroInformacionLaboral'] ?? null,
            ':nombres_empleador' => $data['nombresEmpleador'] ?? null,
            ':cargo_empleador' => $data['cargoEmpleador'] ?? null,
            ':correo_empleador' => $data['correoEmpleador'] ?? null
        ];

        $query = $this->db->prepare($sql);
        $result = $query->execute($params);
        if (!$result) {
            throw new Exception("No se pudo actualizar los datos");
        }
    }

    //modificar los datos de la seccion 1 funciona correctamente
    public function updateSeccion1Data($postulanteId, $datos)
    {
        try {
            $sql = "UPDATE postulante SET 
            primer_apellido = :primer_apellido,
            segundo_apellido = :segundo_apellido,
            nombres_completos = :nombres_completos,
            tipo_identidad = :tipo_identidad,
            numero_identidad = :numero_identidad,
            nacionalidad = :nacionalidad,
            fecha_nacimiento = :fecha_nacimiento,
            estado_civil = :estado_civil,
            correo_electronico = :correo_electronico,
            codigo_telefono = :codigo_telefono,
            numero_celular = :numero_celular,
            red_profesional = :red_profesional,
            tipo_direccion = :tipo_direccion,
            direccion = :direccion,
            numero_direccion = :numero_direccion,
            pais = :pais,
            region_estado = :region_estado,
            provincia_municipio = :provincia_municipio,
            distrito = :distrito,
            referencia_domicilio = :referencia_domicilio";

            // Agregar campos de archivos solo si están presentes
            if (isset($datos['fotoPerfil'])) {
                $sql .= ", foto_perfil = :foto_perfil";
            }
            if (isset($datos['pdfDocumentoIdentidad'])) {
                $sql .= ", pdf_documento_identidad = :pdf_documento_identidad";
            }

            $sql .= " WHERE id = :postulante_id";

            $params = [
                ':primer_apellido' => $datos['apellido1'] ?? null,
                ':segundo_apellido' => $datos['apellido2'] ?? null,
                ':nombres_completos' => $datos['nombresCompletos'] ?? null,
                ':tipo_identidad' => $datos['tipoIdentidad'] ?? null,
                ':numero_identidad' => $datos['numDoc'] ?? null,
                ':nacionalidad' => $datos['nationality'] ?? null,
                ':fecha_nacimiento' => $datos['fechaNacimiento'] ?? null,
                ':estado_civil' => $datos['estadoCivil'] ?? null,
                ':correo_electronico' => $datos['correoElectronico'] ?? null,
                ':codigo_telefono' => $datos['phoneCode'] ?? null,
                ':numero_celular' => $datos['celular'] ?? null,
                ':red_profesional' => $datos['redProfesional'] ?? null,
                ':tipo_direccion' => $datos['tipoDireccion'] ?? null,
                ':direccion' => $datos['direccion'] ?? null,
                ':numero_direccion' => $datos['numeroDireccion'] ?? null,
                ':pais' => $datos['PaisDatoDominicial'] ?? null,
                ':region_estado' => $datos['PaisDatoDominicialRegion'] ?? null,
                ':provincia_municipio' => $datos['provinciaDatoDominicial'] ?? null,
                ':distrito' => $datos['distritoDatoDominicial'] ?? null,
                ':referencia_domicilio' => $datos['referenciaDomicilio'] ?? null,
                ':postulante_id' => $postulanteId
            ];

            // Agregar parámetros de archivos solo si están presentes
            // if (isset($datos['fotoPerfil'])) {
            //     $params[':foto_perfil'] = $datos['fotoPerfil'];
            // }
            // if (isset($datos['pdfDocumentoIdentidad'])) {
            //     $params[':pdf_documento_identidad'] = $datos['pdfDocumentoIdentidad'];
            // }

            $query = $this->db->prepare($sql);
            $result = $query->execute($params);

            if (!$result) {
                throw new Exception("No se pudo actualizar los datos");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error en updateSeccion1Data: " . $e->getMessage());
            throw new Exception("Error al actualizar los datos: " . $e->getMessage());
        }
    }


    /* Obtener los datos de las secciones */
    // Obtener datos de la sección 1 (Datos personales)
    public function getSeccion1Data($usuarioId)
    {
        $sql = "SELECT * FROM postulante WHERE usuario_id = :usuario_id";
        $query = $this->db->prepare($sql);
        $query->execute([':usuario_id' => $usuarioId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener datos de la sección 2 (Información laboral)
    public function getSeccion2Data($postulanteId)
    {
        $sql = "SELECT * FROM informacionlaboral WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener datos de la sección 3 (Formación académica)
    public function getSeccion3Data($postulanteId)
    {
        $data = [
            'formacion_academica' => $this->getFormacionAcademica($postulanteId),
            'idiomas' => $this->getIdiomas($postulanteId),
            'cursos_profesionales' => $this->getCursosProfesionales($postulanteId),
            'cursos_academicos' => $this->getCursosAcademicos($postulanteId),
            'cursos_evaluacion' => $this->getCursosEvaluacion($postulanteId)
        ];

        return $data;
    }

    private function getFormacionAcademica($postulanteId)
    {
        error_log("DEBUG_MODEL_S3: getFormacionAcademicaData llamado con postulanteId = " . $postulanteId);
        $sql = "SELECT id, tipo_formacion, pais, ano_graduacion, universidad, nombre_grado, pdf_formacion_academica 
            FROM formacionacademica 
            WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getIdiomas($postulanteId)
    {
        $sql = "SELECT  id, idioma, competencia_escrita, competencia_lectora, competencia_oral FROM idiomas WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCursosProfesionales($postulanteId)
    {
        $sql = "SELECT id, ano, institucion, nombre_curso_seminario, duracion, tipo_seminario FROM cursosseminarioscampoprofesional WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCursosAcademicos($postulanteId)
    {
        $sql = "SELECT id, ano, institucion, nombre_curso_seminario, duracion, tipo_seminario FROM cursosseminariosambitoacademico WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCursosEvaluacion($postulanteId)
    {
        $sql = "SELECT id, ano, institucion, nombre_curso_seminario, duracion, tipo_seminario FROM cursosseminariosambitoevaluacion WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener datos de la sección 4 (Experiencia)
    public function getSeccion4Data($postulanteId)
    {
        $data = [
            'experiencia_laboral' => $this->getExperienciaLaboral($postulanteId),
            'experiencia_docente' => $this->getExperienciaDocente($postulanteId),
            'experiencia_comite' => $this->getExperienciaComite($postulanteId),
            'experiencia_evaluador' => $this->getExperienciaEvaluador($postulanteId),
            'membresias' => $this->getMembresias($postulanteId)
        ];

        return $data;
    }

    private function getExperienciaLaboral($postulanteId)
    {
        $sql = "SELECT * FROM experiencialaboral WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getExperienciaDocente($postulanteId)
    {
        $sql = "SELECT * FROM experienciadocente WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getExperienciaComite($postulanteId)
    {
        $sql = "SELECT * FROM experienciacomite WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getExperienciaEvaluador($postulanteId)
    {
        $sql = "SELECT * FROM experienciaevaluador WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMembresias($postulanteId)
    {
        $sql = "SELECT * FROM membresias WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener datos de la sección 5 (Investigaciones)
    public function getSeccion5Data($postulanteId)
    {
        $sql = "SELECT * FROM investigaciones WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener datos de la sección 6 (Premios y reconocimientos)
    public function getSeccion6Data($postulanteId)
    {
        $sql = "SELECT * FROM premiosreconocimientos WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener datos de la sección 7 (Carta de presentación)
    public function getSeccion7Data($postulanteId)
    {
        $sql = "SELECT * FROM cartapresentacion WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener datos de la sección 8 (Conducta ética)
    public function getSeccion8Data($postulanteId)
    {
        $sql = "SELECT * FROM conductaetica WHERE postulante_id = :postulante_id";
        $query = $this->db->prepare($sql);
        $query->execute([':postulante_id' => $postulanteId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener todos los datos del postulante
    public function getAllData($usuarioId)
    {
        // Primero obtener el ID del postulante
        $postulante = $this->getSeccion1Data($usuarioId);

        if (!$postulante) {
            return null;
        }

        $postulanteId = $postulante['id'];

        return [
            'seccion1' => $postulante,
            'seccion2' => $this->getSeccion2Data($postulanteId),
            'seccion3' => $this->getSeccion3Data($postulanteId),
            'seccion4' => $this->getSeccion4Data($postulanteId),
            'seccion5' => $this->getSeccion5Data($postulanteId),
            'seccion6' => $this->getSeccion6Data($postulanteId),
            'seccion7' => $this->getSeccion7Data($postulanteId),
            'seccion8' => $this->getSeccion8Data($postulanteId)
        ];
    }
}
