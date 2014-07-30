<?php
/**
 *@package interfaces
 */
/**
 *This is the interface for  the html class
 */
/**
 *@package interfaces
 */

interface html_interface {
    function __construct() ;
    function arraysplit($tagid='') ;
    function div($array) ;
    function image($array) ;
    function table($array ) ;
    function tableCell($array) ;
    function tableRow($array );
    function breaker($amount=1);
    function ptag($array) ;
    function span($array) ;
    function headline($array) ;
    function lists($array) ;
    function ahref($array) ;
    function pre($array) ;
    function ul($array) ;
    function select($array) ;
    function option($array) ;
    function description($description) ;
    function label($info) ;
    function input($array) ;
    function inputField($array) ;
    function hiddenField() ;
    function form($array,$type ,$method="POST") ;
    function __destruct() ;
}
?>
