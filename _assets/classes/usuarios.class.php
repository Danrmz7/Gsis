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
                //
                if($this->insert_users())
                {
                    $alert = '
                    <div class="alert alert-success">
                        <strong>Success!</strong> Usuario Agregado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
                else
                {
                    $alert = '
                   <div class="alert alert-danger">
                    <strong>Danger!</strong> Error al agregar usuario
                    </div> ';
                    //
                    $output .= $this->show_all_rows();
                    return $output;
                    
                }
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

    public function insert_users()
    {
        $query = "INSERT INTO usuarios (NOMBRE,CORREO,CONTRASENA,EDAD,active) VALUES (?,?,?,?,1)";
        $params_query = array(
            $this->postData['nuevoNombre'],
            $this->postData['nuevoUsuario'],
            $this->postData['nuevoPassword'],
            $this->postData['nuevoEdad']

        );

        if($this->sql->insert($query, $params_query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /***
     * FIN de Funciones SQL
     */

    public function show_all_rows($alert='')
    {
        if ($this->action == "save_user_form")
        {
            $output .= '
            <div class="card-body">
            comentario
            <div class="container">  
                <div class="row">
                    <div class="col">
                        <div class="box-body">
                        <form action="./?action=save_user" method="POST">
                            <!-- Entrada para el nombre -->
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control input-lg" name="nuevoNombre" placeholder="Ingresar nombre" required>
                                </div>
                            </div>

                            <!-- Entrada para el usuario -->
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control input-lg" name="nuevoUsuario" placeholder="Ingresar usuario" required>
                                </div>
                            </div>

                            <!-- Entrada para el edad -->
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control input-lg" name="nuevoEdad" placeholder="Ingresar Edad" required>
                                </div>
                            </div>

                            <!-- Entrada para la contraseña -->
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" class="form-control input-lg" name="nuevoPassword" placeholder="Ingresar contraseña" required>
                                </div>
                            </div>

                            <!-- Entrada para seleccionar perfil 
                            <div class="form-group">
                                <div class="input-group">
                                    <select class="form-control input-lg" name="nuevoPerfil">
                                        <option value="">Seleccionar perfil</option>
                                        <option value="Administrador">Administrador</option>
                                        <option value="Especial">Especial</option>
                                        <option value="Vendedor">Vendedor</option>
                                    </select>
                                </div>
                            </div> -->
                            <!-- Botón de enviar -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>';


        }
        else
        {
            $output .= '
            '.$alert.'
            <a href = "./?action=save_user_form" class="btn btn-primary">Agregar Nuevo Usuario</a>
            <div class="container">
                <div class="table-responsive">
                    <table class = "table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Contraseña</th>
                                <th>Edad</th>
                                <th>Editar</th>
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
                                <td><a href = "./?action=update_user_form&&id_user='.$user['ID'].'" class="btn btn-info">Editar Usuario</a></td>
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