<?php
/**
 * @core true
 * @author moesgaard
 */
class users_class {
    //put your code here
    public $db;
    function __construct($alias = '') {
        global $db;
        $this->db = $db;
       /* if (!empty($alias)) {
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
       }*/
    }
    function user_logincheck(){
    }
    public function blacklistcheck(){
        $ip = $_SERVER['REMOTE_ADDR'];
        $one = $this->db->getArray('ip_blacklist:','ip,blacklist,ban:',' 0.0 = \''.$ip.'\' AND ( 0.1 = \'1\' OR 0.2 = \'1\')','');
        if(!empty($one)){
            return 'die';
        }else{
        $this->checklogin();
        }
    }
    function checklogin(){
        if($_COOKIE['login'] == sha1('logedin')){
            return true;
        }else{
            if($_POST['username'] != '' ){
                $user = $this->db->getArray('user:','id,username,password,is_active,groups,is_admin,admin_token,name',' 0.1 = \''.sha1($_POST["username"]).'\' AND 0.2 =\''.sha1($_POST["password"]).'\' AND 0.3 =\'0\' ','');
                if(!empty($user) != ''){
                    $expire = time() + 3600;
                    setcookie('login',sha1('logedin'),$expire,'/');
                    setcookie('userinfo',$user[0]['username'],$expire,'/');
                    setcookie('name',$user[0]['name'],$expire,'/');
                    if($user[0]['is_admin'] == '1'){
                     setcookie('userpriv',$user[0]['admin_token'],$expire,'/');
                    }   
                    $page = $_GET[q];
                    header("Refresh: url=$page");
                    
                }
            }
        }
    }
    function user_login(){
    }
    function __destruct() {
    }
}
$user = new users_class();
?>
