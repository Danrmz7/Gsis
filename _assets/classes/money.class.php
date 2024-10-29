<?php
/**
 * File Name: Money
 * Creator URI: 
 * Description: Main configuration file
 * Author: 
 * Version: 1.1
 * Author URI: 
 */
class Money {

    var $sql;
    var $mail;
    var $current_user;
    var $output;
    var $alert;
  
    /*---------------------------------*/
    public function __construct($sql, $getCurrentUser){
      
        $this->sql        = $sql;
        $this->_user      = $getCurrentUser;
    
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
        switch ($this->action)
        {
            case 'send_money':
                if ($this->update_current_money())
                {
                    if($this->transfer_money())
                    {
                        $alert = '
                        <div class="alert alert-success">
                            <strong>Success!</strong> Transacción Realizada
                        </div> ';
                        $output .= $this->show_all_rows($alert);
                    }
                    else
                    {
                        $alert = '
                        <div class="alert alert-danger">
                            <strong>Error</strong> Transacción NO Realizada. Fondos Insuficientes.
                        </div> ';
                        $output .= $this->show_all_rows($alert);
                    }
                }
                else
                {
                    $alert = '
                        <div class="alert alert-danger">
                            <strong>Error</strong> Transacción NO Realizada
                        </div> ';
                    $output .= $this->show_all_rows($alert);
                }
                return $output;
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

    public function get_users()
    {
        $query = "SELECT * from usuarios where id_usuario != ?";
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

    public function get_coins()
    {
        $query = "SELECT * from usuarios where id_usuario = ?";
        $params_query = array($this->_user['id_usuario']);

        if($rs = $this->sql->select($query, $params_query))
        {
            return $rs[0];
        }
        else
        {
            return false;
        }
    }

    public function transfer_money()
    {
        $query = "SELECT * from usuarios where id_usuario = ?;";
        $params_query = array($this->postData['destinatario']);
        
        if ($rs = $this->sql->select($query, $params_query))
        {
            $seller = $rs[0];
            $dinero_vendedor = $seller['dino_coins'];
            $dinero_transferido = $this->postData['coins'] + $dinero_vendedor;

            $query = "UPDATE usuarios SET dino_coins = ? WHERE id_usuario = ?; ";
            $params_query = array( $dinero_transferido, $this->postData['destinatario']);    

            if($this->sql->update($query, $params_query) )
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
    
    public function update_current_money()
    {
        $query = "SELECT * from usuarios where id_usuario = ?;";
        $params_query = array($this->_user['id_usuario']);

        if ($rs = $this->sql->select($query, $params_query))
        {
            $usuario = $rs[0];
            $dinero_actual = $usuario['dino_coins'];
            $dinero_final = $dinero_actual - $this->postData['coins'];

            $query = "UPDATE usuarios SET dino_coins = ? WHERE id_usuario = ?; ";
            $params_query = array( $dinero_final, $this->_user['id_usuario']);    

            if($this->sql->update($query, $params_query) )
            {
                return true;
            }
            
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
        /*if ($this->view == 'send_money_form')
        {*/
            $coins = $this->get_coins();
            $output .= '
            '.$alert.'
            <div class="container">
                <h2>Transferir monedas</h2>
            
                <form class="card mt-3" action="money.php?action=send_money" method="post">

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                Monedas a transferir:<br>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" min="1" name="coins">
                                </div>
                            </div>

                            <div class="col-md-4">
                                Usuario a transferir:<br>
                                <div class="form-group">
                                    <select class="form-control" name="destinatario">
                                        <option value="0">Seleccione un vendedor</option>';
                                        foreach ($this->get_users() as $user)
                                        {
                                            $output .= '<option value="'.$user['id_usuario'].'">'.$user['nombre_usuario'].'</option>';
                                        }
                                        $output .= '
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                Mi Bolsa:<br>
                                <strong>$'.number_format($coins['dino_coins'], 2, '.', ',').'</strong>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Transferir</button>
                    </div>

                </form>

            </div>
            ';
        /*}
        else
        {
            $output .= '
            <h1><h1>
            ';
        }*/
        return $output;
    }

}