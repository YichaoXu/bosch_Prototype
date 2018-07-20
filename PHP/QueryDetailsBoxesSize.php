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
$sizeCode = $_REQUEST["sizeCode"];

$dbForWarehouse = new DBHandler(DBHandler::DATABASE_WAREHOUSES);

/*Check If parameters are all satisfied*/
if(empty($warehouseID)||empty($sizeCode)){
    outputResult("401","NULL PARAMETERS");
    return;
}

try {
    $dbForWarehouse->startQuery();

    $results = array();

    $results["StoredBoxes"] = $dbForWarehouse->queryBoxesNumWithSpecialSize("wh".$warehouseID,"STORE", $sizeCode);
    $results["DeliveringBoxes"] = $dbForWarehouse->queryBoxesNumWithSpecialSize("wh".$warehouseID,"DELIVER", $sizeCode);
    $results["WaitingBoxes"] = $dbForWarehouse->queryBoxesNumWithSpecialSize("wh".$warehouseID,"WAIT", $sizeCode);
    $results["DestroyedBoxes"] = $dbForWarehouse->queryBoxesNumWithSpecialSize("wh".$warehouseID,"DESTROY", $sizeCode);

    $dbForWarehouse->commitQuery();
} catch (PDOException $e) {
    $dbForWarehouse->abortQuery();
    outputResult("402", "ERROR QUERY ".$e->getMessage());
    return;
}

outputResult("100", json_encode($results));

function outputResult($code, $result){
    echo(json_encode(array("code"=>$code, "result"=>$result)));
}
