<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @core true
 * @author moesgaard
 */
class group_class {
    private  $dbs;
    public $resultitem;
    function __construct(){
        global $db;
        $this->dbs = $db;
    }
    /**
     * This method is for fetching Group members so we either list or check them
     **/
    function getAllGroups(){
        $itemuser = $this->dbs->getArray('user_groups:', 'groupname,active,position,id:', " 0.1 = '1' ", ' ');
        $this->resultitem = $itemuser;
        return $this->resultitem;
    }
    function getGroupMembers($id = ''){
        if(is_integer($id)){
            $bob = $this->dbs->getAffectedRows('users:', 'username,rights,id:', " 0.1 LIKE '%".$id."%' ", " ");
        }else{
        }
        return $bobs;
    }
    /**
     *This method is for gettting current users group
     **/
    function getUsersGroup($uid = ''){
        if(!is_integer($id)){
            $bob = $this->dbs->getAffectedRows('users:', 'username,rights,id:', " 0.0 = '" .$id. "' ", " ");
        }else{
            $bob = $this->dbs->getAffectedRows('users:', 'username,rights,id:', " 0.2 = '" .$id. "' ", " ");

        }
        return $bob;
    }
    /**
     * this is basicly for cheking if a person has been banned as  you can be a group member
     * but can be pushed into a group ie if people has created a banned group
     * so if the group exists in the persons group array they can ban or promote a person
     **/
    function getCurrentGroup($id = ''){
    }
    /**
     * this is for a subgroup check due to people can be member of multiple groups
     **/
    function getSubGroups($gid = ''){
    }
    function __destruct(){
    }
}
?>
