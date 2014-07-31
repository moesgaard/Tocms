<?php
/**
 * @core true
 * @package coresystem
 * @subpackage content
 */
/**
 *this is the content class where all the content is drawn from
 */
/**
 *@package coresystem
 *@subpackage content
 */
class content_class {
    public $connection;
    public $menu;
    public $sidebar;
    public $topbar;
    public $contents;
    private $htmls;
    private $menus;
    private $data;
    private $section;
    public function __construct($set) {
        /*
         *@global string  $db,$html,$setup,$forum,$standard
         */
        global $db,$html,$setup,$forum,$standard,$node,$module;
        $this->htmls = $html;
        // we fetch the menu that is controlled from the admin menu.
    /*    $this->data = $db->getArray('tocms_menu:',
          "id,name,is_main_menu,main_menu_position:",
            " 0.2 = 1 ",
            " order  0.3  ASC");
        print_r($this->data);
      */  $this->connection = $module->moduleSendInformation();
        if(!is_array($this->data)) {
            $this->__destruct();
        }
        if($setup['menu'] == "1" ) {
            $this->topbar = $html->div(array("id" => 'menu'),"" ,"" ,$this->arrayToMenu($this->data, 1));
        }else {
            $this->menu = $html->div(array("id" => 'menu'),"" ,"" ,$this->arrayToMenu($this->data , 0));
        }
        if(!isset($_REQUEST['si'])) {
            $this->contents = $html->div(array('id' => 'main'),'','',$setup['maincontent']);
        }else {
            //        $this->contents = $html->div(array('id' => 'main'),'','',$this->findContent($_REQUEST['si']));
        }
        $this->contents = $this->connection;
    }
    public function menu() {
        return nl2br($this->menu);
    }
    public function topbar() {
        return nl2br($this->topbar);
    }
    public function content() {
        return nl2br($this->contents);
    }
    public function sidebar() {
        return nl2br($this->sidebar);
    }
    public function arraytoMenu($dataArray,$vertical = 0) {
        $this->menus = "";
        if($vertical == 0) {
            $splitter = $this->htmls->breaker();
        }else {
            $splitter = " | ";
        }
        if(is_array($dataArray)) {
            foreach ($dataArray as $key => $value) {
                $hey = list($id,$horse,$name,$mainmenu,$maimenupos) = array_values($value);
                $this->data = $db->getArray('tocms_menu:',"id,name,is_sub_menu,sub_menu_position,parent_menu:"," 0.2 NOT LIKE '0' AND  0.4 = '".$horse."'"," order 0.3 ASC");
                $this->menus .=  $name;
                foreach($this->data as $keys => $values) {
                    $emo = list($ids,$horses,$names) = array_values($values);
                    $this->section .= $names.$splitter;
                }
                $this->menus .= "".$this->section."";
                $this->section = '';
                $data = '';
            }
            return $this->menus;
        }else {
            $this->__destruct();
        }
    }
    public function __destruct() {}
}
?>
