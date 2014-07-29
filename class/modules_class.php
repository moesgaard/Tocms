<?php
/**
 * @package modulereader
 */
/* * modules_class
 * This is the class for module reading
 */
/**
 * @package modulereader
 */
class modules_class{
    private $files;
    private $setContent;
    private $modReg;
    private $errorLog;
    public function __construct($modulereg = 0) {
        $this->errorLog = new logErrors();
        $this->basePath = "modules/";
        $this->pathFinder = scandir($this->basePath);
        if ($modulereg == '1') {
            $this->moduleReader();
            return $this->hookReader();
        } else {
            $this->mainModulesLoad();
            return $this->modulesLoad();
        }
    }
    public function hookReader() {
        foreach ($this->setContent as $keys) {
            $this->files = $keys['moduleName'] . $keys['installfile'];
            $XML = simplexml_load_file($this->files);
            $XmlNodes = $XML->children();
            if ($this->isInDb($XmlNodes->module_name) == true) {
            } else {
                $this->registerModules($XmlNodes);
            }
        }
    }
    public function mainModulesLoad() {
        global $db;
        $answer = '';
        $answer = $db->getArray('modules:',
            "id,
            module_name,
            module_class,
            module_function,
            module_files,
            module_active,
            module_depend,
            is_main_module:",
            "0.5 = '1'  AND 0.7 = '0'",
            "ORDER BY id ASC");
        if (!empty($answer)) {        
            foreach ($answer as $activate) {
                if ($activate['module_name'] != '') {
                    if (@get_class($activate['module_name']) == false) {
                        if ($activate['module_depend'] == '') {
                            eval('$' . $activate['module_name'] . ' = new ' . $activate['module_name'] . '();');
                        } else {
                            $class = explode(',', $activate['module_depend']);
                            foreach ($class as $name) {
                                if (trim($name) != 'core') {
                                    if (@get_class($name) == false) {
                                        eval('$' . $name . ' = new ' . $name . '();');
                                    }
                                }
                            }
                            eval('$' . $activate['module_name'] . ' = new ' . $activate['module_name'] . '();');
                        }
                    } else {
                    }
                }
            }
        }
    }
    public function modulesLoad() {
        global $db;
        $answer = $db->getArray('modules:',
            "id,
            module_name,
            module_class,
            module_function,
            module_files,
            module_active,
            module_depend,
            is_main_module:",
            "0.5 = '1'  AND 0.7 = '1'",
            "ORDER BY id ASC");
        if (!empty($answer)) {
            foreach ($answer as $activate) {
                if (@get_class($activate['module_name']) == false) {
                    if ($activate['module_depend'] == '') {
                        eval('$' . $activate['module_name'] . ' = new ' . $activate['module_name'] . '();');
                    }else{
                        $class = explode(',', $activate['module_depend']);
                        foreach ($class as $name) {
                            if (trim($name) != 'core') {
                                if (get_class($name) == false) {
                                    eval('$' . $name . ' = new ' . $name . '();');
                                }
                            }
                        }
                        eval('$' . $activate['module_name'] . ' = new ' . $activate['module_name'] . '();');
                    }
                } else {
                }
            }
        }
    }
    public function isActive() {
    }
    public function moduleSendInformation() {
        global $db;
        if (!isset($_GET['mod'])) {
        } else {
            if ($this->isInDb(trim(addslashes($_GET['mod'])), 1) == true) {
                return $this->pathFinder . "/" . $_GET['mod'] . "/" . $_GET['mod'] . ".php";
            } else {
                return "this is not allowed";
            }
        }
    }
    public function registerModules($moduleRegister) {
        global $db;
        $moduleRegister = get_object_vars($moduleRegister);
        switch ($moduleRegister['is_main_module']) {
        case "1" :
            $mod = 1;
            break;
        default:
            $mod = 0;
            break;
        }
        $answer = $db->insertSqlFields('modules:',
            "module_name,
            is_main_module,
            module_provide,
            module_depend,
            module_version,
            class_depend,
            class_provide,
            is_dev,
            author,
            is_optional:",
            trim($moduleRegister['module_name']) . "," .
            $mod . "," .
            $moduleRegister['module_provide'] . "," .
            $moduleRegister['module_depend'] . "," .
            $moduleRegister['module_version'] . "," .
            $moduleRegister['class_depend'] . "," .
            $moduleRegister['class_provide'] . "," .
            $moduleRegister['is_dev'] . "," .
            $moduleRegister['author'] . "," .
            $moduleRegister['is_optional'],
            "");
        $db->sqlInsert($answer);
    }
    public function isNeeded() {
    }
    public function isDev() {
    }
    public function fileRequired() {
    }
    public function isInDb($modName, $isactive = 0) {
        global $db;
        try{
            $answer = $db->getAffectedRows('modules:'
                , "id,module_name,module_is_active:", " 0.1 = '" . trim($modName) . "'"
                , " limit 1");
            if ($answer != 0 && $isactive == 0) {
                return true;
            } elseif ($isactive == 1 && $answer['module_is_active'] == 1) {
                return true;
            } elseif ($isactive == 1 && $answer['module_is_active'] == 0) {
                return false;
            } else {
                $message = "An error has occoured".__FILE__." >> ".__FUNCTION__."The module does not excist ";
                return false;
            }
        }catch(Exception $e){
            $this->errorLog->write($e->message());
        }
    }
    public function fetchModule($moduleName) {
        global $db;
        $answer = $db->getAffectedRows('modules:',
            "id,
            module_name,
            module_class,
            module_function,
            module_files,
            module_active:",
            "0.1 = '" . trim($moduleName) . "'",
            "order id ASC ");
        if ($answer == '') {
            $answer['default'] = 'default';
        }
        return $answer;
    }
    public function moduleReader() {
        global $db;
        $i = 0;
        foreach ($this->pathFinder as $key) {
            if (is_dir($this->basePath . $key)) {
                $fileSearch = scandir($this->basePath . $key);
                foreach ($fileSearch as $value) {
                    if ($value == 'install.xml') {
                        $this->setContent[$i]['moduleName'] = $this->basePath . "" . $key . "/";
                        $this->setContent[$i]['installfile'] = 'install.xml';
                        $i++;
                        break;
                    }
                }
            }
        }
        return $this->setContent;
    }
    public function result() {
    }
    public function __destruct() {
    }
}
?>
