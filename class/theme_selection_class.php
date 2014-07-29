<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of theme_selection_class
 *
 * @author moesgaard
 */
class theme_selection_class {
    public $dbs;
    public $current;
    function __construct() {
        global $db;
        $this->dbs = $db;
        return $this->theme();
    }
    function theme() {
        return $this->themeActive();
    }
    function themeActive() {
        global $db;
        $this->dbs = $db;
        $this->current =  $this->dbs->getArray('theme:','id,name,themes,is_active:'," 0.3 = '1' " ," LIMIT 1");
        return $this->current;
    }
    function __destruct() {
    }
}
?>
