<?php
    include("../config.php");
    session_start();
    $admin_id = $_SESSION['id'];
    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $userID = $_POST['id'];

        $deletestmt = $conn->prepare("DELETE FROM user where id = ?");
        $deletestmt->bind_param("i", $userID);
        if($deletestmt->execute()){
            $action = "User Deletion";
            $details = "User Deleted Successfully";
            $logstmt = $conn->prepare("INSERT INTO activity_logs(user_id, action, details) VALUES(?, ?, ?)");
            $logstmt->bind_param("iss", $admin_id, $action, $details);
            if($logstmt->execute()){
                echo json_encode(['success' => true, 'message' => $details]);
                exit();
            }else{
                echo json_encode(['success' => false, 'message' => "Insertion into activity_logs Failed"]);
                exit();
            }
        }else{
            echo json_encode(['success' => false, 'message' => "Deletion of User Failed!"]);
            exit();
        }
    }
?>