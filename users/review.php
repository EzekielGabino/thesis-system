<?php
    include("../config.php");

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $title = $_POST['thesis1'];
        $reviewer = $_POST['reviewer'];
        $status = $_POST['status'];
        $comments = $_POST['comments'];

        $stmtThesis = $conn->prepare("SELECT id FROM thesis WHERE title = ?");
        $stmtThesis->bind_param("s", $title);
        $stmtThesis->execute();
        $resThesis = $stmtThesis->get_result()->fetch_assoc();

        $thesis_id = $resThesis['id'];

        $stmtReviewer = $conn->prepare("SELECT id FROM user WHERE name = ? ");
        $stmtReviewer->bind_param("s", $reviewer);
        $stmtReviewer->execute();
        $resReviewer = $stmtReviewer->get_result()->fetch_assoc();

        $reviewer_id = $resReviewer['id'];

        $approved_at = date('Y-m-d H:i:s');

        $stmtUpdateThesis = $conn->prepare("UPDATE thesis SET status = ?, approved_at = ? WHERE id = ?");
        $stmtUpdateThesis->bind_param("ssi", $status, $approved_at, $thesis_id);
        if($stmtUpdateThesis->execute()){

            $stmtApproval = $conn->prepare("INSERT INTO approvals(thesis_id, reviewer_id, status, comments) VALUES(?, ?, ?, ?)");
            $stmtApproval->bind_param("iiss", $thesis_id, $reviewer_id, $status, $comments);
            
            if($stmtApproval->execute()){
                echo json_encode(['success' => true, 'message' => "Thesis have been Updated"]);
                exit();
            }
            
            $action = "Thesis Review";
            $details = "Thesis Have been $status";
            $stmtAcitvityLogs = $conn->prepare("INSERT INTO activity_logs(user_id, action, details) VALUES(?, ?, ?)");
            $stmtAcitvityLogs->bind_param("iss", $reviewer_id, $action, $details);
            $stmtAcitvityLogs->execute();
        }
        
    }
?>