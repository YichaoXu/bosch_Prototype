<?php
mysql_query('SET NAMES UTF8');
header("Content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: aoo
 * Date: 10/7/2018
 * Time: 1:16 PM
 */

class DBHandler
{
    const DATABASE_BASIC = "proto_basic";
    const DATABASE_WAREHOUSES = "proto_warehouses";

    private $pdoForDB;

    public function __construct($dbName){

        $connectStr_dbHost = '';
        $connectStr_dbName = '';
        $connectStr_dbUsername = '';
        $connectStr_dbPassword = '';

        foreach ($_SERVER as $key => $value) {
            if (strpos($key, "MYSQLCONNSTR_localdb") !== 0) {
                continue;
            }

            $connectStr_dbHost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
            $connectStr_dbName = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
            $connectStr_dbUsername = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
            $connectStr_dbPassword = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
            echo($connectStr_dbHost.", ".$connectStr_dbName.", ".$connectStr_dbUsername.", ".$connectStr_dbPassword);
        }

        $db_hostname = $connectStr_dbHost;
        $db_database = $dbName;
        $db_username = $connectStr_dbUsername;
        $db_password = $connectStr_dbPassword;
        $db_charset = "utf8mb4";
        $dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=$db_charset";
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        );
        $this->pdoForDB = new PDO($dsn, $db_username, $db_password, $opt);
        if($this->pdoForDB ===null) echo('IS NULL');
    }

    public function __destruct(){
        $this->pdoForDB = NULL;
    }

    public function startQuery(){
        $this->pdoForDB->beginTransaction();
    }

    public function exeQuery($sql, $sqlArray){
        $stmt = $this->pdoForDB->prepare($sql);
        $stmt->execute($sqlArray);
        return $stmt;
    }

    public function abortQuery(){
        $this->pdoForDB->rollBack();
    }

    public function commitQuery(){
        $this->pdoForDB->commit();
    }

    public function queryBoxesNum($tableName, $stateCode){
        $stmt = $this->exeQuery(
            "SELECT COUNT(*) AS num FROM $tableName WHERE stateCode=?",
            array($stateCode)
        )->fetch();
        return $stmt["num"];
    }
}