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
                break;
            case 'update_user':
                if($this->update_users())
                {
                    $alert = '
                    <div class="alert alert-success">
                        <strong>Success!</strong> Usuario Actualizado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
                break;
            case 'delete_user':
                if($this->delete_users())
                {
                    $alert = '
                    <div class="alert alert-success">
                        <strong>Success!</strong> Usuario Eliminado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                        
                 }else
                {
                    $alert = '
                   <div class="alert alert-danger">
                    <strong>Danger!</strong> Error al eliminar usuario
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
        $query = "INSERT INTO usuarios (nombre_usuario,correo_usuario,contrasena_usuario,edad_usuario,active) VALUES (?,?,?,?,1)";
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

    public function Get_users_info()
    {
        $query = "SELECT * from usuarios Where id_usuario = ?" ;
        $params_query = array($this->getData['id_user']);

        if($rs = $this->sql->select($query, $params_query))
        {
            return $rs;
        }
        else
        {
            return false;
        }
    }

    public function update_users()
    {
        
        $query = "UPDATE usuarios SET nombre_usuario = ?, correo_usuario = ?, contrasena_usuario = ?, edad_usuario = ? Where id_usuario = ?";
        $params_query = array(
            $this->postData['putNombre'],
            $this->postData['putUsuario'],
            $this->postData['putPassword'],
            $this->postData['putEdad'],
            $this->postData['id_user']
        );
        

        if($this->sql->update($query, $params_query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function delete_users()
    {
        
        $query = "DELETE FROM usuarios Where id_usuario = ?";
        $params_query = array($this->postData['id_user']);
        

        if($this->sql->delete($query, $params_query))
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
        if ($this->action == "delete_user_form"){
             $output .= '
            <form action="./?action=delete_user" method="POST">
                <h1>¿Estás Seguro Que Desea Eliminar a este usuario?</h1>
                    <input value="'.$this->getData['id_user'].'" type="hidden" name="id_user" >
                    <div class="form-group">
                        <div class="input-group">
                            <button type="submit" class="btn btn-warning">Aceptar</button>
                            <a href="javascript:history.back()" class="btn btn-primary">No, Volver</a>
                        </div>
                    </div>   
            </form>';
        }
        else if ($this->action == "update_user_form"){
            $output .= '
            <div class="card-body">
            update
            <div class="container">  
                <div class="row">
                    <div class="col">
                        <div class="box-body">';
                            foreach ($this->Get_users_info() as $user){
                            $output .= '
                                <form action="./?action=update_user" method="POST">
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
            }

            else if ($this->action == "save_user_form")
            {
                $output .= '
                <div class="card-body">
              
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

                                <!-- Entrada para el correo -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="email" class="form-control input-lg" name="nuevoUsuario" placeholder="Ingresar correo" required>
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
            <a href = "./?action=save_user_form" class="btn btn-primary">Agregar Nuevo Vendedor</a>
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
                                <td>'.$user['nombre_usuario'].'</td>
                                <td>'.$user['correo_usuario'].'</td>
                                <td>'.$user['contrasena_usuario'].'</td>
                                <td>'.$user['edad_usuario'].'</td>
                                <td>
                                    <a href = "./?action=update_user_form&&id_user='.$user['id_usuario'].'" class="btn btn-info btn-sm">Editar vendedores</a>
                                    <a href = "./?action=delete_user_form&&id_user='.$user['id_usuario'].'" class="btn btn-danger btn-sm">Eliminar vendedores</a>
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