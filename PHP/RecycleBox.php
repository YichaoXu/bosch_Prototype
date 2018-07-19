<?php
mysql_query('SET NAMES UTF8');
header("Content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: aoo
 * Date: 7/13/2018
 * Time: 2:03 PM
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

/*Check if the box has already exist*/
$dbForWarehouse = new DBHandler(DBHandler::DATABASE_WAREHOUSES);
try {
    $dbForWarehouse->startQuery();
    $existResult = $dbForWarehouse->exeQuery(
        "SELECT boxID FROM wh$warehouseID WHERE boxID=?",
        array($boxID)
    );
    $dbForWarehouse->commitQuery();

    if ($existResult->rowCount()!=0){
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

    $dbForBasic->exeQuery(
        "UPDATE boxestable SET stateCode='RECYCLE' AND warehouseID=? WHERE boxID=?",
        array("RECYCLE_CENTER", $boxID)
    );

    $dbForWarehouse->exeQuery(
        "DELETE FROM wh$warehouseID WHERE boxID=?",
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

