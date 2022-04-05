<?php
$un="root";
$pw="utsa2022!";
$db="data";
$hostname="localhost";
$dblink=new mysqli($hostname, $un, $pw, $db);


if(!isset($_POST['submit'])){
   
    echo "<form method='post' action=''>";
    #Serial entry
    echo "<p>Enter an existing serial number:</p>";
    echo "<input type='text' name='serial' required='required' pattern='SN-[a-z0-9]{32}' title='SN-[32 characters]'></input>";
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

        #Delete query
        $sql = "delete from serials where serial_num='$_POST[serial]'";
        $result=$dblink->query($sql) or
            die("Something went wrong with $sql");
        echo "<p>Serial number: $_POST[serial] deleted.</p>";

    }
}

?>