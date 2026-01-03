<?php
    include("../config.php");
    session_start();

    $faculty_id = $_SESSION['id'];
    $role = $_SESSION['role'];
    $name = $_SESSION['name'];
    $profile = $_SESSION['profile'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="faculty.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="faculty.js"></script>
</head>
<body>
    <div id="header">
        <h1>Student's Theses</h1>
        <h2><?= $role ?>: <?= $name ?></h2>
        <img src="../facultyuploads/<?= $profile ?>" alt="Profile" width="100px" height="100px">
        <button id="logoutbtn" >Logout</button>
    </div>
    <button id="searchbtn">Search</button>
    <div id="search_filter">
        <form id="search">
            <input type="text" name="search" required>
            <select name="filter" id="filter">
                <option value="title">Title</option>
                <option value="author">Author</option>
                <option value="year">Year</option>
                <option value="adviser">Adviser</option>
                <option value="keywords">Keywords</option>
            </select>
            <input type="submit" name="submit" id="submit" value="Search">
            <button id="cancelsearchbtn">Cancel</button>
        </form>
    </div>
    <div id="thesis">
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
                            $adviser_id = $row['adviser_id'];   
                            $stmt1 = $conn->prepare("SELECT name FROM user WHERE id = ?");
                            $stmt1->bind_param("i", $adviser_id);
                            $stmt1->execute();
                            $result1 = $stmt1->get_result()->fetch_assoc();
                            $adviser = $result1['name'];
                            echo "<p>Adviser: $adviser</p>";
                        ?>
                        
                        <p>Year: <?= $row['year'] ?></p>
                        <p>Submitted At: <?= $row['submitted_at'] ?></p>
                        <?php
                            $stmt2 = $conn->prepare("SELECT title from thesis WHERE id = ?");
                            $stmt2->bind_param("i", $row['id']);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result()->fetch_assoc();
                            $title = $result2['title'];

                            $stmt3 = $conn->prepare("SELECT name FROM user WHERE id = ?");
                            $stmt3->bind_param("i", $faculty_id);
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
</body>
</html>