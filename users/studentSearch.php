    <?php
        include("../config.php");

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $search = trim($_POST['search']);
            $department = $_POST['department'];
            $filter = $_POST['filter'];
            $role = "Student";

            $deptstmt = $conn->prepare("SELECT dept_id FROM department WHERE name = ?");
            $deptstmt->bind_param("s", $department);
            $deptstmt->execute();
            $deptres = $deptstmt->get_result()->fetch_assoc();
            $dept_id = $deptres['dept_id'];

            if($filter == "name"){
                $namestmt = $conn->prepare("SELECT * FROM user WHERE department_id = ? AND role = ? AND name LIKE ?");
                $searchterm = "%{$search}%";
                $namestmt->bind_param("iss", $dept_id, $role, $searchterm);
                $namestmt->execute();
                $nameres = $namestmt->get_result();
                if($nameres->num_rows > 0){
                    while($row = $nameres->fetch_assoc()){
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
                    echo "<p>No Student Found with a Name: $search in the Department: $department</p>";
                }
            }

            if($filter == "email"){
                $emailstmt = $conn->prepare("SELECT * FROM user WHERE department_id = ? AND role = ? AND email LIKE ?");
                $searchterm = "%{$search}%";
                $emailstmt->bind_param("iss", $dept_id, $role,$searchterm);
                $emailstmt->execute();
                $emailres = $emailstmt->get_result();
                if($emailres->num_rows > 0){
                    while($row = $emailres->fetch_assoc()){
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
                    echo "<p>No Student Found with a Email: $search in the Department: $department</p>";
                }
            }

            if($filter == "Username"){
                $usernamestmt = $conn->prepare("SELECT * FROM user WHERE department_id = ? AND role = ? AND username LIKE ?");
                $searchterm = "%{$search}%";
                $usernamestmt->bind_param("iss", $dept_id, $role, $searchterm);
                $usernamestmt->execute();
                $usernameres = $usernamestmt->get_result();
                if($usernameres->num_rows > 0){
                    while($row = $usernameres->fetch_assoc()){
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
                    echo "<p>No Student Found with a Username: $search in the Department: $department</p>";
                }
            }
        }   
    ?>