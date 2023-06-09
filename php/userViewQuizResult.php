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

if (isset($_GET['score_dated'])) {
    $score_dated = $_GET['score_dated'];

    $selectAnswersQuery = "SELECT s.*, q.question_id, q.question, q.option1, q.option2, q.option3, q.option4, q.question_answer, u.user_answer 
                            FROM tbl_score s 
                            JOIN tbl_question q ON s.category_id = q.category_id 
                            JOIN tbl_useranswer u ON s.category_id = u.category_id
                            WHERE u.answer_dated = :score_dated AND q.question_id = u.question_id AND s.user_id = :user_id";
    $selectAnswersStmt = $conn->prepare($selectAnswersQuery);
    $selectAnswersStmt->bindParam(':score_dated', $score_dated, PDO::PARAM_STR);
    $selectAnswersStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $selectAnswersStmt->execute();
    $quizAnswers = $selectAnswersStmt->fetchAll(PDO::FETCH_ASSOC);

    $questions = [];
    foreach ($quizAnswers as $questionquiz) {
        $question_id = $questionquiz['question_id'];
        $question = $questionquiz['question'];
        $option1 = $questionquiz['option1'];
        $option2 = $questionquiz['option2'];
        $option3 = $questionquiz['option3'];
        $option4 = $questionquiz['option4'];
        $question_answer = $questionquiz['question_answer'];
        $userAnswer = $questionquiz['user_answer'];

        if (!isset($questions[$question_id])) {
            $questions[$question_id] = [
                'question' => $question,
                'question_answer' => $question_answer,
                'user_answer' => $userAnswer,
                'option1' => $questionquiz['option1'],
                'option2' => $questionquiz['option2'],
                'option3' => $questionquiz['option3'],
                'option4' => $questionquiz['option4']
            ];
        }
    }

    $correct_answers = 0;
    $total_questions = count($questions);

    foreach ($questions as $questionData) {
        if ($questionData['question_answer'] === $questionData['user_answer']) {
            $correct_answers++;
        }
    }
    $score = $correct_answers . " / " . $total_questions;
} else {
    echo "<script>window.location.replace('userQuiz.php')</script>";
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
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />

    <link rel="stylesheet" type="text/css" href="../css/userViewQuizResult.css">
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

        <div class="result">
            <h2>Quiz Result:</h2>
            <div class="score">
                <p><strong>Correct Answers:</strong> <?php echo $correct_answers; ?></p>
                <p><strong>Total Questions:</strong> <?php echo $total_questions; ?></p>
                <p><strong>Your Score:</strong> <?php echo $score; ?> points</p>
            </div>
        </div>

        <form method="post">
            <?php
            $i = 0;
            foreach ($questions as $question_id => $questionData) {
                $i++;
                $question = $questionData['question'];
                $question_answer = $questionData['question_answer'];
                $userAnswer = $questionData['user_answer'];

                echo "<div class='question'>";
                echo "<h2>$i. $question</h2>";

                $options = [$questionData['option1'], $questionData['option2'], $questionData['option3'], $questionData['option4']];
                foreach ($options as $option) {
                    echo "<label><input type='radio' name='answer[$question_id]' value='$option'";
                    if ($userAnswer === $question_answer && $userAnswer === $option) {
                        echo " checked><span style='color: green'>$option</span>";
                    } elseif ($userAnswer === $option) {
                        echo " checked><span style='color: red'>$option</span>";
                    } else {
                        echo ">$option";
                    }
                    echo "</label>";
                }

                if ($userAnswer !== '') {
                    if ($userAnswer === $question_answer) {
                        // echo "<p style='color: green'>Congrats!! You got a correct answer!</p>";
                    } else {
                        echo "<p style='color: red'>Correct Answer: $question_answer</p>";
                    }
                }

                echo "</div>";
            }
            ?>
        </form>
    </div>

    <script>
        let nav = document.querySelector("nav");
        window.onscroll = function() {
            if (document.documentElement.scrollTop > 20) {
                nav.classList.add("sticky");
            } else {
                nav.classList.remove("sticky");
            }
        }
    </script>

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