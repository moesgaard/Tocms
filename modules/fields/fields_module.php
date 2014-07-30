<?php
/**
 * This is the field module
 * we build fields in the CMS with this module
 */
class fields extends html_class {
    public $html;
    public  function __construct() {
        $this->html = new html_class;
        /**
         * this just constructs the html call and also
         * will create a db class instance in the future
         */
    }
    public function addNewField($value,$class,$type) {
        /**
         * method wich will call fieldType()  and generate new fields that will be stored in  a database
         * so we can create them on the fly..
         */
    }
    public function fields($array){
        foreach( $array as $key){
            $result .= $this->fieldType($key);
        }
        return $result;
    }
    public function fieldList($array){
        $i = 1;
        $preresult = '';
        $result ='';
        if(!empty($array)){
            foreach($array as $key){
                if(!empty($key)){
                    $preresult[$i]['content'] = $this->fieldType($key);
                    if(isset($key['list'])){
                        if(isset($key['list']['tagid'])){
                            $preresult[$i]['tagid'] = $key['list']['tagid'] ;
                        }
                        if(isset($key['list']['class'])){
                            $preresult[$i]['class'] = $key['list']['class'] ;
                        }
                        if(isset($key['list']['script'])){
                            $preresult[$i]['script'] = $key['list']['script'];
                        }
                    }
                    $i++;
                }
            }
        }
        foreach($preresult as $keys => $value){
            $result  .= $this->html->lists($value);
        }
        $results = array('tagid' => $array[0]['ul']['tagid'],'class'=> $array[0]['ul']['class'] , 'script' => $array[0]['ul']['script']);
        $results['content'] = $result;
        return $this->html->ul($results);
    }
    public function fieldType($array = array('type','description','method')) {
        /**
         * this Method is for creating  different types of  fields for forms and other tags
         * this method will accept  a  array with defined holder called ['type'] wich must consist of
         * the listed below
         */
        // print_r($array);
        $araays = $array;
        switch($array["type"]) {
        case "input":
            return $this->html->inputField($array);
            break;
        case "select":
            return $this->html->select($array);
            break;
        case "hidden":
            return $this->html->hiddenField($array);
            break;
        case "div":
            return $this->html->div($array['div']);
            break;
        default:
            return $this->html->form($array,'',$array['method']);
            break;
        }
    }
    public function userLoginFormFields($page) {
        /**
         * This method is for creating a Standard login module
         */
    }
    function __destruct() {
    }
}
?>
