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
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" type="text/css" href="../css/adminDiscussion.css">
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
                    <span class="text">Discussion</span>
                </div>

                <div class="main" id="main">

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
                        $sqllike = "SELECT * FROM `tbl_like` WHERE post_id = $postid";
                        $stmt = $conn->prepare($sqllike);
                        $stmt->execute();
                        $number_of_result = $stmt->rowCount();

                        echo "<div class='like' title='$postid'><img class='like_icon' src='../photo/heart.svg'><span>" . $count . "</span></div>";

                        echo "</div></div>";
                        echo "<div class='text-gry comment'><a href='adminDiscussionComment.php?postid=$postid'>view all comments</a></div>";

                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>

            </div>
        </div>
    </section>

</body>

</html>