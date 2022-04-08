<?php

#Validate serial number
function check_serial($serial){
    require('config.php');

    #Verify serial number
    $sql = "select auto_id 
    from data.serials 
    where serial_num like '%$serial%' limit 1;";

    $result=$dblink->query($sql) or
        die("Something went wrong with $sql");
    $data = $result->fetch_array(MYSQLI_ASSOC);

    if(!$data){
        return FALSE;
    }
    else{
        return $data['auto_id'];
    }
}

#Fetch device by id from database
function fetch_device($id){
    require('config.php');

    $sql = "select device from devices where device_id = '$id'";
    $result=$dblink->query($sql) or
        die("Something went wrong with $sql");
    $data = $result->fetch_array(MYSQLI_ASSOC);

    return $data['device'];
}

#Fetch brand by id from database
function fetch_brand($id){
    require('config.php');

    $sql = "select brand from brands where brand_id = '$id'";
    $result=$dblink->query($sql) or
        die("Something went wrong with $sql");
    $data = $result->fetch_array(MYSQLI_ASSOC);

    return $data['brand'];
}

?>