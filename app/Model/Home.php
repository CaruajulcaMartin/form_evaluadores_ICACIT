<?php

namespace Mini\Model;

use Mini\Core\Model;
use PDO;
use Exception;

class Home extends Model
{
    public function insertPersonalInfo($data)
    {
        //tabla de informacion personal
        try {
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

            //tabla de informacion laboral actual

            $sql2 = "INSERT INTO informacionlaboral (postulante_id, nombre_centro_laboral, cargo_actual, tiempo_laboral, pais, ciudad, rubro, 
            nombres_empleador, cargo_empleador, correo_empleador) VALUES ( 
                :postulante_id, :nombre_centro_laboral, :cargo_actual, :tiempo_laboral, :pais, :ciudad, :rubro, 
                :nombres_empleador, :cargo_empleador, :correo_empleador
            )";

            $query2 = $this->db->prepare($sql2);
            $query2->execute([
                ':postulante_id' => $this->db->lastInsertId(),
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

            return true;
        } catch (Exception $e) {
            error_log("Error en insertPersonalInfo: " . $e->getMessage()); // Guarda el error en logs
            echo json_encode(["status" => "error", "message" => "Error en BD: " . $e->getMessage()]); //Muestra el error
            return false;
        }
    }
}
