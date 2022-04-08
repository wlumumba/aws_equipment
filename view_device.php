<?php
include_once('config.php');
include_once('bootstrap.php');
include('helpers.php');

$device_id = $_REQUEST['did'];

#Pull device and brand of serial
$sql = "select * from data.serials where auto_id = '$device_id';";
$result=$dblink->query($sql) or
    die("Something went wrong with $sql");
$data = $result->fetch_array(MYSQLI_ASSOC);
$device_type = fetch_device($data['device_id']);
$brand_type = fetch_brand($data['brand_id']);

echo "<h1>$brand_type, $device_type : $data[serial_num]</h1>";

#Find all files associated
$sql = "select * from data.files where device_id = '$device_id';";
$result = $dblink->query($sql) or
    die("Something went wrong with $sql");

echo "<table class='table table-striped table-bordered'>";
echo "<thead>";
echo "<tr>";
echo "<th>File Name</th>";
echo "<th>File Type</th>";
echo "<th>File Size</th>";
echo "<th>File Link</th>";
echo "</tr>";
while($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>".$row['file_name']."</td>";
    echo "<td>".$row['file_type']."</td>";
    echo "<td>".$row['file_size']."</td>";
    echo "<td>";
        echo "<a href=".$row['file_link'].">Open File</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</thead>";
echo "<tbody>";

?>