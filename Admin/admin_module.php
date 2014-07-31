<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of admin_module
 *
 * @author moesgaard
 */ 
class admin {
   public $users; 
    function __construct() {
        global $db,$html,$pear,$theme,$layout,$mails,$user;
        $this->db = $db;
        $this->htmls = $html;
        $this->users = new users_class();
        $this->layouts = $layout;
        $this->users->blacklistcheck();
        if($_COOKIE['login'] == sha1("logedin") ){
            $this->initialize();
        }else{
            require_once('Admin/login.php');
        }
    }
   
   function initialize() {

   }
}
$admin = new admin();
?>
