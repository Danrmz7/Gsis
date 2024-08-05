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
            case 'product_product':
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
            
            default:
                $output .= $this->show_all_rows();
                return $output;
            break;
        }
    }

    /***
     * Funciones SQL
     */

    public function select_products()
    {
        $query = "SELECT * from productos";
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

    public function get_users()
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
        $query = "INSERT INTO usuarios (nombre_producto,correo_usuario,contrasena_usuario,edad_usuario,active) VALUES (?,?,?,?,1)";
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

        if ($this->action == "save_product_form")
            {
                $output .= '
                <div class="card-body">
                comentario
                <div class="container">  
                    <div class="row">
                        <div class="col">
                            <div class="box-body">
                            <form action="./?action=save_user" method="POST">
                                <!-- Entrada para el nombre del producto -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" name="nuevoNombre" placeholder="Ingresar nombre del producto" required>
                                    </div>
                                </div>

                                <!-- Entrada para la categoria -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" name="nuevoCategoria" placeholder="Ingresar categoria" required>
                                    </div>
                                </div>

                                <!-- Entrada para la descripcion -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" name="nuevoDescripcion" placeholder="Ingresar Descripcion" required>
                                    </div>
                                </div>

                                <!-- Entrada para el precio -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" name="nuevoPrecio" placeholder="Ingresar Precio" required>
                                    </div>
                                </div>

                                <!-- Entrada para el Usuario -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control input-lg" name="nuevoPerfil">
                                            <option value="">Seleccionar perfil</option>';
                                            foreach ($this->get_users() as $users)
                                            {
                                                $output .= '<option value="'.$users['id_usuario'].'">'.$users['nombre_usuario'].'</option>';
                                            }
                                        $output .= '
                                        </select>
                                    </div>
                                </div>

                                 <!-- Entrada para la foto -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" name="foto" placeholder="foto" required>
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
        else{
                $output .= '
                '.$alert.'
                <a href = "products.php?action=save_product_form" class="btn btn-primary">Agregar Nuevo Producto</a>
                <div class="container">
                    <div class="table-responsive">
                        <table class = "table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre del producto</th>
                                    <th>Categoria</th>
                                    <th>Descripcion</th>
                                    <th>Precio</th>
                                    <th>Usuario</th>
                                    <th>Foto</th>
                                    <th>Fecha de publicacion</th>
                                </tr>
                            </thead>
                            <tbody>';
                            foreach ($this->select_products() as $product)
                            {
                                $output .= '
                                <tr> 
                                    <td>'.$product['nombre_producto'].'</td>
                                    <td>'.$product['id_categoria'].'</td>
                                    <td>'.$product['descripcion_producto'].'</td>
                                    <td>'.$product['precio_producto'].'</td>
                                    <td>'.$product['id_usuario'].'</td>
                                    <td>'.$product['id_foto'].'</td>
                                    <td>'.$product['fecha_pub_producto'].'</td>
                                    <td>
                                       <!-- <a href = "./?action=update_user_form&&id_user='.$user['id_producto'].'" class="btn btn-info btn-sm">Editar Usuario</a> 
                                       <a href = "./?action=delete_user_form&&id_user='.$user['id_usuario'].'" class="btn btn-danger btn-sm">Eliminar Usuario</a> -->
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