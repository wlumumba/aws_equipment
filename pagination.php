<?php
session_start();

# Pull credientials
include_once('config.php');
 
$totalRecords = $_SESSION['total'];
$sql = $_SESSION['sql'];

# Headers from the GET request
$length = $_GET['length'];
$start = $_GET['start'];
 

if (isset($_GET['search']) && !empty($_GET['search']['value'])) {
    $search = $_GET['search']['value'];
    $sql .= " and s.serial_num like '%$search%'";
}

# Start adjusts on each re-render
$sql .= " LIMIT $start, $length";


$query = $dblink->query($sql) or
     die("Something went wrong with $sql");
$result = [];
while ($row = $query->fetch_assoc()) 
{
    $result[] = [$row['serial_num']];
}

# Return the data as JSON
echo json_encode([
    'draw' => $_GET['draw'],
    'recordsTotal' => $totalRecords,
    'recordsFiltered' => $totalRecords,
    'data' => $result,
]);
?>
