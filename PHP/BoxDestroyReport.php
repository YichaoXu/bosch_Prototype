<?php
mysql_query('SET NAMES UTF8');
header("Content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: aoo
 * Date: 7/16/2018
 * Time: 2:48 PM
 */

/*Import and Declaration*/
include_once 'DBHandler.php';
header('Access-Control-Allow-Origin: *');

/*Initiate the parameters*/
$boxID = $_REQUEST["boxID"];
$warehouseID = $_REQUEST["warehouseID"];

/*Check If parameters are all satisfied*/
if(empty($boxID)||empty($warehouseID)){
    outputResult("401","NULL PARAMETERS");
    return;
}

/*Check if the box do not exist*/
$dbForWarehouse = new DBHandler(DBHandler::DATABASE_WAREHOUSES);
try {
    $dbForWarehouse->startQuery();
    $existResult = $dbForWarehouse->exeQuery(
        "SELECT logID FROM wh$warehouseID WHERE boxID=?",
        array($boxID)
    );
    $dbForWarehouse->commitQuery();

    if ($existResult->rowCount()==0){
        outputResult("406","BOX DO NOT EXISTED");
        return;
    }
} catch (PDOException $e) {
    outputResult("402","ERROR IN QUERY".$e->getMessage());
    $dbForWarehouse->abortQuery();
    return;
}

/*Update database information*/
$dbForBasic = new DBHandler(DBHandler::DATABASE_BASIC);
try{
    $dbForBasic->startQuery();
    $dbForWarehouse->startQuery();

    $dbForWarehouse->exeQuery(
        "INSERT INTO 
            wh$warehouseID(boxID, inDate)
        VALUE (?, ?)",
        array($boxID, time())
    );

    $dbForBasic->exeQuery(
        "UPDATE warehousestable SET waitingBoxesNum=waitingBoxesNum+1 WHERE warehouseID=?",
        array($warehouseID)
    );

    $dbForBasic->exeQuery(
        "UPDATE boxestable SET stateCode=1 WHERE boxId=?",
        array($boxID)
    );

    $dbForBasic->commitQuery();
    $dbForWarehouse->commitQuery();

}catch(PDOException $e){
    $dbForBasic->abortQuery();
    $dbForWarehouse->abortQuery();
    outputResult("404","ERROR IN UPDATE ".$e->getMessage());
    return;
}

outputResult("100","SUCCESS");

function outputResult($code, $result){
    echo("{'code':'$code', 'result':'$result'}");
}
