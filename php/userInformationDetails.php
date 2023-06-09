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
    $categoryid = $_GET['categoryid'];

    // Get category details
    $sqlCategoryDetails = "SELECT * FROM tbl_category WHERE category_id = '$categoryid'";
    $stmt = $conn->prepare($sqlCategoryDetails);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();
    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $category = $stmt->fetch();
        $categoryid = $category['category_id'];
        $category_level = $category['category_level'];
        $category_name = $category['category_name'];
        $category_step = "Step $category_level: $category_name";
        $category_title = $category['category_title'];
        $category_titledes =  nl2br($category['category_titledes']);
    } else {
        echo "<script>alert('Information not found.');</script>";
        echo "<script> window.location.replace('userInformation.php')</script>";
    }

    // Get information details
    $sqlInfoDetails = "SELECT * FROM tbl_info WHERE category_id = '$categoryid'";
    $stmt = $conn->prepare($sqlInfoDetails);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();
    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $infos = $stmt->fetchAll();
    } else {
        $infos = array();
    }
} else {
    echo "<script>alert('Page Error.');</script>";
    echo "<script> window.location.replace('userInformation.php')</script>";
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

    <link rel="stylesheet" type="text/css" href="../css/userInformationDetails.css">
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
        <section class="home-section">

            <div class="title">
                <a href="userMainpage.php">Home / </a><a href="userInformation.php">Information / </a><a href="userInformationStep.php?categorylevel=<?php echo $category_level ?>">Step <?php echo $category_level ?>: <?php echo $category_name ?> / </a><a><?php echo $category_title ?> /</a>
            </div>

            <div class="ltitle">
                <b><?php echo $category_step ?></b>
            </div>

            <div class="image">
                <img src="../photo/information/<?php echo $categoryid ?>.png" onerror=this.onerror=null;this.src="../photo/information/1.png">
            </div><br><br>

            <div class="btitle">
                <b><?php echo $category_title ?></b>
            </div><br>

            <div class="ctitle">
                <b>What you will learn?</b>
            </div>

            <div class="desc">
                <?php echo $category_titledes ?>
            </div><br><br>

            <?php
            foreach ($infos as $info) {

                $infoid = $info['info_id'];
                $info_level = $info['info_level'];
                $info_title = $info['info_title'];
                $info_sub = nl2br($info['info_sub']);
                $info_subDes = nl2br($info['info_subDes']);

                echo "<div class='title1'><b>$info_sub</b></div>";
                echo "<div class='desc1'>$info_subDes</div><br><br>";
            }
            ?>
        </section>
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