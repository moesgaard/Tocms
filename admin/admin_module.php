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
    function __construct() {
        global $db,$html,$pear,$layout,$mails;
        $this->data = $db;
        $this->htmls = $html;
        $this->layouts = $layout;
        $this->mail = $mails;
    }
   function initialize() {
    }
}
