<?php
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

        $db_hostname = "localhost";
        $db_database = $dbName;
        $db_username = "aooblocc_proto";
        $db_password = "Prototype";
        $db_charset = "utf8mb4";
        $dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=$db_charset";
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        );
        try{
            $this->pdoForDB = new PDO($dsn, $db_username, $db_password, $opt);
        }catch(Exception $e){
            echo('IS NULL'.$e->getMessage());
        }
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

    public function queryBoxesNumWithSpecialSize($tableName, $stateCode, $sizeCode){
        $stmt = $this->exeQuery(
            "SELECT COUNT(*) AS num FROM $tableName WHERE stateCode=? AND  boxID REGEXP '^$sizeCode'",
            array($stateCode)
        )->fetch();
        return $stmt["num"];
    }

}