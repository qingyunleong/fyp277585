<?php

session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['id'];
    $user_email = $_SESSION['email'];
    $user_name = $_SESSION['name'];
} else if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Session not available. Please login');</script>";
    echo "<script> window.location.replace('login.php')</script>";
}

include_once("dbconnect.php");

if (isset($_GET['categoryid'])) {
    $category_id = $_GET['categoryid'];

    $sqlcategoryQuestion = "SELECT * FROM `tbl_question` WHERE category_id = '$category_id'";
    $stmt = $conn->prepare($sqlcategoryQuestion);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();

    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        foreach ($rows as $quiz) {
            $question_id = $quiz['question_id'];
            $question = $quiz['question'];
            $option1 = $quiz['option1'];
            $option2 = $quiz['option2'];
            $option3 = $quiz['option3'];
            $option4 = $quiz['option4'];
            $question_answer = $quiz['question_answer'];
            $category_id = $quiz['category_id'];
        }
    } else {
        echo "<script>alert('Post not found.');</script>";
        echo "<script> window.location.replace('userQuiz.php')</script>";
    }
} else {
    echo "<script>alert('Page Error. post');</script>";
    // echo "<script> window.location.replace('userDiscussion.php')</script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../css/userQuizQuestion.css">
    <link rel="stylesheet" type="text/css" href="../css/userFooter.css">

    <title>Welcome to CryptoGet Admin Page</title>

</head>

<body>
    <nav>
        <div class="nav-content">
            <div class="logo">
                <a href="userInformation.php">CryptoGet</a>
            </div>
            <ul class="nav-links">
                <li><a href="userSearchInformation.php"><i class="fa fa-search"></i></a></li>
                <li><a href="userMainpage.php">Home</a></li>
                <li><a href="userInformation.php">Learn</a></li>
                <li><a href="userDiscussion.php">Discussion</a></li>
                <li><a href="userQuiz.php">Quiz</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="userProfile.php"><img class="image" src='../photo/registerPhoto/<?php echo $user_email ?>.png' onerror=this.onerror=null; this.src='../photo/profile_icon.png'></a></li>
            </ul>
        </div>
    </nav>

    <div class="main">
        <h1>Quiz</h1>

        <form action="userSubmitQuiz.php?category_id=<?php echo $category_id ?>" method="POST">
            <?php
            $i = 0;
            foreach ($rows as $questionquiz) {
                $i++;
                $question_id = $questionquiz['question_id'];
                $question = $questionquiz['question'];
                $option1 = $questionquiz['option1'];
                $option2 = $questionquiz['option2'];
                $option3 = $questionquiz['option3'];
                $option4 = $questionquiz['option4'];
                $question_answer = $questionquiz['question_answer'];
                $category_id = $questionquiz['category_id'];

                echo "<div class='question'>";
                echo "<input type='hidden' name='question_id[]' value='$question_id'>";
                echo "<input type='hidden' name='category_id[$question_id]' value='$category_id'>";
                echo "<h2>$i. $question</h2>";
                echo "<label><input type='radio' name='answer[$question_id]' value='$option1'>$option1</label>";
                echo "<label><input type='radio' name='answer[$question_id]' value='$option2'>$option2</label>";
                echo "<label><input type='radio' name='answer[$question_id]' value='$option3'>$option3</label>";
                echo "<label><input type='radio' name='answer[$question_id]' value='$option4'>$option4</label>";
                echo "</div>";
            }
            ?>
            <br>
            <button type="submit">Submit</button>
            <!-- <a href="userSubmitQuiz.php?categoryid=<?php echo $category_id ?>&questionid=<?php echo $question_id ?>">Submit</a> -->
        </form>
    </div>
</body>

<footer>
    <div class="content">
        <div class="left box">

            <div class="upper">
                <div class="topic">About us</div>
                <p>CryptoGet is a free education platform designed to help users easily learn about cryptocurrency, with simple, relevant and engaging content.</p>
            </div>

            <div class="lower">
                <div class="topic">Contact us</div>
                <div class="phone">
                    <a href="#"><i class="fas fa-phone-volume"></i>+60197108853</a>
                </div>
                <div class="email">
                    <a href="#"><i class="fas fa-envelope"></i>leongqingyun@gmail.com</a>
                </div>
            </div>

        </div>

        <div class="middle box">
            <div class="topic">Our Services</div>
            <div><a href="userMainpage.php">Home</a></div>
            <div><a href="userInformation.php">Information</a></div>
            <div><a href="userDiscussion.php">Discussion</a></div>
            <div><a href="userQuiz.php">Quiz</a></div>
        </div>

    </div>
    <div class="bottom">
        <p>Copyright CryptoGet&copy; <span>|</span> <a href="privacyPolicy.php">Privacy Policy</a> <span>|</span> <a href="termAndCondition.php">Terms and Conditions</a> </p>
    </div>
</footer>

</html>