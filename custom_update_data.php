<?php
$conn = mysqli_connect("localhost", "root", "","diacdelhi_db");
// This file is just for updating data.
// This does not have any link with build
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encryption</title>
</head>
<body>
<h1 style="text-align: center;">Encrypt/Decrypt String using a Private Secret Key with PHP</h1>

<?php
    $query = mysqli_query($conn, "SELECT * FROM cs_case_details_tbl ORDER BY id DESC");
    $file = [];
    while($row = mysqli_fetch_array($query)){
        if($row['case_file']){
            str_replace('"', '', $row['case_file']);
            array_push($file, $row['case_file']);
            $file_store = json_encode($file);
            $id = $row['id'];
            
            $result = mysqli_query($conn, "UPDATE cs_case_details_tbl SET case_file='$file_store' WHERE id='$id'");
            echo (($result == 1)? 'Updation Success':'Updation failed').'<br />';
        }
    }

    // Check connection
    if ($conn -> connect_errno) {
        echo "Failed to connect to MySQL: " . $conn -> connect_error;
        exit();
    }
?>

</body>
</html>