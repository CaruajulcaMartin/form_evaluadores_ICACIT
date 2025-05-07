<?php

namespace Mini\Controller;

use Exception;

use Mini\Model\Persona;
use Mini\Model\Home;

class PersonaController
{
    //funcion de inicio de sesion
    public function login()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Establecer headers primero y suprimir salidas no deseadas
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        ob_start(); // Iniciar buffer de salida

        try {
            if (!isset($_SESSION)) {
                session_start();
            }

            $response = ['status' => 'error', 'message' => ''];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = isset($_POST['email']) ? trim($_POST['email']) : null;
                $password = isset($_POST['password']) ? trim($_POST['password']) : null;
                $verify = isset($_POST["verify"]) ? $_POST["verify"] : "";

                if (empty($email) || empty($password)) {
                    $response['message'] = 'Por favor, completa todos los campos.';
                    ob_end_clean(); // Limpiar buffer
                    echo json_encode($response);
                    exit;
                }

                // Verificar CAPTCHA primero
                if (!isset($_SESSION["captcha"]) || $verify != $_SESSION["captcha"]) {
                    $response['message'] = 'Captcha incorrecto o expirado.';
                    ob_end_clean();
                    echo json_encode($response);
                    exit;
                }

                $personaModel = new Persona();
                $user = $personaModel->verifyCredentials($email, $password);

                if ($user) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];

                    $homeModel = new Home();
                    $formularioEnviado = $homeModel->verificarEnvio($user['id']);
                    $_SESSION['formulario_enviado'] = $formularioEnviado;

                    $response['status'] = 'success';
                    $response['message'] = 'Inicio de sesión exitoso.';
                    $response['formulario_enviado'] = $formularioEnviado;
                } else {
                    $response['message'] = 'Correo o contraseña incorrectos.';
                }
            } else {
                $response['message'] = 'Método no permitido.';
            }

            ob_end_clean();
            echo json_encode($response);
            exit;
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode([
                'status' => 'error',
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    //funcion de registro
    public function register()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : null;
            $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : null;
            $motherLastName = isset($_POST['motherLastName']) ? trim($_POST['motherLastName']) : null;
            $email = isset($_POST['email']) ? trim($_POST['email']) : null;
            $password = isset($_POST['password']) ? trim($_POST['password']) : null;
            $vrandom = '?' . bin2hex(random_bytes(16));

            if (empty($name) || empty($lastName) || empty($motherLastName) || empty($email) || empty($password)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Por favor, completa todos los campos.'
                ]);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El formato del correo electrónico no es válido.'
                ]);
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $personaModel = new Persona();
            $result = $personaModel->registerUser($name, $lastName, $motherLastName, $email, $hashedPassword, $vrandom);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Usuario registrado exitosamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El correo electrónico ya está registrado.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
        }
    }

    // funcion de recuperar contraseña
    // public function recuperarPassword(){}
}
