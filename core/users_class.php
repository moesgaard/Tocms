<?php
/**
 * Description of users_class
 * This is the user class where we can set groups and check login, credentials.
 * @author moesgaard
 */
class users_class {
    //put your code here
    public $dbs;
    function __construct($alias = '') {
        global $db,$userinfo,$mails;
        $this->dbs = $db;
        if (!empty($alias)) {
            if ($this->user_logincheck() == false) {
                if ($this->user_login($alias) == true) {
                    $_SESSION['logintime'] = time();
                } else {
                    return false;
                }
            }else{
            }
        }else{
            $this->user_logincheck();
        }
    }
   function user_logincheck(){
	}
	function user_login($alias){

}
   function __destruct() {
        if ($bob != '') {
            return $nothing;
        }
    }

}
?>
