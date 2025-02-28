<?php

namespace Mini\Controller;

use Mini\Model\Home;
use Exception;

class HomeController
{
    private $model;

    public function __construct()
    {
        $this->model = new Home();
    }

    public function index()
    {
        require APP . 'view/_templates/header_adm.php';
        require APP . 'view/home/index.php';
        require APP . 'view/_templates/footer_firmante.php';
    }

    public function enviarFormulario()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["status" => "error", "message" => "Método no permitido"]);
        return;
    }

    try {
        $postulante = $_POST;
        $numDoc = $_POST["numDoc"];

        if (empty($numDoc)) {
            throw new Exception("Número de documento requerido");
        }

        // Procesar archivos subidos
        $fotoPerfil = $this->procesarArchivo($_FILES['fotoPerfil'] ?? null, APP . 'view/admin/upload/foto_perfil/', "foto_perfil_$numDoc");
        $pdfDocumento = $this->procesarArchivo($_FILES['pdfDocumentoIdentidad'] ?? null, APP . 'view/admin/upload/documentos/', "pdf_documento_identidad_$numDoc");

        // Agregar los nombres de los archivos al arreglo de datos del formulario
        $postulante['fotoPerfil'] = $fotoPerfil;
        $postulante['pdfDocumentoIdentidad'] = $pdfDocumento;

        // Llamar al método del modelo para insertar todos los datos
        $resultado = $this->model->insertPersonalInfo($postulante);

        if ($resultado) {
            echo json_encode(["status" => "success", "message" => "Formulario enviado con éxito"]);
        } else {
            throw new Exception("Error al guardar los datos en la base de datos");
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    // Imprimir los datos del postulante (para depuración)
    // print_r($postulante);
}

    private function procesarArchivo($archivo, $directorio, $nombreBase)
    {
        if (!$archivo || empty($archivo['name'])) {
            return '';
        }

        $permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        $tipoArchivo = mime_content_type($archivo['tmp_name']);

        if (!in_array($tipoArchivo, $permitidos)) {
            throw new Exception("Formato de archivo no permitido");
        }

        if ($archivo['size'] > 5 * 1024 * 1024) { // Máximo 5MB
            throw new Exception("El archivo es demasiado grande");
        }

        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = $nombreBase . '.' . $extension;
        $rutaDestino = rtrim($directorio, '/') . '/' . $nombreArchivo;

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            throw new Exception("Error al subir el archivo");
        }

        return $nombreArchivo; // Guardamos solo el nombre del archivo en la base de datos
    }
}
