<?php
/**
 * This is the class for creating the forum this is used for the basic forum
 * expanded forum will be created soon
 */
/**
 * @package class
 **/
class forum {
    public $data;
    public $wupti;
    public $names;
    public $arrays;
    public $Subjects;
    public $id;
    public $SubDivs;
    public $setting;
    public function __construct($first = false){
        global $db,$html,$pear,$layout;
        $this->data = $db;
        $this->htmls = $html;
        $this->layouts = $layout;
        $monster = '';
        switch($first){       
        case "1":
            $monster = $this->draw_index($layout);
            break;
        }
        return $monster;
    }
    public function initialize(){
        $point = array('forum' => $this->draw_index(), 'forum/$' => $this->draw_index($_GET['q']),'&forum/setting&' => $this->draw_index());
        return $point;
    }
    public function draw_index($section = ''){
        global $db,$html;
        echo $this->layouts;
        if(trim($this->layouts) != ''){
            $this->setting = explode(',',$this->layouts);
            $this->setting['forum']['TheMainIndex'] = $this->setting[0];
            $this->setting['forum']['MainCat']['MainNames'] = $this->setting[1];
            $this->setting['forum']['MainCat']['SubDivs'] = $this->setting[2];
            return $html->div(array( 'id' => $this->setting['forum']['TheMainIndex'], 'content' => "fanget  ".$this->getMainIndex().""));
        }else{
            return 'No index yet';
        }
    }
    public function getMainIndex(){
        global $db,$html;
        $i = 0;
        $this->data = array('name' => 'Seti', 'description' => 'nagash');
        foreach($this->data as $key){
            if($key['name']){
                $this->wupti .= $html->div('','','',
                    $html->headline('3','','','',
                    $html->ahref('#','','','', $key['name']."--".$key['description'])).
                    $html->div(array( 'id' => $this->setting['forum']['MainCat']['MainNames'].'-'.$i ),'','',$this->getMainCats($key['id'])));
            }
            $i++ ;
        }
        return $this->wupti;
    }
    public function getMainCats( $id = ""){
        global $db,$html,$pear,$setting;
        $this->data = array();
        $this->names = "";
        $this->SubDivs = "";
        foreach($this->data as $key){
            $this->names .= $html->lists('' ,'','',$html->ahref('#'.$this->setting['forum']['MainCat']['MainNames'].'-'.$key['Id'],'','','',$key['Name']));
            $this->SubDivs .= $html->div(array( 'id' => $this->setting['forum']['MainCat']['SubDivs'].'-'.$key['Id'] ),'','',$this->getSubjectOfMainCats($key['SubIds'],$key['Id']));
        }
        return '<ul>'.$this->names.'</ul>'.$this->SubDivs ;
    }
    public function getSubjectOfMainCats($cats,$mainid){
        global $db,$html,$pear,$setting;
        $this->Subjects = "";
        $this->arrays = "";
        $this->arrays = explode(',',$cats);
        foreach($this->arrays as $key => $values){
            $this->data = $db->getAssoc();
            if(!empty($this->data)){
                foreach($this->data as $keys){
                    $this->Subjects .= $html->ahref('#Subject-'.$keys['Id']  ,'','','',$keys['Name']).$html->breaker('1');
                }
            }
        }
        return	$this->Subjects;
    }
    public function getAnswers($subject,$access){
        $this->data = $db->getAssoc(array( ));
    }
    public function __destruct(){
    }
}
?>
