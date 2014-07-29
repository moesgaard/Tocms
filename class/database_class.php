<?php
/**
 *@package coresystem
 *@subpackage database
 */
/**
 *this is the database class for connectiont
 * this database  uses the database_interface ads guide line
 */
/**
 * @package coresystem
 * @subpackage database
 * @author Morten Moesgaard - Moesgaards.dk
 */
class database_class{
    private  $validkey;
    /**
     * @var string contains information about established connection
     */
    private $connection;
    /**
     * @var string contains information about established backup connection
     */
    private $connectionBackup;
    /**
     * @var string contains information about what type established primary connection
     */
    private $PrimaryConnection;
    /**
     * @var string contains information about if failover is active;
     */
    private $failover;
    /**
     * @var string contains information about what type established backup connection
     */
    private $BackupConnection;
    /**
     * @var string contains information about on established connetion to database;
     */
    private $connect;
    /**
     * @var string contains information about if failover is active on second choise;
     */
    private $failoverSecond;
    /**
     *@var mixed this is used in mysql for connection
     */
    private $connections;
    /**
     *@var string  contains the query string
     */
    private $query;
    /**
     *@var array contains the array
     */
    private $selecttables;
    /**
     *contains information wich database we use
     *@var array
     */
    private $handle;
    /**
     *contains information wich database we use
     *@var array
     */
    public $rowfetch = '';
    /**
     * contains information on rowfetched
     * @var array
     */
    private $row = '';
    /**
     * contains results
     * @var array
     */
    private $statements;
    /**
     *@var string
     */
    private $dns;
    /**
     *@var array
     */
    private $tables;
    /**
     *@var array
     */
    private $fields;
    /**
     *@var array
     */
    private $values;
    /**
     *@var string
     */
    private $whereids;
    /**
     * contains explosion array
     *@var array
     */
    private $explosion;
    /**
     *@var string
     */
    private $limitgroupsort;

    private $replace;
    /**
     * pattern for replacing SQL
     *@var array
     */
    private $pattern;
    /**
     * pattern for replacing SQL
     *@var array
     */
    private $replaces;
    /**
     * pattern for replacing SQL
     *@var array
     */
    private $patterns;
    /**
     * pattern for replacing SQL
     *@var string
     */
    private $solution;
    function __construct() {
        /**
         *This is the constructor for the class where the config.php file is created via a gpg command
         */
        require_once('db/config.php');
        $this->__connect();
    }
    function __connect($DatabaseSelection = 0 , $DatabaseSelectionSecond = 0) {
        /**
         *This is the main connect function where the variables from  the config.php  is being used.
         * here we test the first/primary connection
         **/
        switch($this->handle['Primary'][0]) {
        case "mysql":
            try {
                $this->connection = $this->__construct_mysql($DatabaseSelection);
                $this->PrimaryConnection = "mysql";
            }catch(Exception $e ) {
                logErrors::write($e->getMessage(),1);
                $this->failover = 1;
            }
            break;
        case "pgsql":
            try {
                $this->connection = $this->__construct_pgsql($DatabaseSelection) ;
                $this->PrimaryConnection = "pgsql";
            }catch(Exception $e ) {
                logErrors::write($e->getMessage(),1);
                $this->failrover = 1;
            }
            break;
        case "oci":
            try {
                $this->connection = $this->__construct_oci($DatabaseSelection);
                $this->PrimaryConnection = "oci";
            }catch(Exception $e ) {
                logErrors::write($e->getMessage(),1);
                $this->failover = 1;
            }
            break;
        case "pdo_mysql":
            try {
                $this->connection = $this->__construct_pdo_mysql($DatabaseSelection);
                $this->PrimaryConnection ="pdo_mysql";
            }catch(Exception $e ) {
                logErrors::write($e->getMessage(),1);
                $this->failover = 1;
            }
            break;
        case "pdo_pgsql":
            try {
                $this->connection = $this->__construct_pdo_pgsql($DatabaseSelection);
                $this->PrimaryConnection ="pdo_pgsql";
            }catch(Exception $e ) {
                logErrors::write($e->getMessage(),1);
                $this->failover = 1;
            }
            break;
        case "xml":
            try {
                $this->connection = $this->__construct_xml($DatabaseSelection);
                $this->PrimaryConnection = "xml";
            }catch(Exception $e ) {
                logErrors::write($e->getMessage(),1);
                $this->failover = 1;
            }
            break;
        default:
            break;
        }
        /**
         *here we try the secondary connection
         */
        if(isset($this->handle['Secondary'][0]) && !empty($this->handle['Secondary'][0])) {
            switch($this->handle['Secondary'][0]) {
            case "mysql":
                try {
                    $this->connectionBackup = $this->__construct_mysql($DatabaseSelectionSecond);
                    $this->BackupConnection ="mysql";
                }catch(Exception $e ) {
                    logErrors::write($e->getMessage(),1);
                    $this->failoverSecond = 1;
                }
                break;
            case "pgsql":
                try {
                    $this->connectionBackup = $this->__construct_pgsql( $DatabaseSelectionSecond);
                    $this->BackupConnection ="pgsql";
                }catch(Exception $e ) {
                    logErrors::write($e->getMessage(),1);
                    $this->failoverSecond = 1;
                }
                break;
            case "oci":
                try {
                    $this->connectionBackup = $this->__construct_oci( $DatabaseSelectionSecond);
                    $this->BackupConnection ="oci";
                }catch(Exception $e ) {
                    logErrors::write($e->getMessage(),1);
                    $this->failoverSecond = 1;
                }
                break;
            case "pdo_mysql":
                try {
                    $this->connectionBackup = $this->__construct_pdo_mysql($DatabaseSelectionSecond);
                    $this->BackupConnection ="pdo_mysql";
                }catch(Exception $e ) {
                    logErrors::write($e->getMessage(),1);
                    $this->failoverSecond = 1;
                }
                break;
            case "pdo_pgsql":
                try {
                    $this->connectionBackup = $this->__construct_pdo_mysql($DatabaseSelectionSecond);
                    $this->BackupConnection ="pdo_mysql";
                }catch(Exception $e ) {
                    logErrors::write($e->getMessage(),1);
                    $this->failoverSecond = 1;
                }
                break;
            case "xml":
                try {
                    $this->connectionBackup = $this->__construct_xml($DatabaseSelection);
                    $this->BackupConnection ="xml";
                }catch(Exception $e ) {
                    logErrors::write($e->getMessage(),1);
                    $this->failoverSecond = 1;
                }
                break;
            default:
                break;
            }
        }else {
            $this->handle['Secondary'][0] = "";
        }
        /**
         * Now we try and see if the failover is set if not this is ignored
         */
        if($this->failover == true) {
            $this->connection = $this->connectionBackup;
            $this->PrimaryConnection = $this->BackupConnection;
            if($this->PrimaryConnection == "" ) {
                $this->failoverSecond = true;
            }
        }
        /**
         *we try and see if the secondary has failed
         */
        if($this->failoverSecond == true) {
            try {
                $this->connection = $this->__construct_xml($DatabaseSelection);
                $this->PrimaryConnection = "xml";
                $this->databaseName = $this->handle[''.$this->handle["Primary"][0].'_Database'][0];
            }catch(Exception $e ) {
                logErrors::write($e->getMessage(),1);
                die(nl2br('The system is not set up properly..Please do so!
                    You only recieve this message due to xml is not found on your system
                    or the databases are not set up properly..
                    if this is first time running please go to setup with the required key recieved by email '));
            }
        }
        /**
         * We return the connection that is used
         */
        return $this->connection;
    }
  /**
   function __set($property,$value) {
   }
  **/
    /**
     *Constructors start  ( mysql,xml, pgsql,oracle,pdo for  mysql and pgsql)
     */
    function __construct_mysql($h = false ) {
        $this->connections = @mysql_connect($this->handle['mysql_Host'][$h],$this->handle['mysql_User'][$h],$this->handle['mysql_Password'][$h]);
        $this->connect =@mysql_select_db($this->handle['mysql_Database'][$h],$this->connections);
        if(@mysql_error()) {
            throw new Exception('Mysql is down or you are using another Database . \n  The report is :'.mysql_error().' \n please untick MySql if not in use  ');
        }else {
            return $this->connect;
        }
    }
    function __construct_mssql(){
        $this->connections = mssql_connect($servername, $username, $password, $new_link);
        $this->connect = mssql_select_db($database_name, $link_identifier);
        if(@mssql_get_last_message()) {
            throw new Exception('Mysql is down or you are using another Database . \n  The report is :'.mysql_error().' \n please untick MySql if not in use  ');
        }else {
            return $this->connect;
        }
    }
    function __construct_xml($h = false) {
        $this->connect = is_dir("db/xml");
        if($this->connect == false) {
            throw new Exception('xml is not working ');
        }else {
            return $this->connect;
        }
    }
    function __construct_pgsql($h = false) {
        $this->connect = @pg_connect($this->handle['pgsql_Connection'][$h]);
        if(!$this->connect) {
            throw new Exception('Postgressql database is down or you are using another Database . \n  The report is :'.@pg_result_error().' \n please untick PostGresSql if not in use ');
        }else {
            return $this->connect;
        }
    }
    function __construct_oci($h = false) {
        $this->connect = @oci_connect(
            $this->handle['oci_User'][$h],
            $this->handle['oci_Password'][$h],
            $this->handle['oci_Host'][$h]."/".$this->handle['oci_Database'][$h]);
        $error = oci_error();
        if(!empty($error)) {
            $message = "";
            foreach($error as $key => $value ) {
                $message .= $key." = ".$value."  ;  ";
            }
            throw new Exception('Oracle database is down or you are using another Database .
                \n  The report is :'.$message.'\n please untick Oracle if not in use  ');
        }else {
            return $this->connect;
        }
    }
    function __construct_pdo_mysql($h = false) {
        try {
            $this->dns = "mysql:dbname=".$this->handle['pdo_mysql_Database'][$h]."; host=".$this->handle['pdo_mysql_Host'][$h]."";
            $this->connect = new PDO($this->dns,$this->handle['pdo_mysql_User'][$h],$this->handle['pdo_mysql_Password'][$h],array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $ne ) {
            throw new Exception('Mysql database via pdo is down or you are using another Database . \n  The report is :'.$ne->getMessage().' \n please untick pdo_Mysql if not in use  ');
        }
        return $this->connect;
    }
    function __construct_pdo_pgsql($h = 0) {
        try {
            $this->dns = "pgsql:dbname=".$this->handle['pdo_pgsql_Database'][$h]."; port=5432; host=".$this->handle['pdo_mysql_Host'][$h].";";
            $this->connect = new PDO($this->dns,$this->handle['pdo_pgsql_User'][$h],$this->handle['pdo_pgsql_Password'][$h]);
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $ne ) {
            throw new Exception('Postgres datbase via pdo is down or you are using another Database . \n  The report is :'.$ne->getMessage().' \n please untick pdo_Postgress if not in use  ');
        }
        return $this->connect;
    }
    /**
     *Construtors end.
     */
    /**
     * This section above will be  extended when
     * the database for selection is made
     * */
    /**
     *This method explodes the tables
     */
    function tablesExploded($tables) {
        $this->explosion = explode(":",$tables);
        $this->query = "";
        foreach($this->explosion as $key => $value ) {
            if($value != "" ) {
                $this->query[$key] =  $value;
            }
        }
        return $this->query;
    }
    /**
     *this method explodes the fields
     */
    function fieldsExploded($fields) {
        $this->explosion = explode(":",$fields);
        $this->query = "";
        foreach($this->explosion as $key => $value ) {
            $this->query[$key] =  explode(",",$value);
        }
        return $this->query;
    }
    /**
     *This method explodes the values wich does not contain ; as it is the seperator.
     */
    function valuesExploded($fields) {
        $this->explosion = explode(":",$fields);
        $this->query = "";
        foreach($this->explosion as $key => $value ) {
            $this->query[$key] =  explode(",",$value);
        }
        return $this->query;
    }
    /**
     *method uses the aboves methods  to create a UPDATE sql statement.
     */
    function updateSqlFields($table,$field,$value,$where,$debug = 0) {
        /**
         *We explode the values we get in.
         */
        $this->tables = $this->tablesExploded($table);
        $this->fields = $this->fieldsExploded($field);
        $this->values = $this->valuesExploded($value);
        $this->whereids =  " WHERE  $where";
        /**
         *herewe set the pattern
         */
        $this->selecttable = "";
        $this->selection = "";
        $this->pattern = "";
        $this->replace = "";
        $this->pattern[] = "/;/";
        $this->replace[] = "";
        /**
         *Creating the pattern for replacing the values
         */
        for($i=0;$i < count($this->tables);$i++) {
            if(($i == 0 && count($this->tables) == 1) || $i == count($this->tables)-1 ) {
                $this->selecttable[] = $this->tables[$i];
            }elseif($this->tables[$i] != "" ) {
                $this->selecttable[] = $this->tables[$i];
            }
            for($n=0;$n < count($this->fields[$i]); $n++) {
                $this->insertion[$i][$n] = $this->values[$i][$n];
                $this->fieldreq[$i][$n] = $this->fields[$i][$n];
            }
        }
        $this->query = "";
        /**
         *Creating the pattern with the replacements
         */
        for($y=0;$y < count($this->tables);$y++ ) {
            for($z=0; $z < count($this->fieldreq[$y]); $z++) {
                if($z < count($this->fieldreq{$y})-1 ) {
                    $this->insert .= $this->fieldreq[$y][$z]." = '".$this->insertion[$y][$z]."',";
                }else {
                    $this->insert .= $this->fieldreq[$y][$z]." = '".$this->insertion[$y][$z]."'";
                }
            }
            /**
             * we set $this->query with the selected values we have created.
             */
            $this->whereid = $this->whereids ;
            $this->query .= " UPDATE ".$this->tables[$y]." SET  ".$this->insert." ".$this->whereid.";";
            $this->fieldto = "";
            $this->insert = "";
        }
        if($debug == false ) {
            return $this->query;
        }else {
            $this->query;
        }
    }
    /**
     * method  for creating  a insert sql statement
     */
    function insertSqlFields($table,$field,$value,$debug = 0) {
        /**
         *We explode the incomming values
         */
        $this->tables = $this->tablesExploded($table);
        $this->fields = $this->fieldsExploded($field);
        $this->values = $this->valuesExploded($value);
        $this->selecttable = "";
        $this->selection = "";
        $this->pattern = "";
        $this->replace = "";
        /**
         *we set the patterns
         */
        $this->pattern[] = "/;/";
        $this->replace[] = "";
        $this->pattern[] = "/drop/";
        $this->replace[] = "";
        $this->pattern[] = "/'/";
        $this->replace[] = "\'";
        for($i=0;$i < count($this->tables);$i++) {
            if(($i == 0 && count($this->tables) == 1) || $i == count($this->tables)-1 ) {
                $this->selecttable[] = $this->tables[$i];
            }elseif($this->tables[$i] != "" ) {
                $this->selecttable[] = $this->tables[$i];
            }
            for($n=0;$n < count($this->fields[$i]); $n++) {
                $this->values[$i][$n] = preg_replace($this->pattern,$this->replace,$this->values[$i][$n]);
                if($i == count($this->tables)-1 && $n == count($this->fields[$i])-1) {
                    $this->insertion[$i][$n] = "'".$this->values[$i][$n]."'";
                    $this->fieldreq[$i][$n] = $this->fields[$i][$n];
                }else {
                    if( $n == count($this->fields[$i])-1 ) {
                        $this->insertion[$i][$n] = "'$this->values[$i][$n]'";
                        $this->fieldreq[$i][$n] = $this->fields[$i][$n];
                    }else {
                        $this->insertion[$i][$n] = "'".$this->values[$i][$n]."',";
                        $this->fieldreq[$i][$n] = $this->fields[$i][$n].",";
                    }
                }
            }
        }
        $this->query = "";
        /**
         *we create the respctive patern for each table
         */
        for($y=0;$y < count($this->tables);$y++ ) {
            for($z=0; $z < count($this->fieldreq[$y]); $z++) {
                $this->insert .= $this->insertion[$y][$z];
                $this->fieldto .= $this->fieldreq[$y][$z];
            }
            $this->query .= "INSERT INTO ".$this->tables[$y]."(".$this->fieldto.") VALUES (".$this->insert.");";
            $this->fieldto = "";
            $this->insert = "";
        }
        if($debug == false ) {
            return $this->query;
        }else {
            return $this->query;
        }
    }
    function selectSqlFields($table, $field, $query,$limits) {
        /**
         *we explode the incommin values
         */
        $this->tables = $this->tablesExploded($table);
        $this->fields = $this->fieldsExploded($field);
        $this->selecttable = "";
        $this->selection = "";
        $this->pattern = "";
        $this->replace = "";
        /**
         *We create the pattern
         */
        $this->pattern[] = "/\b;\b/";
        $this->replace[] = "";
        for($i=0;$i < count($this->tables);$i++) {
            if($i == 0 && $i == count($this->tables)-1 ) {
                $this->selecttable .= $this->tables[$i]." ";
            }elseif($i == 0 && count($this->tables)  > 0 ){
                $this->selecttable = $this->tables[$i].", ";

            }elseif(!empty($this->tables[$i])) {
                $this->selecttable .= $this->tables[$i].",";
            }

            for($n=0;$n < count($this->fields[$i]); $n++) {
                $this->pattern[] = "/\b".$i."[[:punct:]]".$n."\b/";
                $this->replace[] = $this->tables[$i].".".$this->fields[$i][$n];
                if($i ==  count($this->tables)-1 && $n == count($this->fields[$i])-1) {
                    $this->selection .= $this->tables[$i].".".$this->fields[$i][$n]."";
                }else {
                    if(count($this->tables) == 1 &&  $n == count($this->fields[$i])-1 ) {
                        $this->selection .= $this->tables[$i].".".$this->fields[$i][$n]."";
                    }else {
                        $this->selection .= $this->tables[$i].".".$this->fields[$i][$n].",";
                    }
                }
            }
        }
        /**
         *we replace with the pattern we have created
         */
        $values = preg_replace($this->pattern,$this->replace,$query);
        $this->pattern[] = "/\bgroup\b\s/";
        $this->replace[] = "GROUP BY ";
        $this->pattern[] = "/\border\b\s/";
        $this->replace[] = "ORDER  BY ";
        $this->pattern[] = "/\blimit\b\s/";
        $this->replace[] = "LIMIT ";
        /**
         * we replace the limit patterns
         */
        $this->limitgroupsort = preg_replace($this->pattern,$this->replace,$limits);
        $this->solution = "SELECT ".$this->selection." FROM ".$this->selecttable." WHERE ".$values." ".$this->limitgroupsort.";";
        return $this->solution;
    }
    /**
     *we do a sql to xml translation not fully functional yet
     */
    function selectXMLTransform($table, $field, $query,$limits) {
        $this->tables = $this->tablesExploded($table);
        $this->fields = $this->fieldsExploded($field);
        $this->selecttable = "";
        $this->selection = "";
        $this->pattern = "";
        $this->replace = "";
        $this->pattern[] = "/;/";
        $this->replace[] = "";
               /* for($i=0;$i < count($this->tables);$i++) {
                        $this->selecttable[] = $this->tables[$i]." ";
                        for($n=0;$n < count($this->fields[$i]); $n++) {
                                $this->pattern[] = "/".$i.".".$n."/";
                                $this->replace[] = "/moesgaards_db/".$this->tables[$i]."/row[".$this->fields[$i][$n]."";
                                $this->selection[] = $this->fields[$i][$n];
                        }
                }
                $this->pattern[] = "/=/";
                $this->replace[] = "=";
                // print_r($this->pattern);
                $this->pattern[] = "/group/";
                $this->replace[] = "GROUP BY ";
                $this->pattern[] = "/order/";
                $this->replace[] = "ORDER  BY ";
                $this->pattern[] = "/limit/";
                $this->replace[] = "LIMIT ";
                $this->pattern[] = "/ /";
                $this->replace[] = "";
                $this->pattern[] = "/'/";
                $this->replace[] = "";
                $values = preg_replace($this->pattern,$this->replace,$query);
                $this->limitgroupsort = preg_replace($this->pattern,$this->replace,$limits);
                *//**
                    * loading XML file
                 */
                    try {
                        if(is_file("db/xml/".$this->databaseName.".xml")) {
                            $horse = simplexml_load_file("db/xml/".$this->databaseName.".xml");
                            $jew =  $horse->xpath("/$this->databaseName/".$this->tables[0]);
                            $moo = get_object_vars($jew);
                            echo $moo[$this->fields[0]];
                        }else {
                        }
                    }catch(DOMException  $e) {
                        echo   $e;
                    }
                foreach($nodeList as $node) {
                    var_dump($node);
                }
    }
    /**
     *a key generation for later use
     */
    function keyselect($keys) {
        $values = "abcdefghijklmnopqrstuvwyzx";
        $this->validkey = $values{$keys};
        return $this->validkey;
    }
    /**
     * method for calling row names
     * this is not yet finished
     */
    function sqlFetchAssoc($sqlQuery,$selection) {
        $this->row = "";
        switch($this->PrimaryConnection) {
        case "mysql":
            try {
                $this->__construct_mysql();
                $sqlQueries = @mysql_query($sqlQuery);
                if(@mysql_error()) {
                    throw new Exception ('An error  has occoured '.mysql_error().' was injected from query '.$sqlQuery);
                }else {
                    while($raw = @mysql_fetch_assoc($sqlQueries)) {
                        $this->row[] = $raw;
                    }
                }
            }catch(Exception  $e) {
                logErrors::write($e,1);
            }
            break;
        case "pdo_mysql":
            try {
                $this->row = $this->connection->query($sqlQuery)->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $ne ) {
                logErrors::write($ne->getMessage(),1);
            }
            break;
        case "pdo_pgsql":
            try {
                $this->row = $this->connection->query($sqlQuery)->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $ne ) {
                logErrors::write($ne->getMessage(),1);
            }
            break;
        case "pgsql":
            try {
                $this->__construct_pgsql();
                $sqlQueries = @pg_query($sqlQuery);
                if(pg_error()) {
                    throw new Exception ('An error  has occoured '.pg_error().' was injected from query '.$sqlQuery);
                }else {
                    $this->row = @pg_fetch_assoc($sqlQueries);
                }
            }catch(Exception  $e) {
                logErrors::write($e,1);
            }
            break;
        case "oci":
            $connected = $this->__construct_oci();
            $statement = @oci_parse ($connected, $sqlQuery);
            oci_execute ($statement);
            $this->row = oci_fetch_assoc ($statement, OCI_BOTH);
            break;
        default:
            $this->row = $sqlQuery;
            break;
        }
        return $this->row;
    }
    /**
     * method for calling  array is not yet finished
     */
    function sqlInsert($sqlQuery) {
        $this->row = '';
        switch($this->PrimaryConnection) {
        case "mysql":
            try {
                $this->__construct_mysql();
                $this->sqlQueries = @mysql_query($sqlQuery);
                if(@mysql_error() ) {
                    throw new Exception ('query is wrong '.$sqlQuery."\n\r ".mysql_error()."\n\r");
                }
            }catch(Exception $e  ) {
                logErrors::write($e,1);
            }
            break;
        case "pdo_mysql":
            try {
                $this->row = $this->connection->query($sqlQuery);
            }catch(PDOException $ne ) {
                logErrors::write("error :".$ne->getMessage(),1);
            }
            break;
        case "pdo_pgsql":
            try {
                $this->row = $this->connection->query($sqlQuery);
                $this->row = array_map('reset', $this->row);
            }catch(PDOException $ne ) {
                logErrors::write("error :".$ne->getMessage(),1);
            }
            break;
        case "pgsql":
            try {
                $this->row = $this->connection->query($sqlQuery);
            }catch(PDOException $ne ) {
                logErrors::write("error :".$ne->getMessage(),1);
            }
            break;
        case "oci":
            $connected = $this->__construct_oci();
            $statement = oci_parse ($connected, $sqlQuery);
            oci_execute($statement);
            break;
        default:
            $this->row = simplexml_load_file( "db/xml/".$this->databaseName.".xml");
            break;
        }
        return $this->row;
    }
    function sqlFetchArray($sqlQuery,$selection) {
        $this->row = '';
        switch($this->PrimaryConnection) {
        case "mysql":
            try {
                $this->__construct_mysql();
                $this->sqlQueries = @mysql_query($sqlQuery);
                $this->tester = @mysql_num_rows($this->sqlQueries);
                if($this->tester != '0') {
                    while($raw = @mysql_fetch_array($this->sqlQueries)) {
                        $this->row .= $raw;
                    }
                    if(empty($this->row)) {
                        throw new Exception ('0 row caught by query '.$sqlQuery."\n\r".mysql_error());
                    }
                }else {
                    throw new Exception ('0 row caught by query '.$sqlQuery."\n\r".mysql_error());
                }
            }catch(Exception $e ) {
                logErrors::write($e,1);
            }
            break;
        case "pdo_mysql":
            try {
                $this->row = $this->connection->query($sqlQuery)->fetchAll(PDO::FETCH_BOTH);
            }catch(PDOException $ne ) {
                logErrors::write("error :".$ne->getMessage(),1);
            }
            break;
        case "pdo_pgsql":
            try {
                $this->row = $this->connection->query($sqlQuery)->fetchAll(PDO::FETCH_BOTH);
            }catch(PDOException $ne ) {
                logErrors::write("error :".$ne->getMessage(),1);
            }
            break;
        case "pgsql":
            try {
                $this->row = $this->connection->query($sqlQuery)->fetch(PDO::FETCH_BOTH);
            }catch(PDOException $ne ) {
                logErrors::write("error :".$ne->getMessage(),1);
            }
            break;
        case "oci":
            $connected = $this->__construct_oci();
            $statement = oci_parse ($connected, $sqlQuery);
            oci_execute($statement);
            $this->row = oci_fetch_array($statement, OCI_BOTH);
            break;
        default:
            logErrors::write("error :No Connection is set please dont ddo this again..\n",1);
            break;
        }
        return $this->row;
    }
    /**
     * this is the method for  getteing affected rows
     */
    function sqlAffectedRows($sqlQuery,$selection) {
        $this->rowfetch = "";
        $this->PrimaryConnection;
        switch($this->PrimaryConnection) {
        case "mysql":
            try {
                $this->__construct_mysql();
                $this->sqlQueries = mysql_query($sqlQuery);
                $this->tester = mysql_num_rows($this->sqlQueries);
                if($this->tester != 0) {
                    $this->row = $this->tester;
                }elseif(@mysql_error() ) {
                    throw new Exception ('0 row caught by query '.$sqlQuery."\n\r".mysql_error());
                }
            }catch(Exception $e  ) {
                logErrors::write($e,1);
            }
            break;
        case "pdo_mysql":
            try {
                $this->row = $this->connection->query($sqlQuery)->rowCount();
            }catch(PDOException $ne) {
                logErrors::write("error :".$ne->getMessage().__FILE__,1);
            }
            break;
        case "pdo_pgsql":
            try {
                $this->row = $this->connection->query($sqlQuery)->rowCount();
            }catch(PDOException $ne ) {
                logErrors::write("error :".$ne->getMessage(),1);
            }
            break;
        case "pgsql":
            try {
                $this->__construct_pgsql();
                $this->sqlQueries = pg_query($sqlQuery);
                $this->tester = pg_num_rows($this->sqlQueries);
                if($this->tester > '1') {
                    $this->row = $this->tester;
                }else {
                    throw new Exception ('0 row caught by query '.$sqlQuery);
                }
            }catch(Exception $e  ) {
                logErrors::write($e,1);
            }
            break;
        case "oci":
            $connected = $this->__construct_oci();
            $statement = oci_parse ($connected, $sqlQuery);
            oci_execute($statement);
            $this->row = oci_num_rows($statement, OCI_BOTH);
            break;
        default:
            break;
        }
        return $this->row;
    }
    /**
     *
     *
     *method  for calling a row
     */
    function getRow($table, $field, $query,$limit) {
        switch($this->PrimaryConnection) {
        case "mysql":
        case "pdo_mysql":
        case "pdo_pgsql":
        case "pgsql":
        case "oci":
            $this->rowfetch = $this->sqlFetchArray($this->selectSqlFields($table, $field, $query,$limit),$this->PrimaryConnection);
            break;
        case "xml":
            $this->rowfetch =  $this->sqlFetchArray($this->selectXMLTransform($table, $field, $query,$limit),"xml");
            break;
        default:
            break;
        }
        return $this->rowfetch;
    }
    /**
     *method for calling a complete array
     */
    function getArray($table, $field, $query,$limit) {
        switch($this->PrimaryConnection) {
        case "mysql":
        case "pdo_mysql":
        case "pdo_pgsql":
        case "pgsql":
        case "oci":
            $this->rowfetch = $this->sqlFetchArray($this->selectSqlFields($table, $field, $query,$limit),"".$this->PrimaryConnection."");
            break;
        case "xml":
            $this->rowfetch =  $this->sqlFetchArray($this->selectXMLTransform($table, $field, $query,$limit),"xml");
            break;
        }
        return $this->rowfetch;
    }
    /**
     *method for calling  a assoc array
     */
    function getAssoc($table, $field, $query,$limit ) {
        $this->rowfetch = "";
        switch($this->PrimaryConnection) {
        case "mysql":
        case "pdo_mysql":
        case "pdo_pgsql":
        case "pgsql":
        case "oci":
            $this->rowfetch = $this->sqlFetchAssoc($this->selectSqlFields($table, $field, $query,$limit),$this->PrimaryConnection);
            break;
        case "xml":
            $this->rowfetch = array("idea" => "wuptidooo");
            break;
        default:
            break;
        }
        return $this->rowfetch;
    }
    function getAffectedRows($table, $field, $query,$limit ) {
        $this->rowfetch = "";
        switch($this->PrimaryConnection) {
        case "mysql":
        case "pdo_mysql":
            case "pdo_pgsql";
            case "pgsql";
        case "oci":
            $this->rowfetch = $this->sqlAffectedRows($this->selectSqlFields($table, $field, $query,$limit),$this->PrimaryConnection);
            break;
        case "xml":
            $this->rowfetch = array("idea" => "wuptidooo");
            break;
        default:
            break;
        }
        return $this->rowfetch;
    }
    /**
     *method for destructing class and get ridoff the config.php file
     */
    function __destruct() {
        $filepath = str_replace("class","",dirname(__FILE__));
        //      shell_exec('rm -f '.$filepath.'/db/config.php');
    }
}
?>
