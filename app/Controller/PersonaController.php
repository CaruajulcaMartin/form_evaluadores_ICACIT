<?php

namespace Mini\Controller;

use Mini\Model\Persona;
use Mini\Model\Home;

class PersonaController
{
    //funcion de inicio de sesion
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : null;
            $password = isset($_POST['password']) ? trim($_POST['password']) : null;

            if (empty($email) || empty($password)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Por favor, completa todos los campos.'
                ]);
                return;
            }

            $personaModel = new Persona();
            $user = $personaModel->verifyCredentials($email, $password);

            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id']; // Guarda el ID del usuario en la sesión
                //$_SESSION['formulario_enviado'] = $user->formulario_enviado;
                // Verificar estado del formulario

                $homeModel = new Home();
                $formularioEnviado = $homeModel->verificarEnvio($user['id']);
                $_SESSION['formulario_enviado'] = $formularioEnviado;

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Inicio de sesión exitoso.',
                    'formulario_enviado' => $formularioEnviado
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Correo o contraseña incorrectos.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
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

}