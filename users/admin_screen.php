<?php
    include("../config.php");

    session_start();
    $userID = $_SESSION['id'];
    $department_name = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="admin.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="admin.js"></script>
</head>
<body>
    <div>
        <h1>Dashboard</h1>
        <button id="logoutbtn">Logout</button>
    </div>
    <div id="navbar">
        <button id="studentbar">Students</button>
        <button id="facultybar">Faculty</button>
        <button id="thesisbar">Theses</button>
        <button id="activityLogs">Activity Logs</button>
    </div>
    
    <div id="student">  
        <h3>Students</h3>
        <div id="search_student">
            <form id="search_filterStudent">
                <input type="text" name="search" id="search" required>
                <select name="department" id="department">
                    <?php
                        $deptstmt = $conn->prepare("SELECT name FROM department");
                        $deptstmt->execute();
                        $resdeptstmt = $deptstmt->get_result();
                        if($resdeptstmt->num_rows > 0){
                            while($row = $resdeptstmt->fetch_assoc()){
                                echo "<option value='".$row['name']."'>".$row['name']."</option>";
                            }
                        }
                    ?>
                </select>
                <select name="filter" id="filter">
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                    <option value="username">Username</option>
                </select>
                <input type="submit" name="submit" id="submit" value="Search">
                <button id="cancelsearchbtn">Cancel</button>
            </form>
        </div>
        <table border="1" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Username</td>
                    <td>Department</td>
                    <td>Profile</td>
                    <td>Signature</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody id="studentTable">
                <?php
                    $stmtStudent = $conn->prepare("SELECT * FROM user WHERE role = ?");
                    $role = "Student";
                    $stmtStudent->bind_param("s", $role);
                    $stmtStudent->execute();
                    $resStudent = $stmtStudent->get_result();
                    if($resStudent->num_rows > 0){
                        while($row = $resStudent->fetch_assoc()){
                            ?>
                                <tr>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['username'] ?></td>
                                    <td>
                                        <?php
                                            $dept = $conn->prepare("SELECT name FROM department WHERE dept_id = ?");
                                            $dept->bind_param("i", $row['department_id']);
                                            $dept->execute();
                                            $resdept = $dept->get_result()->fetch_assoc();
                                            $department_name = $resdept['name'];
                                            echo $department_name;
                                        ?>
                                    </td>
                                    <td><img src="../studentuploads/<?= $row['profile'] ?>" alt="Profile" width="100px" height="100px"></td>
                                    <td><img src="../studentuploads/<?= $row['sign'] ?>" alt="Signature" width="100px" height="100px"></td>
                                    <td><button class="updateStudent" 
                                    data-header="<?=  $row['role'].' Update'?>"
                                    data-user="<?= $row['role'].' ID' ?>"
                                    data-id="<?= $row['id'] ?>"
                                    data-name="<?= $row['name'] ?>"
                                    data-email="<?= $row['email'] ?>"
                                    data-username="<?= $row['username'] ?>"
                                    data-password="<?= $row['password'] ?>"
                                    data-role="<?= $row['role'] ?>"
                                    data-department="<?php
                                        $stmtdept = $conn->prepare("SELECT name FROM department WHERE dept_id = ?");
                                        $stmtdept->bind_param("i", $row['department_id']);
                                        $stmtdept->execute();
                                        $resdept = $stmtdept->get_result()->fetch_assoc();
                                        echo $resdept['name'];
                                    ?>"
                                    data-profile="<?= $row['profile'] ?>"
                                    data-sign="<?= $row['sign'] ?>"
                                    >Update</button> | 
                                    <button class="deleteUser" 
                                    data-id="<?= $row['id'] ?>" 
                                    data-name="<?= $row['name'] ?>"
                                    >Delete</button></td>
                                </tr>
                            <?php
                        }
                    }else{
                        echo "<p>No Student Found!</p>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div id="faculty">
        <h3>Faculty</h3>
        <div id="search_faculty">
            <form id="search_filterFaculty">
                <input type="text" name="search" id="search1" required>
                <select name="department" id="department1">
                    <?php
                        $deptstmt = $conn->prepare("SELECT name FROM department");
                        $deptstmt->execute();
                        $resdeptstmt = $deptstmt->get_result();
                        if($resdeptstmt->num_rows > 0){
                            while($row = $resdeptstmt->fetch_assoc()){
                                echo "<option value='".$row['name']."'>".$row['name']."</option>";
                            }
                        }
                    ?>
                </select>
                <select name="filter" id="filter1">
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                    <option value="username">Username</option>
                </select>
                <input type="submit" name="submit" id="submit1" value="Search">
                <button id="cancelsearchFaculty">Cancel</button>
            </form>
        </div>
        <table border="1" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Username</td>
                    <td>Department</td>
                    <td>Profile</td>
                    <td>Signature</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody id="facultyTable">
                <?php
                    $stmtStudent = $conn->prepare("SELECT * FROM user WHERE role = ?");
                    $role = "Faculty";
                    $stmtStudent->bind_param("s", $role);
                    $stmtStudent->execute();
                    $resStudent = $stmtStudent->get_result();
                    if($resStudent->num_rows > 0){
                        while($row = $resStudent->fetch_assoc()){
                            ?>
                                <tr>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['username'] ?></td>
                                    <td>
                                        <?php
                                            $dept = $conn->prepare("SELECT name FROM department WHERE dept_id = ?");
                                            $dept->bind_param("i", $row['department_id']);
                                            $dept->execute();
                                            $resdept = $dept->get_result()->fetch_assoc();
                                            $department_name = $resdept['name'];
                                            echo $department_name;
                                        ?>
                                    </td>
                                    <td><img src="../facultyuploads/<?= $row['profile'] ?>" alt="Profile" width="100px" height="100px"></td>
                                    <td><img src="../facultyuploads/<?= $row['sign'] ?>" alt="Signature" width="100px" height="100px"></td>
                                    <td><button class="updateStudent" 
                                    data-header="<?=  $row['role'].' Update'?>"
                                    data-user="<?= $row['role'].' ID' ?>"
                                    data-id="<?= $row['id'] ?>"
                                    data-name="<?= $row['name'] ?>"
                                    data-email="<?= $row['email'] ?>"
                                    data-username="<?= $row['username'] ?>"
                                    data-password="<?= $row['password'] ?>"
                                    data-role="<?= $row['role'] ?>"
                                    data-department="<?php
                                        $stmtdept = $conn->prepare("SELECT name FROM department WHERE dept_id = ?");
                                        $stmtdept->bind_param("i", $row['department_id']);
                                        $stmtdept->execute();
                                        $resdept = $stmtdept->get_result()->fetch_assoc();
                                        echo $resdept['name'];
                                    ?>"
                                    data-profile="<?= $row['profile'] ?>"
                                    data-sign="<?= $row['sign'] ?>"
                                    >Update</button> | 
                                    <button class="deleteUser" 
                                    data-id="<?= $row['id'] ?>" 
                                    data-name="<?= $row['name'] ?>"
                                    >Delete</button></td>
                                </tr>
                            <?php
                        }
                    }else{
                        echo "<p>No Student Found!</p>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div id="thesis">
        <h3>Theses</h3>
        <div id="search_thesis">
            <form id="search_filterThesis">
                <input type="text" name="search" id="search2" required>
                <select name="filter" id="filter2">
                    <option value="title">Title</option>
                    <option value="author">Author</option>
                    <option value="year">Year</option>
                    <option value="adviser">Adviser</option>
                    <option value="keywords">Keywords</option>
                </select>
                <input type="submit" name="submit" id="submit2" value="Search">
                <button id="cancelsearchThesis">Cancel</button>
            </form>
        </div>
        <div id="thesis_table">
            <?php
                $stmt = $conn->prepare("SELECT * FROM thesis");
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        ?>
                        <div id = "thesis_card">
                            <h1>Title: <?= htmlspecialchars($row['title']) ?></h1>
                            <h3>Abstract: <br><?= htmlspecialchars($row['abstract']) ?></h3>
                            <button class="reviewFile" data-filepath="<?= $row['file_path'] ?>">File</button>
                            <div class="filepreview" style="margin-bottom:10px;"></div>
                            <button class="closebtn">Close</button><br>
                            <p>Status: <?= htmlspecialchars($row['status']) ?></p>
                            <?php 
                                $adviser = "No Adviser Yet";
                                if(!empty($row['adviser_id'])){
                                    $adviser_id = $row['adviser_id']; 

                                    $stmtadviser = $conn->prepare("SELECT name FROM user WHERE id = ?");
                                    $stmtadviser->bind_param("i", $adviser_id);
                                    $stmtadviser->execute();

                                    $resultadviser = $stmtadviser->get_result();
                                    if($result && $resultadviser -> num_rows > 0){
                                        $data = $resultadviser->fetch_assoc();
                                        $adviser = $data['name'];
                                    }
                                }

                                echo "<p>Adviser: $adviser</p>";
                            ?>
                            
                            <p>Year: <?= $row['year'] ?></p>
                            <p>Submitted At: <?= $row['submitted_at'] ?></p>
                            <?php
                                $stmtTitle = $conn->prepare("SELECT title from thesis WHERE id = ?");
                                $stmtTitle->bind_param("i", $row['id']);
                                $stmtTitle->execute();
                                $resultTitle = $stmtTitle->get_result()->fetch_assoc();
                                $title = $resultTitle['title'];

                                $stmt3 = $conn->prepare("SELECT name FROM user WHERE id = ?");
                                $stmt3->bind_param("i", $userID);
                                $stmt3->execute();
                                $result3 = $stmt3->get_result()->fetch_assoc();

                                $reviewer = $result3['name'];
                            ?>
                            <button class="reviewThesis" 
                            data-thesis_id="<?= $title ?>"
                            data-reviewer_id="<?= $reviewer?>"
                            >Review</button>
                        </div>
                        <?php
                    }
                }else{
                    echo "<p>No Thesis Found!</p>";
                }
            ?>
        </div>
    </div>

    <div id="activity">
        <table border="1" style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <td>Activity ID</td>
                        <td>User ID</td>
                        <td>Actions</td>
                        <td>Details</td>
                        <td>Created At</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $logstmt = $conn->prepare("SELECT * FROM activity_logs");
                        $logstmt->execute();
                        $logres = $logstmt->get_result();
                        if($logres->num_rows > 0){
                            while($row = $logres->fetch_assoc()){
                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['user_id'] ?></td>
                                        <td><?= $row['action'] ?></td>
                                        <td><?= $row['details'] ?></td>
                                        <td><?= $row['created_at'] ?></td>
                                    </tr>
                                <?php
                            }
                        }
                    ?>
                </tbody>
        </table>
    </div>

    <div id="reviewModal">
        <form id="reviewForm">
            <label for="thesis">Title</label><br>
            <input type="text" name="thesis1" id="thesis1" style="width: 700px;" readonly><br>
            <label for="reviewer">Reviewer</label><br>
            <input type="text" name="reviewer" id="reviewer" style="width: 700px;" readonly><br>
            <label for="status">Status</label><br>
            <select name="status" id="status">
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="pending">Pending</option>
            </select><br>
            <label for="comments">Comments</label><br>
            <textarea name="comments" id="comments"></textarea><br>
            <input type="submit" name="submit" id="submit" value="Submit">
            <button id="cancelreviewbtn">Cancel</button>
        </form>
    </div>

    <div id="FormModal">
        <h1 id="formheader"></h1>
        <form id="userForm">
            <div id="student-details">
                <label for="user_id" id="user"></label><br>
                <input type="number" name="user_id" id="user_id" readonly><br>

                <label for="name">Name</label><br>
                <input type="text" name="name" id="name"><br>

                <label for="email">Email</label><br>
                <input type="email" name="email" id="email"><br>

                <label for="role">Role</label><br>
                <input type="text" name="role" id="role" readonly><br>

                <label for="dept_id">Department</label><br>
                <select name="dept_id" id="dept_id">
                    <?php 
                        $deptstmt = $conn->prepare("SELECT name from department");
                        $deptstmt->execute();
                        $deptres = $deptstmt->get_result();
                        if($deptres->num_rows > 0){
                            while($row = $deptres->fetch_assoc()){
                                ?>
                                <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                                <?php
                            }
                        }
                    ?>
                </select>

                <div id="profileImg"></div>
                <div id="signImg"></div>
                <label for="profileUpdate">Profile</label><br>
                <input type="file" name="profileUpdate" id="profileUpdate"><br>
                
                <label for="signUpdate">Signature</label><br>
                <input type="file" name="signUpdate" id="signUpdate"><br>
            </div>

            <div id="account-details">
                <label for="username">Username</label><br>
                <input type="text" name="username" id="username"><br>

                <label for="password">Password</label><br>
                <input type="text" name="password" id="password"><br>

            </div>

            <input type="submit" name="formsubmit" id="formsubmitfaculty" value="Update">
            <button id="closeForm">Cancel</button>
        </form>
    </div>
</body>
</html>