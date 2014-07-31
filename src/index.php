<?php
/**
 * @ignore true
 * @author Morten Moesgaard
 * @position tocms main function
 * @plugin false
 */
if(!defined("TOCMS")){
    die('Not okay');
}
global $db,$html,$pear,$theme,$layout,$mails,$user;
/**
 * TOCMS use Tokens defined in the core/module/plugin/theme
 * The set up of the tokens are
 * 
 * For core 
 * @core  true
 * @author xxxxxx
 * @website xxxxxx
 * @prodvides class_name
 * @dependencies class_name,class_name (only if needed we call the global $db when all plugins and themes are loaded )
 * 
 * For modules
 * @module true
 * @author xxxxxx
 * @website xxxxxx
 * @provides class_name
 * @dependencies class_name,class_name
 *
 * For plugins
 * @plugin  true
 * @author xxxxxx
 * @website xxxxxx
 * @declare xxxx (with out $ so we can auto call plugin)
 * 
 * For theme
 * @theme  true
 * @token xxxxxxxxx
 * @author xxxxxx
 * @website xxxxxx
 *
 */
function tocms(){
    //We first load the core files
    $fun =  scandir('core/');
    foreach($fun as $key => $value ){
        if(file_get_contents("core/".$value)){
            $str =  file_get_contents('core/'.$value);
            $tokens = token_get_all($str);
            foreach ($tokens as $token){
                //is the token a comment?
                if($token[0] == 373) {
                    // This is a comment ;-)
                    $fire = explode('*',str_replace('/**','',str_replace('*/','',$token[1])));
                    //is it declared core?
                    if(trim($fire[1]) == '@core true'){
                        require_once('core/'.$value); 
                    }
                }
            }
        }
    }
    //We then load the modules files
    $fun =  scandir('modules/');
    foreach($fun as $key => $value ){
        if(str_replace(".",'',$value) != ''){
            if(file_get_contents("modules/".$value."/load.php")){
                $str =  file_get_contents('modules/'.$value.'/load.php');
                $tokens = token_get_all($str);
                foreach ($tokens as $token){
                    //is the  token a comment?
                    if($token[0] == 373) {
                        // This is a comment ;-)
                        $fire = explode('*',str_replace('/**','',str_replace('*/','',$token[1])));
                        //is it  declared module
                        if(trim($fire[1]) == '@module true'){
                            require_once('modules/'.$value.'/load.php'); 
                            $modules[] = $value;
                        }
                    }
                }
            }

        }
    }
    //Then we load plugins
    $fun =  scandir('plugins/');
    foreach($fun as $key => $value ){
        if(str_replace(".",'',$value) != ''){
            if(file_exists("plugins/".$value."/load.php")){
                $str =  file_get_contents('plugins/'.$value.'/load.php');
                $tokens = token_get_all($str);
                foreach ($tokens as $token){
                    //is the  token a comment?
                    if($token[0] == 373) {
                        // This is a comment ;-)
                        $fire = explode('*',str_replace('/**','',str_replace('*/','',$token[1])));
                        //is it declared plugin 
                        if(trim($fire[1]) == '@plugin true'){
                            require_once('plugins/'.$value.'/load.php'); 
                            $plugins[] = $value;
                        }
                    }
                }
            }
        }
    }
    if(preg_match('/admin/',$_GET['q']) == 0){
    //then we load the themes
    $fun =  scandir('themes/');
    foreach($fun as $key => $value ){
        if(str_replace(".",'',$value) != ''){
            if(file_exists("themes/".$value."/load.php")){
                $str =  file_get_contents('themes/'.$value.'/load.php');
                $tokens = token_get_all($str);
                foreach ($tokens as $token){
                    //is the  token a comment?
                    if($token[0] == 373) {
                        // This is a comment ;-)
                        $fire = explode('*',str_replace('/**','',str_replace('*/','',$token[1])));
                        //is it declared plugin 
                        if(trim($fire[1]) == '@theme true'){
                            if(trim(str_replace('@token','',trim($fire[2]))) == $theme->active_theme()){
                                require_once('themes/'.$value.'/load.php'); 
                            }
                        }
                    }
                }
            }
        }
    }
    }else{
        require_once('Admin/load.php');
    }
}

?>
