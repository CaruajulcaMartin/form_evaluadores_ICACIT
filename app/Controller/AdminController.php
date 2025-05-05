<?php

namespace Mini\Controller;
// use Mini\Model\Admin;
use Mini\Model\Persona;

class AdminController
{
    //mostrar la vista del home principal y obtener el id del usuario
    // public function Home()
    // {
    //     session_start();
    //     if (!isset($_SESSION['user_id'])) {
    //         header('Location: ' . URL . 'login');
    //         exit;
    //     }

    //     $userId = $_SESSION['user_id'];
    //     $personaModel = new Persona();
    //     $user = $personaModel->getUserById($userId);

    //     if (!$user) {
    //         header('Location: ' . URL . 'login');
    //         exit;
    //     }

    //     $data = [
    //         'user' => $user
    //     ];

    //     require APP . 'view/_templates/header_adm.php';
    //     require APP . 'view/admin/home_principal.php';
    // }

    //home del formulario
    public function HomeFormulario()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $personaModel = new Persona();
        $user = $personaModel->getUserById($userId);
        $progress = $personaModel->getProgressByUserId($userId);

        if (!$user) {
            header('Location: ' . URL . 'login');
            exit;
        }

        // Si no hay progreso registrado, inicializa el progreso
        if (!$progress) {
            $personaModel->initializeProgress($userId);
            $progress = $personaModel->getProgressByUserId($userId);
        }

        $data = [
            'user' => $user,
            'progress' => $progress
        ];

        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/admin/home_formulario.php';
        // require APP . 'view/formulario/seccion1.php';
    }

}
