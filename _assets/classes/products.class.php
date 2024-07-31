<?php
/**
 * File Name: Products
 * Creator URI: 
 * Description: Main configuration file
 * Author: 
 * Version: 1.1
 * Author URI: 
 */
class Products {

    var $sql;
    var $mail;
    var $current_user;
    var $output;
    var $alert;
  
    /*---------------------------------*/
    public function __construct($sql/*, $getCurrentUser*/){
      
      $this->sql        = $sql;
      //$this->_user      = $getCurrentUser;
    }
  
    /** 
    * @param action
    * @return null
    */
    public function setData(){
      
      $this->postData       = BlockXSS::sanitizes($_POST);
      $this->getData        = BlockXSS::sanitizes($_GET);
      $this->requestData    = BlockXSS::sanitizes($_REQUEST);
      $this->action         = $this->getData['action'];  
  
    }

    public function process()
    {
        switch($this->action){
            case 'save_user':
            break;
            
            default:
                $output .= $this->show_all_rows();
                return $output;
            break;
        }
    }

    /***
     * Funciones SQL
     */



    /***
     * FIN de Funciones SQL
     */

    public function show_all_rows($alert='')
    {
        $output .= '
         <!--<a href = "./?action=save_user_form" class="btn btn-primary">Agregar Nuevo Usuario</a>-->
            <div class="container">
                <div class="table-responsive">
                    <table class = "table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nombre del producto</th>
                                <th>Categoria</th>
                                <th>Descripcion</th>
                                <th>Precio</th>
                                <th>id-usuario</th>
                                <th>Foto</th>
                                 <th>Fecha de publicacion</th>
                            </tr>
                        </thead>          
                </div>
            </div>
                        <tbody>
                            <tr> 
                                <td>'.$user['nombre_producto'].'</td>
                                <td>'.$user['id_categoria'].'</td>
                                <td>'.$user['descripcion_producto'].'</td>
                                <td>'.$user['precio_producto'].'</td>
                                <td>'.$user['id_usuario'].'</td>
                                <td>'.$user['id_foto'].'</td>
                                <td>'.$user['fecha_pub_producto'].'</td>
                               <!-- <td>
                                    <a href = "./?action=update_user_form&&id_user='.$user['id_usuario'].'" class="btn btn-info btn-sm">Editar Usuario</a>
                                    <a href = "./?action=delete_user_form&&id_user='.$user['id_usuario'].'" class="btn btn-danger btn-sm">Eliminar Usuario</a>
                                </td> -->
                            </tr>';
        return $output;
    }
    
}

?>