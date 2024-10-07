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
                        <strong>Success!</strong> Producto NO Agregado
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

    public function add_to_cart()
    {
        if ($this->cart->add($this->postData['id_prod']))
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

    public function show_all_rows()
    {
        if ($this->action)
        {
            $output .= 'caca';
        }
        else
        {
            $output .= '
            <div class="container row">
                <div class="col-md-6">
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
                                                <strong>'.$prd['nombre_producto'].'</strong><br>
                                                $'.$prd['precio_producto'].'
                                            </td>
                                            <td>
                                                <form action="sales.php?action=add_to_cart" method="post">
                                                    <input type="hidden" value="'.$prd['id_producto'].'" name="id_prod">
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

                                $output .= '<div class="table-responsive">
                                <table class = "table" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                    foreach ($allItems as $items)
                                    {
                                        foreach ($items as $item)
                                        {
                                            $output .= '
                                            <tr>
                                                <td>
                                                    <strong>'.$item['id'].'</strong>
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
                                Pagar $00.00
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
        return $output;        
    }

}
