<?php

namespace Mini\Libs;

class Helper
{
    /**
* debugPDO
* *
* Muestra la consulta SQL emulada en una declaración PDO. Lo que hace es extremadamente simple, pero poderoso:
* Combina consulta bruta y espacios reservados. Ciertamente no es realmente perfecto (ya que PDO es más complejo que solo
* Combinando consulta y argumentos en bruto), pero hace el trabajo.
* *
* @autor Carlos Sánchez
* @param string $raw_sql
* @param array $parameters
* @cadena de retorno
     */
    static public function debugPDO($raw_sql, $parameters) {

        $keys = array();
        $values = $parameters;

        foreach ($parameters as $key => $value) {

// comprueba si se utilizan los parámetros con nombre (': param') o los parámetros anónimos ('?')
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

// trae el parámetro al formato legible por humanos
            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            } elseif (is_array($value)) {
                $values[$key] = implode(',', $value);
            } elseif (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }

        /*
        echo "<br> [DEBUG] Keys:<pre>";
        print_r($keys);

        echo "\n[DEBUG] Values: ";
        print_r($values);
        echo "</pre>";
        */

        $raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);

        return $raw_sql;
    }

}
