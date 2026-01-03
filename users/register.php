<?php
    include("../config.php");

    $response = ['success' => false, 'message' => ''];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit();
    }

    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $username = trim($_POST['username'] ?? '');
    $role = $_POST['role'] ?? '';
    $deptname = $_POST['dept'] ?? '';

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

    $check = $conn->prepare("SELECT id FROM user WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
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
    $allowedExt  = ['jpg', 'jpeg', 'png'];
    $maxSize     = 2 * 1024 * 1024;

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
        if(!empty($_FILES['profile']['name'])){
            $profile = uploadFile('profile', $uploadDir, $allowedExt, $allowedMime, $maxSize);
        }
        if(!empty($_FILES['sign']['name'])){
            $sign = uploadFile('sign', $uploadDir, $allowedExt, $allowedMime, $maxSize);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit();
    }
    $profile = $profile ?? null;
    $sign = $sign ?? null;

    $stmt = $conn->prepare("INSERT INTO user (name, email, username, password, role, department_id, profile, sign) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiss", $name, $email, $username, $password, $role, $deptid, $profile, $sign);

    if ($stmt->execute()) {
        $action = "Registration";
        $details = "User Successfully Registered";
        
        $user_id = $conn->insert_id;
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
