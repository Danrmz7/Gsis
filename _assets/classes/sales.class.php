<?php
/**
 * File Name: Sales
 * Creator URI: 
 * Description: Main configuration file
 * Author: 
 * Version: 1.1
 * Author URI: 
 */
class Sales {

    var $sql;
    var $mail;
    var $current_user;
    var $output;
    var $alert;
  
    /*---------------------------------*/
    public function __construct($sql, $getCurrentUser, $cart){
      
      $this->sql        = $sql;
      $this->_user      = $getCurrentUser;
      $this->cart       = $cart;
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
      $this->view        = $this->getData['view'];
  
    }

    public function process()
    {
        switch($this->action)
        {
            case 'add_to_cart':
                //
                if($this->add_to_cart())
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

            case 'delete_item':
                //
                if($this->delete_item())
                {
                    $alert = '
                    <div class="alert alert-success">
                        <strong>Success!</strong> Producto Eliminado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
                else
                {
                    $alert = '
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Producto NO Eliminado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
            break;
            
            case 'update_item':
                //
                if($this->update_item())
                {
                    $alert = '
                    <div class="alert alert-success">
                        <strong>Success!</strong> Producto Actualizado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
                else
                {
                    $alert = '
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Producto NO Actualizado
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
            break;

            case 'confirm_sale':
                if ($this->save_sale())
                {
                    $this->cart->destroy();
                    $alert = '
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>Success!</strong> Venta Realizada
                        <a href="./"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></a>
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
                else
                {
                    $alert = '
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Error!</strong> Venta NO Realizada
                        <a href="./"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></a>
                    </div> ';
                    $output .= $this->show_all_rows($alert);
                    return $output;
                }
                
            break;

            default:
                $output .= $this->show_all_rows($alert);
                return $output;
            break;
        }
    }

    /** 
    * @param action
    * @return null
    */

    public function select_products()
    {
        $query = "SELECT * from productos where id_usuario = ?";
        $params_query = array($this->_user['id_usuario']);

        if($rs = $this->sql->select($query, $params_query))
        {
            return $rs;
        }
        else
        {
            return false;
        }
    }

    public function get_product_info($producto)
    {
        $query = "SELECT * from productos where id_producto = ?";
        $params_query = array($producto);

        if($rs = $this->sql->select($query, $params_query))
        {
            return $rs[0];
        }
        else
        {
            return false;
        }
    }

    public function add_to_cart()
    {
        if ($this->cart->add($this->postData['id_prod'], 
                1, 
                ['price'  => $this->postData['precio_prod']]
            ))
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }
    

    public function delete_item()
    {
        if ($this->cart->remove($this->postData['id_prod']))
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }

    public function update_item()
    {
        if ($this->cart->update($this->postData['id_prod'], 
                $this->postData['qty'], 
                ['price'  => $this->postData['precio_prod']]
            ))
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }

    public function get_rewards()
    {
        $allItems = $this->cart->getItems();

        foreach ($allItems as $items)
        {
            foreach ($items as $item)
            {
                $query = "SELECT * from productos where id_producto = ?";
                $params_query = array($item['id']);

                if ($rs = $this->sql->select($query, $params_query))
                {
                    $length = count($rs);
                    for($i=0; $i <= $length; $i++)
                    {
                        $productos_comprados = $rs[$i];
                        $recompensas = $productos_comprados['dino_producto'] * $item['quantity'];
                        $total_rew += $recompensas;
                    }
                }
                else
                {
                    $recompensas = 0;
                }
            }
        }
        return $total_rew;
    }

    public function get_buyers()
    {
        $query = "SELECT * from compradores";
        //$params_query = array($this->_user['id_usuario']);

        if($rs = $this->sql->select($query, $params_query))
        {
            return $rs;
        }
        else
        {
            return false;
        }
    }

    public function save_sale()
    {
        $fecha_actual = date("Y-m-d");
        $query = "INSERT INTO ventas (id_comprador,fecha_compra) VALUES (?,?)";
        $params_query = array($this->_user['id_usuario'],$fecha_actual);

        if($this->sql->insert($query, $params_query))
        {
            $query = "SELECT id_venta from ventas where id_comprador = ? order by id_venta DESC Limit 1;";
            $params_query = array($this->postData['comprador']);

            if ($rs = $this->sql->select($query, $params_query))
            {
                $allItems = $this->cart->getItems();
                $ultima_venta = $rs[0];
                foreach ($allItems as $items)
                {
                    foreach ($items as $item)
                    {
                        $query = "INSERT INTO carrito_productos (id_carrito, id_producto) VALUES (?,?)";
                        $params_query = array($ultima_venta['id_venta'], $item['id']);
                        $this->sql->insert($query, $params_query);
                    }
                }

                $recompensas = $this->get_rewards();
                if ($this->update_buyer_money($this->postData['comprador']) && $this->update_seller_money()){

                }

                /*if ($this->update_seller_money()){

                }*/

                if ($this->add_rewards($recompensas, $this->postData['comprador'])){
                
                }
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function update_buyer_money($id_comprador)
    {
        $query = "SELECT * from compradores where id_comprador = ?;";
        $params_query = array($id_comprador);
        
        if ($rs = $this->sql->select($query, $params_query))
        {
            $comprador = $rs[0];
            $dinero_comprador = $comprador['dino_coins'];
            $total_compra = $this->cart->getAttributeTotal('price');
            $total_dinero_comprador = $dinero_comprador - $total_compra;

            $query = "UPDATE compradores SET dino_coins = ? WHERE id_comprador = ?; ";
            $params_query = array( $total_dinero_comprador, $id_comprador);    

            if($article = $this->sql->update($query, $params_query) )
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function update_seller_money()
    {
        $query = "SELECT * from usuarios where id_usuario = ?;";
        $params_query = array($this->_user['id_usuario']);
        
        if ($rs = $this->sql->select($query, $params_query))
        {
            $seller = $rs[0];
            $total_compra = $this->cart->getAttributeTotal('price');
            $total_dinero_vendedor = $seller['dino_coins'] + $total_compra;

            $query = "UPDATE usuarios SET dino_coins = ? WHERE id_usuario = ?; ";
            $params_query = array( $total_dinero_vendedor, $this->_user['id_usuario']);    

            if($article = $this->sql->update($query, $params_query) )
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function add_rewards($recompensas, $id_comprador)
    {
        $query = "SELECT * FROM compradores where id_comprador = ?";
        $params_query = array( $id_comprador );   
        
        if ($rs = $this->sql->select($query, $params_query))
        {
            $dinero_usuario = $rs[0];
            $dinero_acumulado = $dinero_usuario['dino_coins'] + $recompensas;

            $query = "UPDATE `compradores` SET dino_coins = ? WHERE id_comprador = ?; ";
            $params_query = array( $dinero_acumulado, $id_comprador );   
            
            if($article = $this->sql->update($query, $params_query) ) {
                return true;
                }else{
                return false; 
            }
        }
    }

    /** 
    * @param action
    * @return null
    */

    public function show_all_rows($alert='')
    {
        if ($this->view == "confirm_form")
        {
            $allItems = $this->cart->getItems();
            $output .= '
            <form action="sales.php?action=confirm_sale" method="post" class="container row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body row">

                            <h2>Confirmar Compra</h2>
                            <hr>

                            <div class="col-md-6">
                                <strong>Productos seleccionados</strong>
                                <ul class="list-group list-group-flush mt-3">';
                                foreach ($allItems as $items)
                                {
                                    foreach ($items as $item)
                                    {
                                        $currnt_prd = $this->get_product_info($item['id']);
                                        $output .= '
                                            <li class="list-group-item"><strong>#'.$item['id'].'</strong> - '.ucfirst($currnt_prd['nombre_producto']).'</li>
                                        ';
                                    }
                                }
                                $output .= '
                                </ul>
                            </div>

                            <div class="col-md-6" style="padding:2%;">
                                <strong>Total Compra:</strong> $'.number_format($this->cart->getAttributeTotal('price'), 2, '.', ',').'<br>
                                <strong>Recompensas:</strong> $'.number_format($this->get_rewards(), 2 , '.', ',').'<br>
                                <strong>Comprador:</strong>
                                <select name="comprador" class="form-control form-control-sm mt-1">
                                    <option value="0">Comprador</option>';
                                    foreach($this->get_buyers() as $buyer)
                                    {
                                        $output .= '<option value="'.$buyer['id_comprador'].'">'.$buyer['nombre_comprador'].'</option>';
                                    }
                                
                                $output .= '
                                </select>
                                <br>
                                <button type="submit" class="btn btn-primary btn-block">
                                    Pagar $'.number_format($this->cart->getAttributeTotal('price'), 2, '.', ',').'
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
            ';
        }
        else
        {
            $output .= '
            <div class="container row">
                <div class="col-md-6">
                '.$alert.'
                    <div class="card">
                        <div class="card-body">
                            <h1>Mis Productos</h1>
                            
                            <div class="table-responsive">
                                <table class = "table" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Agregar</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                    foreach ($this->select_products() as $prd)
                                    {
                                        $output .= '
                                        <tr>
                                            <td>
                                                <strong>'.$prd['nombre_producto'].'</strong> - $'.$prd['precio_producto'].'<br>
                                                <small>rwds: $'.$prd['dino_producto'].'</small>
                                            </td>
                                            <td>
                                                <form action="sales.php?action=add_to_cart" method="post">
                                                    <input type="hidden" value="'.$prd['id_producto'].'" name="id_prod">
                                                    <input type="hidden" value="'.number_format($prd['precio_producto'], 2, '.', ',').'" name="precio_prod">
                                                    <button class="btn btn-primary btn-sm">
                                                        Agregar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>';
                                    }
                                    $output .= '</tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h1>Orden Actual</h1>
                            <hr>';
                            
                            if ($this->cart->isEmpty())
                            {
                                $output .= '
                                <div class="alert alert-primary">
                                    No hay nada en la orden actual
                                </div>
                                ';
                            }
                            else
                            {
                                $allItems = $this->cart->getItems();

                                $output .= '
                                <form action="sales.php?action=destroy_cart" method="post">
                                    <button type="submit" class="btn btn-link">Cancelar Orden</button>
                                </form>
                                <div class="table-responsive">
                                <table class = "table" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Actualizar</th>
                                            <th>Borrar</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                    foreach ($allItems as $items)
                                    {
                                        foreach ($items as $item)
                                        {
                                            $currnt_prd = $this->get_product_info($item['id']);
                                            $output .= '
                                            <tr>
                                                <td>
                                                    <strong><small>#'.$item['id'].'</small> - '.$currnt_prd['nombre_producto'].' </strong>
                                                </td>
                                                <td>
                                                    <form action="sales.php?action=update_item" method="post" class="input-group">
                                                        <input type="hidden" value="'.$item['id'].'" name="id_prod">
                                                        <input type="hidden" value="'.number_format($currnt_prd['precio_producto'], 2, '.', ',').'" name="precio_prod">
                                                        <input class="form-control" value="'.$item['quantity'].'" type="number" min="1" name="qty" style="width:20px;">
                                                        <button type="submit" class="btn btn-warning">A</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="sales.php?action=delete_item" method="post">
                                                        <input type="hidden" value="'.$item['id'].'" name="id_prod">
                                                        <button type="submit" class="btn btn-danger">B</button>
                                                    </form>
                                                </td>
                                            </tr>';
                                        }
                                    }
                                $output .= '
                                    </tbody>
                                </table>
                                ';
                            }
                            
                            
                            $output .= '
                            <a href="sales.php?view=confirm_form" class="btn btn-success '; if($this->cart->getAttributeTotal('price') <= 0.0){ $output .= 'btn disabled'; } $output .= '">
                                Pagar $'.number_format($this->cart->getAttributeTotal('price'), 2, '.', ',').'
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
        return $output;        
    }

}
