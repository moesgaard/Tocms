<?php
/* *
 * this will contain the  pages..
 * pages will be for standalone "clones" of the system e.g.
 * people have a branch site of the mainsite
 * so they throw in a small script for the website
 * with the standards in a URL that will fecth this system
 * and the specs will be generating a page via the ajax call
 * and  they only need one system for  multiple pages
 */
class page {
    public $page = '';
    public $dbs;
    public $generateAction;
    function  __construct($i = '') {
        global $db;
        $alias = '';
        $this->dbs = $db;
        if(isset($_GET['q'])){
            $this->page = $_GET['q'];
        }else{
            $this->page = '';
        }
    }
    function  __destruct() {
    }
}
?>
