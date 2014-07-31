<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * @core true
 * @author moesgaard
 */
class theme_class {
    private $db;
    public $current;
    public $menu;
    public $header = array();
    public $leftbar;
    public $rightbar;
    public $footer;
    public $javascript;
    public $css;
    public $content;
    public $breadcrum;
    function __construct() {
        global $db,$html;
        $this->db = $db;
        $this->htmls = new html_class();
        $this->theme();
        $this->preloader($this->active_theme());
    }
    function theme() {
        $this->themeActive();
    }

    function themeActive() {
        $this->current =  $this->db->getArray('theme:','id,name,themes,is_active,token:'," 0.3 = '1' " ," LIMIT 1");
        return $this->current;
    }
    function active_theme (){
        return $this->current[0]['token'];
    }
    function preloader($preloader){
        $preload =  $this->db->getArray('css:','id,active,token,path,media:'," 0.1 = '1' " ," LIMIT 1");
        foreach($preload as $key => $value){
            if($value['token'] == 'reset' || $value['token'] == $preloader || $value['use_in'] == $preloader){
                $this->header[] = array('type'=>'css','src' =>$value['path'],'media' =>$value['media'] );
            }
        }
    }
    function set_header($array,$position){
        if($position == '' ){
            $this->header[] = $array;
        }else{
            $this->header = $this->array_shift($this->header,$array,$position,$position);
        }
    }
    function set_footer($array){
        $this->footer[] = $array;
    }   
    function set_css($array){
        $this->css[] = $array;
    }
    function set_content($array,$position = ''){
        $this->content[] = $array;
    }
    function footer(){

    }

    function css(){

    }
    function array_shift(&$array, $object, $position, $name = null)
    {
        $count = 0;
        $imo = 0;
        $return = array();
        foreach ($array as $k => $v) 
        {   
            // insert new object

            if ($count == $position){   
                if (!$name) $name = $count;
                $return[$name] = $object;
                $return[$name+1] = $v;
                $inserted = true;
                $imo = 1;
            }   
            // insert old object
            $return[$k+$imo] = $v; 
            $count++;
        }   
        if (!$name) $name = $count;
        if (!$inserted) $return[$name];
        $array = $return;
        return $array;
    }
    function content(){
        $headers = '';
        foreach($this->content as $key => $value){
            $content .= $value; 
        } 
    }

    function header(){
        global $html;
        $headers = '';
        foreach($this->header as $key => $value){
            switch($value['type']){
                case 'javascript';
                $headers .=  $this->htmls->script($value);
                break;
                case 'meta';
                $headers .=  $this->htmls->meta($value);
                break;
                case 'css';
                $headers .=  $this->htmls->css($value);
                break;

            }
        }
        return $headers;
    }

    function __destruct(){

    }
}
$theme = new theme_class;
?>
