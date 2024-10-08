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

    /** 
    * @param action
    * @return null
    */

    public function show_all_rows($alert='')
    {
        /*if ($this->action)
        {
            $output .= 'asdasdasd';
        }
        else
        {*/
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
                            <buttton class="btn btn-success">
                                Pagar $'.number_format($this->cart->getAttributeTotal('price'), 2, '.', ',').'
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            ';
        /*}*/
        return $output;        
    }

}
