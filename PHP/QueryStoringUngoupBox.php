<?php
header("Content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: aoo
 * Date: 7/18/2018
 * Time: 1:53 AM
 */
include_once 'DBHandler.php';
header('Access-Control-Allow-Origin: *');

$warehouseID = $_REQUEST["warehouseID"];


/*Check If parameters are all satisfied*/
if(empty($warehouseID)){
    outputResult("401","NULL PARAMETERS");
    return;
}

$dbForWarehouse = new DBHandler(DBHandler::DATABASE_WAREHOUSES);

try {
    $dbForWarehouse->startQuery();

    $results = $dbForWarehouse->exeQuery(
        "SELECT boxID FROM wh$warehouseID WHERE stateCode=? AND groupCode=?",
        array("STORE", 0)
    )->fetchAll(PDO::FETCH_NUM);
    $tmpResults = array();
    $i=0;
    foreach($results as $result){
        $tmpResults[$i++]=$result[0];
    }


    $dbForWarehouse->commitQuery();
} catch (PDOException $e) {
    $dbForWarehouse->abortQuery();
    outputResult("402", "ERROR QUERY ".$e->getMessage());
    return;
}

outputResult("100", json_encode($tmpResults));

function outputResult($code, $result){
    echo(json_encode(array("code"=>$code, "result"=>$result)));
}
