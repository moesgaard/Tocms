<?php
/**
 *@package class
 */
/**
 *This is the class for  the css
 */
/**
 *@package class
 */
class css_class{
    private $cssArray;
    private $theming = "";
    public function __construct() {
        global $db,$theme,$currentTheme;
        $themer = $theme->themeActive();
        $this->theming = "";
        if(is_array($themer)) {
            $this->theming .= " 0.1 = ".$themer[0]['id']." ";
            $this->cssArray = $db->getArray('css_content','id,parent,content,path,name',''. $this->theming.'','');
        }else {
            $this->__destruct();
        }
    }
    public function activeModules() {}
        public function cssPath() {
        }
    public function headcontent() {
        global $db,$theme,$currentTheme;
        $themer = $theme->themeActive();
        $this->theming = "";
        if(is_array($themer)) {
            $this->theming .= " 0.1 = ".$themer[0]['id']." ";
            $this->cssArray = $db->getArray('css_content','id,parent,content,path,name',''. $this->theming.'','');
        }
        $content = '';
        if(is_array($this->cssArray) && !empty($this->cssArray)) {
            foreach($this->cssArray as $key => $value) {
                $content .= "<link  rel=\"stylesheet\" type=\"text/css\" href=\"http://".$_SERVER['SERVER_NAME']."".$this->cssArray[$key]['path']."/".$this->cssArray[$key]['content']."\" />";
            }
        }
        return $content;
    }
    public function __destruct() {}
}
?>
