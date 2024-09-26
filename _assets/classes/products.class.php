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
            case 'save_product':
                if($this->insert_products())
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

            case 'update_product':
                if($this->update_products())
                {
                    $alert = '
                    <div class="alert alert-success">
                        <strong>Success!</strong> Usuario Actualizado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
                else{
                $alert = '
                <div class="alert alert-warning">
                    <strong>warning jg!</strong> Producto no agregado
                </div> ';
                $output.= $this->show_all_rows($alert);
                return $output;
                }
            break;

            case 'delete_product':
                if($this->delete_products())
                {
                    $alert = '
                    <div class="alert alert-success">
                        <strong>Success!</strong> Usuario Eliminado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;

                }
            break;
            
            default:
                $output.= $this->show_all_rows();
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

    public function get_product_category($id_cat)
    {
        $query = "SELECT * from categorias where id_categoria = ?";
        $params_query = array($id_cat);

        if($rs = $this->sql->select($query, $params_query))
        {
            return $rs[0];
        }
        else
        {
            return false;
        }
    }

    public function get_product_user($id_us)
    {
        $query = "SELECT * from usuarios where id_usuario = ?";
        $params_query = array($id_us);

        if($rs = $this->sql->select($query, $params_query))
        {
            return $rs[0];
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

    public function get_categories()
    {
        $query = "SELECT * from categorias";
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

    public function get_products_info()
    {
        $query = "SELECT * from productos Where id_producto = ?";
        $params_query = array($this->getData['id_product']);

        if($rs = $this->sql->select($query, $params_query))
        {
            return $rs;
        }
        else
        {
            return false;
        }
    }

    //  Descartado hasta que podamos subir archivos con código
    /*public function insert_products()
    {
        $query = "INSERT INTO productos (nombre_producto,id_categoria,descripcion_producto,precio_producto,id_usuario,foto_producto) VALUES (?,?,?,?,?,?)";
        $params_query = array(
            $this->postData['nuevoProducto'],
            $this->postData['nuevoCategoria'],
            $this->postData['nuevoDescripcion'],
            $this->postData['nuevoPrecio'],
            $this->postData['nuevoPerfil'],
            $_FILES["foto_producto"]["name"]
        );

        $target_dir = "_assets/img/productos/"; //directorio en el que se subira
        $target_file = $target_dir . basename($_FILES["foto_producto"]["name"]);//se añade el directorio y el nombre del archivo
        $uploadOk = 1;//se añade un valor determinado en 1
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
        {
            if (move_uploaded_file($_FILES["foto_producto"]["tmp_name"], $target_file))
            {
                if($this->sql->insert($query, $params_query))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
        
        
    }*/

    public function insert_products()
    {
        $query = "INSERT INTO productos (nombre_producto,id_categoria,descripcion_producto,precio_producto,id_usuario,foto_producto) VALUES (?,?,?,?,?,?)";
        $params_query = array(
            $this->postData['nuevoProducto'],
            $this->postData['nuevoCategoria'],
            $this->postData['nuevoDescripcion'],
            $this->postData['nuevoPrecio'],
            $this->postData['nuevoPerfil'],
            $this->postData['foto_producto']
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

    public function update_products()
    {
        
        $query = "UPDATE productos SET nombre_producto = ?, id_categoria = ?, descripcion_producto = ?, precio_producto = ? Where id_producto = ?";
        $params_query = array(
            $this->postData['putNombre'],
            $this->postData['nuevoCategoria'],
            $this->postData['putdescripcion'],
            $this->postData['putprecio'],
            $this->postData['id_product']
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

    public function delete_products()
    {
        
        $query = "DELETE FROM productos Where id_producto = ?";
        $params_query = array($this->postData['id_product']);
        

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
        if ($this->action == "delete_product_form"){
            $output .= '
           <form action="products.php?action=delete_product" method="POST">
               <h1>¿Estás Seguro Que Desea Eliminar a este usuario?</h1>
                   <input value="'.$this->getData['id_product'].'" type="hidden" name="id_product" >
                   <div class="form-group">
                       <div class="input-group">
                           <button type="submit" class="btn btn-warning">Aceptar</button>
                           <a href="javascript:history.back()" class="btn btn-primary">No, Volver</a>
                       </div>
                   </div>   
           </form>';
       }
        else if ($this->action == "update_product_form"){

            $output .= '
                <div class="card-body">
                update
                <div class="container">  
                    <div class="row">
                        <div class="col">
                            <div class="box-body">';
                                foreach ($this->Get_products_info() as $product){
                                $output .= '
                                        <form action="products.php?action=update_product" method="POST">
                                        <input value="'.$product['id_producto'].'" type="hidden" name="id_product" >

                                    <!-- Entrada para el nombre del producto -->
                                    <div class="form-group">
                                        Nombre:
                                        <div class="input-group">
                                            <input value="'.$product['nombre_producto'].'" type="text" class="form-control input-lg" name="putNombre" placeholder="Actualizar nombre" required>
                                        </div>
                                    </div>
    
                                    <!-- Entrada para la categoria -->
                                    <div class="form-group">
                                    Categoria:
                                    <div class="input-group">
                                        <select class="form-control input-lg" name="nuevoCategoria">
                                            <option value="">Seleccionar categoria</option>';
                                            foreach ($this->get_categories() as $categories)
                                            {
                                                if ($categories['id_categoria'] == $product['id_categoria'])
                                                {
                                                    $output .= '<option value="'.$categories['id_categoria'].'" selected>'.$categories['nombre_categoria'].'</option>';
                                                }
                                                else
                                                {
                                                    # code...
                                                    $output .= '<option value="'.$categories['id_categoria'].'">'.$categories['nombre_categoria'].'</option>';
                                                }
                                                
                                            }
                                        $output .= '
                                        </select>
                                    </div>
                                </div>
    
                                    <!-- Entrada para la descripcion -->
                                    <div class="form-group">
                                        Descripción:
                                        <div class="input-group">
                                        <input value="'.$product['descripcion_producto'].'" type="text" class="form-control input-lg" name="putdescripcion" placeholder="Actualizar contraseña" required>
                                        </div>
                                    </div>
    
                                    <!-- Entrada para el precio -->
                                    <div class="form-group">
                                        Precio:
                                        <div class="input-group">
                                            <input value="'.$product['precio_producto'].'" type="text" class="form-control input-lg" name="putprecio" placeholder="Actualizar Edad" required>
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
    

        else if ($this->action == "save_product_form")
            {
                $output.= '
                <div class="card-body">
                comentario
                <div class="container">  
                    <div class="row">
                        <div class="col">
                            <div class="box-body">
                            <form action="products.php?action=save_product" method="POST">
                                <!-- Entrada para el nombre del producto -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" name="nuevoProducto" placeholder="Ingresar nombre del producto" required>
                                    </div>
                                </div>

                                <input type="file" id="foto_producto" name="foto_producto"><br>

                                <!-- Entrada para la categoria -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control input-lg" name="nuevoCategoria">
                                            <option value="">Seleccionar categoria</option>';
                                            foreach ($this->get_categories() as $categories)
                                            {
                                                $output .= '<option value="'.$categories['id_categoria'].'">'.$categories['nombre_categoria'].'</option>';
                                            }
                                        $output .= '
                                        </select>
                                    </div>
                                </div>

                                <!-- Entrada para la categoria
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" name="nuevoCategoria" placeholder="Ingresar categoria" required>
                                    </div>
                                </div> -->

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
                                        <input type="text" class="form-control input-lg" name="nuevofoto" placeholder="foto" required>
                                    </div>
                                </div>
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>';
                            foreach ($this->select_products() as $product)
                            {
                                $categoria  = $this->get_product_category($product['id_categoria']);
                                $usuario = $this->get_product_user($product['id_usuario']);
                                $output .= '
                                <tr> 
                                    <td>'.$product['nombre_producto'].'</td>
                                    <td>'.$categoria['nombre_categoria'].'</td>
                                    <td>'.$product['descripcion_producto'].'</td>
                                    <td>'.$product['precio_producto'].'</td>
                                    <td>'.$usuario['nombre_usuario'].'</td>
                                    <td>'.$product['id_foto'].'</td>
                                    <td>'.$product['fecha_pub_producto'].'</td>
                                    <td>
                                        <a href = "products.php?action=update_product_form&&id_product='.$product['id_producto'].'" class="btn btn-info btn-sm">Editar producto</a> 
                                       <a href = "products.php?action=delete_product_form&&id_product='.$product['id_producto'].'" class="btn btn-danger btn-sm">Eliminar producto</a> 
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