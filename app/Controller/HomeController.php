<?php

namespace Mini\Controller;
use Mini\Model\Home;

class HomeController
{
    public function index()
    {
        require APP . 'view/_templates/header_adm.php';
        $taxonomia = 'active';
        $menutax='menu-open';
        $firmante = 'active';
        require APP . 'view/_templates/main_menu.php';
        require APP . 'view/home/index.php';
        require APP . 'view/_templates/footer_firmante.php';
    }


    public function grid()
    {
        $Firmante = new Home();
        $rs           = $Firmante->getAllFirmante();
        $tabla = "";
        
        foreach ($rs as $row) {
        $accion="<button type='button' name='update' id='".$row->id."'class='btn btn-success btn-sm update' data-toggle='modal'><span class='fas fa-pencil-alt'></span></button>";
        $image = "<img src='".URL."app/view/admin/upload/firma/".$row->imagen."' class='img-thumbnail' width='65'/>";

            $tabla.='{
                      "id":"'.$row->id.'",
                      "firmante":"'.$row->firmante.'",
                      "cargo":"'.$row->cargo.'",
                      "institucion":"'.$row->institucion.'",
                      "imagen":"'.$image.'",
                      "accion":"'.$accion.'"
                    },';        
        }   
        //eliminamos la coma que sobra
        $tabla = substr($tabla,0, strlen($tabla) - 1);
        echo '{"data":['.$tabla.']}';

    }

        public function update_modal()
    { 
        if(isset($_POST["user_id"]))
        {
            $output = array();
            $id=$_POST["user_id"];
            
            $Firmante = new Home();
            $rs = $Firmante->cargar_modal($id);
            foreach($rs as $row)
            {
                $output["nom_ape"] = $row->firmante;
                $output["cargo"] = $row->cargo;
                $output["institucion"] = $row->institucion;
                if($row->imagen != '')
                {
                  $output['file'] = "<img src='".URL."app/view/admin/upload/firma/".$row->imagen."' class='img-thumbnail' width='78'/> <input type='hidden' name='hidden_user_image' value='".$row->imagen."' />";
                }else
                {
                  $output['file'] = '<input type="hidden" name="hidden_user_image" value="" />';
                }
            }
            echo json_encode($output);
        }
    }
    
    public function add_edit()
    { 
        if(isset($_POST["operation"]))
        {
            if($_POST["operation"] == "Add")
            {

                $image = '';
                  if($_FILES["file"]["name"] != '')
                  {
                    $extension = explode('.', $_FILES['file']['name']);
                    $imagen= rand() . '.' . $extension[1];
                    $destination = APP.'view/admin/upload/firma/' . $imagen;
                    move_uploaded_file($_FILES['file']['tmp_name'], $destination);
                    //redimensionar la imagen solo PNG
                    $imagen= "csa_".$imagen;
                    $Firmante = new Home();
                    $Firmante->copyTransparent($destination,$imagen);
                  }
                    $firmante=$_POST["nom_ape"];
                    $cargo=$_POST["cargo"];
                    $institucion=$_POST["institucion"];
                    $Firmante = new Home();
                    $firma=$Firmante->add_modal($firmante, $cargo, $imagen,$institucion);
                    echo 'Datos Insertados';
            }
            if($_POST["operation"] == "Edit")
            {
                $image = '';
                  if($_FILES["file"]["name"] != '')
                  {
                    $extension = explode('.', $_FILES['file']['name']);
                    $imagen = rand() . '.' . $extension[1];
                    $destination = APP.'view/admin/upload/firma/' . $imagen;
                    move_uploaded_file($_FILES['file']['tmp_name'], $destination);
                    //redimensionar la imagen solo PNG
                    $imagen= "csa_".$imagen;
                    $Firmante = new Home();
                    $Firmante->copyTransparent($destination,$imagen);
                  }
                  else
                  {
                    $imagen = $_POST["hidden_user_image"];
                  }

                $id=$_POST["user_id"];
                $firmante=$_POST["nom_ape"];
                $cargo=$_POST["cargo"];
                $institucion=$_POST["institucion"];
                $Firmante = new Home();
                $firma=$Firmante->update_modal($id,$firmante, $cargo, $imagen,$institucion);
                
                echo 'Datos Actualizados';
            }
        }
    }

    public function delete_grid()
    {
        if(isset($_POST["user_id"]))
        {
        $id=$_POST["user_id"];
        $Firmante = new Home();
        $firma=$Firmante->delete_modal($id);
        echo 'Registro Eliminado';
        }
    }
    
}
