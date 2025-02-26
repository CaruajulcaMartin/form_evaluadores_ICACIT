<?php

namespace Mini\Core;

use PDO;

class Model
{
    /**
     * @var Conexión de base de datos nula
     */
    public $db = null;

    /**
     * Cada vez que se crea el modelo, abra una conexión a la base de datos.
     */
    function __construct()
    {
        try {
            self::openDatabaseConnection();
        } catch (\PDOException $e) {
            exit('No se pudo establecer la conexión de la base de datos.');
        }
    }

    /**
     * Abra la conexión de la base de datos con las credenciales de la aplicación. /config/config.php
     */
    private function openDatabaseConnection()
    {
        // configura las opciones de conexión PDO. En este caso, establecemos el modo de búsqueda en
        // "objects", lo que significa que todos los resultados serán objetos, como este: $result->user_name !
        // Por ejemplo, fetch mode FETCH_ASSOC retornaria resultados como este: $result["user_name] !
        // @see http://www.php.net/manual/en/pdostatement.fetch.php
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        
        // establecer la codificación es diferente cuando se usa PostgreSQL
        if (DB_TYPE == "pgsql") {
            $databaseEncodingenc = " options='--client_encoding=" . DB_CHARSET . "'";
        } else {
            $databaseEncodingenc = "; charset=" . DB_CHARSET;
        }
//array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        // generar una conexión de base de datos utilizando el conector PDO
        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
        $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . $databaseEncodingenc, DB_USER, DB_PASS, $options);
    }
}
