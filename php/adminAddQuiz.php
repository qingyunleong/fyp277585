<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    $user_email = $_SESSION['email'];
    $user_name = $_SESSION['name'];
} else if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Session not available. Please login');</script>";
    echo "<script>window.location.replace('login.php')</script>";
}

include_once("dbconnect.php");

if (isset($_GET['submit'])) {
    $operation = $_GET['submit'];
    if ($operation == 'delete') {
        $questionid = $_GET['questionid'];
        $categoryid = $_GET['categoryid'];

        echo $sqldeletequestion = "DELETE FROM `tbl_question` WHERE question_id = '$questionid'";
        $conn->exec($sqldeletequestion);
        echo "<script>alert('Question deleted')</script>";
        echo "<script>window.location.replace('adminAddQuiz.php?categoryid=$categoryid')</script>";
    }
}

if (isset($_POST['submit'])) {

    $categoryid = $_POST['categoryid'];

    $sqlMaxQues = "SELECT * FROM `tbl_question` WHERE `category_id` = '$categoryid'";
    $result = $conn->query($sqlMaxQues);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $question_answer = $_POST['question_answer'];
    $categoryid = $_POST['categoryid'];

    $sqlInsertQues = "INSERT INTO `tbl_question`(`question`, `option1`, `option2`, `option3`, `option4`, `question_answer`, `category_id`) VALUES ('$question','$option1','$option2','$option3','$option4','$question_answer','$categoryid')";

    try {
        $conn->exec($sqlInsertQues);
        echo "<script>alert('Success')</script>";
        echo "<script>window.location.replace('adminAddQuiz.php?categoryid=$categoryid')</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Failed')</script>";
        echo "<script>window.location.replace('adminAddQuiz.php')</script>";
    }
}

$rows = null;

if (isset($_GET['categoryid'])) {
    $categoryid = $_GET['categoryid'];

    $sqlquestion = "SELECT * FROM `tbl_question` INNER JOIN tbl_category ON tbl_question.category_id = tbl_category.category_id WHERE tbl_question.category_id = '$categoryid'";
    $stmt = $conn->prepare($sqlquestion);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();

    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Raleway'>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" type="text/css" href="../css/adminAddQuiz.css">
    <script src="../js/adminSide.js" defer></script>

    <title>Welcome to CryptoGet Admin Page</title>
</head>

<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="../photo/logoAdmin.png" alt="">
            </div>

            <span class="logo_name">CryptoGet</span>
        </div>

        <div class="menu-items">
        <ul class="nav-links">
                <li><a href="adminDashboard.php">
                        <i class="uil uil-estate"></i>
                        <span class="link-name">Dahsboard</span>
                    </a></li>
                <li><a href="adminManageUser.php">
                        <i class="uil uil-user-circle"></i>
                        <span class="link-name">User</span>
                    </a></li>
                <li><a href="adminInformation.php">
                        <i class="uil uil-files-landscapes"></i>
                        <span class="link-name">Information</span>
                    </a></li>
                <li><a href="adminDiscussion.php">
                        <i class="uil uil-comments"></i>
                        <span class="link-name">Discussion</span>
                    </a></li>
                <li><a href="adminQuiz.php">
                        <i class="uil uil-edit"></i>
                        <span class="link-name">Quiz</span>
                    </a></li>
            </ul>

            <ul class="logout-mode">
                <li><a href="logout.php">
                        <i class="uil uil-signout"></i>
                        <span class="link-name">Logout</span>
                    </a></li>

                <li class="mode">
                    <div class="mode-toggle">
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">

        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
        </div>

        <div class="dash-content">

            <div class="activity">
                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Add Quiz</span>
                </div>

                <div class="main-content">
                    <form action="adminAddQuiz.php" class="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="categoryid" value="<?php echo $categoryid; ?>">

                        <label>Question :</label>
                        <input type="text" name="question" required><br><br>
                        <label>Option 1 :</label>
                        <input type="text" name="option1" required><br><br>
                        <label>Option 2 :</label>
                        <input type="text" name="option2" required><br><br>
                        <label>Option 3 :</label>
                        <input type="text" name="option3" required><br><br>
                        <label>Option 4 :</label>
                        <input type="text" name="option4" required><br><br>
                        <label>Answer :</label>
                        <input type="text" name="question_answer" required><br><br>
                        <input type="submit" name="submit" value="Add Question">
                    </form>
                </div>

                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">View Question</span>
                </div>

                <?php if ($rows == null) { ?>
                    <p>No question added yet.</p>
                <?php } else { ?>
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Question</th>
                                <th>Option 1</th>
                                <th>Option 2</th>
                                <th>Option 3</th>
                                <th>Option 4</th>
                                <th>Answer</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <?php
                        $i = 0;
                        foreach ($rows as $questionadd) {

                            $i++;
                            $questionid = $questionadd['question_id'];
                            $question = $questionadd['question'];
                            $option1 = $questionadd['option1'];
                            $option2 = $questionadd['option2'];
                            $option3 = $questionadd['option3'];
                            $option4 = $questionadd['option4'];
                            $question_answer = $questionadd['question_answer'];
                            $categoryid = $questionadd['category_id'];

                            echo "<tr><td>$i</td><td>$question</td><td>$option1</td><td>$option2</td><td>$option3</td><td>$option4</td><td>$question_answer</td>
                        <td><a href='adminEditQuiz.php?categoryid=$categoryid&questionid=$questionid'>Edit</a> | <a href='adminAddQuiz.php?submit=delete&categoryid=$categoryid&questionid=$questionid' onClick=\"return confirm('Confirm delete?')\">Delete</a></td></tr>";
                        }
                        ?>


                    </table>
                <?php } ?>
            </div>

        </div>
    </section>

</body>

</html>