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
    public function Usuarios($sql/*, $getCurrentUser*/){
      
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
    public function show_all_rows()
    {
        $output .= '<h1> gEI EL QUE Lo lea </h1>';
        return $output;
    }
    
}

?>