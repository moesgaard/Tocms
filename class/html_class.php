<?php
/**
 * @package coresystem
 * @subpackage HTML
 * @author Morten Moesgaard
 * @version 0.1 alpha
 */

/**
 * This is the class for creating the HTML this is used everywhere if it is not
 * from here it is part of  the basic setup
 */
/**
 * @package coresystem
 * @subpackage html
 */
class html_class{
    private $break;
    private $options;
    public $t;
    function __construct() {
      $this->t = new translate_class();
    }
    function arraysplit($tagid = '') {
        $taged = '';
        if(!empty($tagid)){
        if(isset($tagid['name'])) {
            $taged = "name=\"".$tagid['name']."\"";
        }elseif(isset($tagid['id'])) {
            $taged = 'id="'.$tagid['id'] .'"';
        }elseif(isset($tagid['class'])) {
            $taged = "class=\"".$tagid['class']."\"";
        }

        }

        return $taged;
    }
    function div($array) {
         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return '<div '.$array['id'].' '.$array['class'].' '.$array['script'].'>'.$this->t->translate($array['content']).'</div>';
    }
    function image($array) {
         if(!isset($array['tagid'])){
         $array['tagid']    = ' ';
        }
        if(!isset($array['class'])){
           $array['class'] = ' ';
                }
        if(!isset($array['script'])){
           $array['script'] = ' ';
        }
        return "<img src=\" ".$array['src']."\" ".$array['tagid']." class=\"".$array['class']."\" ".$array['script'].">";

    }
    function table($array ) {
         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return "<table ".$array['tagid']." class=\"".$array['class']."\" ".$array['script'].">".$array['content']."</table>";

    }
    function tableCell($array) {
         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return "<td ".$array['tagid']." class=\"".$array['class']."\" ".$array['script'].">".$array['content']."</td>";
    }
    function tableRow($array ) {
         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return "<tr ".$array['tagid']." class=\"".$array['class']."\" ".$array['script'].">".$array['content']."</tr>";
    }
    function breaker($amount=1) {
        for($i=0;$i < $amount; $i++) {

            $this->break = "<br />";
        }
        return $this->break;
    }
    function ptag($array) {
         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return "<p ".$array['tagid']." class=\"".$array['class']."\" ".$array['script'].">".$array['content']."</p>";
    }
    function span($array) {
         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return "<span ".$array['tagid']." class=\"".$array['class']."\" ".$array['script'].">".$array['content']."</span>";

    }
    function headline($array) {
         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return '<h'.$array['size'].' '.$array['tagid'].' '.$array['class'].' '.$array['script'].' >'.$array['content'].'</h'.$array['size'].'>';

    }
    function lists($array) {

         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return '<li '.$array['tagid'].' '.$array['class'].' '.$array['script'].'>'.$array['content'].'</li>';

    }
    function ahref($array) {
         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return '<a href="'.$array['src'].'"'.'  '.$array['tagid'].' '.$array['class'].' '.$array['script'].'>'.$array['content'].'</a>';
    }
    function pre($array) {
         if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        if(!empty($array['content'])) {
            return "<pre ".$array['tagid']."> ".$array['content']."</pre>";
        }
    }
    function ul($array) {
        if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
        return '<ul'.'  '.$array['tagid'].' '.$array['class'].' '.$array['script'].'>'.$array['content'].'</ul>';

    }
    function select($array) {
        if(!isset($array['tagid'])){
         $array['tagid']    = '';
        }
        if(!isset($array['class'])){
           $array['class'] = '';
                }
        if(!isset($array['script'])){
           $array['script'] = '';
        }
              return '<select '.''.$array['tagid'].''.$array['class'].''.$array['script'].'>'.self::option($array['content']).'</select>';
    }
    function option($array) {
              if(!empty($array)){
                 foreach($array as $key => $value ) {
             $this->options .= "<option value=\"$key\">$value</option>";
            }
        }
        return $this->options;
    }
    function description($description) {
        if(!empty($description)) {
            return self::pre($description['ident'], $description['content']);
        }else {
            return;
        }
    }
    function label($info) {
        if(!empty($info)) {
            return "<label for=".$info['for']." >".$info['content']."</label>";
        }else {
            return ;
        }
    }
    function input($array) {
        return self::label($array['label']).'<input '.$array['content'].' />'.self::description($array['description']);
    }
    function inputField($array) {
        $info = " type=\"".$array['type']."\" ";
       
        foreach($array['input'] as $key => $value){
                $info .= " $key=\"$value\" ";
        }
        $array['content'] = $info;
        return self::input($array);
    }
    function hiddenField() {

    }
    function form($array,$type ,$method="POST") {
        if(preg_match("/submit/", $array['content']) == 0) {
            $array['content'] .= self::inputField( array('label'=> '','description'=>'','content'=>'','type'=> 'submit','input' => array( 'name'=>'submit', 'value'=> 'submit')));
        }
      
        if(empty($method) == 0) {
            $method = "POST";
        }
        return '<form method="'.$method.'" '.$type.'>'.$array['content'].'</form>';
    }
    function __destruct() {
    }
}
?>
