<?php
/**
*@package inclusion
*/
/**
* This class have intensions of creating a auto inclusion
* for interfaces and classes so we dont need to think about
* interfaces and including classes when we need them so we just load
* the lot and hope performens is not to worry about
*/
/**
*@package inclusion
*/
class inclusion{
function __construct($path,$paths = false){
	/*
	 * we construct what we need and we have multiple options on this
	 * it is so if we need interface we have 3 options incase we are lazy
	 * same	goes for classes..
	 * but with images we need to know where too look so thats why paths
	 * are set to false for the other options sake
	 */
	include('config/conf.php');
	switch($path){
		case "int":
		case "interface":
		case "interfaces":
		case "inter":
			return $this->require_interfaces('interfaces');
			break;
		case "cla":
		case "class":
		case "classes":
			return $this->require_class('class');
			break;
		case "img":
		case "images":
		case "image":
			return $this->require_images($paths);
			break;
		case "page":
		case "setup":
		case "nonstandard":
		case "themes":
			return $this->require_folder($paths);
			break;
		case "pear":
		case "PEAR":
			return $this->require_pear('pear');
			break;

		default;
		break;
		}
}

public function require_interfaces($path){
	/*
	 * the function that loads the interfaces
	 * */
	$thePath = $GLOBALS['thePath'];
	$theFiles = scandir($thePath.$path);
	foreach($theFiles as $files){
		if(is_file( $thePath.$path."/".$files)){
			require_once($thePath.$path."/".$files);
		}
	}
}
public function require_folder($path){
	/*
	 * the function that loads the files in the patch yopu choose
	 * */
	$thePath = $GLOBALS['thePath'];
	$theFiles = scandir($thePath.$path);
	foreach($theFiles as $files){
		if(is_file( $thePath.$path."/".$files)){
			include($thePath.$path."/".$files);
		}
	}
}

public function require_class($path){
	/*
	 * the function that loads classes
	 * */
	$thePath = $GLOBALS['thePath'];
	$theFiles = scandir($thePath.$path);
	foreach($theFiles as $files){
		if(is_file( $thePath.$path."/".$files)){
			require_once($thePath.$path."/".$files);
		}
	}
}
public function require_pear($path){
	/*
	 * the function that loads classes
	 * */
	$thePath = $GLOBALS['thePath'];
	$theFiles = scandir($thePath.$path);
	foreach($theFiles as $files){
		if(is_file( $thePath.$path."/".$files)){
			require_once($thePath.$path."/".$files);
		}
	}
}
public function require_images ($path){
	/*
	 * the function that loads the images and returns an array
	 * */
	$images = array();
	$thePath = $GLOBALS['thePath'];
	$theFiles = scandir($thePath.$path);
	foreach($theFiles as $files){
		if(is_file( $thePath.$path."/".$files)){
			$image = explode('.',$files);
			$images[$image[0] ] = $path."/".$files;
		}
	}
	return $images;
}

function __destruct(){

}
}

?>
