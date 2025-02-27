<?php

namespace Mini\Controller;
use Mini\Model\Home;

class HomeController
{
  public function index()
  {
      $Home = new Home();
      $personas = $Home->getAllPersonas();
      require APP . 'view/_templates/header.php';
      require APP . 'view/home/index.php';
      require APP . 'view/_templates/footer.php';
  }
  public function cargar_modal($id)
  {
      $Home = new Home();
      $firmante = $Home->cargar_modal($id);
      require APP . 'view/_templates/header.php';
      require APP . 'view/home/modal.php';
      require APP . 'view/_templates/footer.php';
  }
  public function add_modal()
  {
      if (isset($_POST["add_modal"])) {
          $firmante = $_POST["firmante"];
          $cargo = $_POST["cargo"];
          $imagen = $_POST["imagen"];
          $institucion = $_POST["institucion"];
          $Home = new Home();
          $Home->add_modal($firmante, $cargo, $imagen, $institucion);
      }
      header('location: ' . URL . 'home/index');
  }
  public function update_modal()
  {
      if (isset($_POST["update_modal"])) {
          $id = $_POST["id"];
          $firmante = $_POST["firmante"];
          $cargo = $_POST["cargo"];
          $imagen = $_POST["imagen"];
          $institucion = $_POST["institucion"];
          $Home = new Home();
          $Home->update_modal($id, $firmante, $cargo, $imagen, $institucion);
      }
      header('location: ' . URL . 'home/index');
  }
  public function delete_modal($id)
  {
      if (isset($id)) {
          $Home = new Home();
          $Home->delete_modal($id);
      }
      header('location: ' . URL . 'home/index');
  }
  public function copyTransparent($destination, $imagen)
  {
      $Home = new Home();
      $Home->copyTransparent($destination, $imagen);
  }
}
