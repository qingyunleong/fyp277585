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
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" type="text/css" href="../css/userMainpage.css">
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

    <section class="home"></section>

    <div class="content">

        <div class="first">
            <div class="photo">
                <div class="main">
                    <div class="a">Learn Everything About</div>
                    <div class="b"><b>Cryptocurrency & Trading</b></div>
                    <div class="c">We give you the power to level up your crypto knowledge & trade like a pro. From beginner to advanced crypto trading guides and courses, we've got you covered daily and when it matters the most.</div>
                </div>
            </div>
        </div>

        <div class="second">
            <div class="title">
                <div class="fcontent">
                    <b>Explore The Crypto Space Confidently</b>
                    <p>With Expert Recommendations</p>
                </div>

                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-book-open"></i>
                        <a href="userInformation.php"><span class="text">Education <i class="right uil-arrow-right"></i></span></a>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-chat-info"></i>
                        <a href="userDiscussion.php"><span class="text">Discussion <i class="right uil-arrow-right"></i></span></a>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-pen"></i>
                        <a href="userQuiz.php"><span class="text">Quiz <i class="right uil-arrow-right"></i></span></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="third">
            <div class="third_main">
                <div class="third_title">With our Cryptocurrency courses, you can discover:</div>

                <div class="third_content">
                    <div class="third_detail">
                        <i class="uil uil-pen">The historical definition and characteristics of money</i>
                        <i class="uil uil-pen">The present landscape of how value is exchanged</i>
                        <i class="uil uil-pen">Blockchain technology and how it’s used</i>
                        <i class="uil uil-pen">How Bitcoin is designed</i>
                        <i class="uil uil-pen">The Ethereum network and how it works with Smart Contracts and Decentralized Applications (Dapps)</i>
                    </div>
                </div>
            </div>
        </div>

        <div class="forth">
            <div class="forth_title">
                <div class="ftitle">
                    <b>CryptoGet features</b>
                </div>
                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-database"> <b>Continuous Learning</b></i>
                        <p>Once you’ve worked your way through our structured content you can keep learning through the CryptoGet blog with features from the wider crypto ecosystem and broaden your knowledge with our recommended list of best crypto books and podcasts.</p>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-subject"> <b>Helpful Glossary</b></i>
                        <p>Crypto has a language all of its own, so CryptoGet features an exhaustive crypto Glossary with hundreds of definitions from Hodling to Shitcoin, ensuring you understand the language of crypto.</p>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-skip-forward-alt"> <b>Built For Beginners</b></i>
                        <p>We’ve conducted face-to-face interviews with people who are new to cryptocurrency to understand what they find confusing and how they want to learn. We used this insight to create an online survey asking over a thousand people worldwide what they want to learn about crypto, as well as what most confuses them.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="fifth">
            <div class="fifth_main">
                <div class="fifth_title">
                    <p><b>CryptoGet's Mission</b></p>
                </div>

                <div class="fifth_content">
                    <p>CryptoGet is a free education platform designed to help users easily learn about cryptocurrency, with simple, relevant and engaging content.</p>
                    <p>We appreciate that for beginners, learning about cryptocurrency is both complex and unfamiliar, so we've designed the site to be accessible to newcomers for a full crypto knowledge base divided into five categories.</p>
                    <p>We believe in the potential of crypto, but for anyone new to the subject, just understanding why it has value is a challenge. To help those not yet convinced about crypto, our Why Crypto section offers easily accessible data to make a case for crypto.</p>
                    <p>CryptoGet also acknowledges that cryptocurrency has its critics, so our TLDR section is designed to respond directly to the most common criticisms of Bitcoin (and other cryptocurrencies) as well as popular myths and misconceptions. These are purposely short answers to the big crypto questions.</p>
                    <p>We regularly add new content to the CryptoGet blog to expose new perspectives on cryptocurrency, bust myths, provide suggestions to help you grow your crypto stack as well creative ways to just keep learning.</p>
                    <p>If don't understand the unique language of the subject, our crypto glossary will help, while you can extend your learning with a guide to popular crypto books and podcasts.</p>
                    <p>CryptoGet - Crypto made easy.</p>
                </div>
            </div>
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