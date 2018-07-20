<?php
header("Content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: aoo
 * Date: 7/10/2018
 * Time: 9:28 PM
 */

/*Import and Declaration*/
include_once 'DBHandler.php';
header('Access-Control-Allow-Origin: *');

$warehouseID = $_REQUEST["warehouseID"];

/*Check If parameters are all satisfied*/
if(empty($warehouseID)){
    outputResult("401","NULL PARAMETERS");
    return;
}

$dbForBasic=new DBHandler(DBHandler::DATABASE_BASIC);
try {
    $dbForBasic->startQuery();

   $result=$dbForBasic->exeQuery(
       "SELECT * FROM warehousestable WHERE warehouseID=?",
       array($warehouseID)
   )->fetch(PDO::FETCH_ASSOC);


    $dbForBasic->commitQuery();
} catch (PDOException $e) {
    $dbForBasic->abortQuery();
    outputResult("402", "ERROR QUERY ".$e->getMessage());
    return;
}

outputResult("100", json_encode($result));

function outputResult($code, $result){
    echo(json_encode(array("code"=>$code, "result"=>$result)));
}
