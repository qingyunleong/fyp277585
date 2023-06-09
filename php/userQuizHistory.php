<?php
//userQuizHistory.php
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

$rows = array(); // Initialize $rows as an empty array

if (isset($_GET['user_id'])) {

    $user_id = $_GET['user_id'];

    $selectHistoryQuery = "SELECT * FROM tbl_score WHERE user_id = $user_id ORDER BY score_dated DESC";
    $stmt = $conn->prepare($selectHistoryQuery);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();

    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
    } else {
        // echo "<script>alert('Post not found.');</script>";
        // echo "<script> window.location.replace('userQuiz.php')</script>";
        $noRecordMessage = "No records yet.";
    }
} else {
    echo "<script> window.location.replace('userQuiz.php')</script>";
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

    <link rel="stylesheet" type="text/css" href="../css/userQuizHistory.css">
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
        <h1>User Quiz History</h1>

        <?php if (isset($noRecordMessage)) : ?>
            <p><?php echo $noRecordMessage; ?></p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Score</th>
                        <th>Total Questions</th>
                        <th>Date</th>
                        <th>More</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rows as $history) {
                        $category_id = $history['category_id'];
                        $user_id = $history['user_id'];
                        $score = $history['score'];
                        $total_score = $history['total_score'];
                        $score_dated = $history['score_dated'];
                        //$scoredate_formatted = date('Y-m-dH:i', strtotime($score_dated));

                        // Retrieve the category name based on the category_id
                        $selectCategoryQuery = "SELECT category_name FROM tbl_category WHERE category_id = :category_id";
                        $selectCategoryStmt = $conn->prepare($selectCategoryQuery);
                        $selectCategoryStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
                        $selectCategoryStmt->execute();
                        $category_name = $selectCategoryStmt->fetchColumn();
                    ?>

                        <tr>
                            <td><?php echo $category_name; ?></td>
                            <td><?php echo $score; ?></td>
                            <td><?php echo $total_score; ?></td>
                            <td><?php echo $score_dated; ?></td>
                            <td>
                                <a href="userViewQuizResult.php?score_dated=<?php echo ($score_dated); ?>"><button class="button-80" role="button">Check</button></a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
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