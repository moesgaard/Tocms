<?php
require_once('core/error_class.php');
require_once('core/base_class.php');
//$str =  file_get_contents('core/base_class.php');
$fun =  scandir('core/');
foreach($fun as $key => $value ){
    if(file_get_contents("core/".$value)){
        if($value != 'base_class.php'){
            $str =  file_get_contents('core/'.$value);
            $tokens = token_get_all($str);
            foreach ($tokens as $token){
            
                if($token[0] == 373) {
                    // This is a comment ;-)
                    $fire = explode('*',str_replace('/**','',str_replace('*/','',$token[1])));
                    if(trim($fire[1]) == '@core true'){
                    require_once('core/'.$value); 
                    }
                    }
            }

        }
    }
}
print_r(get_declared_classes());

?>
