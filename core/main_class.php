<?php
/**
 * @core true
 * @package class
 * @subpackage main
 * @author Morten Moesgaard
 * @version 0.1 alpha
 */

/**
 * This is the class for creating the main site layout this is the part that
 * decides what todo with the layout
 */
/**
 * @package class
 * @subpackage main
 */
class main_class{
	function __construct($constructMode = false,$version = false,$index = false){
		switch($constructMode){
		case "wholeSite ":
			break;
		case "main":
			break;
		case "menu":
			break;
		case "top":
			break;
		case "bottom":
			break;
		default :
			break;
		}
	}
	function __construct_body($main = false,$menu = false, $top = false, $bottom = false){
		echo   $main.$top.$bottom;
	}
	function __construct_main($version,$index){
		return $version;
	}
	function __construct_menu($version = false,$index){
	}
	function __construct_top($version=false ,$index){
	}
	function __construct_bottom($version = false , $index){
	}
	function __destruct( ){
	}
}
?>
