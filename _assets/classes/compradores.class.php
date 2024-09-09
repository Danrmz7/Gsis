<?php
/**
 * File Name: Usuarios
 * Creator URI: 
 * Description: Main configuration file
 * Author: 
 * Version: 1.1
 * Author URI: 
 */
class compradores {

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

            case 'save_buyer':
                if($this->insert_buyers())
                {
                    $alert = '
                    <div class="alert alert-success">
                        <strong>Success!</strong> Producto Agregado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
                else
                {
                    $alert = '
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Producto NO Agregado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
            break;
            
            /*case 'update_buyer':
                if($this->insert_buyers())
                {
                    $alert = '
                    <div class="alert alert-success">
                        <strong>Success!</strong> Producto Agregado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
                else
                {
                    $alert = '
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Producto NO Agregado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
            break;*/
                
            
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
         $query = "SELECT * from compradores";
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

     public function insert_buyers()
     {
         $query = "INSERT INTO compradores (nombre_comprador,correo_comprador,contrasena_comprador,dino_coins,active) VALUES (?,?,?,?,1)";
         $params_query = array(
             $this->postData['nuevoNombre'],
             $this->postData['nuevoUsuario'],
             $this->postData['nuevoPassword'],
             $this->postData['nuevoCoins']
 
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
        //if ($this->action == "update_buyer_form"){
            /*$output .= '
            <div class="card-body">
            update
            <div class="container">  
                <div class="row">
                    <div class="col">
                        <div class="box-body">';
                            foreach ($this->Get_users_info() as $user){
                            $output .= '
                                <form action="buyers.php?action=update_buyer" method="POST">
                                <input value="'.$user['id_usuario'].'" type="hidden" name="id_user" >
                                <!-- Entrada para el nombre -->
                                <div class="form-group">
                                    <div class="input-group">
                                        Nombre:<br><br>
                                        <input value="'.$user['nombre_usuario'].'" type="text" class="form-control input-lg" name="putNombre" placeholder="Actualizar nombre" required>
                                    </div>
                                </div>

                                <!-- Entrada para el correo -->
                                <div class="form-group">
                                    <div class="input-group">
                                    Correo:<br><br>
                                        <input value="'.$user['correo_usuario'].'" type="email" class="form-control input-lg" name="putUsuario" placeholder="Actualizar usuario" required>
                                    </div>
                                </div>

                                <!-- Entrada para la contraseña -->
                                <div class="form-group">
                                    <div class="input-group">
                                    Contraseña:<br><br>
                                    <input value="'.$user['contrasena_usuario'].'" type="text" class="form-control input-lg" name="putPassword" placeholder="Actualizar contraseña" required>
                                    </div>
                                </div>

                                <!-- Entrada para el edad -->
                                <div class="form-group">
                                    <div class="input-group">
                                    Edad:<br><br>
                                    <input value="'.$user['edad_usuario'].'" type="text" class="form-control input-lg" name="putEdad" placeholder="Actualizar Edad" required>
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
                                        </form>';      
                            }
                            
                                $output .='
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
            }*/

         if ($this->action == "save_buyer_form")
            {
                $output .= '
                <div class="card-body">
                <div class="container">  
                    <div class="row">
                        <div class="col">
                            <div class="box-body">
                            <form action="buyers.php?action=save_buyer" method="POST">
                                <!-- Entrada para el nombre -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" name="nuevoNombre" placeholder="Ingresar usuario" required>
                                    </div>
                                </div>

                                <!-- Entrada para el correo -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="email" class="form-control input-lg" name="nuevoUsuario" placeholder="Ingresar correo" required>
                                    </div>
                                </div>

                                 <!-- Entrada para la contraseña -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="password" class="form-control input-lg" name="nuevoPassword" placeholder="Ingresar contraseña" required>
                                    </div>
                                </div>

                                <!-- Entrada para los dinocoins -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" name="nuevoCoins" placeholder="Ingresar dinocoins" required>
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
                <a href = "buyers.php?action=save_buyer_form" class="btn btn-primary">Agregar Nuevo comprador</a>
                <div class="container">
                    <div class="table-responsive">
                        <table class = "table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Contraseña</th>
                                    <th>Monedas</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                                foreach ($this->select_users() as $user)
                                {
                                    $output .= '
                                    <tr> 
                                        <td>'.$user['nombre_comprador'].'</td>
                                        <td>'.$user['correo_comprador'].'</td>
                                        <td>'.$user['contrasena_comprador'].'</td>
                                        <td>'.$user['dino_coins'].' Dinocoins</td>
                                        <td>
                                            <a href = "buyers.php?action=update_buyer_form&&id_comprador='.$user['id_comprador'].'" class="btn btn-info btn-sm">Editar Usuario</a>
                                            <a href = "buyers.php?action=delete_buyer_form&&id_comprador='.$user['id_comprador'].'" class="btn btn-danger btn-sm">Eliminar Usuario</a>
                                        </td>
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