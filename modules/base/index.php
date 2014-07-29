<?php
/*
 * @package base
 */
class fields{
    function __construct(){

    }
    public function addNewField($value,$class,$type){

    }

    public function fieldType($type,$name,$input = "" ,$action = "" ){
        global $html;
        switch($type){
        case "input":
            return $html->inputField();
            break;
        case "select":
            return $html->selectField($name,$input,$action);
            break;
        case "input":
            return $html->inputField();
            break;
        }
    }

    public function userLoginFormFields($page){

    }
    function __destruct(){

    }
}

?>
