<?php
/**
 * This is the class for fetching themes 
  */
/**
 * @package class
 **/
class theme {
    public $data;
    public $wupti;
    public $names;
    public $arrays;
    public $Subjects;
    public $id;
    public $SubDivs;
    public $setting;
    public function __construct($first = false){
        global $db,$html,$pear,$layout;
        $this->data = $db;
        $this->htmls = $html;
        $this->layouts = $layout;
    }
    public function initialize(){
        $point = '' ;
        /**
         * we must find  active theme  in databse and fetch its informatione via index.php of the theme folder 
         **/
        return $point;
    }
    public function draw_index($section = ''){
        global $db,$html;
        /**
         * we draw to the screen what it  returned  to us.
         **/
    }
    
    public function __destruct(){
    }
}
?>
