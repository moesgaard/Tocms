<?php
/* 
 * This class is for creation of nodes and pages
 * So we do first off a call to see
 * if there is anything preset for the fields
 * if not we go to call hook settings to see
 * if there are any hooked functions incase of module is involved
 * last we call the basic settings
 * when this has been runned once it will be set to preset so so we cut off some of the execution time.
 *
 */

/**
 * Description of content_create_interface
 *
 * @author moesgaard
 */
class content_create_interface {
    public function __construct($set){
    }
    public function callType(){
    }
    public function callMethod(){
    }
    public function callSettings(){
    }
    public function callHookSettings(){
    }
    public function callHookMethod(){
    }
    public function callPreSettings(){
    }
    public function __destruct(){
    }
}
?>
