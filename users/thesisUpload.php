    <?php
        include("../config.php");
        session_start();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
            $abstract = $_POST['abstract'];
            $keywords = $_POST['keywords'];
            $student_id = $_SESSION['id'];

            $adviser = "";
            $stmt4 = $conn->prepare("SELECT * FROM user WHERE name = ? and role = ?");
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
            $stmt5 = $conn->prepare("SELECT * FROM program WHERE name = ?");
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
            $status = "Submitted";

            $actions = "Thesis Submission";
            $details = "Submitted Thesis: $title";
            
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
                echo json_encode(['success' => false, 'message' => $thesis]);
                exit();
            }

            $stmt = $conn -> prepare("INSERT INTO thesis (title, abstract, keywords, student_id, adviser_id, dept_id, prog_id, year, status, file_path) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiiiiiss", $title, $abstract, $keywords, $student_id, $adviser, $dept_id, $program, $year, $status, $thesis['filename']);

            if ($stmt->execute()) {
                $stmt2 = $conn->prepare("INSERT INTO activity_logs(user_id, action, details) VALUES(?, ?, ?)");
                $stmt2->bind_param("iss", $student_id, $actions, $details);
                $stmt2->execute();
                echo json_encode(['success' => true, 'message' => 'Thesis Submitted successfully', 'file' => $thesis['filename']]);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Database insert failed']);
                exit();
            }

            $stmt->close();
            $conn->close();
        }
    ?>