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

if (isset($_POST['submit'])) {

    $questionid = $_POST['questionid'];
    $category_id = $_POST['category_id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $question_answer = $_POST['question_answer'];

    $sqlUpdatequestion = "UPDATE `tbl_question` SET `question`='$question', `option1`='$option1', `option2`='$option2', `option3`='$option3', `option4`='$option4', `question_answer`='$question_answer' WHERE question_id = $questionid";
    try {
        $conn->exec($sqlUpdatequestion);
        echo "<script>alert('Edit successful')</script>";
        echo "<script>window.location.replace('adminAddQuiz.php?categoryid=$category_id')</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Edit failed')</script>";
        echo "<script>window.location.replace('adminEditQuiz.php?categoryid=$category_id&questionid=$questionid')</script>";
    }
}

if (isset($_GET['questionid'])) {

    $questionid = $_GET['questionid'];

    $sqlquestion = "SELECT * FROM `tbl_question` WHERE question_id = $questionid";
    $stmt = $conn->prepare($sqlquestion);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();

    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        foreach ($rows as $questionedit) {
            $questionid = $questionedit['question_id'];
            $question_no = $questionedit['question_no'];
            $question = $questionedit['question'];
            $option1 = $questionedit['option1'];
            $option2 = $questionedit['option2'];
            $option3 = $questionedit['option3'];
            $option4 = $questionedit['option4'];
            $question_answer = $questionedit['question_answer'];
            $category_id = $questionedit['category_id'];
        }
    } else {
        echo "<script>alert('Question not found.');</script>";
        echo "<script> window.location.replace('adminAddQuiz.php?categoryid=$categoryid')</script>";
    }
} else {
    echo "<script>alert('Page Error');</script>";
    echo "<script> window.location.replace('adminQuiz.php')</script>";
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
                    <span class="text">Edit Question</span>
                </div>

                <div class="main-content">
                    <form action="adminEditQuiz.php" class="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                        <input type="hidden" name="questionid" value="<?php echo $questionid ?>">

                        <label>Question :</label>
                        <input type="text" name="question" value="<?php echo $question ?>" required><br><br>
                        <label>Option 1 :</label>
                        <input type="text" name="option1" value="<?php echo $option1 ?>" required><br><br>
                        <label>Option 2 :</label>
                        <input type="text" name="option2" value="<?php echo $option2 ?>" required><br><br>
                        <label>Option 3 :</label>
                        <input type="text" name="option3" value="<?php echo $option3 ?>" required><br><br>
                        <label>Option 4 :</label>
                        <input type="text" name="option4" value="<?php echo $option4 ?>" required><br><br>
                        <label>Answer :</label>
                        <input type="text" name="question_answer" value="<?php echo $question_answer ?>" required><br><br>
                        <input type="submit" name="submit" value="Edit Question"><br>
                    </form><br>
                </div>

            </div>

        </div>
    </section>

</body>

</html>