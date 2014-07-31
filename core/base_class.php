<?php
/** @information
 * @member core
 * @package base 
 */
require_once('core/database_class.php');
global $db,$content,$content_create,$html,$theme,$css;
class base_class{
    public $page = '';
    public $dbs;
    public $generateAction;
    function  __construct($i = '') {
        global $db;
        $this->alias = '';
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
    }
    function findAlias($alias) {	
    }
    function startsystem(){
       $fun =  scandir('/core/');
       foreach($fun as $key ){
       
       }
    }
    function getplugininfo($fine){
    $str =  file_get_contents($fine);
    $tokens = token_get_all($str);
    foreach ($tokens as $token){
        if($token[0] == 373){
            // This is a comment ;-)
            $fire = explode('*',str_replace('/**','',str_replace('*/','',$token[1])));
            foreach($fire as $key => $value){
                $section =  explode(':',$value);
                $plugin[''.trim($section[0]).''] = trim($section[1]);
            }    
        }

    }
    return $plugin;
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
$base = new base_class();
?>
