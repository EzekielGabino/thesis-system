<?php
    include("../config.php");
    session_start();
    $faculty_id = $_SESSION['id'];
    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $search = trim($_POST['search']);
        $filter = $_POST['filter'];

        if($filter == "title"){
            $stmt = $conn->prepare("SELECT * FROM thesis WHERE title LIKE ?");
            $searchterm = "%{$search}%";
            $stmt->bind_param("s", $searchterm);
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
                echo "<p>No Thesis Found With Title $search</p>";
            }

            
        }
        if($filter == "author"){
            $stmt = $conn->prepare("SELECT id FROM user WHERE name LIKE ?");
            $searchterm = "%{$search}%";
            $stmt->bind_param("s", $searchterm);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows === 0){
                echo "<p>No Author found with name $search</p>";
            }

            while($author = $result->fetch_assoc()){
                $foundThesis = true;
                $author_id = $author['id'];

                $stmt1 = $conn->prepare("SELECT * FROM thesis WHERE student_id = ?");
                $stmt1->bind_param("i", $author_id);
                $stmt1->execute();
                $result1 = $stmt1->get_result();
                if($result1 -> num_rows > 0){
                    while($rows = $result1->fetch_assoc()){
                        ?>
                            <div id = "thesis_card">
                                <h1>Title: <?= htmlspecialchars($rows['title']) ?></h1>
                                <h3>Abstract: <br><?= htmlspecialchars($rows['abstract']) ?></h3>
                                <button class="reviewFile" data-filepath="<?= $rows['file_path'] ?>">File</button>
                                <div class="filepreview" style="margin-bottom:10px;"></div>
                                <p>Status: <?= htmlspecialchars($rows['status']) ?></p>
                                <?php 
                                    $adviser_id = $rows['adviser_id'];
                                    $stmts = $conn->prepare("SELECT name FROM user WHERE id = ?");
                                    $stmts->bind_param("i", $adviser_id);
                                    $stmts->execute();
                                    $results = $stmts->get_result()->fetch_assoc();
                                    $adviser = $results['name'];
                                    echo "<p>Adviser: $adviser</p>";
                                ?>
                                
                                <p>Year: <?= $rows['year'] ?></p>
                                <p>Submitted At: <?= $rows['submitted_at'] ?></p>
                                <?php
                                    $stmt2 = $conn->prepare("SELECT title from thesis WHERE id = ?");
                                    $stmt2->bind_param("i", $rows['id']);
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
                }
                if(!$foundThesis){
                    echo "<p>No Thesis Found with name $search</p>";
                }
            }
            
        }

        if($filter == "year"){
            $stmt = $conn->prepare("SELECT * FROM thesis WHERE year LIKE ?");
            $searchterm = "%{$search}%";
            $stmt->bind_param("s", $searchterm);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0 ){
                while($row = $result->fetch_assoc()){
                    ?>
                        <div id = "thesis_card">
                            <h1>Title: <?= htmlspecialchars($row['title']) ?></h1>
                            <h3>Abstract: <br><?= htmlspecialchars($row['abstract']) ?></h3>
                            <button class="reviewFile" data-filepath="<?= $row['file_path'] ?>">File</button>
                            <div class="filepreview" style="margin-bottom:10px;"></div>
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
                echo "<p>No Thesis found in the year $search</p>";
            }
        }

        if($filter == "adviser"){
            $stmt = $conn->prepare("SELECT id FROM user WHERE name = ?");
            $searchterm = "%{$search}%";
            $stmt->bind_param("s", $searchterm);
            $stmt->execute();
            $res = $stmt->get_result();

            while($res->fetch_assoc()){
                $foundThesis = true;
                $adviser_id = $res['id'];
                $stmt1 = $conn->prepare("SELECT * FROM thesis WHERE adviser_id LIKE ?");
                $stmt1->bind_param("i", $adviser_id);
                $stmt1->execute();
                $res1 = $stmt1->get_result();

                if($res1->num_rows > 0){
                    while($row = $res1->fetch_assoc()){
                        ?>
                            <div id = "thesis_card">
                                <h1>Title: <?= htmlspecialchars($row['title']) ?></h1>
                                <h3>Abstract: <br><?= htmlspecialchars($row['abstract']) ?></h3>
                                <button class="reviewFile" data-filepath="<?= $row['file_path'] ?>">File</button>
                                <div class="filepreview" style="margin-bottom:10px;"></div>
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
                }
                else{
                    echo "<p>No Thesis Found with an Adviser $search";
                }   
            }
        }

        if($filter == "keywords"){
            $stmt = $conn->prepare("SELECT * FROM thesis WHERE keywords LIKE ?");
            $searchterm = "%{$search}%";
            $stmt->bind_param("s", $searchterm);
            $stmt->execute();
            $res = $stmt->get_result();

            if($res->num_rows > 0){
                while($row = $res->fetch_assoc()){
                    ?>
                        <div id = "thesis_card">
                            <h1>Title: <?= htmlspecialchars($row['title']) ?></h1>
                            <h3>Abstract: <br><?= htmlspecialchars($row['abstract']) ?></h3>
                            <button class="reviewFile" data-filepath="<?= $row['file_path'] ?>">File</button>
                            <div class="filepreview" style="margin-bottom:10px;"></div>
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
                echo "<p>No Thesis Found With keywords $search";
            }
        }
    }
?>