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

if (isset($_POST['submit'])) {

    $info_id = $_POST['info_id'];
    $categoryid = $_POST['categoryid'];
    $sub = $_POST["sub"];
    $description = $_POST["description"];

    $sqlUpdateinfo = "UPDATE `tbl_info` SET `info_sub`='$sub', `info_subDes`='$description' WHERE info_id = $info_id";
    try {
        $conn->exec($sqlUpdateinfo);
        echo "<script>alert('Add successful')</script>";
        echo "<script>window.location.replace('adminAddInformation.php?categoryid=$categoryid')</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Add failed')</script>";
        echo "<script>window.location.replace('adminEditInformation.php?categoryid=$categoryid&infoid=$infoid')</script>";
    }
}

if (isset($_GET['infoid'])) {

    $infoid = $_GET['infoid'];
    $sqlinfo = "SELECT * FROM tbl_info INNER JOIN tbl_category ON tbl_info.category_id = tbl_category.category_id WHERE tbl_info.info_id = $infoid";
    $stmt = $conn->prepare($sqlinfo);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();

    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        foreach ($rows as $info) {
            $infoid = $info['info_id'];
            $info_level = $info['info_level'];
            $info_title = $info['info_title'];
            $info_sub = $info['info_sub'];
            $info_subDes = $info['info_subDes'];

            $categoryid = $info['category_id'];
            $categorylevel = $info['category_level'];
            $categoryname = $info['category_name'];
            $categorytitle = $info['category_title'];
            $categorytitledes = $info['category_titledes'];
        }
    } else {
        echo "<script>alert('Infromation not found.');</script>";
        echo "<script> window.location.replace('adminAddInformation.php?categoryid=$categoryid')</script>";
    }
} else {
    echo "<script>alert('Page Error');</script>";
    echo "<script> window.location.replace('adminInformation.php')</script>";
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

    <link rel="stylesheet" type="text/css" href="../css/adminEditInformation.css">
    <script src="../js/adminSide.js" defer></script>

    <title>Welcome to CryptoGet Admin Page</title>
</head>

<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="../photo/logoAdmin.png">
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
                        <span class="switch"></span>
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

                <span class="level">Step <?php echo $categorylevel ?>: <?php echo $categoryname ?></span>

                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Add Sub of</span>
                    <span class="texta"><?php echo $categorytitle ?></span>
                </div>

                <div class="main-content">
                    <form action="adminEditInformation.php" class="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">

                        <input type="hidden" name="categoryid" value="<?php echo $categoryid; ?>">
                        <input type="hidden" name="info_id" value="<?php echo $infoid ?>">

                        <label>Sub</label>
                        <textarea name="sub" id="sub" required><?php echo $info_sub ?></textarea><br><br>
                        <label>Description</label>
                        <textarea name="description" id="description" required><?php echo $info_subDes ?></textarea><br><br>
                        <input type="submit" name="submit" value="Edit Sub">
                    </form>
                </div>
            </div>
        </div>

    </section>

</body>

</html>