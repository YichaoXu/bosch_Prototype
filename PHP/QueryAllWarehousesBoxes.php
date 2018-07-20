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

$dbForBasic=new DBHandler(DBHandler::DATABASE_BASIC);
$dbForWarehouse=new DBHandler(DBHandler::DATABASE_WAREHOUSES);

try {
    $dbForBasic->startQuery();

    $results = array();

    $results["StoredBoxes"] = $dbForBasic->queryBoxesNum("boxestable","STORE");
    $results["DeliveringBoxes"] = $dbForBasic->queryBoxesNum("boxestable","DELIVER");
    $results["WaitingBoxes"] = $dbForBasic->queryBoxesNum("boxestable","WAIT");
    $results["DestroyedBoxes"] = $dbForBasic->queryBoxesNum("boxestable","DESTROY");

    $dbForBasic->commitQuery();
} catch (PDOException $e) {
    $dbForBasic->abortQuery();
    outputResult("402", "ERROR QUERY ".$e->getMessage());
    return;
}

outputResult("100", json_encode($results));

function outputResult($code, $result){
    echo(json_encode(array("code"=>$code, "result"=>$result)));
}

