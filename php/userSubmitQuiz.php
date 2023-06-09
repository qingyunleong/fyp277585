<?php
// userSubmitQuiz.php
session_start();

if (isset($_SESSION['user_id'])) {
  $userid = $_SESSION['id'];
  $user_email = $_SESSION['email'];
  $user_name = $_SESSION['name'];
} else if (!isset($_SESSION['user_id'])) {
  echo "<script>alert('Session not available. Please login');</script>";
  echo "<script>window.location.replace('login.php')</script>";
}

include_once("dbconnect.php");

$category_id = $_GET['category_id'];
$selectQuestionsQuery = "SELECT * FROM tbl_question WHERE category_id = :category_id";
$selectQuestionsStmt = $conn->prepare($selectQuestionsQuery);
$selectQuestionsStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
$selectQuestionsStmt->execute();
$rows = $selectQuestionsStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $insertAnswerQuery = "INSERT INTO tbl_useranswer (user_id, category_id, question_id, user_answer, answer_dated) 
                        VALUES (:user_id, :category_id, :question_id, :user_answer, :answer_dated)";
  $insertAnswerStmt = $conn->prepare($insertAnswerQuery);
  $insertAnswerStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT); // Add this lin

  $answer_dated = date('Y-m-d H:i:s');

  $total_questions = 0;
  $correct_answers = 0;

  foreach ($_POST['question_id'] as $index => $question_id) {
    $user_answer = $_POST['answer'][$question_id];
    $category_id = $_POST['category_id'][$question_id];

    $insertAnswerStmt->bindParam(':user_id', $userid, PDO::PARAM_INT);
    $insertAnswerStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $insertAnswerStmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    $insertAnswerStmt->bindParam(':user_answer', $user_answer, PDO::PARAM_STR);
    $insertAnswerStmt->bindParam(':answer_dated', $answer_dated, PDO::PARAM_STR);
    $insertAnswerStmt->execute();

    $total_questions++;

    $selectCorrectAnswerQuery = "SELECT question_answer FROM tbl_question WHERE question_id = :question_id";
    $selectCorrectAnswerStmt = $conn->prepare($selectCorrectAnswerQuery);
    $selectCorrectAnswerStmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    $selectCorrectAnswerStmt->execute();
    $correct_answer = $selectCorrectAnswerStmt->fetchColumn();

    if ($user_answer === $correct_answer) {
      $correct_answers++;
    }
  }

  $score = $correct_answers . " / " . $total_questions;

  $insertScoreQuery = "INSERT INTO tbl_score (category_id, user_id, score, total_score, score_dated)
                       VALUES (:category_id, :user_id, :score, :total_score, :score_dated)";
  $insertScoreStmt = $conn->prepare($insertScoreQuery);
  $insertScoreStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
  $insertScoreStmt->bindParam(':user_id', $userid, PDO::PARAM_INT);
  $insertScoreStmt->bindParam(':score', $score, PDO::PARAM_STR);
  $insertScoreStmt->bindParam(':total_score', $total_questions, PDO::PARAM_INT);
  $insertScoreStmt->bindParam(':score_dated', $answer_dated, PDO::PARAM_STR);
  $insertScoreStmt->execute();
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

  <link rel="stylesheet" type="text/css" href="../css/userSubmitQuiz.css">
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

    <h1>Question And Answer</h1>

    <form>
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

        // Check if the user has answered this question
        if (isset($_POST['answer'][$question_id])) {
          $user_answer = $_POST['answer'][$question_id];

          echo "<label><input type='radio' name='answer[$question_id]' value='$option1'";
          if ($user_answer === $question_answer && $user_answer === $option1) {
            echo " checked><span style='color: green'>$option1</span>";
          } elseif ($user_answer === $option1) {
            echo " checked><span style='color: red'>$option1</span>";
          } else {
            echo ">$option1";
          }
          echo "</label>";

          echo "<label><input type='radio' name='answer[$question_id]' value='$option2'";
          if ($user_answer === $question_answer && $user_answer === $option2) {
            echo " checked><span style='color: green'>$option2</span>";
          } elseif ($user_answer === $option2) {
            echo " checked><span style='color: red'>$option2</span>";
          } else {
            echo ">$option2";
          }
          echo "</label>";

          echo "<label><input type='radio' name='answer[$question_id]' value='$option3'";
          if ($user_answer === $question_answer && $user_answer === $option3) {
            echo " checked><span style='color: green'>$option3</span>";
          } elseif ($user_answer === $option3) {
            echo " checked><span style='color: red'>$option3</span>";
          } else {
            echo ">$option3";
          }
          echo "</label>";

          echo "<label><input type='radio' name='answer[$question_id]' value='$option4'";
          if ($user_answer === $question_answer && $user_answer === $option4) {
            echo " checked><span style='color: green'>$option4</span>";
          } elseif ($user_answer === $option4) {
            echo " checked><span style='color: red'>$option4</span>";
          } else {
            echo ">$option4";
          }
          echo "</label>";

          // Check if the user's answer is correct
          if ($user_answer === $question_answer) {
            // echo "<p style='color: green'>Congrats!! You got a correct answer!</p>";
          } else {
            echo "<p style='color: red'>Correct Answer: $question_answer</p>";
          }
        } else {
          echo "<label><input type='radio' name='answer[$question_id]' value='$option1'>$option1</label>";
          echo "<label><input type='radio' name='answer[$question_id]' value='$option2'>$option2</label>";
          echo "<label><input type='radio' name='answer[$question_id]' value='$option3'>$option3</label>";
          echo "<label><input type='radio' name='answer[$question_id]' value='$option4'>$option4</label>";
        }

        echo "</div>";
      }
      ?>
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