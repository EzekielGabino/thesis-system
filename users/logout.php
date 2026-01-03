<?php
    
    include("../config.php");
    session_start();
    $response = ['success' => false, 'message' => ''];

    $user_id = $_SESSION['id'];
    $actions = "Logout";
    $details = "User Logged out";

    $stmt = $conn->prepare("INSERT INTO activity_logs(user_id, action, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $actions, $details);
    $stmt->execute();


    $_SESSION = [];

    if (session_destroy()) {
        $response['success'] = true;
        $response['message'] = "Logged out successfully";
    } else {
        $response['message'] = "Failed to logout damn";
    }

    echo json_encode($response);

?>