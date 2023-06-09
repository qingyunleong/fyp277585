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
    $operation = $_GET['submit'];
    if ($operation == 'delete') {
        $postid = $_GET['postid'];

        echo $sqldeletepost = "DELETE FROM `tbl_post` WHERE post_id = '$postid'";
        $conn->exec($sqldeletepost);
        echo "<script>alert('Post deleted')</script>";
        echo "<script>window.location.replace('userDiscussion.php')</script>";
    }
}

if (isset($_GET['submit'])) {
    $operation = $_GET['submit'];
    if ($operation == 'deletecomment') {

        $postid = $_GET['postid'];
        $commentid = $_GET['commentid'];

        echo $sqldeletecomment = "DELETE FROM `tbl_comment` WHERE comment_id = '$commentid'";
        $conn->exec($sqldeletecomment);
        echo "<script>alert('Comment deleted')</script>";
        echo "<script>window.location.replace('userPostDetail.php?postid=$postid')</script>";
    }
}

if (isset($_GET['postid'])) {

    $postid = $_GET['postid'];
    $sqlpost = "SELECT * FROM tbl_post INNER JOIN tbl_users ON tbl_post.user_id = tbl_users.user_id WHERE tbl_post.post_id = '$postid'";
    $stmt = $conn->prepare($sqlpost);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();

    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        foreach ($rows as $post) {
            $postid = $post['post_id'];
            $posttitle = $post['post_title'];
            $postdescription = $post['post_description'];
            $postdate = $post['post_date'];

            $userId = $post['user_id'];
            $userName = $post['user_name'];
            $userEmail = $post['user_email'];
        }

        $sqlcomment = "SELECT c.*, u.user_name,u.user_email
               FROM tbl_comment AS c
               INNER JOIN tbl_users AS u ON c.comment_userid = u.user_id
               WHERE c.post_id = '$postid'";
        $stmt = $conn->prepare($sqlcomment);
        $stmt->execute();
        $number_of_result = $stmt->rowCount();

        if ($number_of_result > 0) {
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
        } else {
            $rows = array();
        }
    } else {
        echo "<script>alert('Post not found.');</script>";
        echo "<script> window.location.replace('userDiscussion.php')</script>";
    }
} else {
    echo "<script>alert('Page Error');</script>";
    echo "<script> window.location.replace('userDiscussion.php')</script>";
}

if (isset($_GET['postid'])) {

    $postid = $_GET['postid'];

    if (isset($_POST['submit'])) {

        $commentDetail = $_POST['commentDetail'];
        $sqlinsertcomment = "INSERT INTO `tbl_comment`(`comment_detail`, `comment_userid`, `comment_username`, `post_id`) VALUES (:commentDetail, :user_id, :user_name, :postid)";
        $stmt = $conn->prepare($sqlinsertcomment);
        $stmt->bindParam(':commentDetail', $commentDetail);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':postid', $postid);

        try {
            $stmt->execute();
            echo "<script>alert('Success')</script>";
            echo "<script>window.location.replace('userPostDetail.php?postid=$postid')</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Failed')</script>";
            echo "<script>window.location.replace('userDiscussion.php')</script>";
        }
    }
} else {
    echo "<script>alert('Page Error');</script>";
    echo "<script> window.location.replace('userDiscussion.php')</script>";
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
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />

    <link rel="stylesheet" type="text/css" href="../css/userPostDetail.css">
    <link rel="stylesheet" type="text/css" href="../css/userFooter.css">

    <script src="../js/userDiscussion.js" defer></script>

    <title>Welcome to CryptoGet User Discussion Page</title>

    <style>
        .feeds .feed .delete_button {
            font-size: 14px;
            font-weight: normal;
            margin-right: 2.5rem;
            text-align: right;
            margin-bottom: 6px;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            color: #a6a7ab;
        }

        .feeds .feed .delete_button a {
            color: #a6a7ab;
        }
    </style>
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
        <div class="feeds">
            <div class="feed">

                <div class="head">
                    <div class="user">
                        <a href="userDiscussion.php"><span class="edit fa fa-angle-left"></span></a>
                        <div class="profile-phots"><img class="image" src="../photo/registerPhoto/<?php echo $userEmail ?>.png" onerror=this.onerror=null; this.src="../photo/profile_icon.png"></div>
                        <div class="info">
                            <h><?php echo $userName ?></h>
                        </div>
                    </div>
                </div>

                <?php
                $has_photo = file_exists('../photo/post/' . $postid . '.png');
                ?>

                <div class="feed-phots<?php if (!$has_photo) echo ' hidden'; ?>">
                    <?php if ($has_photo) : ?>
                        <img class="pimg" src="../photo/post/<?php echo $postid ?>.png" onerror="this.onerror=null; this.src='../photo/profile_icon.png'">
                    <?php endif; ?>
                </div>

                <br>
                <div class="title"><b><?php echo $posttitle ?></b></div>
                <div class="description"><?php echo $postdescription ?></div>

                <?php
                $i = 0;
                $showDeleteButton = isset($_SESSION['id']) && $userId == $_SESSION['id'];

                if ($showDeleteButton) {
                    echo "<div class='delete_button'><a href='userPostDetail.php?submit=delete&postid=$postid' onClick=\"return confirm('Confirm delete?')\">Delete</a><span> | </span><a href='userDiscussionEdit.php?postid=$postid'>Edit</a></div>";
                }
                ?>

                <hr>
                <div class="comment">
                    <h>All Comment</h>
                </div>

                <form action="userPostDetail.php?postid=<?php echo $postid ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
                    <div class="headComment">
                        <div class="userComment">
                            <div class="profile-phots"><img class="image" src="../photo/registerPhoto/<?php echo $user_email ?>.png" onerror=this.onerror=null; this.src="../photo/profile_icon.png"></div>
                            <textarea name="commentDetail" placeholder="What's on your mind, <?php echo $user_name ?>?" required></textarea>
                            <button class="button-39 uil-play" type="submit" name="submit" id="idsumit"></button>
                        </div>
                    </div>
                </form>

                <?php
                foreach ($rows as $comment) {
                    $commentId = $comment['comment_id'];
                    $commentDetail = $comment['comment_detail'];
                    $commentDate = $comment['comment_date'];
                    $commentUserid = $comment['comment_userid'];
                    $commentUsername = $comment['comment_username'];

                    $commentEmail = $comment['user_email'];

                    $postid = $comment['post_id'];

                    $deleteButton = isset($_SESSION['id']) && $commentUserid == $_SESSION['id'];

                    echo "<div class='column'>";
                    echo "<div class='userc'>";

                    echo "<div class='profile-phots'><img class='image' src='../photo/registerPhoto/$commentEmail.png' onerror=this.onerror=null; this.src='../photo/profile_icon.png'></div>";
                    echo "<div class='info'><h>$commentUsername</h><small>$commentDetail</small></div>";

                    if ($deleteButton) {
                        echo "<div class='delete_buttona'><a href='userPostDetail.php?postid=$postid&submit=deletecomment&commentid=$commentId' onClick=\"return confirm('Confirm delete?')\"><i class='fa fa-trash'></i></a></div>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
                ?>
                <hr>
                <div class="end">
                    <p>-- The End --</p>
                </div>

            </div>
        </div>
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