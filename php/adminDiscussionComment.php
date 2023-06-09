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
if (isset($_GET['submit'])) {
    $operation = $_GET['submit'];
    if ($operation == 'delete') {
        $postid = $_GET['postid'];

        echo $sqldeletepost = "DELETE FROM `tbl_post` WHERE post_id = '$postid'";
        $conn->exec($sqldeletepost);
        echo "<script>alert('Post deleted')</script>";
        echo "<script>window.location.replace('adminDiscussion.php')</script>";
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
        echo "<script>window.location.replace('adminDiscussionComment.php?postid=$postid')</script>";
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

$sqluser = "SELECT * FROM tbl_users";
$stmt = $conn->prepare($sqluser);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$usera = $stmt->fetchAll();
foreach ($usera as $user) {
    $userid = $user['user_id'];
    $username = $user['user_name'];
    $useremail = $user['user_email'];
    $userphone = $user['user_phone'];
    $usergender = $user['user_gender'];
    $userpassword = $user['user_password'];
    $userdatereg = $user['user_datereg'];
    $postdate_formatted = date('d-m-Y H:i', strtotime($userdatereg));
    $userrole = $user['user_role'];
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
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" type="text/css" href="../css/adminDiscussionComment.css">
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

                <div class="main">
                    <div class="feeds">
                        <div class="feed">

                            <div class="head">
                                <div class="user">
                                    <a href="adminDiscussion.php"><span class="edit fa fa-angle-left"></span></a>
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

                            <div class="delete_button"><a href="adminDiscussionComment.php?submit=delete&postid=<?php echo $postid ?>" onClick="return confirm('Confirm delete?')">Delete</a><span></a></div>

                            <hr>
                            <div class="comment">
                                <h>All Comment</h>
                            </div>

                            <?php
                            foreach ($rows as $comment) {
                                $commentId = $comment['comment_id'];
                                $commentDetail = $comment['comment_detail'];
                                $commentDate = $comment['comment_date'];
                                $commentUserid = $comment['comment_userid'];
                                $commentUsername = $comment['comment_username'];

                                $commentEmail = $comment['user_email'];
                                $postid = $comment['post_id'];

                                echo "<div class='column'>";
                                echo "<div class='userc'>";

                                echo "<div class='profile-phots'><img class='image' src='../photo/registerPhoto/$commentEmail.png' onerror=this.onerror=null; this.src='../photo/profile_icon.png'></div>";
                                echo "<div class='info'><h>$commentUsername</h><small>$commentDetail</small></div>";

                                echo "<div class='delete_buttona'><a href='adminDiscussionComment.php?postid=$postid&submit=deletecomment&commentid=$commentId' onClick=\"return confirm('Confirm delete?')\"><i class='fa fa-trash'></i></a></div>";

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

            </div>
        </div>
    </section>

</body>

</html>