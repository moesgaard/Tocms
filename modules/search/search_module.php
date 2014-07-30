<?php
/**
 * Description of search_module
 * This is  the search module  where we can search the  database be it pages/notes/products what ever. 
 * @author moesgaard
 */
class search {
    public $result = '';
    public $data = '';
    public $htmls = '';
    public function __construct($first = false){
        global $db,$html,$pear,$layout;
        $this->data = $db;
        $this->layouts = $layout;
        $monster = '';
    }
    public function initialize(){	
        $point = array('search' => $this->search(),'search/$' => $this->searchid($_GET['q']));
        return $point;
    }
    public function search($array = ''){
        return $this->results ;
    }
    public function searchid($item){
        $fieldings = new fields();
        return $this->results ;
    }
    public function __destruct(){
    }
}
?>
