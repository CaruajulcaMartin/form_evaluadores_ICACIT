<?php

/**
 * Class Error
 *
 */

namespace Mini\Controller;

class ErrorController
{
    public function index()
    {
        // Carregar a view error
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/error/index.php';
        require APP . 'view/_templates/footer_firmante.php';
    }
}
