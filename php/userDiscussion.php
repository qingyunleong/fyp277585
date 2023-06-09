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

if (isset($_POST['submit'])) {
    $postTitle = $_POST['postTitle'];
    $postDescription = $_POST['postDescription'];
    $count = 0;

    // Use prepared statements
    $sqlinsertpost = "INSERT INTO `tbl_post`(`post_title`, `post_description`, `user_id`, `count`) VALUES (?, ?, ?, ?)";
    try {
        $stmt = $conn->prepare($sqlinsertpost);
        $stmt->execute([$postTitle, $postDescription, $user_id, $count]);

        if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
            $last_id = $conn->lastInsertId();
            uploadImage($last_id);
            echo "<script>alert('Success')</script>";
            echo "<script>window.location.replace('userDiscussion.php')</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Failed')</script>";
        echo "<script>window.location.replace('userDiscussion.php')</script>";
    }
}

function uploadImage($postid)
{
    $target_dir = "../photo/post/";
    $target_file = $target_dir . $postid . ".png";
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
}

$sqlpost = "SELECT * FROM tbl_post INNER JOIN tbl_users ON tbl_post.user_id = tbl_users.user_id ORDER BY tbl_post.post_id DESC";
$stmt = $conn->prepare($sqlpost);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
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

    <!-- <link rel="stylesheet" type="text/css" href="../css/userSidebar.css"> -->
    <link rel="stylesheet" type="text/css" href="../css/userDiscussion.css">
    <link rel="stylesheet" type="text/css" href="../css/userFooter.css">

    <script src="../js/userDiscussion.js" defer></script>
    <script src="../js/script.js"></script>

    <title>Welcome to CryptoGet User Discussion Page</title>
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

    <div class="main" id="main">

        <div class="write-post-container">
            <div class="user-profile">
                <img class="image" src='../photo/registerPhoto/<?php echo $user_email ?>.png' onerror=this.onerror=null; this.src='../photo/profile_icon.png'>
            </div>
            <div class="post-input-container">
                <textarea onclick="openForm()" rows="2" placeholder="What's on your mind, <?php echo $user_name ?> ?"></textarea>
            </div>
        </div><br>

        <?php
        $i = 0;
        foreach ($rows as $post) {

            $i++;
            $postid = $post['post_id'];
            $posttitle = $post['post_title'];
            $postdescription = $post['post_description'];
            $userId = $post['user_id'];
            $userEmail = $post['user_email'];
            $postdate = $post['post_date'];
            $postdate_formatted = date('Y-m-d H:i', strtotime($postdate));
            $userName = $post['user_name'];
            $count = $post['count'];

            echo "<div class='feeds'>";
            echo "<div class='feed'>";
            echo "<div class='head'>";
            echo "<div class='user'>";
            echo "<div class='profile-phots'><img class='image' src='../photo/registerPhoto/$userEmail.png' onerror=this.onerror=null; this.src='../photo/profile_icon.png'></div>";
            echo "<div class='info'><h>$userName</h><small>$postdate_formatted</small></div>";

            echo "</div>";
            echo "</div>";

            if (file_exists('../photo/post/' . $postid . '.png')) {
                echo "<div class='feed-phots'><img class='pimg' src='../photo/post/$postid.png' onerror=this.onerror=null; this.src='../photo/profile_icon.png'></div>";
            }

            echo "<div class='title'><b>$posttitle</b></div>";
            echo "<div class='description'>$postdescription</div>";

            echo "<div class='action-buttons'><div class='inter-action-buttons'>";

            // Check if the user has already liked the post
            $sqllike = "SELECT * FROM `tbl_like` WHERE user_id = $user_id AND post_id = $postid";
            $stmt = $conn->prepare($sqllike);
            $stmt->execute();
            $number_of_result = $stmt->rowCount();

            // If the user has already liked the post, set the heart icon to red
            if ($number_of_result > 0) {
                echo "<div class='like' title='$postid'><img class='like_icon' src='../photo/red_heart.svg'><span>" . $count . "</span></div>";
            } else {
                echo "<div class='like' title='$postid'><img class='like_icon' src='../photo/heart.svg'><span>" . $count . "</span></div>";
            }

            echo "</div></div>";
            echo "<div class='text-gry comment'><a href='userPostDetail.php?postid=$postid'>view all comments</a></div>";

            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>

    <div class="container" id="myForm">
        <div class="wrapper">
            <section class="post">
                <span class="close" onclick="closeForm()">&times;</span>
                <header>Create Post</header>

                <form action="userDiscussion.php" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
                    <div class="content">
                        <img class="image" src='../photo/registerPhoto/<?php echo $user_email ?>.png' onerror=this.onerror=null; this.src='../photo/profile_icon.png'>
                        <div class="details">
                            <p><?php echo $user_name ?></p>
                        </div>
                    </div>
                    <div class="postPhoto">
                        <img class="avatar_image" src="../photo/profile_icon.png"><br>
                        <input class="inputs" type="file" onchange="previewFile()" name="fileToUpload" id="fileToUpload"><br>
                    </div>
                    <input class="title" name="postTitle" type="postTitle" id="postTitle" placeholder="Title" required>
                    <textarea name="postDescription" id="postDescription" placeholder="What's on your mind, <?php echo $user_name ?>?"></textarea>
                    <button type="submit" name="submit" id="idsumit">Post</button>
                </form>
            </section>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".like").click(function() {
                var postid = $(this).attr("title");
                var i = $(this).children(".like_icon").attr("src");
                if (i == "../photo/heart.svg") {
                    $(this).children(".like_icon").attr("src", "../photo/red_heart.svg");
                    $(this).children("span").text("liked");
                } else if (i == "../photo/red_heart.svg") {
                    $(this).children(".like_icon").attr("src", "../photo/heart.svg");
                    $(this).children("span").text("disliked");
                }
                // $.post("like.php", {data: postid,how: 'c'});
                $.post("like.php", {
                    data: postid,
                    how: 'c'
                }, function(data) {
                    alert(data);
                });

            });
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