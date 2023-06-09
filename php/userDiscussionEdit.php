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

    $postid = $_POST['postid'];
    $postTitle = $_POST['postTitle'];
    $postDescription = $_POST['postDescription'];

    $sqlUpdatepost = "UPDATE `tbl_post` SET `post_title`='$postTitle',`post_description`='$postDescription' WHERE post_id = $postid";
    try {
        $conn->exec($sqlUpdatepost);
        if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
            $last_id = $conn->lastInsertId();
            uploadImage($last_id);
            echo "<script>alert('Edit successful')</script>";
            echo "<script>window.location.replace('userDiscussion.php')</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Edit failed')</script>";
        echo "<script>window.location.replace('userDiscussionEdit.php?postid=$postid')</script>";
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
        }
    } else {
        echo "<script>alert('Post not found.');</script>";
        echo "<script> window.location.replace('userDiscussion.php')</script>";
    }
} else {
    echo "<script> window.location.replace('userDiscussion.php')</script>";
}

function uploadImage($postid)
{
    $target_dir = "../photo/post/";
    $target_file = $target_dir . $postid . ".png";
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
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

    <link rel="stylesheet" type="text/css" href="../css/userDiscussionEdit.css">
    <link rel="stylesheet" type="text/css" href="../css/userFooter.css">

    <script src="../js/userDiscussion.js" defer></script>
    <script src="../js/script.js" defer></script>

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

    <div class="main">
        <a href="userPostDetail.php?postid=<?php echo $postid ?>"><span class="edit fa fa-angle-left"> Back</span></a>

        <div class="container">
            <div class="wrapper">
                <section class="post">
                    <header>Edit Post</header>

                    <form action="userDiscussionEdit.php" class="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="postid" value="<?php echo $postid ?>">

                        <div class="content">
                            <img class="image" src='../photo/registerPhoto/<?php echo $user_email ?>.png' onerror=this.onerror=null; this.src='../photo/profile_icon.png'>
                            <div class="details">
                                <p><?php echo $user_name ?></p>
                            </div>
                        </div>
                        <div class="postPhoto">
                            <img class="avatar_image" src="../photo/post/<?php echo $postid ?>.png" onerror=this.onerror=null; this.src="../photo/profile_icon.png"><br>
                            <input class="inputs" type="file" onchange="previewFile()" name="fileToUpload" id="fileToUpload"><br>
                        </div>
                        <input class="title" name="postTitle" type="postTitle" id="postTitle" placeholder="Title" value="<?php echo $posttitle ?>" required>
                        <textarea name="postDescription" placeholder="What's on your mind, <?php echo $user_name ?>?" spellcheck="false" required><?php echo $postdescription ?></textarea><br>
                        <button type="submit" name="submit" id="idsumit">Post</button>
                        <br>
                    </form>
                </section>
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