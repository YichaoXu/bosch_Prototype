<?php
/**
 * Created by PhpStorm.
 * User: aoo
 * Date: 7/20/2018
 * Time: 3:48 AM
 */

include_once 'DBHandler.php';
header('Access-Control-Allow-Origin: *');

$dbForBasic=new DBHandler(DBHandler::DATABASE_BASIC);
try {
    $dbForBasic->startQuery();
$results= $dbForBasic->exeQuery(
    "SELECT warehouseID FROM warehousestable",
    array()
)->fetch(PDO::FETCH_NUM);

} catch (PDOException $e) {
    $dbForBasic->abortQuery();
    outputResult("402", "ERROR QUERY ".$e->getMessage());
    return;
}

outputResult("100", json_encode($results));

function outputResult($code, $result){
    echo(json_encode(array("code"=>$code, "result"=>$result)));
}
