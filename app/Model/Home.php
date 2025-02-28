<?php

namespace Mini\Model;

use Mini\Core\Model;
use PDO;
use Exception;

class Home extends Model
{
    public function insertPersonalInfo($data)
    {
        try {
            $sql = "INSERT INTO postulante (
                primer_apellido, segundo_apellido, nombres_completos, tipo_identidad, num_doc, pdf_documento_identidad, 
                nacionalidad, fecha_nacimiento, estado_civil, foto_perfil, correo_electronico, celular, red_profesional, 
                tipo_direccion, direccion, numero_direccion, pais, region, provincia, distrito, referencia_domicilio
            ) VALUES (
                :primer_apellido, :segundo_apellido, :nombres_completos, :tipo_identidad, :num_doc, :pdf_documento_identidad, 
                :nacionalidad, :fecha_nacimiento, :estado_civil, :foto_perfil, :correo_electronico, :celular, :red_profesional, 
                :tipo_direccion, :direccion, :numero_direccion, :pais, :region, :provincia, :distrito, :referencia_domicilio
            )";
            
            $stmt = $this->db->prepare($sql);
            
            // Vincular parámetros con validación previa
            foreach ($data as $key => $value) {
                $stmt->bindValue(":" . $key, htmlspecialchars(strip_tags($value)), PDO::PARAM_STR);
            }
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en insertPersonalInfo: " . $e->getMessage());
            return false;
        }
    }
}
