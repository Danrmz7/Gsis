<?php
/**
 * File Name: Usuarios
 * Creator URI: 
 * Description: Main configuration file
 * Author: 
 * Version: 1.1
 * Author URI: 
 */
class Usuarios {

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
      
      $this->postData    = BlockXSS::sanitizes($_POST);
      $this->getData     = BlockXSS::sanitizes($_GET);
      $this->requestData = BlockXSS::sanitizes($_REQUEST);
      $this->action      = $this->getData['action'];  
  
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

    public function select_users()
    {
        $query = "SELECT * from usuarios";
        $params_query = array();

        if($rs = $this->sql->select($query, $params_query))
        {
            return $rs;
        }
        else
        {
            return false;
        }
    }

    public function show_all_rows()
    {
        if ($this->action == "save_user_form")
        {
            $output .= '
                <div class="card">
                    
                    <div class = "card-body">           
                    Comentario
                    </div>

                </div>

            ';


        }else{
            $output .= '
            <a href = "./?action=save_user_form">Agregar Nuevo Usuario</a>
        <div class="container">
            <div class="table-responsive">
                <table class = "table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Contraseña</th>
                            <th>Edad</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach ($this->select_users() as $user)
                    {
                        $output .= '
                        <tr> 
                            <td>'.$user['NOMBRE'].'</td>
                            <td>'.$user['CORREO'].'</td>
                            <td>'.$user['CONTRASENA'].'</td>
                            <td>'.$user['EDAD'].'</td>
                        </tr>';
                    }
                    $output .= '
                    </tbody>
                </table>
            </div>
        </div>
        ';
        }
        return $output;
    }
    
}

?>