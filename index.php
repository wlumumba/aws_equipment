<?php
include_once('bootstrap.php');

echo '
    <header class="mb-auto">
    <nav class="navbar navbar-dark bg-dark">
        <a href="" class="navbar-brand">Home</a>
    </nav>
    </header>
     ';


echo "
    <body class='text-white bg-light'>
    <div class='container-fluid'>
        <a href='search.php'>
            <button class='btn btn-primary'>Search for devices</button>
        </a>
        <a href='addDevice.php'>
        <button class='btn btn-primary'>Add a device</button>
        </a>
        <a href='modifyDevice.php'>
        <button class='btn btn-primary'>Modify a device</button>
        </a>
        <a href='fileUpload.php'>
        <button class='btn btn-primary'>Upload file for a device</button>
        </a>
        <a href='deleteDevice.php'>
        <button class='btn btn-primary'>Delete a device</button>
        </a>
    </div>
    </body>
    ";
?>