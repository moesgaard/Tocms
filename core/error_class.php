<?php
/**
 * @core true 
 * @member core
 * @package error 
 */
class logErrors {
    function __construct(){

    }
    public  static function write($ExceptionError,$write = false ) {
        if($write == false) {
            //            mailme::mail($ExceptionError);
        }
        /**
         * all caught exceptions gets written here
         * into a flat file so here we get ALL errors..
         */
        if(is_file('logs/error/error_log.txt')) {
            $handle = fopen('logs/error/error_log.txt',"a");
            fwrite($handle,'<handle><date>'.date("H:i:s j-m-y ").'</date><message>'.$ExceptionError."".@debug_backtrace($ExceptionError)."</message></handle>");
            fclose($handle);

        }else {
            // mailme::mail("the file error/error_logs.txt does not excistplease create it to maintain the errors..");

        }

    }
    function __destruct() {
    }
}
set_exception_handler('logErrors::write');
?>
