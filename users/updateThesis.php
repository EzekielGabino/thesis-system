    <?php
        include("../config.php");
        session_start();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $thesis_id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
            $abstract = $_POST['abstract'];
            $keywords = $_POST['keywords'];
            $student_id = $_SESSION['id'];

            $adviser = "";
            $stmt4 = $conn->prepare("SELECT id FROM user WHERE name = ? and role = ?");
            $adname = $_POST['adviser'];
            $faculty = "Faculty";
            $stmt4->bind_param("ss", $adname, $faculty);
            $stmt4->execute();
            $result3 = $stmt4->get_result();
            if($result3->num_rows === 1){
                while($row = $result3->fetch_assoc()){
                    $adviser = $row['id'];
                }
            }

            $dept_id = $_SESSION['dept'];

            $program = "";
            $stmt5 = $conn->prepare("SELECT prog_id FROM program WHERE name = ?");
            $progname = $_POST['program'];
            $stmt5->bind_param("s", $progname);
            $stmt5->execute();
            $result4 = $stmt5->get_result();
            if($result4->num_rows === 1){
                while($row = $result4->fetch_assoc()){
                    $program = $row['prog_id'];
                }
            }

            $year = $_POST['year'];
            $approved_at = $_POST['approved'];
            $actions = "Thesis Updated";
            $details = "Updated Thesis: $title";
            
            if($approved_at == null){
                echo json_encode(['success' => false, 'message' => "Thesis Approved Cannot be Updated"]);
                exit();
            }
            if(!isset($_FILES['thesis']) || $_FILES['thesis']['error'] === UPLOAD_ERR_NO_FILE){
                //File Not changed
                $stmt = $conn -> prepare("UPDATE thesis SET title = ?, abstract = ?, keywords = ?, adviser_id = ?, dept_id = ?, prog_id = ?, year = ? WHERE id = ? AND student_id = ?");
                $stmt->bind_param("sssiiiiii", $title, $abstract, $keywords, $adviser, $dept_id, $program, $year, $thesis_id, $student_id);

                if ($stmt->execute()) {
                    $stmt2 = $conn->prepare("INSERT INTO activity_logs(user_id, action, details) VALUES(?, ?, ?)");
                    $stmt2->bind_param("iss", $student_id, $actions, $details);
                    $stmt2->execute();
                    echo json_encode(['success' => true, 'message' => 'Thesis Updated successfully']);
                    exit();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database Update failed']);
                    exit();
                }

            }else{
                //File Changed

                //get old filename
                $stmtOld = $conn->prepare("SELECT file_path FROM thesis WHERE id = ? AND student_id = ?");
                $stmtOld->bind_param("ii", $thesis_id, $student_id);
                $stmtOld->execute();
                $resultOld = $stmtOld->get_result();

                $oldFile = null;
                if ($row = $resultOld->fetch_assoc()) {
                    $oldFile = $row['file_path'];
                }
                $stmtOld->close();


                //change file
                $uploadDir = "../thesisFiles/";
                $maxSize = 30 * 1024 * 1024;
                $allowedMime = [
                    'application/pdf',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];
                $allowedExt = ['pdf', 'docx'];  

                function uploadFile($fileInput, $uploadDir, $allowedExt, $allowedMime, $maxSize){
                    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
                        return ['success' => false, 'message' => "$fileInput upload error"];
                    }

                    $file = $_FILES[$fileInput];

                    // Check size
                    if ($file['size'] > $maxSize) {
                        return ['success' => false, 'message' => "$fileInput is too large"];
                    }

                    // Check MIME type
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->file($file['tmp_name']);

                    if (!in_array($mimeType, $allowedMime)) {
                        return ['success' => false, 'message' => "$fileInput invalid file type"];
                    }

                    // Check extension
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowedExt)) {
                        return ['success' => false, 'message' => "$fileInput invalid file extension"];
                    }
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Move file
                    $newName = uniqid($fileInput.'_') . "." . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                        return ['success' => true, 'filename' => $newName];
                    }else{
                        return ['success' => false, 'message' => "file movement failed"];
                    }
                }

                $thesis = uploadFile('thesis', $uploadDir, $allowedExt, $allowedMime, $maxSize);

                if (!$thesis['success']) {
                    echo json_encode(['success' => false, 'message' => $thesis['message']]);
                    exit();
                }

                if ($oldFile) {
                    $oldFilePath = $uploadDir . $oldFile;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $stmt = $conn -> prepare("UPDATE thesis SET title = ?, abstract = ?, keywords = ?, adviser_id = ?, dept_id = ?, prog_id = ?, year = ?, file_path = ?  WHERE id = ? AND student_id = ?");
                $stmt->bind_param("sssiiiisii", $title, $abstract, $keywords, $adviser, $dept_id, $program, $year, $thesis['filename'], $thesis_id, $student_id);

                if ($stmt->execute()) {
                    $stmt2 = $conn->prepare("INSERT INTO activity_logs(user_id, action, details) VALUES(?, ?, ?)");
                    $stmt2->bind_param("iss", $student_id, $actions, $details);
                    $stmt2->execute();
                    echo json_encode(['success' => true, 'message' => 'Thesis Updated successfully', 'file' => $thesis['filename']]);
                    exit();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database Update failed '.$thesis['message']]);
                    exit();
                }

                $stmt->close();
                $conn->close();
            }

            
        }
    ?>