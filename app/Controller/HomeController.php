<?php

namespace Mini\Controller;

use Mini\Model\Home;
use Exception;

class HomeController
{
    private $model;

    public function index()
  {
      $Home = new Home();
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
            // Se omite la validación ya que FormularioValidator no está implementado
            $postulante = $_POST;
            
            // Manejo seguro de archivos
            $postulante['foto_perfil'] = $this->procesarArchivo($_FILES['fotoPerfil'] ?? null, 'uploads/fotos/');
            $postulante['pdf_documento_identidad'] = $this->procesarArchivo($_FILES['pdfDocumentoIdentidad'] ?? null, 'uploads/documentos/');
            
            // Guardar postulante en la base de datos
            if ($this->model->insertPersonalInfo($postulante)) {
                echo json_encode(["status" => "success", "message" => "Formulario enviado con éxito"]);
            } else {
                throw new Exception("Error al guardar los datos");
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    private function procesarArchivo($archivo, $directorio)
    {
        if (!$archivo || empty($archivo['name'])) {
            return null;
        }

        $permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        $tipoArchivo = mime_content_type($archivo['tmp_name']);

        if (!in_array($tipoArchivo, $permitidos)) {
            throw new Exception("Formato de archivo no permitido");
        }

        if ($archivo['size'] > 5 * 1024 * 1024) { // 5MB máximo
            throw new Exception("El archivo es demasiado grande");
        }

        $nombreArchivo = uniqid() . "_" . basename($archivo['name']);
        $rutaDestino = $directorio . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            throw new Exception("Error al subir el archivo");
        }

        return $rutaDestino;
    }
}
