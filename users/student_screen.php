<?php
    include("../config.php");
    session_start();
    $student_id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $profile = $_SESSION['profile'];
    $sign = $_SESSION['sign'];
    $role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="student_screen.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="student.js"></script>
</head>
<body>
    <div id="header">
        <h1>My Theses</h1>
        <h2><?= $role ?>: <?= $name ?></h2>
        <img src="../studentuploads/<?= $profile ?>" alt="Profile" width="100px" height="100px">
        <button id="logoutbtn" >Logout</button>
    </div>
    <div id="navbar">
        <button id="SubmittedThesis">Track Submitted Thesis</button>
        <button id="SubmissionForm">Submission Form</button>
    </div>
    <div id="thesis_submission">
        <form id="form" enctype="multipart/form-data">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required style="width: 700px;"><br><br>
            <label for="abstract">Abstract</label><br>
            <textarea name="abstract" id="abstract" style="width: 700px; height: 100px;"></textarea><br><br>
            <label for="keywords">Keywords</label><br>  
            <textarea name="keywords" id="keywords" style="width: 700px;"></textarea><br><br>
            <label for="adviser">Adviser</label><br>
            <select name="adviser" id="adviser">
                <?php
                    $stmt = $conn->prepare("SELECT * FROM user WHERE role = ?");
                    $roleFaculty = "Faculty";
                    $stmt->bind_param("s", $roleFaculty);
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
            </select><br><br>
            <label for="program">Program</label><br>
            <select name="program" id="program">
                <?php
                    $stmt1 = $conn->prepare("SELECT * FROM program");
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();

                    if($result->num_rows > 0){
                        while($row1 = $result1->fetch_assoc()){
                            ?>
                            <option value="<?= $row1['name'] ?>"><?= $row1['name'] ?></option>
                            <?php
                        }
                    }
                ?>
            </select><br><br>
            <label for="year">Year</label><br>
            <input type="number" name="year"><br>
            <label for="thesisfile">Thesis File</label><br>
            <input type="file" name="thesis"><br>
            <input type="submit" name="submit" value="submit">
        </form>
    </div>
    <div id="trackersbtn">
        <button id="submittedbtn">Submitted</button>
        <button id="approvedbtn">Approved</button>
        <button id="rejectedbtn">Rejected</button>
        <button id="pendingbtn">Pending</button>
    </div>
    <div id="thesis_tracker">
        <div id="submitted">
                <?php
                $stmt2 = $conn->prepare("SELECT * FROM thesis WHERE student_id = ? and status = ?");
                $statusSubmitted = "Submitted";
                $stmt2->bind_param("is", $student_id, $statusSubmitted);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if($result2->num_rows > 0){
                    while($row = $result2->fetch_assoc()){
                        ?>
                        <div>
                            <h1>Title: <?= htmlspecialchars($row['title']) ?></h1>
                            <h3>Abstract: <br><?= htmlspecialchars($row['abstract']) ?></h3>
                            <p>Year: <?= $row['year'] ?></p>
                            <p>Submitted At: <?= $row['submitted_at'] ?></p>
                            <p>Approved At: <?= $row['approved_at'] == null ? "Not Yet Approved" : $row['approved_at'] ?></p>
                            <button class="editThesis" 
                            data-id="<?= $row['id'] ?>"
                            data-title="<?= htmlspecialchars($row['title']) ?>"
                            data-abstract="<?= htmlspecialchars($row['abstract']) ?>"
                            data-keywords="<?= htmlspecialchars($row['keywords']) ?>"
                            data-year="<?= $row['year'] ?>"
                            data-thesis="<?= htmlspecialchars($row['file_path']) ?>"
                            data-date_approved="<?= $row['approved_at'] ?? '' ?>"
                            >Update</button>
                        </div>
                        <?php
                    }
                }else{
                    echo "<p>No Thesis Found!</p>";
                }
                ?>
        </div>
        <div id="approved">
            <?php
                $stmt3 = $conn->prepare("SELECT * FROM thesis WHERE student_id = ? and status = ?");
                $statusApproved = "Approved";
                $stmt3->bind_param("is", $student_id, $statusApproved);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
                if($result3->num_rows > 0){
                    while($row = $result3->fetch_assoc()){
                        ?>
                        <div>
                            <h1>Title: <?= htmlspecialchars($row['title']) ?></h1>
                            <h3>Abstract: <br><?= htmlspecialchars($row['abstract']) ?></h3>
                            <p>Year: <?= $row['year'] ?></p>
                            <p>Submitted At: <?= $row['submitted_at'] ?></p>
                            <p>Approved At: <?= $row['approved_at'] == null ? "Not Yet Approved" : $row['approved_at'] ?></p>
                        </div>
                        <?php
                    }
                }else{
                    echo "<p>No Thesis Found!</p>";
                }
                ?>
        </div>
        <div id="pending">
            <?php
                $stmt4 = $conn->prepare("SELECT * FROM thesis WHERE student_id = ? and status = ?");
                $statusPending = "Pending";
                $stmt4->bind_param("is", $student_id, $statusPending);
                $stmt4->execute();
                $result4 = $stmt4->get_result();
                if($result4->num_rows > 0){
                    while($row = $result4->fetch_assoc()){
                        ?>
                        <div>
                            <h1>Title: <?= htmlspecialchars($row['title']) ?></h1>
                            <h3>Abstract: <br><?= htmlspecialchars($row['abstract']) ?></h3>
                            <p>Year: <?= $row['year'] ?></p>
                            <p>Submitted At: <?= $row['submitted_at'] ?></p>
                            <p>Approved At: <?= $row['approved_at'] == null ? "Not Yet Approved" : $row['approved_at'] ?></p>
                            <button class="editThesis" 
                            data-id="<?= $row['id'] ?>"
                            data-title="<?= htmlspecialchars($row['title']) ?>"
                            data-abstract="<?= htmlspecialchars($row['abstract']) ?>"
                            data-keywords="<?= htmlspecialchars($row['keywords']) ?>"
                            data-year="<?= $row['year'] ?>"
                            data-thesis="<?= htmlspecialchars($row['file_path']) ?>"
                            data-date_approved="<?= $row['approved_at'] ?? '' ?>"
                            >Update</button>
                        </div>
                        <?php
                    }
                }else{
                    echo "<p>No Thesis Found!</p>";
                }
                ?>
        </div>
        <div id="rejected">
            <?php
                $stmt5 = $conn->prepare("SELECT * FROM thesis WHERE student_id = ? and status = ?");
                $statusRejected = "Rejected";
                $stmt5->bind_param("is", $student_id, $statusRejected);
                $stmt5->execute();
                $result5 = $stmt5->get_result();
                if($result5->num_rows > 0){
                    while($row = $result5->fetch_assoc()){
                        ?>
                        <div>
                            <h1>Title: <?= htmlspecialchars($row['title']) ?></h1>
                            <h3>Abstract: <br><?= htmlspecialchars($row['abstract']) ?></h3>
                            <p>Year: <?= $row['year'] ?></p>
                            <p>Submitted At: <?= $row['submitted_at'] ?></p>
                            <p>Approved At: <?= $row['approved_at'] == null ? "Not Yet Approved" : $row['approved_at'] ?></p>
                            <button class="editThesis" 
                            data-id="<?= $row['id'] ?>"
                            data-title="<?= htmlspecialchars($row['title']) ?>"
                            data-abstract="<?= htmlspecialchars($row['abstract']) ?>"
                            data-keywords="<?= htmlspecialchars($row['keywords']) ?>"
                            data-year="<?= $row['year'] ?>"
                            data-thesis="<?= htmlspecialchars($row['file_path']) ?>"
                            data-date_approved="<?= $row['approved_at'] ?? '' ?>"
                            >Update</button>
                        </div>
                        <?php
                    }
                }else{
                    echo "<p>No Thesis Found!</p>";
                }
                ?>
        </div>
    </div>

    <div id="modal">
        <form id="updateForm" enctype="multipart/form-data">
            <label for="id">Thesis ID</label><br>
            <input type="number" name="id" id="id" readonly><br>
            <label for="title">Title</label><br>
            <input type="text" id="title1" name="title" required style="width: 700px;"><br><br>
            <label for="abstract">Abstract</label><br>
            <textarea name="abstract" id="abstract1" style="width: 700px; height: 100px;"></textarea><br><br>
            <label for="keywords">Keywords</label><br>  
            <textarea name="keywords" id="keywords1" style="width: 700px;"></textarea><br><br>
            <label for="adviser">Adviser</label><br>
            <select name="adviser" id="adviser1">
                <?php
                    $stmt = $conn->prepare("SELECT * FROM user WHERE role = ? ");
                    $stmt->bind_param("s", $roleFaculty);
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
            </select><br><br>
            <label for="program">Program</label><br>
            <select name="program" id="program">
                <?php
                    $stmt1 = $conn->prepare("SELECT * FROM program");
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();

                    if($result->num_rows > 0){
                        while($row1 = $result1->fetch_assoc()){
                            ?>
                            <option value="<?= $row1['name'] ?>"><?= $row1['name'] ?></option>
                            <?php
                        }
                    }
                ?>
            </select><br><br>
            <label for="year">Year</label><br>
            <input type="number" name="year" id="year"><br>
            <button id="reviewfile">Review File</button>
            <div id="filePreview" style="margin-bottom:10px;"></div>
            <div id="fileOptions">
                <label>Choose Another File?</label>
                <button id="yesbtn">Yes</button>
                <button id="nobtn">No</button>
            </div>
            <div id="chooseFile">
                <label for="thesisfile">Thesis File</label><br>
                <input type="file" name="thesis" id="thesis"><br>
            </div>
            <label for="date_approved">Date Approved</label><br>
            <input type="text" name="approved" id="approved_date" readonly><br>
            <input type="submit" id="save" name="submit" value="Save">
            <button id="cancel">Cancel</button>
        </form>
    </div>

</body>
</html>