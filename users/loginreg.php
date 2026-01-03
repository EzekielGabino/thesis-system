<?php
    include("../config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="login.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="login.js"></script>
</head>
<body>
    <div id="register">
        <h1>Registration</h1>
        <form id="registerform" enctype="multipart/form-data">
            <label for="name">Name</label>
            <input type="text" id="name" name="name"><br>

            <label for="email">Email</label>
            <input type="email" name="email"><br>

            <label for="username">Username</label>
            <input type="text" id="username" name="username"><br>

            <label for="password">Password</label>
            <input type="password" id="password" name="password"><br>

            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="Student">Student</option>
                <option value="Faculty">Faculty</option>
            </select><br>
            
            <label for="dept">Department</label>
            <select name="dept" id="dept">
                <?php
                    $stmt = $conn->prepare("SELECT * FROM department");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            ?>
                            <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                            <?php
                        }
                    }
                ?>
            </select><br>

            <label for="profile">Profile</label>
            <input type="file" id="profile" name="profile"><br>

            <label for="sign">Signature</label>
            <input type="file" id="sign" name="sign"><br>

            <input type="submit" name="submit" id="submit" value="Register">
        </form> 
    </div>
    <div id="login">
        <h1>Login</h1>
        <form id="loginform">
            <label for="username">Username</label>
            <input type="text" id="username1" name="username" required><br>
            <label for="password">Password</label>
            <input type="password" id="password1" name="password" required><br>
            <input type="submit" name="submit1" id="submit1" value="Login">
        </form> 
    </div>
    <div>
        <button id="loginbtn">Login</button>
        <button id="regbtn">Register</button>
    </div>
    <div>
        sample accounts <br>
        student: Shiro123, Aqutan123! <br>
        Faculty: faculty, password133 <br>
        admin: admin, admin <br>
    </div>
</body>
</html>