<?php
$un="root";
$pw="utsa2022!";
$db="data";
$hostname="localhost";
$dblink=new mysqli($hostname, $un, $pw, $db);

#Fill devices array
$sql="Select device from devices order by device_id";
$result=$dblink->query($sql) or
    die("Something went wrong with $sql");
$devices=array();

#fetch row as associative array (column name as key)
while ($data = $result->fetch_array(MYSQLI_ASSOC)){
    $devices[] = $data['device'];
}

#Fill manufacturers array
$sql="Select brand from brands order by brand_id";
$result=$dblink->query($sql) or
    die("Something went wrong with $sql");
$brands = array();
while ($data = $result->fetch_array(MYSQLI_ASSOC)){
    $brands[] = $data['brand'];
}

if(!isset($_POST['submit'])){
    echo "<form method='post' action=''>";
    
    #Serial entry
    echo "<p>Enter an existing serial number:</p>";
    echo "<input type='text' name='serial' required='required' pattern='SN-[a-z0-9]{32}' title='SN-[32 characters]'></input>";
    echo "<hr>";

    #Change device/manufacturer
    echo "<p>Set new device type:</p>";
    echo "<select name='device'>";
    echo "<option value='No_device'>".'Select a device type'."</option>";
    foreach ($devices as $key => $value) #index, device
    {
        echo "<option value='".$key."'>".$value."</option>"; #real value is index, but display name
    }
    echo "</select>";

    echo "<p>Set a new manufacturer:</p>";
    echo "<select name='manufacturer'>";
    echo "<option value='No_manufacturer'>".'Select a manufacturer'."</option>";
    foreach ($brands as $key => $value) #index, manufacturer
    {
        echo "<option value='".$key."'>".$value."</option>";
    }
    echo "</select>";
    echo "<div style='padding:20px 0px'><button type='submit' name='submit' value='submit'>Submit Change</button></div>";
    echo "</form>"; 
}

if(isset($_POST['submit'])){
    if($_POST['serial']){
        #Verify serial number
        $sql = "select device_id, brand_id, serial_num 
            from data.serials 
            where serial_num like '%$_POST[serial]%';";

        $result=$dblink->query($sql) or
            die("Something went wrong with $sql");
        $data = $result->fetch_array(MYSQLI_ASSOC);

        if(!$data){
            echo "<p>Serial number does NOT exist. Try a different one.</p>";
            return;
        }

        #Get device_id, brand_id for modify query
        if($_POST['device'] != 'No_device')
        {
            $device = $devices[$_POST[device]];
            $result=$dblink->query("select device_id from devices where device='$device'") or
                die("Something went wrong with query");
            $device_res = $result->fetch_array(MYSQLI_ASSOC);
            $device_id = $device_res['device_id'];
        }
        if($_POST['manufacturer'] != 'No_manufacturer')
        {
            $brand = $brands[$_POST[manufacturer]];
            $result=$dblink->query("select brand_id from brands where brand='$brand'") or
                die("Something went wrong with query");
            $brand_res = $result->fetch_array(MYSQLI_ASSOC);
            $brand_id = $brand_res['brand_id'];
        }

        #Modify query (need to add active/inactive)
        if($_POST['device'] != 'No_device' && $_POST['manufacturer'] != 'No_manufacturer'){
            $sql = "update serials set device_id = '$device_id', brand_id = '$brand_id' where serial_num like '%$_POST[serial]%';";
            $result=$dblink->query($sql) or
                die("Something went wrong with $sql");
            echo "<p>Serial number $_POST[serial] updated.</p>";
        }
        else if($_POST['device'] != 'No_device'){
            $sql = "update serials set device_id = '$device_id' where serial_num like '%$_POST[serial]%';";
            $result=$dblink->query($sql) or
                die("Something went wrong with $sql");
            echo "<p>Serial number $_POST[serial] updated.</p>";
        }
        else if($_POST['manufacturer'] != 'No_manufacturer'){
            $sql = "update serials set brand_id = '$brand_id' where serial_num like '%$_POST[serial]%';";
            $result=$dblink->query($sql) or
                die("Something went wrong with $sql");
            echo "<p>Serial number $_POST[serial] updated.</p>";
        }
        else{
            echo "<p>No changes made to serial number: $_POST[serial].</p>";
        }

    }
}
?>