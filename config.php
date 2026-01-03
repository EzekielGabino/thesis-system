<?php
    $conn = new mysqli("localhost", "root", "", "thesis");
    
    if($conn->error){
        die("Error ".$conn->connect_error);
    }
?>