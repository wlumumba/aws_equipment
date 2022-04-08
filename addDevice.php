<?php
include_once('config.php');
include_once('bootstrap.php');
include('helpers.php');

if(!isset($_POST['submit'])){
    echo "<form method='post' action=''>";
    echo "<p>Enter a device type:</p>";
    echo "<input type='text' name='device' required='' maxlength='20'></input>";
    echo "<p>Enter a manufacturer:</p>";
    echo "<input type='text' name='manufacturer' required='required' maxlength='20'></input>";
    echo "<p>Enter a serial number:</p>";
    echo "<input type='text' name='serial' required='required' pattern='SN-[a-z0-9]{32}' title='SN-[32 characters]'></input>";
    echo "<div><button class='btn btn-primary' type='submit' name='submit' value='submit'>Submit</button></div>";
    echo "</form>";
}

if(isset($_POST['submit'])){
    $_POST['device'] = strtolower($_POST['device']);
    $_POST['manufacturer'] = strtolower($_POST['manufacturer']);
    
    #check for serial existence -> bad
    if(check_serial($_POST['serial'])){
        echo "<p>Serial number already exists. Try a different one.</p>";
        return;
    }
    
    #find device, manu if exist; otherwise create
    $sql = "insert into data.devices (device)
    SELECT * FROM (SELECT '$_POST[device]') AS tmp
    WHERE NOT EXISTS (
        SELECT device from data.devices WHERE device= '$_POST[device]'
    )   LIMIT 1;";
    $dblink->query($sql) or
        die("Something went wrong with $sql");

    $sql = "insert into data.brands (brand)
    SELECT * FROM (SELECT '$_POST[manufacturer]') AS tmp
    WHERE NOT EXISTS (
        SELECT brand from data.brands WHERE brand= '$_POST[manufacturer]'
    )   LIMIT 1;";
    $dblink->query($sql) or
        die("Something went wrong with $sql");

    #insert to serials table
    $result=$dblink->query("select * from devices where device='$_POST[device]'") or
        die("Something went wrong with query");
    $device_res = $result->fetch_array(MYSQLI_ASSOC);

    $result=$dblink->query("select * from brands where brand='$_POST[manufacturer]'") or
        die("Something went wrong with query");
    $brand_res = $result->fetch_array(MYSQLI_ASSOC);
    
    $insert_query = "insert into data.serials values (NULL, $device_res[device_id], $brand_res[brand_id], '$_POST[serial]');";
    $dblink->query($insert_query) or
        die("Something went wrong with $insert_query");
    
    echo "<h1>Success!</h1>";
    echo "<p>Inserted: $device_res[device], $brand_res[brand], $_POST[serial] into database</p>";
}


?>