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

if (isset($_GET['submit'])) {
    $search = $_GET['search'];
    // Use the Google Custom Search JSON API
    $api_key = "AIzaSyClKGvpv2_60Pt3SCMdg7_S3X_ZTLDtQeU"; // Replace with your API key
    $search_engine_id = "e3f13564f5de44a12"; // Replace with your search engine ID

    // Create the URL for the API request
    $url = "https://www.googleapis.com/customsearch/v1?key=" . $api_key . "&cx=" . $search_engine_id . "&q=" . urlencode($search);

    // Make the API request
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    // Parse the API response
    $data = json_decode($response, true);

    // Extract search results
    $search_results = $data['items'];
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

    <link rel="stylesheet" type="text/css" href="../css/userSearchInformation.css">
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

    <section class="home">
        <div class="container">
            <div class="box">
                <h3><b>Information Search</b></h3>
                <form>
                    <div class="input-box">
                        <i class="uil uil-search"></i>
                        <input type="search" name="search" placeholder="Search here..." />
                        <button class="button" type="submit" name="submit" value="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div class="text">
        <h2>Search Results</h2>
    </div>

    <div class="main">
        <div class="w3-grid-template">
            <?php
            if (isset($search_results)) {
                foreach ($search_results as $result) {
                    $title = $result['title'];
                    $link = $result['link'];
                    $snippet = $result['snippet'];

                    echo "<div class='box'>";
                    echo "<div class='title'><a href='$link'>$title</a></div>";
                    echo "<div class='image'>";

                    if (isset($result['pagemap']['cse_image'][0]['src'])) {
                        $imageSrc = $result['pagemap']['cse_image'][0]['src'];
                        echo "<img src='$imageSrc' alt='Result Image'>";
                    } else {
                        echo "<img src='../photo/information/11.png' alt='Result Image'>";
                    }

                    echo "</div>";
                    echo "<p>$snippet</p>";
                    echo "</div>";
                }
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