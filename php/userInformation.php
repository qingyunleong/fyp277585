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

$sqlinfo = "SELECT * FROM tbl_category ORDER BY category_level";
$stmt = $conn->prepare($sqlinfo);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

function truncate($string, $length, $dots = "...")
{
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

    <link rel="stylesheet" type="text/css" href="../css/userInformationPage.css">
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

    <img class="photo" src="../photo/backgroundInformation.png">

    <div class="main"><br><br>

        <section class="swiper mySwiper">

            <div class="swiper-wrapper">

                <?php
                $previous_step = null;
                foreach ($rows as $info) {
                    $categoryid = $info['category_id'];
                    $categorylevel = $info['category_level'];
                    $categoryname = $info['category_name'];
                    $categorystep = "Step $categorylevel: $categoryname";
                    $categorytitle = $info['category_title'];
                    $categorytitledes = $info['category_titledes'];

                    if ($previous_step !== $categorystep) {
                        echo "<div class='card swiper-slide'>";
                        echo "<div class='card_image'>";
                        echo " <img src='../photo/information/step$categorylevel.png' alt='card image' onerror=this.onerror=null;this.src='../photo/information/1.png'>";
                        echo "<div class='card_content'>";
                        echo "<span class='card_title'>Step $categorylevel</span>";
                        echo "<span class='card_text'>$categoryname</span>";
                        echo "<span class='card_btn'><button><a href='userInformationStep.php?categorylevel=$categorylevel'>View More</a></button></span>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }

                    $previous_step = $categorystep;
                }
                ?>

            </div>
        </section>

        <h1>Learn About Crytocurrency</h1>

        <div class="w3-grid-template">
            <?php
            $i = 0;
            foreach ($rows as $info) {
                $i++;
                $categoryid = $info['category_id'];
                $category_level = $info['category_level'];
                $category_name = $info['category_name'];
                $category_step = "Step $category_level: $category_name";
                $category_title = $info['category_title'];
                $category_titledes = $info['category_titledes'];

                echo "<div class='box'>";
                echo "<div class='image'><img src='../photo/information/$categoryid.png' onerror=this.onerror=null;this.src='../photo/information/1.png'></div>";
                echo "<div class='title'>$category_title</div>";
                echo "<div class='level'>$category_step</div>";
                echo "<p>$category_titledes</p>";
                echo "<div class='btns'><button><a href='userInformationDetails.php?categoryid=$categoryid'>Read More</a></button></div>";
                echo "</div>";
            }
            ?>
        </div>
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

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 300,
                modifier: 1,
                slideShadows: false,
            },
            pagination: {
                el: ".swiper-pagination",
            },
        });
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