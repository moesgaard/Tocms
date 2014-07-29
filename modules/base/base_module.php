<?php
/* *
 * this will be the base module for the system this will be a co-working
 * with the other modules..
 * @package base
 * 
 */
class base {
    public $page = '';
    public $dbs;
    public $generateAction;
    function  __construct($i = '') {
        global $db;
        $alias = '';
        $this->dbs = $db;
        if(isset($_GET['q'])){
            $this->page = $_GET['q'];
        }else{
            $this->page = '';
        }
        /**
         * find we find alias if $i is set to 1
         * because without 1 its just initialized from module load
         */
        if($i == 1) {
            if($this->page != ""){
                $alias = $this->findAlias($this->page);
            }else{
                $alias = '';
            }
            if(trim($alias) != ''){
                $this->generateAction = $this->getActions($alias);
                if($this->generateAction == '' && empty($this->generateAction) ){
                    $error = new errorPage($this->generateAction);
                    //		die('Fejl!!');
                }else{
                    return true;
                }
            }else {
                $this->generateAction = $this->getActions('frontpage');
                return $this->generateAction;
            }
        }
    }
    function findAlias($alias) {	
        if($alias != ''){
            $result = '';
            $alias = explode('/',$alias);
            $defined =  $this->dbs->getArray('modules:','id,alias,module_name:',"0.1 LIKE '%".addslashes($alias[0])."%'",' limit  1 ');
            if(isset($defined[0]['alias']) && $defined[0]['alias'] != ''){
                $hit = preg_match('/'.$alias['0'].'/U',$defined[0]['alias']);
                if($hit == 1){
                    $result = $defined[0]['module_name'];
                }else{
                        /*$defined =  $this->dbs->getArray('modules:','id,alias,module_name:',"0.1 LIKE '%".addslashes($alias[0])."%'",' limit  1 ');
                        $hit = preg_match('/'.$defined[alias].'/',$alias[0]); */
                    $result = '';
                }
            }
            return $result;
        }
    }
    function getActions($class) {
        if(trim($class) != ''){
            $amon = '';
            $bebo = get_class_methods($class);
            $actions = get_defined_functions();
            $int = new $class();
            $find = $int->initialize();
            if($this->page == ''){
                $this->page = 'frontpage';
            }else{
                $pages =  explode('/',$this->page);
            }
            if(!empty($find)){
                foreach($find as $key => $value){  
                    if(strtolower($key) ==  strtolower($this->page)) {
                        $amon = $value;
                    }elseif($key == '&'.$pages.'&' ){
                        if($this->checkIntegrety() == true){
                            $amon =  $value;
                        }
                    }elseif($pages[1] != '' && strtolower($key) == strtolower(''.$pages[0].'/$')){
                        $amon =  $value;
                    }elseif(strtolower($key) ==  strtolower($pages[0])){
                        $amon =  $value;
                    }
                }
                if($amon == '' && $this->page != '' && empty($amon)){
                    $amon  = "";
                }
                return $amon;
            }
        }
    }
    function isDeclared() {
    }
    public function checkIntegrety() {
        if(isset($_SESSION['loggedIn'])) {
            return true;
        }
    }
    function produceResult() {
    }
    function  __destruct() {
    }
}
?>
