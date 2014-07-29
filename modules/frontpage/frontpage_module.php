<?php
/* 
 * this module is for displaying the front page iit will contain
 * the posibility to change the appaerance and look of the front by fetching a modules default site / frontpage
 * or it will display some boxes that a module has been set  with and this can be changed by users.
 */

/**
 * Description of frontPage_module
 *
 * @author moesgaard
 */
class frontpage {
    public $tpl;
    public $dbs;
    function __construct(){
        global $db,$theme;
    }
    function initialize(){
        return  array('frontpage' => "".$this->__default()."");
    }
    function __default(){
        return "This is the default front page";
    }
    function __destruct(){}
}
?>
