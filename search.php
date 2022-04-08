<?php
include_once('bootstrap.php');
include_once('config.php');
include('helpers.php');

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


if(!isset($_POST['submit1']) && !isset($_POST['submit2'])){
    #Device drop-down
    echo "<div class='container'>";
        echo "<form method='post' action=''>";
        echo "<h5>Please select device type to query:</h5>";
        echo "<div class='form-floating'>";
            echo "<select class='form-select' id='devices' name='device'>";
                echo "<option value='No_device'>".'Select a device type'."</option>";
                foreach ($devices as $key => $value) #index, device
                {
                    echo "<option value='".$key."'>".$value."</option>"; #real value is index, but display name
                }
            echo "</select>";
        echo "</div>";
        

        #Manufacturer drop-down
        echo "<h5>Please select manufacturer to query:</h5>";
        echo "<div class='form-floating'>";
            echo "<select class='form-select' id='manufacturers' name='manufacturer'>";
                echo "<option value='No_manufacturer'>".'Select a manufacturer'."</option>";
                foreach ($brands as $key => $value) #index, manufacturer
                {
                    echo "<option value='".$key."'>".$value."</option>";
                }
                echo "</select>";
        echo "</div>";

        echo "<div><button class='btn btn-primary' type='submit' name='submit1' value='submit'>Submit</button></div>";
        echo "</form>";
        
        echo "<hr>";
    echo "</div>";
    
    #Serial entry
    echo "<div class='container'>";
        echo "<form method='post' action=''>";
        echo "<h5>Enter a serial number:</h5>";
        echo "<input type='text' class='form-control' name='serial' required='required' pattern='SN-[a-z0-9]{32}' title='SN-[32 characters]'></input>";
        echo "<div><button class='btn btn-primary' type='submit' name='submit2' value='submit'>Submit</button></div>";
        echo "</form>";
    echo "</div>";

}

#Submit clicked for serial
if(isset($_POST['submit2']) && $_POST['submit2'] == "submit")
{
    if($_POST['serial']){
        $sql = "select * from data.serials where serial_num like '%$_POST[serial]%' limit 1;";
        #$sql = "select device_id, brand_id, serial_num from data.serials where serial_num like '%SN-e177694c03062a1d28470a60f39d45b5%'";

        $result=$dblink->query($sql) or
            die("Something went wrong with $sql");
        $data = $result->fetch_array(MYSQLI_ASSOC);

        #check if valid serial
        if($data){
            $device = fetch_device($data['device_id']);
            $brand = fetch_brand($data['brand_id']);

            echo "<h1>$brand, $device</h1>";
            echo "<h4>$data[serial_num]</h4>";

            #show one row table
            echo '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
            <table id="tblSerial">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Serial Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>'.$data['auto_id'].'</td>
                        <td>'.$data['serial_num'].'</td>
                        <td><a href="view_device.php?did='.$data['auto_id'].'">View</a></td>
                    </tr>
                </tbody>
            </table>
            
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
            <script>
            jQuery(document).ready(function($) {
                $("#tblSerial").DataTable();
            } );
            </script>';
            
        }
        else{
            echo "<p>Serial not found in DB</p>";
        }   
    }
}

#Submit clicked for dev, manu
if (isset($_POST['submit1']) && $_POST['submit1'] == "submit"){
    $sql = "";
    
    #both device, manu are set and valid
    if($_POST['device'] != "No_device" && $_POST['manufacturer'] != "No_manufacturer")
    {
        $device_index = $_POST['device'];
        $brand_index = $_POST['manufacturer'];

        echo "<h1>Device and manufacturer query</h1>";
        echo "<p>You chose: ".$brands[$brand_index]. " and ".$devices[$device_index]."</p>";

        $sql = "select s.auto_id, s.serial_num
        from data.serials as s, data.brands as b, data.devices as d
        where d.device_id = s.device_id and b.brand_id = s.brand_id and brand = '$brands[$brand_index]' and device = '$devices[$device_index]'";

    }
    else if($_POST['device'] != "No_device") #only device is set
    {
        $device_index = $_POST['device'];
        echo "<h1>Device query</h1>";
        echo "<p>You chose device: ".$devices[$device_index]."</p>";
        
        $sql="select s.auto_id, s.serial_num 
        from data.serials as s, data.brands as b, data.devices as d
        where d.device_id = s.device_id and b.brand_id = s.brand_id and device = '$devices[$device_index]'";
        
    }
    else if($_POST['manufacturer'] != "No_manufacturer") #only manufacturer is set
    {
        $brand_index = $_POST['manufacturer'];
        echo "<h1>Manufacturer query</h1>";
        echo "<p>You chose manufacturer: ".$brands[$brand_index]."</p>";

        $sql="select s.auto_id, s.serial_num 
        from data.serials as s, data.devices as d, data.brands as b 
        where d.device_id = s.device_id and b.brand_id = s.brand_id and brand = '$brands[$brand_index]'";
    }

    #display the serials
    if($sql){
        $countSQL = "select count(*) from ($sql) x;";
        $result=$dblink->query($countSQL) or
                die("Something went wrong with $$countSQL");
        
        #get count from result
        $count = $result->fetch_array(MYSQLI_NUM)[0];
        echo "<p>Number of rows is: ".$count."</p>";

        #pass vars to server side processing
        session_start();
        $_SESSION['sql'] = $sql;
        $_SESSION['total'] = $count;
        
        include('table.php');
    }
}



?>