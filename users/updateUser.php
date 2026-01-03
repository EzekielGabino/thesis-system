<?php
    include("../config.php");

    $response = ['success' => false, 'message' => ''];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit();
    }
    $user_id = $_POST['user_id'];
    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $username = trim($_POST['username'] ?? '');
    $role = $_POST['role'] ?? '';
    $deptname = $_POST['dept_id'] ?? '';

    if ($name === '') {
        echo json_encode(['success' => false, 'message' => 'Name required']); exit();
    }
    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Invalid email']); exit();
    }
    if ($username === '') {
        echo json_encode(['success' => false, 'message' => 'Username required']); exit();
    }
    if (empty($_POST['password'])) {
        echo json_encode(['success' => false, 'message' => 'Password required']); exit();
    }
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM user WHERE (username = ? OR email = ?) AND id != ?");
    $check->bind_param("ssi", $username, $email, $user_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
        exit();
    }

    $stmtDept = $conn->prepare("SELECT dept_id FROM department WHERE name = ?");
    $stmtDept->bind_param("s", $deptname);
    $stmtDept->execute();
    $resDept = $stmtDept->get_result();

    if ($resDept->num_rows !== 1) {
        echo json_encode(['success' => false, 'message' => 'Invalid department']);
        exit();
    }

    $deptid = $resDept->fetch_assoc()['dept_id'];

    $uploadDir = ($role === 'Student') ? "../studentuploads/" : "../facultyuploads/";
    $allowedMime = ['image/jpeg', 'image/png'];
    $allowedExt = ['jpg', 'jpeg', 'png'];
    $maxSize = 2 * 1024 * 1024;

    //fetch the previous profile and sign
    $stmtOld = $conn->prepare("SELECT profile, sign FROM user WHERE id = ?");
    $stmtOld->bind_param("i", $user_id);
    $stmtOld->execute();
    $resOld = $stmtOld->get_result()->fetch_assoc();

    function uploadFile($key, $uploadDir, $allowedExt, $allowedMime, $maxSize) {
        if (empty($_FILES[$key]['name'])) return null;

        $file = $_FILES[$key];

        if ($file['size'] > $maxSize) throw new Exception("$key too large");

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!in_array($mime, $allowedMime)) throw new Exception("$key invalid type");

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) throw new Exception("$key invalid extension");

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename = uniqid($key.'_') . ".$ext";
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);

        return $filename;
    }
    try {
        if(!empty($_FILES['profileUpdate']['name'])){
            $profile = uploadFile('profileUpdate', $uploadDir, $allowedExt, $allowedMime, $maxSize);
        }else{
            $profile_old = $resOld['profile'];
            $profile = $profile_old;
            if ($profile_old) {
                $oldFilePath = $uploadDir . $profile_old;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        }
        if(!empty($_FILES['signUpdate']['name'])){
            $sign = uploadFile('signUpdate', $uploadDir, $allowedExt, $allowedMime, $maxSize);
        }else{
            $sign_old = $resOld['sign'];
            $sign = $sign_old;
            if ($sign_old) {
                $oldFilePath = $uploadDir . $sign_old;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE user SET name = ?, email = ?, username = ?, password = ?, role = ?, department_id = ?, profile = ?, sign = ? WHERE id = ?");
    $stmt->bind_param("sssssissi", $name, $email, $username, $password, $role, $deptid, $profile, $sign, $user_id);

    if ($stmt->execute()) {
        $action = "User Update";
        $details = "User Successfully Updated";
        
        $log = $conn->prepare("INSERT INTO activity_logs(user_id, action, details) VALUES(?, ?, ?)");
        $log->bind_param("iss", $user_id, $action, $details);
        if($log->execute()){
            echo json_encode(['success' => true, 'message' => $details]);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database Update failed '. $stmt->error]);
        exit();
    }
?>
