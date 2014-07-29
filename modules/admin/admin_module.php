<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of admin_module
 *
 * @author moesgaard
 */
class admin {
    public $resulter;
    public $resultitem;
    public $result;
    private $inserted;
    public $mail;
    function __construct() {
        global $db,$html,$pear,$layout,$mails;
        $this->data = $db;
        $this->htmls = $html;
        $this->layouts = $layout;
        $this->mail = $mails;
    }
    function initialize() {
        return array('admin' => $this->switchsection(),'admin/$' => $this->switchsection());
    }
    function switchsection() {
        $bob = explode('/',$_GET['q']);
        switch ($bob[1]) {
        case "addnews":
            $this->resulter = $this->addnews()." ".$this->listnewsforedit();
            break;
        case "addgroups":
            $this->resulter = $this->addgroups();
            break;
        case "deletegroups":
            $this->resulter = $this->deletegroups($bob[2]);
            break;
        case "addusers":
            $this->resulter = $this->addusers();
            break;
        case "editcompany":
            $this->resulter = $this->editcompany();
        case "addcompany":
            $this->resulter = $this->addcompany();
            break;
        case "removeusers":
            $this->resulter = $this->removeusers($bob[2]);
            break;
        case "listusers":
            $this->resulter = $this->listusers();
            break;
        case "addquestion":
            $this->resulter = $this->addquestions();
            break;
        case "createsubanswer":
            $this->resulter = $this->createsubanswer($bob[2]);
            break;
        case "addsubquestion":
            $this->resulter = $this->addsubquestions();
            break;
        case "removequestion":
            $this->resulter = $this->addquestions();
            break;
        case "removesubquestion":
            $this->resulter = $this->addsubquestions();
            break;
        case "removenews":
            $this->resulter = $this->removenews();
            break;
        case "logusers":
            $this->resulter = $this->throwusers();
            break;
        case "editsection":
            $this->resulter = $this->editsections();
            break;
        case "deletesections":
            $this->resulter = $this->deletesections();
            break;
        case "addmoreusers":
            $this->resulter = $this->addmoreusers();
            break;
        case "editusers":
            $this->resulter = $this->addusers();
            break;
        case 'editmainquestion':
            $this->resulter = $this->editmainanswer($bob[2]);
            break;
        case 'editsubquestion':
            $this->resulter = $this->editsubanswer($bob[2]);
            break;
        case "createmainanswer":
            $this->resulter = $this->addquestion();
            break;
        case "createmainquestion":
            $this->resulter = $this->createmainanswer($ownerid);
            break;
        case "addpdf":
            $this->resulter = $this->addfiles();
            break;
        case "mycompanies":
            $this->resulter = $this->assignCompanys();
            break;
        case "specialadmin":
            $this->resulter = $this->assignCompanyspecial();
            break;
        default:
            $this->resulter = '<div>Velkommen til Administrations delen </div>';
            break;
        }
        switch ($bob[0]) {
        case "editusers":
            $this->resulter = $this->addusers();
            break;
        }
        return $this->resulter;
    }
    function get_icons($id = ''){
        $questionings = $this->data->getArray('etypes:','refid,imagename,name:',' 0.0 = \''.$id.'\' ','ORDER  BY 0.1 ASC');
        $list = "<img src=\"".$questionings[0]['imagename']."\"  alt=\"".$questionings[0]['name']."\"  style=\"width:20px; height:20px;margin-left:2px; \"  title=\"".$questionings[0]['name']."\" class=\"icons_for_list\" />";
        return $list;
    }
    public function editsubanswer($id) {
        if (isset($_POST) && !empty($_POST)) {
            if ($_POST['etypedegree'] != '') {
                $resources = $this->resourcesmake($_POST['etypedegree']);
                $degrees = $_POST['etypedegree'];
            } elseif ($_POST['etypedegrees'] != '') {
                $resources = $this->resourcesmake($_POST['etypedegrees']);
                $degrees = $_POST['etypedegrees'];
            }
            $_POST['headline'] = $_POST['question'];
            $sql = " UPDATE question_sub SET question = '" . trim($_POST['headline']) . "', description = '" . trim($_POST['description']) . "', degreelimits ='".$degrees."', resource =  '".$resources."',etype = '".$_POST['etype']."'  WHERE id = '" . $id . "');";
            $this->data->sqlInsert($sql);
            $_POST = '';
        }
        $questions = $this->data->getArray('question_sub', 'id,question,description,active,restricted,degreelimits,etype:', '0.0 = \'' . $id . '\'', ' LIMIT 1 ');
        $this->reason = $questions;
        return  $this->reason;
    }
    function editmainanswer($id) {
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST['groups'])) {
                foreach ($_POST['groups'] as $key => $value) {
                    $groups .= trim($value).',';
                }
            }
            if ($_POST['frequensmain'] == "D") {
                foreach ($_POST['days'] as $key => $value) {
                    $rights .= trim($value).',';
                }
            } else {
                $rights = $_POST['frequensmain'];
            }
            if($_POST['frequensmain'] == 'W'){
                $rich = ",oddweek ='".$_POST['odd']."',evenweek ='".$_POST['even']."' ";
            }else{
                $rich = ",oddweek ='',evenweek ='' ";
            }
            $sql = " UPDATE  question_main SET question='".trim($_POST['question'])."', active='".(trim(strtolower($_POST['active'])) == '1' ? '1' : '0')."',freqency = '".$rights."' , restricted='".$rights."',is_Important = '".$_POST['timeofday']."', trickerdate = '".$_POST['firstrun']."' WHERE id = '".$id."'";
            $this->data->sqlInsert($sql);
            $_POST = '';
        }
        $questions = $this->data->getArray('question_main','question,active,restricted,id,rights,freqency,oddweek,evenweek,is_important,trickerdate','0.3 = \''.$id.'\'','');
        $this->reason = $questions;
        return  $questions;
    }
    function removenews() {
        $sql = "DELETE FROM newsitems WHERE  id = '".str_replace('admin/removenews/','',$_GET[q])."'";
        $this->data->sqlInsert($sql);
        return '<script type="text/javascript">window.location = "/admin/addnews"; </script>';
    }
    function assignCompanys() {
        if (!empty($_POST['comps'])) {
            $bobs = $this->data->getArray('company_assigned:','userid,companies:'," 0.0 = '".$_POST['userid']."' ",' LIMIT 1');
            foreach ($_POST['comps'] as $key => $value) {
                if ($value != '') {
                    $comps .= ','.$value;
                }
            }
            $bob = count($bobs);
            if ($bob == 1) {
                $sql = " UPDATE company_assigned SET companies = '".$comps."'  WHERE userid = '".$_POST['userid']."' ;";
                $this->data->sqlInsert($sql);
            } else {
                $sql = " INSERT INTO  company_assigned (userid,companies)VALUES('".$_POST['userid']."','".$comps."');";
                $this->data->sqlInsert($sql);
            }
        }
        $this->result = $this->data->getArray('company_assigned:','userid,companies:'," 0.0 = '".$_POST['userid']."' ",' LIMIT 1');
        if (!empty($this->result)) {
            return $this->result[0]['companies'];
        }
    }
    function assignCompanyspecial() {
        if (!empty($_POST['comps'])) {
            $bobs = $this->data->getArray('company_special_assigned:','userid,companies:'," 0.0 = '".$_POST['userid']."' ",' LIMIT 1');
            foreach ($_POST['comps'] as $key => $value) {
                if ($value != '') {
                    $comps .= ','.$value;
                }
            }
            $bob = count($bobs);
            if ($bob == 1) {
                $sql = " UPDATE company_special_assigned SET companies = '".$comps."'  WHERE userid = '".$_POST['userid']."' ;";
                $this->data->sqlInsert($sql);
            } else {
                $sql = " INSERT INTO  company_special_assigned (userid,companies)VALUES('".$_POST['userid']."','".$comps."');";
                $this->data->sqlInsert($sql);
            }
        }
        $this->result = $this->data->getArray('company_special_assigned:','userid,companies:'," 0.0 = '".$_POST['userid']."' ",' LIMIT 1');
        if (!empty($this->result)) {
            return $this->result[0]['companies'];
        }
    }
    function addmefile() {
        if (isset($_FILES)) {
            if ($_FILES["Filedata"]["error"] != 0) {
            } else {
                //  5MB maximum file size
                $MAXIMUM_FILESIZE = 100 * 1024 * 1024;
                //  Valid file extensions (images, word, excel, powerpoint)
                $rEFileTypes = "/^\.(jpg|jpeg|gif|png|doc|docx|zip|pdf){1}$/i";
                $dir_base = getcwd()."/files/pdf/";
                $isFile = is_uploaded_file($_FILES['Filedata']['tmp_name']);
                if ($isFile == 1) {   //  do we have a file? {//  sanatize file name
                    //     - remove extra spaces/convert to _,
                    //     - remove non 0-9a-Z._- characters,
                    //     - remove leading/trailing spaces
                    //  check if under 5MB,
                    //  check file extension for legal file types
                    $safe_filename = preg_replace(
                        array("/\s+/","/[^-\.\w]+/"),array("_",""),trim($_FILES['Filedata']['name']));
                    if ($_FILES['Filedata']['size'] <= $MAXIMUM_FILESIZE &&
                        preg_match($rEFileTypes,strrchr($safe_filename,'.'))) {
                            $safe_filename = rand(0,10000000)."_".$safe_filename;
                            $isMove = move_uploaded_file($_FILES['Filedata']['tmp_name'],$dir_base.$safe_filename);
                        }
                }
            }
            return '/files/pdf/'.$safe_filename;
        }
    }
    function addfiles() {
        if (isset($_FILES)) {
            if ($_FILES["Filedata"]["error"] != 0) {
            } else {
                //  5MB maximum file size
                $MAXIMUM_FILESIZE = 100 * 1024 * 1024;
                //  Valid file extensions (images, word, excel, powerpoint)
                $rEFileTypes = "/^\.(jpg|jpeg|gif|png|doc|docx|zip|pdf){1}$/i";
                $dir_base = getcwd()."/files/pdf/";
                $isFile = is_uploaded_file($_FILES['Filedata']['tmp_name']);
                if ($isFile == 1) {   //  do we have a file? {//  sanatize file name
                    //     - remove extra spaces/convert to _,
                    //     - remove non 0-9a-Z._- characters,
                    //     - remove leading/trailing spaces
                    //  check if under 5MB,
                    //  check file extension for legal file types
                    $safe_filename = preg_replace(
                        array("/\s+/","/[^-\.\w]+/"),array("_",""),trim($_FILES['Filedata']['name']));
                    if ($_FILES['Filedata']['size'] <= $MAXIMUM_FILESIZE &&
                        preg_match($rEFileTypes,strrchr($safe_filename,'.'))) {
                            $safe_filename = rand(0,10000000)."_".$safe_filename;
                            $isMove = move_uploaded_file($_FILES['Filedata']['tmp_name'],$dir_base.$safe_filename);
                        }
                }
            }
            $sql = "INSERT  INTO userfiles (id,name,description,owner,filespath,compadminonly)VALUES('','".htmlentities("".$_POST['name']."",ENT_QUOTES,"ISO-8859-1")."','".htmlentities("".$_POST['description']."",ENT_QUOTES,"ISO-8859-1")."' ,'".$_POST['companyid']."','/files/pdf/".$safe_filename."','".$_POST['firmaadmin']."');  ";
            $this->data->sqlInsert($sql);
            $_FILES["Filedata"]["error"] = 1;
            echo "<script type=\"text/javascript\"> alert('Du har nu lagt en fil op der kan ses af ".($_POST['firmaadmin'] == 1 ? ' Firma administrator ' : 'alle i det  valgte  firma')."');  </script>";
            $_POST = '';
        }
    }
    function throwusers() {
        $itemuser = $this->data->getArray('users_login_logs:','username,ip,logintime,id:'," 0.1 != '' ",' ORDER by logintime DESC ');
        foreach ($itemuser as $key => $value) {
            $this->listen .= " <div class=\"newsitm\">".$value['username']." &nbsp; - &nbsp; ".$value['ip']."&nbsp;  - &nbsp;  ".date('d/m/y H:i:s  ',$value[logintime])."</div>";
        }
        $this->resultitem = '<div id="newsitemcont">'.$this->listen.'</div>';
        return $this->resultitem;
    }
    function resourcesmake($val) {
        if (trim($val) != '') {
            $val = trim($val);
            if ($val <= 39) {
                if (($val + 2) >= 0) {
                    $deduct = 2.5;
                } else {
                    $deduct = 2;
                }
                $value = '<p class="even">
                    <label>
                    <input class="bad"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="error" "/>
                    <font class="bad" > Over '.($val + $deduct ).' &#8451;  </font> <br/>
                    </label>
                    </p> ';
                for ($i = $val + 2; $i >= $val - 2; $i--) {
                    if ($val >= $i) {
                        $lass = 'good';
                    } else {
                        $lass = 'bad';
                    }
                    if ($i >= 0 && $i != $val) {
                        if ($i == 0) {
                            $value .= '
                                <p class="even">
                                <label>
                                <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                                <font  class="'.$lass.'" >    '.$i.'.5 &#8451;</font> <br/>
                                </label>
                                </p>
                                <p class="even">
                                <label>
                                <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                                <font  class="'.$lass.'" >    '.$i.' &#8451;</font> <br/>
                                </label>
                                </p>
                                <p class="even">
                                <label>
                                <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.-$i.'.5"/>
                                <font  class="'.$lass.'" >    -'.$i.'.5 &#8451;</font> <br/>
                                </label>
                                </p>'
                                ;
                        } else {
                            $value .= '
                                <p class="even">
                                <label>
                                <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                                <font  class="'.$lass.'" >    '.$i.'.5 &#8451;</font> <br/>
                                </label>
                                </p>
                                <p class="even">
                                <label>
                                <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                                <font  class="'.$lass.'" >    '.$i.' &#8451;</font> <br/>
                                </label>
                                </p>';
                        }
                    } else {
                        if ($val == $i) {
                            if ($i < 0) {
                                $value .= '
                                    <p class="even">
                                    <label>
                                    <input class="good"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                                    <font  class="good" >    '.$i.' &#8451;</font> <br/>
                                    </label>
                                    </p>
                                    <p class="even">
                                    <label>
                                    <input class="good" name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                                    <font  class="good" >    '.$i.'.5 &#8451;</font> <br/>
                                    </label>
                                    </p>';
                            } else {
                                if ($i == 0) {
                                    $value .= '
                                        <p class="even">
                                        <label>
                                        <input class="bad"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                                        <font  class="bad" >    '.$i.'.5 &#8451;</font> <br/>
                                        </label>
                                        </p>
                                        <p class="even">
                                        <label>
                                        <input class="good"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                                        <font  class="good" >    '.$i.' &#8451;</font> <br/>
                                        </label>
                                        </p>
                                        <p class="even">
                                        <label>
                                        <input class="good"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.-$i.'.5"/>
                                        <font  class="good" >    -'.$i.'.5 &#8451;</font> <br/>
                                        </label>
                                        </p>'
                                        ;
                                } else {
                                    $value .= '
                                        <p class="even">
                                        <label>
                                        <input class="bad"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                                        <font  class="bad" >    '.$i.'.5 &#8451;</font> <br/>
                                        </label>
                                        </p>
                                        <p class="even">
                                        <label>
                                        <input class="good" name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                                        <font  class="good" >    '.$i.' &#8451;</font> <br/>
                                        </label>
                                        </p>';
                                }
                            }
                        } else {
                            $value .= '
                                <p class="even">
                                <label>
                                <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                                <font  class="'.$lass.'" >    '.$i.' &#8451;</font> <br/>
                                </label>
                                </p>
                                <p class="even">
                                <label>
                                <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                                <font  class="'.$lass.'" >    '.$i.'.5 &#8451;</font> <br/>
                                </label>
                                </p>';
                        }
                    }
                    $last = $i;
                }
                if ($last == 0) {
                    $last = '-0';
                }
                if (($val - 2) <= 0) {
                    $half = '.5';
                }
                $value .= '<p class="even">
                    <label>
                    <input class="good"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="ok"/>
                    <font class="good" > Under '.$last.$half.' &#8451; </font> <br/>
                    </label>
                    </p> ';
            } else {
                if ($val > 40) {
                    $value = '<p class="even">
                        <label>
                        <input class="bad"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="error"/>
                        <font class="bad" > Under '.($val - 2).'.5 &#8451;  </font> <br/>
                        </label>
                        </p> ';
                } else {
                    $value = '<p class="even">
                        <label>
                        <input class="bad"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="error"/>
                        <font class="bad" > Under '.($val - 2).'.5 &#8451;  </font> <br/>
                        </label>
                        </p> ';
                }
                for ($i = $val - 2; $i <= $val + 2; $i++) {
                    if ($val < 40) {
                        if ($i <= $val) {
                            $lass = 'good';
                        } else {
                            $lass = 'bad';
                        }
                    } else {
                        if ($i >= $val) {
                            $lass = 'good';
                        } else {
                            $lass = 'bad';
                        }
                    }
                    if ($i <= 0) {
                        $value .= '
                            <p class="even">
                            <label>
                            <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                            <font  class="'.$lass.'" >'.$i.'.5 &#8451;</font> <br/>
                            </label>
                            </p>
                            <p class="even">
                            <label>
                            <input clas="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                            <font  class="'.$lass.'" >    '.$i.' &#8451;</font> <br/>
                            </label>
                            </p>';
                    } else {
                        if ($val == $i) {
                            if ($val <= 0) {
                                $value .= '
                                    <p class="even">
                                    <label>
                                    <input class="good"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                                    <font  class="good" >    '.$i.' &#8451;</font> <br/>
                                    </label>
                                    </p>
                                    <p class="even">
                                    <label>
                                    <input class="good" name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                                    <font  class="good" >    '.$i.'.5 &#8451;</font> <br/>
                                    </label>
                                    </p>';
                            } else {
                                $value .= '
                                    <p class="even">
                                    <label>
                                    <input class="good"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                                    <font  class="good" >    '.$i.' &#8451;</font> <br/>
                                    </label>
                                    </p>
                                    <p class="even">
                                    <label>
                                    <input class="good" name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                                    <font  class="good" >    '.$i.'.5 &#8451;</font> <br/>
                                    </label>
                                    </p>';
                            }
                        } else {
                            $value .= '
                                <p class="even">
                                <label>
                                <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'"/>
                                <font  class="'.$lass.'" >    '.$i.' &#8451;</font> <br/>
                                </label>
                                </p>
                                <p class="even">
                                <label>
                                <input class="'.$lass.'"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="'.$i.'.5"/>
                                <font  class="'.$lass.'" >    '.$i.'.5 &#8451;</font> <br/>
                                </label>
                                </p>';
                        }
                    }
                    $last = $i;
                }
                if ($val >= 40) {
                    $value .= '<p class="even">
                        <label>
                        <input class="good"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="ok"/>
                        <font class="good" > Over '.$last.'.5 &#8451;  </font> <br/>
                        </label>
                        </p> ';
                } else {
                    $value .= '<p class="even">
                        <label>
                        <input class="bad"   name="answer-{$pageid}-{$content[contents].id}" type="radio" value="error"/>
                        <font class="bad" > Over '.$last.'.5 &#8451;  </font> <br/>
                        </label>
                        </p> ';
                }
            }
            return $value;
        }
    }
    function get_companyinfo($id) {
        $this->result = $this->data->getArray('company_information:','id,company_name,company_address,company_city,company_regcode,company_regdate,company_userleft,company_email,company_register,company_registdate,company_licences:'," 0.0 = '".$id."' ",' ');
        return $this->result;
    }
    function createmainanswer($ownerid) {
        if (isset($_POST) && !empty($_POST)) {
            $ownerid = 2;
            if (isset($_POST['groups'])) {
                foreach ($_POST['groups'] as $key => $value) {
                    $groups .= trim($value).',';
                }
            }
            $relive = explode($_GET[q],'/');

            if ($relive[0] == 'ajaxrequest') {
                $_POST['question'] = utf8_encode($_POST['question']);
            }
            if ($_POST['frequensmain'] == D) {
                foreach ($_POST['days'] as $key => $value) {
                    $rights .= trim($value).',';
                }
            } else {
                $rights = $_POST['frequensmain'];
            }
            if (trim($_POST['firstrun']) != '') {

                $trickerday = $_POST['firstrun'];
            } else {
                $trickerday = '';
            }
            if ($_POST['even'] == 1 || $_POST['even'] == 'on') {
                $even = '1';
            }
            if ($_POST['odd'] == 1 || $_POST['odd'] == 'on') {
                $odd = '1';
            }
            $sql = " INSERT INTO  question_main (question,active,restricted,rights,majorCat,freqency,ownerCompany,createdBy,is_Important,createdtime,trickerdate,evenweek,oddweek)VALUES('".trim(htmlentities($_POST['question']))."','1','".$rights."','".$groups."','".$ownerid."','".$rights."','".$_SESSION['companyid']."','".$_SESSION['userid']."','".$_POST['timeofday']."','".time()."','".$trickerday."','".$even."','".$odd."');";
            $this->data->sqlInsert($sql);
            $questions = $this->data->getArray('question_main:','ownercompany,id:','0.0 = \''.$_POST['company_id'].'\'',' ORDER  by 0.1 DESC');
            $_POST = '';
            echo "<script> window.location = '/admin/createsubanswer/".$questions[0]['id']."';</script>";
            return $this->reason;
        }
    }
    function createsubanswer($ownerid) {
        if (isset($_POST) && !empty($_POST)) {
            if ($_POST['etypedegree'] != '') {
                $resources = $this->resourcesmake($_POST['etypedegree']);
                $degrees = $_POST['etypedegree'];
            } elseif ($_POST['etypedegrees'] != '') {
                $resources = $this->resourcesmake($_POST['etypedegrees']);

                $degrees = $_POST['etypedegrees'];
            }
            if ($_POST['frequensmain'] == D) {
                foreach ($_POST['days'] as $key => $value) {
                    $rights .= trim($value).',';
                }
            } else {
                $rights = $_POST['frequensmain'];
            }
            $sql = " INSERT INTO  question_sub (question,active,restricted,children,etype,obligatory,description,frequency,degreelimits,resource)VALUES('".trim($_POST['headline'])."','1','".$_POST['restriction']."','".$ownerid."','".$_POST['etypes']."','1','".trim($_POST['description'])."','".$rights."','".$degrees."','".$resources."');";
            $this->data->sqlInsert($sql);

            $_POST = '';
            echo "<script> alert('".html_entity_decode("Kontrolpunktet er gemt under hovedkontrollen. Vil du tilf&oslash;je flere kontrolpunkter til samme hovedkontrol udfyldes siden igen")."'); </script>";
        }
    }
    function addquestions() {
        if (isset($_POST)) {
            $bob = $this->data->getAffectedRows('questions_main:','id'," 0.0 = '".$_POST['questionid']."' "," ");
            if ($bob == 1) {
                $sql = " UPDATE questions_main: SET groupsid = '".$_POST['assignids']."', questionname = '".addslashes($_POST[question])."'  WHERE questionid = '".$bobber['0']."' ;";
                $this->data->sqlInsert($sql);
            } else {
                $this->data->sqlInsert($sql);
            }

            $this->resultitem = '<div> </div>';
            return $this->resultitem;
        }
    }
    function addsubquestions() {
        if (isset($_POST)) {
            $bob = $this->data->getAffectedRows('questions_subs:','id'," 0.0 = '".$_POST['questionid']."' "," ");
            if ($bob == 1) {
                $sql = " UPDATE questions_sub: SET groupsid = '".$_POST['assignids']."', questionname = '".addslashes($_POST[question])."'  WHERE questionid = '".$bobber['0']."' ;";
                $this->data->sqlInsert($sql);
            } else {
                $sql = " INSERT INTO  questions_sub (groupsid,questionname,)VALUES('".$_POST['assignids']."','".addslashes($_POST[question])."');";
                $this->data->sqlInsert($sql);
            }
            $this->resultitem = '<div> Produkt data er nu indsat</div>';
            return $this->resultitem;
        }
    }
    function editsections() {
        if (!empty($_POST['userselect'])) {
            $_POST['content'] = addslashes($_POST['content']);
            if ($_POST['editid'] != '') {
                $users = $_POST['userselect'];
                $sql = " UPDATE  section_headline SET headline='".$_POST['headline']."', content='".$_POST['content']."', users='".$users."', compadmin ='".$_POST[compadmin]."' WHERE id = '".$_POST['editid']."' ;";
                $this->data->sqlInsert($sql);
            } else {
                $users = $_POST['userselect'];
                $sql = " INSERT INTO  section_headline (headline,content,users,timecreated,compadmin)VALUES('".$_POST['headline']."','".$_POST['content']."','".$users."','".time()."','".$_POST['compadmin']."');";
                $this->data->sqlInsert($sql);
            }
        }
        $_POST = '';
        unset($_POST);
        $bob = $this->listsections();
        return $bob;
    }
    function deletesections() {
        $sql = "DELETE FROM section_headline WHERE  id = '".str_replace('admin/deletesections/','',$_GET[q])."' ";
        $this->data->sqlInsert($sql);
        return '<script type="text/javascript">window.location = "/admin/editsections"; </script>';
    }
    function listsections() {
        $this->listen = '';
        $itemuser = $this->data->getArray('section_headline:','headline,content,users,id,compadmin:'," 0.1 != '' ",' ORDER by timecreated DESC  ');
        foreach ($itemuser as $key => $value) {
            $this->listen .= "<div id=\"".$value['id']."\" class=\"newsitems\">
                <hr/>
                <div class=\"handle\" rel=\"".$value['id']."\">Rediger sektion</div><div class=\"delete\"><a href=\"/admin/deletesections/".$value['id']."\"> Slet sektion </a></div>
                <hr/>
                <p class=\"userselect\"> ".$value['users']." </p> <div class=\"headline\">".$value['headline']."</div> <div class=\"content\">".stripslashes($value['content'])."</div><div class=\"admin\" style=\"display:none\">".$value['compadmin']."</div>";
        }
        $this->listen = '<div id="newsitemcontainers">'.$this->listen.'</div>';
        $bonko = $this->listen;
        return $bonko;
    }
    function listnewsforedit() {
        $this->listen = '';
        $itemuser = $this->data->getArray('newsitems:','headline,content,users,id:'," 0.1 != '' ",' ORDER by timecreated DESC ');
        foreach ($itemuser as $key => $value) {
            $this->listen .= "<div id=\"".$value['id']."\" class=\"newsitems\">
                <hr/>
                <div class=\"handle\" rel=\"".$value['id']."\">Rediger nyhed</div><div class=\"delete\"><a href=\"/admin/removenews/".$value['id']."\"> Slet nyhed </a></div>
                <hr/>
                <p class=\"userselect\"> ".$value['users']." </p> <div class=\"headline\">".$value['headline']."</div> <div class=\"content\">".$value['content']."</div>  </div>";
        }
        $this->resultitem = '<div id="newsitemcontainers">'.$this->listen.'</div>';

        return $this->resultitem;
    }
    function addnews() {

        if (!empty($_POST['userselect'])) {

            $_POST['content'] = addslashes($_POST['content']);
            if ($_POST['editid'] != '') {

                if ($_POST['userselect'][0] == 'all') {
                    $users = $_POST['userselect'][0];
                } else {
                    foreach ($_POST['userselect'] as $key => $value) {
                        if ($value != 'all') {
                            $users = ','.$value;
                        }
                    }
                }
                $sql = " UPDATE  newsitems SET headline='".$_POST['headline']."', content='".$_POST['content']."', users='".$users."' WHERE id = '".$_POST['editid']."' ;";
                $this->data->sqlInsert($sql);
            } else {
                if ($_POST['userselect'][0] == 'all') {
                    $users = $_POST['userselect'][0];
                } else {
                    foreach ($_POST['userselect'] as $key => $value) {
                        if ($value != 'all') {
                            $users = ','.$value;
                        }
                    }
                }
                $sql = " INSERT INTO  newsitems (headline,content,users,timecreated)VALUES('".$_POST['headline']."','".$_POST['content']."','".$users."','".time()."');";
                $this->data->sqlInsert($sql);
            }
            $_POST = '';
        }
    }
    function addmoreusers() {
        if (isset($_POST) && trim($_POST['amountusers']) != '') {
            $sql = "UPDATE  company_information SET company_userleft = '".$_POST['amountusers']."' WHERE id = '".$_POST['company_id']."' ";
            $this->data->sqlInsert($sql);
        } else {
            $itemuser = $this->data->getArray('company_information:','id,company_userleft:'," 0.0 = '".$_POST['company_id']."' ",' ');
            $this->resultitem = $itemuser[0]['company_userleft'];
            return $this->resultitem;
        }
    }
    function editcompany() {
        if (isset($_POST) && trim($_POST['companyname']) != '') {
            if ($this->inserted != 'done') {
                $enoks = explode('/',$_GET[q]);
                foreach ($_POST as $key => $value) {
                    $_POST[$key] = addslashes($value);
                }
                $sql = "UPDATE `company_information` set    `company_name` = '".$_POST[companyname]."' , `company_address` = '".$_POST[companyadress]."' , `company_city` = '".$_POST[companypostal]."' , `company_regcode` ='".$_POST[companyregcode]."' ,`company_userleft` =  '".$_POST[companyamounts]."' , `company_email` = '".$_POST[companyemail]."' , `company_register` = '"."$_POST[companyregistrationdate]"."' WHERE id = '".$enoks[2]."'";
                $this->data->sqlInsert($sql);
                echo '<script type="text/javascript">window.location = "/admin/editcompany/'.$enoks[2].'"; </script>';
                unset($_POST);
                $_POST = '';
                $this->inserted = 'done';
            } else {
            }
        }
    }
    function addcompany() {
        if (isset($_POST) && trim($_POST['companyname']) != '') {
            if ($this->inserted != 'done') {
                foreach ($_POST as $key => $value) {
                    $_POST[$key] = addslashes($value);
                }
                $sql = "INSERT INTO `company_information` (  `company_name` , `company_address` , `company_city` , `company_regcode` , `company_regdate` , `company_userleft` , `company_email` , `company_register` , `company_registdate` )VALUES (
                        '".$_POST[companyname]."', '".$_POST[companyaddress]."', '".$_POST[companypostal]."', '".$_POST[companyregcode]."', ".time()." , '".$_POST[companyamounts]."', '".$_POST[companyemail]."', '".".$_POST[companyregistrationdate]."."', ".time()." )";
                $this->data->sqlInsert($sql);
                $array['email'] = 'nils@internet.dk';
                $array['mobile'] = $_POST['mobile'];
                $array['type'] = 'newcompany';
                $array['companyname'] = htmlentities($_POST[companyname])."<br/>".htmlentities($_POST[companyaddress])." <br/>".htmlentities($_POST[companypostal])."<br/>".$_POST[companyemail];
                $array['creationdate'] = date('d-m-y');
                $this->mail->send_email_information($array);
                echo '<script type="text/javascript">window.location = "/admin/addusers"; </script>';
                $_POST = '';
                $this->inserted = 'done';
            } else {
            }
        }
    }
    function deletegroups($id) {
        $sql = "DELETE FROM user_groups WHERE  id = '".$id."' LIMIT 1";
        $this->data->sqlInsert($sql);
        echo '<script type="text/javascript">window.location = "/admin/addgroups/"; </script>';
    }
    function addgroups() {
        if (isset($_POST) && trim($_POST['groupname']) != '') {
            $bob = $this->data->getAffectedRows('user_groups:','groupname,active,company,id:'," 0.0 = '".trim($_POST['groupname'])."' "," ");
            $sql = " INSERT INTO  user_groups (groupname,active,company)VALUES('".trim($_POST['groupname'])."','1','".trim($_POST['company_id'])."');";
            $this->data->sqlInsert($sql);
        }
        unset($_POST);
        $_POST = '';
        $this->resultitem = $this->getgroups();
        return $this->resultitem;
    }
    function getuserinfo($user = '') {
        if ($user == '') {
            $itemuser = $this->data->getArray('user_groups:','groupname,active,position,id:'," 0.1 = '1' ",' ');
        } elseif ($user != '') {
            $bob = $this->data->getAffectedRows('users:','username,rights:'," 0.0 = '$user' "," ");
            $finder = explode(',',$bob['rights']);
            $count = count($finder);
            $itemusers = $this->data->getArray('user_groups:','groupname,active,position,id:'," 0.1 = '1' ",' ');
            foreach ($itemusers as $key => $value) {
                for ($i = 0; $i <= ($count - 1); $i++) {
                    if ($itemusers[$key]['id'] == $finder[$i]) {
                        $itemuser[] = $itemusers[$key];
                    }
                }
            }
        }
        $this->resultitem = $itemuser;
        return $this->resultitem;
    }
    function addusers() {
        if (isset($_POST) && !empty($_POST)) {
            $bob = $this->data->getAffectedRows('users:','username:'," 0.0 = '".trim($_POST['username'])."' "," ");
            $i = 0;
            if (isset($_POST['groups'])) {
                foreach ($_POST['groups'] as $key => $value) {
                    if ($i == 0) {
                        $rights = "$value";
                        $i++;
                    } else {
                        $rights .= ",$value";
                        $i++;
                    }
                }
            }
            if ($bob == 1) {
                if ($_FILES['consultphoto']['error'] == 0) {
                    $_FILES[Filedata] = $_FILES[consultphoto];
                    $photo = $this->addmefile();
                    $consultphoto = ',consultphoto = \''.$photo.'\'';
                } else {
                    $consultphoto = '';
                }
                $sql = " UPDATE users SET educated = '".$_POST['educated']."',  cvrregdate = '".$_POST['cvrregdate']."' , `group` = '".$_POST['admin']."', cvrnumber = '".$_POST['cvr']."', information = '".$_POST['adresse']."', password = '".trim($_POST['password'])."', email = '".addslashes(trim($_POST['email']))."' , realname = '".$_POST['realname']."' , active = '".(trim(strtolower($_POST['active'])) == '1' ? '1' : '0')."' ,company = '".(trim($_POST['company']) == '1' ? '1' : '')."',company_id = '".trim($_POST['company_id'])."' , rights = '".$rights."' , company_admin = '".trim($_POST['companyadmin'])."', compconsult = '".$_POST[compconsult]."' ,consultdescript = '".$_POST[consultdescript]."', mobile = '".$_POST['mobile']."'  ".$consultphoto." WHERE username = '".trim($_POST['username'])."' ;";
                $this->data->sqlInsert($sql);
            } else {
                if ($_FILES['consultphoto']['error'] == 0) {
                    $_FILES[Filedata] = $_FILES[consultphoto];
                    $photo = $this->addmefile();
                    $consultphoto = $photo;
                } else {
                    $consultphoto = '';
                }
                $sql = " INSERT INTO  users (`group`,cvrregdate,cvrnumber,username,password,email,realname,company,active,rights,information,company_id,company_admin,educated,compconsult,consultdescript,consultphoto,mobile)VALUES('".$_POST['admin']."','".$_POST['cvrregdate']."','".$_POST['cvr']."','".trim($_POST['username'])."','".trim($_POST['password'])."','".trim($_POST['email'])."','".trim($_POST['realname'])."','".trim($_POST['company'])."','".(trim(strtolower($_POST['active'])) == '1' ? '1' : '0')."','".$rights."','".$_POST['adresse']."','".trim($_POST['company_id'])."','".$_POST['companyadmin']."','".$_POST['educated']."','".$_POST['compconsult']."' , '".$_POST['consultdescript']."','".$consultphoto."','".$_POST['mobile']."');";
                $this->data->sqlInsert($sql);
                $bobs = $this->get_companyinfo($_POST['company_id']);
                $array['email'] = $_POST['email'];
                $array['username'] = $_POST['username'];
                $array['mobile'] = $_POST['mobile'];
                $array['type'] = 'newuser';
                $array['password'] = $_POST['password'];
                $array['realname'] = $_POST['realname'];
                $array['identify'] = $bobs[0]['company_name'];
                $this->mail->send_email_information($array);
            }
            $_POST = '';
            // $this->resultitem = "<div> Brugeren er nu indsat</div>";
            //return "ok";
        }
        $userinfo = explode('/',$_GET['q']);
        if ($userinfo[2] != '') {
            $user = $userinfo[2];
        } elseif ($userinfo[1] != '') {
            $user = $userinfo[1];
        } else {
            $user = '';
        }
        return $this->getuserinfo($user);
    }
    function getusers() {
        $bob = $this->data->getAffectedRows('users:','username,password,email,realname,company,active,rights,cvrnumber,cvrregdate:'," 0.0 = '".trim($_POST['username'])."' "," ");
        return $bob;
    }
    function getgroups() {
        $itemuser = $this->data->getArray('user_groups:','groupname,active,position,id,company:'," 0.1 = '1' ",' ');
        $this->resultitem = $itemuser;
        return $this->resultitem;
    }
    function listusers() {
        $this->listen = '';
        $bobs = $this->data->getArray('company_information:','company_name,id:'," 0.1 != '-1' "," ORDER BY 0.0 ASC ");
        foreach ($bobs as $keys => $valuess) {
            $bob = $this->data->getArray('users:','username,realname,id,company_id,rights:'," 0.3 = '".$valuess['id']."' "," ");
            $i = 0;
            $this->lister = '';
            foreach ($bob as $key => $value) {
                $this->lister .= "<div  class=\"useritem\">
                    <div class=\"handles\" rel=\"/admin/editusers/".urlencode($value['username'])."\"><a href=\"/admin/editusers/".urlencode($value['username'])."\">Rediger bruger</a></div><div>
                    <p class=\"username\" > <a href=\"/admin/removeusers/".$value[id]."\" >Slet Bruger</a> ".$value['username']." - ".$value['realname']."<br/></p></div><div>".$this->displayaltinfo($value['id'])."</div>  </div>";
                $i++;
            }
            $this->listen .= "<div class=\"compcontainer\"  ><p class=\"compgroup\" rel=\"".$valuess[id]."\">".$valuess[company_name]."(".$i.")  | Se  brugere</p> <a href=\"/admin/editcompany/".$valuess['id']."\">Rediger firma</a>  &nbsp; &nbsp; &nbsp; <a  class=\"controls\"href=\"/ajaxrequest/getcontrols/".$valuess['id']."\">Se kontroller</a> <div id=\"clank-".$valuess[id]."\" class=\"hidebox\" >".$this->lister."</div></div>";
        }
        $this->resultitem = '<div id="newsitemcons">'.$this->listen.'</div>';
        return $this->resultitem;
    }
    function displayaltinfo($id) {
        $info = '';
        $itemuser = '';
        $bob = $this->data->getArray('users:','username,realname,id,company_id,rights,company_admin,active,`group`:'," 0.2 = '".$id."' "," ");
        if (!empty($bob[0]['rights'])) {
            $finder = explode(',',$bob[0]['rights']);
            $count = count($finder);
            $itemusers = $this->data->getArray('user_groups:','groupname,active,position,id:'," 0.1 = '1' ",' ');
            foreach ($itemusers as $key => $value) {
                for ($i = 0; $i <= ($count - 1); $i++) {
                    if ($itemusers[$key]['id'] == $finder[$i]) {
                        $itemuser .= "<li>".$itemusers[$key]['groupname']."</li>";
                    }
                }
            }
        }
        if ($bob[0]['groupid'] == '1') {
            $info .= '<li>Administrator: <img src="/img/icons/ok_icon.png" style="float:right;width:14px;height:14px;" /></li> ';
        } else {
            $info .= '<li>Administrator: <img src="/img/icons/error_icon.png" style="float:right;width:14px;height:14px;" /></li> ';
        }
        if ($bob[0]['company_admin'] == '1') {
            $info .= '<li>Firma Administrator: <img src="/img/icons/ok_icon.png" style="float:right;width:14px;height:14px;" /></li> ';
        } else {
            $info .= '<li>Firma Administrator: <img src="/img/icons/error_icon.png" style="float:right;width:14px;height:14px;" /></li> ';
        }
        if ($bob[0]['active'] == '1') {
            $info .= '<li>Aktiv bruger: <img src="/img/icons/ok_icon.png" style="float:right;width:14px;height:14px;" /></li> ';
        } else {
            $info .= '<li>Aktiv bruger: <img src="/img/icons/error_icon.png" style="float:right;width:14px;height:14px;" /></li> ';
        }
        $this->resulter = "<div class=\"adminbox\"><ul>".$info."</ul></div><div class=\"groupmember\">Medlem af  gruppe(r):<br/><ul> ".$itemuser."</ul></div>";
        return $this->resulter;
    }
    function removeusers($id) {
        if ($id != '1' && $id != '2') {
            if ($id != '') {
                $sql = 'DELETE  FROM users WHERE  id = \''.$id.'\' LIMIT 1 ';
                $this->data->sqlInsert($sql);
            }
        }
        echo '<script> window.location = \'/admin/listusers\'  </script>';
        return $this->resultitem;
    }
    function __destruct() {
        return '';
    }
}
?>
