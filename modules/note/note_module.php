<?php
/**
 * This will contain the note module wich will generate the normal note
 * such as blogs and forum posts etc
 *
 */
class note {
    function __construct() {
    }
    function initialize() {
        return $array = array('note' => $this->__note_draw($_GET['q']),'note/$'=> $this->note_definition($_GET['q']));
    }
    function __note_draw($section) {
        $result = '';
        if(preg_match('/\/([0-9])/', $section) == 1) {
            $result =  "numbers Found";
            $numberfinder = explode('/',$section);
            foreach($numberfinder as $key => $value) {
                if(preg_match('/[0-9]/',$value) ) {
                    if($key == 1 ) {
                        $result .=  ' </br> Mainsection found it is'.$value;
                    }else {
                        $result .=  ' </br> Subsection found it is'.$value;
                    }
                }else {
                }
            }
        }
        return $result;
    }
    function note_definition($string) {
        $parts = explode('/', $string);
        $cleaned = addslashes($parts[1]);
        if(is_numeric($cleaned)) {
            /**
             * if it is numeric we search on the id
             * to proceed it to the settings and display it correct
             **/
        }else {
            /**
             * if it not is numeric we search on the name/url
             * to proceed it to the settings and display it correct
             **/
        }
    }
    function note_settings($noteId) {
        /**
         * We find the settings for this note so we can proceed correctly
         *
         */
    }
    function __destruct() {
    }
}
?>
