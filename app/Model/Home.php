<?php

namespace Mini\Model;

use Mini\Core\Model;

class Home extends Model
{

    public function getAllPersonas($dni,$cod,$nom)
    {
        $sql   = "SELECT d.cod, d.fec_emision, d.cond, CONCAT(p.nom_per, ' ' , p.ape_per) As nombres, p.nom_per, p.ape_per, p.nro, c.tipo, c.nom_cert, c.horas, DATE_FORMAT(c.fec_cert, '%d-%m-%Y') As fec_cert, c.ciu, c.pais, c.asistencia FROM csa_detalle AS d INNER JOIN csa_persona AS p ON d.id_per = p.id INNER JOIN csa_cert AS c ON d.id_cer = c.id where c.asistencia=1 and p.nro=:dni or c.asistencia=1 and d.cod=:cod or c.asistencia=1 and CONCAT(p.nom_per, ' ' , p.ape_per)=:nom ORDER BY d.fec_emision DESC";
        $query = $this->db->prepare($sql);
        $parameters = array(':dni' => $dni,':cod' => $cod,':nom' => $nom);
        $query->execute($parameters);

        return $query->fetchAll();
    }
    

    public function getAllFirmante()
    {
        $sql   = "SELECT * FROM csa_firmante";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function cargar_modal($id)
    {
        $sql = "SELECT * FROM csa_firmante where id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id);
        $query->execute($parameters);
        return $query->fetchAll();
    }

        public function add_modal($firmante, $cargo, $imagen,$institucion)
    {
        $sql = "INSERT INTO csa_firmante (firmante, cargo, institucion, imagen) VALUES (:firmante,:cargo, :institucion, :imagen)";
        $query = $this->db->prepare($sql);
        $parameters = array(':firmante' => $firmante, ':cargo' => $cargo, ':imagen' => $imagen, ':institucion' => $institucion);

        $query->execute($parameters);
    }

        public function update_modal($id, $firmante, $cargo, $imagen,$institucion)
    {
        $sql = "UPDATE csa_firmante SET firmante = :firmante, cargo = :cargo, institucion = :institucion, imagen = :imagen WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id, ':firmante' => $firmante, ':cargo' => $cargo, ':imagen' => $imagen, ':institucion' => $institucion);
        $query->execute($parameters);
    }

        public function delete_modal($id)
    {
        $sql = "DELETE FROM csa_firmante WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id);

        $query->execute($parameters);
    }

    public function copyTransparent($destination, $imagen)
    {

        $destination = APP.'view/admin/upload/firma/' . $imagen;

    }

    
}
