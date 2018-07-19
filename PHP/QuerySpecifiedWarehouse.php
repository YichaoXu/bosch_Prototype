<?php
/**
 * Created by PhpStorm.
 * User: aoo
 * Date: 7/18/2018
 * Time: 1:53 AM
 */
include_once 'DBHandler.php';
header('Access-Control-Allow-Origin: *');

$warehouseID = $_REQUEST["warehouseID"];

$dbForWarehouse = new DBHandler(DBHandler::DATABASE_WAREHOUSES);

/*Check If parameters are all satisfied*/
if(empty($warehouseID)){
    outputResult("401","NULL PARAMETERS");
    return;
}

try {
    $dbForWarehouse->startQuery();

    $results = array();

    $results["StoredBoxes"] = $dbForWarehouse->queryBoxesNum("wh".$warehouseID,"STORE");
    $results["DeliveringBoxes"] = $dbForWarehouse->queryBoxesNum("wh".$warehouseID,"DELIVER");
    $results["WaitingBoxes"] = $dbForWarehouse->queryBoxesNum("wh".$warehouseID,"WAIT");
    $results["DestroyedBoxes"] = $dbForWarehouse->queryBoxesNum("wh".$warehouseID,"DESTROY");

    $dbForWarehouse->commitQuery();
} catch (PDOException $e) {
    $dbForWarehouse->abortQuery();
    outputResult("402", "ERROR QUERY ".$e->getMessage());
    return;
}

outputResult("100", json_encode($results));

function outputResult($code, $result){
    echo("{'code':'$code', 'result':'$result'}");
}
