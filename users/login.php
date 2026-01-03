<?php
    include("../config.php");
    session_start();

    $response = ['success' => false, 'message' => ""];
    if($_SERVER['REQUEST_METHOD'] === "POST"){

        if(empty($_POST['username'])){
            echo json_encode(['success' => false, 'message' => "Username is Empty"]);
            exit();
        }
        $username = trim($_POST['username']);
        
        $password = $_POST['password'];
        

        $stmt = $conn -> prepare("SELECT * FROM user WHERE username = ?");
        $stmt -> bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result -> num_rows === 1){
            $rows = $result->fetch_assoc();

            if(password_verify($password, $rows['password'])){
                
                $_SESSION['id'] = $rows['id'];
                $_SESSION['name'] = $rows['name'];
                $_SESSION['dept'] = $rows['department_id'];
                $_SESSION['role'] = $rows['role'];
                $_SESSION['profile'] = $rows['profile'];
                $_SESSION['sign'] = $rows['sign'];

                $response['success'] = true;
                $response['message'] = "Login Successful";
                $response['role'] = $rows['role'];

                $action = "Login";
                $details = $rows['role'] == 'admin' ? "Admin Logged in" : "User Logged in";

                $stmt1 = $conn->prepare("INSERT INTO activity_logs(user_id, action, details) VALUES(?, ?, ?)");
                $stmt1->bind_param("iss", $rows['id'], $action, $details);
                $stmt1->execute();
            }else{
                $response['message'] = "Invalid Password ".$rows['name'];
            }
        }else{
            $response['message'] = "Student not found". $stmt->error;
        }
    }else{
        $response['message'] = "Submit error";
    }
    echo json_encode($response);
?>