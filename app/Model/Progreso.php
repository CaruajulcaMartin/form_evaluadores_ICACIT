<?php

namespace Mini\Model;

use Mini\Core\Model;
use Exception;

class Progreso extends Model
{

    // crear un nuevo progreso para un usuario específico
    public function crearProgreso($usuarioId)
    {
        $sql = "INSERT INTO progreso_formulario 
                (usuario_id, seccion_actual, porcentaje_completado, fecha_inicio)
                VALUES (:usuario_id, 'Sección 1', 0, NOW())";
                
        $query = $this->db->prepare($sql);
        $query->execute([':usuario_id' => $usuarioId]);
        
        return $this->db->lastInsertId();
    }

    // obtener el ID del progreso del formulario para un usuario específico
    public function obtenerIdProgreso($usuarioId) {
        $sql = "SELECT id FROM progreso_formulario WHERE usuario_id = :usuario_id";
        $query = $this->db->prepare($sql);
        $query->execute([':usuario_id' => $usuarioId]);
        return $query->fetchColumn();
    }
    
    // actualizar el progreso del formulario
    public function insertarEstadoSeccion($progresoId, $seccion, $completada) {
        $sql = "INSERT INTO estado_secciones 
                (progreso_id, seccion, completada, fecha_completado, ultima_actualizacion)
                VALUES (
                    :progreso_id,
                    :seccion,
                    :completada,
                    IF(:completada = 1, NOW(), NULL),
                    NOW()
                )";
                
        $query = $this->db->prepare($sql);
        return $query->execute([
            ':progreso_id' => $progresoId,
            ':seccion' => $seccion,
            ':completada' => $completada ? 1 : 0
        ]);
    }

    
    
    // actualizar el estado de una sección existente
    public function existeEstadoSeccion($progresoId, $seccion) {
        $sql = "SELECT COUNT(*) FROM estado_secciones 
                WHERE progreso_id = :progreso_id AND seccion = :seccion";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':progreso_id' => $progresoId,
            ':seccion' => $seccion
        ]);
        return $query->fetchColumn() > 0;
    }
    
    // contar el número de secciones completadas
    public function contarSeccionesCompletadas($progresoId) {
        $sql = "SELECT COUNT(DISTINCT seccion) 
                FROM estado_secciones 
                WHERE progreso_id = :progreso_id AND completada = 1";
        $query = $this->db->prepare($sql);
        $query->execute([':progreso_id' => $progresoId]);
        return $query->fetchColumn();
    }
    
    // actualizar el progreso general del formulario
    public function actualizarProgresoGeneral($progresoId, $seccionActual, $porcentaje, $completado) {
        $sql = "UPDATE progreso_formulario 
                SET seccion_actual = :seccion_actual,
                    porcentaje_completado = :porcentaje,
                    completado = :completado
                WHERE id = :progreso_id";
                
        $query = $this->db->prepare($sql);
        return $query->execute([
            ':seccion_actual' => $seccionActual,
            ':porcentaje' => $porcentaje,
            ':completado' => $completado ? 1 : 0,
            ':progreso_id' => $progresoId
        ]);
    }

    // obtener el progreso del formulario
    public function getProgresoFormulario($usuarioId)
    {
        // Obtener información básica del progreso
        $sql = "SELECT pf.seccion_actual, pf.porcentaje_completado, pf.fecha_inicio 
                FROM progreso_formulario pf 
                WHERE pf.usuario_id = :usuario_id";
        $query = $this->db->prepare($sql);
        $query->execute([':usuario_id' => $usuarioId]);
        $progreso = $query->fetch(\PDO::FETCH_ASSOC) ?: [];

        if (empty($progreso)) {
            return [
                'seccion_actual' => 1,
                'porcentaje_completado' => 0,
                'ultimo_guardado' => null,
                'estado_secciones' => []
            ];
        }

        // Obtener el estado de cada sección (último registro por sección)
        $sqlEstados = "SELECT es1.seccion, es1.completada, es1.ultima_actualizacion
                FROM estado_secciones es1
                INNER JOIN (
                    SELECT seccion, MAX(ultima_actualizacion) as max_fecha
                    FROM estado_secciones
                    WHERE progreso_id = (SELECT id FROM progreso_formulario WHERE usuario_id = :usuario_id)
                    GROUP BY seccion
                ) es2 ON es1.seccion = es2.seccion AND es1.ultima_actualizacion = es2.max_fecha
                WHERE es1.progreso_id = (SELECT id FROM progreso_formulario WHERE usuario_id = :usuario_id)";

        $queryEstados = $this->db->prepare($sqlEstados);
        $queryEstados->execute([':usuario_id' => $usuarioId]);
        $estados = $queryEstados->fetchAll(\PDO::FETCH_ASSOC) ?: [];

        // Preparar el array de estado de secciones
        $estadoSecciones = [];
        foreach ($estados as $estado) {
            $numSeccion = (int)str_replace('Sección ', '', $estado['seccion']);
            $estadoSecciones[$numSeccion] = [
                'completada' => $estado['completada'],
                'ultima_actualizacion' => $estado['ultima_actualizacion']
            ];
        }

        return [
            'seccion_actual' => $progreso['seccion_actual'],
            'porcentaje_completado' => $progreso['porcentaje_completado'],
            'ultimo_guardado' => $progreso['fecha_inicio'],
            'estado_secciones' => $estadoSecciones
        ];
    }
}
