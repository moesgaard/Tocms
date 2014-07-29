<?php

/**
 * class forum_stucture
 *
 */
class forum_stucture {
    /** Aggregations: */
    /** Compositions: */
    /*** Attributes: ***/
    /**
     *
     * @access private
     */
    private $urlcode;
    /**
     *
     * @access private
     */
    private $initialize = 0;
    /**
     *
     *
     * @return
     * @access public
     */
    public function __construct( ) {
    } // end of member function __construct

    /**
     * defined is where we are in the forum  and what we must port to the the template
     * definer
     *
     * @param unsigned long position this is the position of where we are at

     * @param char path this is the url path of the website this must be split into atoms to find who
     what and where definitions..

     * @return
     * @access public
     */
    public function defined( $position,  $path ) {
    } // end of member function defined
    /**
     *
     *
     * @param char pathsToLook

     * @return
     * @access public
     */
    public function getChildren( $pathsToLook ) {
    } // end of member function getChildren
    /**
     *
     *
     * @param char theUrlToSplit this is e url we need to split so it can be parsed on

     * @return
     * @access public
     */
    public function findAndSplit( $theUrlToSplit ) {
    } // end of member function findAndSplit
    /**
     *
     *
     * @return
     * @access public
     */
    public function __destruct( ) {
    } // end of member function __destruct
} // end of forum_stucture
?>
