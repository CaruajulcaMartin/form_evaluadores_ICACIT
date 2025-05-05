<?php

namespace Mini\Model;

use Mini\Core\Model;

use Exception;

class Persona extends Model
{
    //funcion de inicio de sesion de usuario
    public function verifyCredentials($email, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $query = $this->db->prepare($sql);
        $query->bindParam(':email', $email, \PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(\PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }

    //funcion de registro de usuario
    public function registerUser($name, $lastName, $motherLastName, $email, $password, $cod_activacion)
    {
        // Verificar si el correo ya está registrado
        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $query = $this->db->prepare($sql);
        $query->bindParam(':email', $email, \PDO::PARAM_STR);
        $query->execute();

        if ($query->fetch()) {
            return false;
        }

        // Insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, email, password, estado, cod_activacion)
            VALUES (:name, :lastName, :motherLastName, :email, :password, 'Inactivo', :cod_activacion)";
        $query = $this->db->prepare($sql);
        $query->bindParam(':name', $name, \PDO::PARAM_STR);
        $query->bindParam(':lastName', $lastName, \PDO::PARAM_STR);
        $query->bindParam(':motherLastName', $motherLastName, \PDO::PARAM_STR);
        $query->bindParam(':email', $email, \PDO::PARAM_STR);
        $query->bindParam(':password', $password, \PDO::PARAM_STR);
        $query->bindParam(':cod_activacion', $cod_activacion, \PDO::PARAM_STR);

        return $query->execute();
    }

    // obtener usuario por id
    public function getUserById($userId)
    {
        $sql = "SELECT id, nombre, apellido_paterno, email FROM usuarios WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->bindParam(':id', $userId, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        return $result;
    }

    //funcion para obtener el progreso del usuario
    public function getProgressByUserId($userId)
    {
        // Obtener progreso básico
        $sql = "SELECT * FROM progreso_formulario WHERE usuario_id = :userId";
        $query = $this->db->prepare($sql);
        $query->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $query->execute();
        $progress = $query->fetch(\PDO::FETCH_ASSOC);

        if (!$progress) {
            return null;
        }

        // Obtener estados de las secciones (solo el último estado de cada sección)
        $sqlEstados = "SELECT 
            SUBSTRING(es.seccion, 9) as num_seccion,
            es.completada,
            es.fecha_completado,
            es.ultima_actualizacion
        FROM estado_secciones es
        INNER JOIN (
            SELECT seccion, MAX(id) as max_id
            FROM estado_secciones
            WHERE progreso_id = :progreso_id
            GROUP BY seccion
        ) ultimos ON es.id = ultimos.max_id
        WHERE es.progreso_id = :progreso_id
        ORDER BY num_seccion";

        $queryEstados = $this->db->prepare($sqlEstados);
        $queryEstados->bindParam(':progreso_id', $progress['id'], \PDO::PARAM_INT);
        $queryEstados->execute();
        $estados = $queryEstados->fetchAll(\PDO::FETCH_ASSOC) ?: [];

        // Preparar el array de estado de secciones
        $estadoSecciones = [];
        $ultimaSeccionCompletada = 0;

        foreach ($estados as $estado) {
            $numSeccion = (int)$estado['num_seccion'];
            $estadoSecciones[$numSeccion] = [
                'completada' => (bool)$estado['completada'],
                'fecha_completado' => $estado['fecha_completado'],
                'ultima_actualizacion' => $estado['ultima_actualizacion']
            ];

            if ($estado['completada'] && $numSeccion > $ultimaSeccionCompletada) {
                $ultimaSeccionCompletada = $numSeccion;
            }
        }

        // Calcular porcentaje completado basado en secciones completadas
        $seccionesCompletadas = array_reduce($estadoSecciones, function ($carry, $item) {
            return $carry + ($item['completada'] ? 1 : 0);
        }, 0);

        $porcentaje = min(100, round(($seccionesCompletadas / 8) * 100));

        $progress['estado_secciones'] = $estadoSecciones;
        $progress['ultima_seccion_completada'] = $ultimaSeccionCompletada;
        $progress['porcentaje_completado'] = $porcentaje;

        return $progress;
    }

    //funcion para inicializar el progreso del usuario
    public function initializeProgress($userId)
    {
        try {
            $this->db->beginTransaction();

            // Insertar en progreso_formulario
            $sql = "INSERT INTO progreso_formulario (usuario_id, seccion_actual, porcentaje_completado, fecha_inicio) 
                VALUES (:userId, 1, 0, NOW())";
            $query = $this->db->prepare($sql);
            $query->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $query->execute();

            $progresoId = $this->db->lastInsertId();

            // Insertar estado inicial para la sección 1
            $sqlEstado = "INSERT INTO estado_secciones (progreso_id, seccion, completada, ultima_actualizacion)
                    VALUES (:progreso_id, 'Sección 1', 0, NOW())";
            $queryEstado = $this->db->prepare($sqlEstado);
            $queryEstado->bindParam(':progreso_id', $progresoId, \PDO::PARAM_INT);
            $queryEstado->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en initializeProgress: " . $e->getMessage());
            return false;
        }
    }

    public function getPostulanteIdByUserId($userId)
    {
        try {
            // Sentencia SQL corregida
            $sql = "SELECT id FROM postulante WHERE usuario_id = :user_id LIMIT 1";
            $query = $this->db->prepare($sql);
            $query->execute([':user_id' => $userId]);
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            return $result ? $result['id'] : null;
        } catch (Exception $e) {
            error_log("Error en getPostulanteIdByUserId: " . $e->getMessage());
            throw new Exception("Error al obtener el ID del postulante.");
        }
    }
}
