<?php
include_once('config.php');
include_once('bootstrap.php');
include('helpers.php');

#create a form with serial number and file upload
echo "<form method='post' action='' enctype='multipart/form-data'>";
echo "<p>Enter a serial number:</p>";
echo "<input type='text' class='form-control' name='serial' required='required' pattern='SN-[a-z0-9]{32}' title='SN-[32 characters]'></input>";
echo "<p>Upload a file:</p>";
echo "<input type='file' name='file' required='required'></input>";
echo "<hr>";
echo "<div><button class='btn btn-success' type='submit' name='submit' value='submit'>Submit</button></div>";
echo "</form>";

#parse form
if(isset($_POST['submit']) && $_FILES['file']['size'] > 0){
    #setup new location for file
    $targetDir = "/var/www/html/files/";

    $fileName = str_replace(" ", "_", $_FILES["file"]["name"]);
    $fileSize = $_FILES["file"]["size"];
    $tmpName = $_FILES["file"]["tmp_name"];
    $fileType = $_FILES["file"]["type"];

    $target_file = $targetDir . $fileName;
    $uploadOk = 1;

    # allow duplicate files, BUT change link in DB entry
    
    // if(file_exists($target_file)){
    //     echo "<p>File already exists</p>";
    //     $uploadOk = 0;
    // }

    $value = check_serial($_POST['serial']);
    if($value == FALSE){
        echo "<p>Serial number does NOT exist. Try a different one.</p>";
        return;
    }
    
    #move file to new location
    if($uploadOk == 1){
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)){
            #insert into database table files
            $sql = "insert into data.files values (NULL, '$fileName', '$fileType', '$fileSize', '/files/$fileName', '$value');";
            $dblink->query($sql) or
                die("Something went wrong with $sql");
            echo "<p>File uploaded successfully to device_id: $value</p>";
        }
        else {
            echo "<p>File upload failed</p>";
            echo "<p>File name: $fileName</p>";
            echo "<p>Temp name: $tmpName</p>";
            echo $target_file;
        }
    }
}

?>