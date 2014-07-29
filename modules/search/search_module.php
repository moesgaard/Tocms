<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of search_module
 *
 * @author moesgaard
 */
class search {
    public $result = '';
    public $data = '';
    public $htmls = '';
    public function __construct($first = false){
        global $db,$html,$pear,$layout;
        $this->data = $db;
        $this->layouts = $layout;
        $monster = '';
    }
    public function initialize(){	
        $point = array('search' => $this->search(),'search/$' => $this->searchid($_GET['q']));
        return $point;
    }
    public function search($array = ''){
        $this->results = '';
        $fieldings = new fields();
        if($_POST['query'] != '' && isset($_POST['query'])){
            $this->result =  $this->data->getArray('products:','prodnumber,groupid,productname,prodfrom,prodto,prodprice,prodlink,prodnotice,id:'," ( 0.0 LIKE '%".$_POST['query']."%') OR ( 0.1 LIKE '%".$_POST['query']."%') OR ( 0.2 LIKE '%".$_POST['query']."%') OR ( 0.3 LIKE '%".$_POST['query']."%') OR ( 0.4 LIKE '%".$_POST['query']."%') OR ( 0.5 LIKE '%".$_POST['query']."%') OR ( 0.6 LIKE '%".$_POST['query']."%' ) OR ( 0.7 LIKE '%".$_POST['query']."%')  ",'');    
            $fielding = array();
            if(count($this->result) == 1){
                foreach( $this->result as $key => $value ){
                    $geo[0] = array('type' => 'div', 'div' => array(
                        'content' => '<h2>Vare:</h2> <hr/>'.$value[prodnumber].'  -'.$value[productname].'', 'class' => 'class="productname_single"')
                    );
                    $prodgroup = $this->data->getArray('product_group:','prodgroup,groupname:',"  0.0 = '".$value['groupid']."'",'');    
                    $geo[1] = array('type' => 'div', 'div' => array('content' => '<h2>Varegruppe:</h2> <hr/>'.$prodgroup[0]['groupname'].'', 'class' => 'class="prodnumber_single"')
                    );
                    $geo[2] = array('type' => 'div', 'div' => array(
                        'content' => '<h2> Bem&aelig;rkning:</h2><hr/>'.$value[prodnotice].'', 'class' => 'class="prodprice_single"')
                    );
                    $geo[3] = array('type' => 'div', 'div' => array(
                        'content' => '<h2>Pris:</h2><hr/> '.$value[prodprice].'', 'class' => 'class="prodprice_single"')
                    );
                    $explosion = explode('/',$value[prodlink]);
                    $counter = count($explosion);
                    $couting = $counter-1;
                    $geo[4] = array('type' => 'div', 'div' => array(
                        'content' => '<h2>Link:</h2> <hr/> <a href="'.$value[prodlink].'" id="prodLink" target="_blank" rel="'.$explosion[$couting].'"> Link til produkt</a> ', 'class' => 'class="prodLink_single"')
                    );
                    for( $i=0; $i < 5 ;$i++){
                        $geon .= $fieldings->fieldType($geo[$i]);
                    }
                    $fielding[] = array('type' => 'div',
                        'div' =>  array(
                            'content' =>''.$geon.'' ,
                            'id'=>'id="IA"')
                        ); 
                } 
            }else{
                foreach( $this->result as $key => $value ){
                    $geo = '';
                    $geo[0] = array('type' => 'div', 'div' => array(
                        'content' => '<a  href="/search/'.$value[id].'" class="link" >'.$value[prodnumber].'</a>', 'class' => 'class="prodnumber"')
                    );
                    $geo[1] = array('type' => 'div', 'div' => array(
                        'content' => ''.$value[productname].'', 'class' => 'class="productname"')
                    );
                    $geo[2] = array('type' => 'div', 'div' => array(
                        'content' => ''.$value[prodprice].'', 'class' => 'class="prodprice"')
                    );
                    $geon = '';
                    for( $i=0; $i <= 2 ;$i++){
                        $geon .= $fieldings->fieldType($geo[$i]);
                    }
                    $fielding[] = array('type' => 'div',
                        'div' =>  array(
                            'content' =>''.$geon.'' ,
                            'class'=>'class="itemcontainer"')
                        ); 
                }
            }
            $counts  = count($fielding);
            for($i = 0; $i < $counts ; $i++){
                $this->results .= $fieldings->fieldType($fielding[$i]);
            }
        }
             /*   if($this->results  == '' || empty($this->results)){
                              $fielding[] = array('type' => 'div',
                               'div' =>  array(
                                   'content' => 'Der er intet resultat p&aring; s&oslash;gningen ' ,
                                   'class'=>'class="itemcontainer"')
                                   ); 

                   $counts  = count($fielding);
                for($i = 0; $i < $counts ; $i++){
                    $this->results .= $fieldings->fieldType($fielding[$i]);
                }

                }                  
              */
        return $this->results ;
    }
    public function searchid($item){
        $fieldings = new fields();
        if($item != ''){
            $explosion = explode('/',$item);
            $this->result =  $this->data->getArray('products:','prodnumber,groupid,productname,prodfrom,prodto,prodprice,prodlink,prodnotice,id:',"  0.8 = '".$explosion[1]."'",'');    
            foreach( $this->result as $key => $value ){
                $geo[] = array('type' => 'div', 'div' => array(
                    'content' => '<h2>Vare:</h2> <hr/>'.$value[prodnumber].'  -'.$value[productname].'', 'class' => 'class="productname_single"')
                );
                $prodgroup = $this->data->getArray('product_group:','prodgroup,groupname:',"  0.0 = '".$value['groupid']."'",'');    
                $geo[] = array('type' => 'div', 'div' => array('content' => '<h2>Varegruppe:</h2> <hr/>'.$prodgroup[0]['groupname'].'', 'class' => 'class="prodnumber_single"')
                );
                $geo[] = array('type' => 'div', 'div' => array(
                    'content' => '<h2> Bem&aelig;rkning:</h2><hr/>'.$value[prodnotice].'', 'class' => 'class="prodprice_single"')
                );
                $geo[] = array('type' => 'div', 'div' => array(
                    'content' => '<h2>Pris:</h2><hr/> '.$value[prodprice].'', 'class' => 'class="prodprice_single"')
                );
                $explosion = explode('/',$value[prodlink]);
                $counter = count($explosion);
                $couting = ($counter-1);
                $geo[] = array('type' => 'div', 'div' => array(
                    'content' => '<h2>Link:</h2> <hr/> <a href="'.$value[prodlink].'" id="prodLink" target="_blank" rel="'.$explosion[$couting].'"> Link til produkt</a> ', 'class' => 'class="prodLink_single"')
                );
                for( $i=0; $i < 5 ;$i++){
                    $geon .= $fieldings->fieldType($geo[$i]);
                }
                $fielding = array('type' => 'div',
                    'div' =>  array(
                        'content' =>''.$geon.'' ,
                        'id'=>'id="IA"')
                    ); 
            } 
        }
        $this->results .= $fieldings->fieldType($fielding);
        return $this->results ;
    }
    public function __destruct(){
    }
}
?>
