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

if (isset($_REQUEST['submit'])) {
    include_once("dbconnect.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $infoTitle = $_POST['infoTitle'];
        $infoLevel = $_POST['infoLevel']; // Get the selected option value from the form
        $infoTitleDes = $_POST['infoTitleDes'];
        $categoryid = $_POST["categoryid"];

        if ($infoLevel != 'Select') {
            $infoLevelParts = explode(': ', $infoLevel); // Split the string into two parts using the colon and space as a delimiter
            $levelNumber = (int) substr($infoLevelParts[0], -1); // Extract the number from the first part and convert it to an integer
            $levelName = $infoLevelParts[1]; // Get the second part as the level name
        }
        $sqlInsertInfo = "INSERT INTO tbl_category (category_level, category_name, category_title, category_titledes) VALUES (?, ?, ?, ?)";

        try {
            $stmt = $conn->prepare($sqlInsertInfo);
            $stmt->execute([$levelNumber, $levelName, $infoTitle, $infoTitleDes]);
            echo "<script>alert('Success')</script>";
            echo "<script>window.location.replace('adminInformation.php')</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Failed: " . $e->getMessage() . "')</script>";
            echo "<script>window.location.replace('adminInformation.php')</script>";
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $operation = $_GET['submit'];
        if ($operation == 'delete') {
            $categoryid = $_GET['categoryid'];
            $sqldeleteinfo = "DELETE FROM `tbl_category` WHERE category_id = '$categoryid'";
            $conn->exec($sqldeleteinfo);
            echo "<script>alert('Information deleted')</script>";
            echo "<script>window.location.replace('adminInformation.php')</script>";
        }
    }
}

$sqlcategoty = "SELECT * FROM tbl_category ORDER BY category_level";
$stmt = $conn->prepare($sqlcategoty);
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
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" type="text/css" href="../css/adminInformation.css">
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

    <section class="dashboard" id="main">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
        </div>

        <div class="dash-content">
            <div class="activity">

                <button class="button-34" role="button" onclick="openForm()">Add Information Title</button>

                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Information</span>
                </div>

                <?php
                $i = 0;

                echo "<table class='content-table'><thead><tr><th>No</th><th>Information Level</th><th>Information Title</th><th>Description</th><th>Operations</th></tr></thead>";

                foreach ($rows as $category) {
                    $i++;
                    $categoryid = $category['category_id'];
                    $categorylevel = $category['category_level'];
                    $categoryname = $category['category_name'];
                    $categorytitle = $category['category_title'];
                    $categorydescription = truncate($category['category_titledes'], 100);


                    echo "<tbody><tr><td>$i</td><td>Step $categorylevel : $categoryname</td><td>$categorytitle</td><td>$categorydescription</td>
                    <td><button class='button-39' role='button'><a href='adminAddInformation.php?categoryid=$categoryid' class='uil-pen'></a></button>
                    <button class='button-39' role='button'><a href='adminInformation.php?submit=delete&categoryid=$categoryid' onClick=\"return confirm('Confirm delete?')\" class='uil-multiply'></a></button></td>
                    </tr></tbody>";
                }
                echo "</table>";
                ?>
            </div>
        </div>
    </section>

    <div class="container" id="myForm">
        <div class="wrapper">
            <section class="post">
                <span class="close" onclick="closeForm()">&times;</span>
                <header>Add Information Title</header>

                <form action="adminInformation.php" class="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">

                    <div class="input-box">
                        <label>Information Title</label>
                        <input name="infoTitle" id="infoTitle" type="text" placeholder="Enter the information title" required />
                    </div>

                    <div class="input-box">
                        <label>Information Level</label>
                        <select class="select-box" name="infoLevel">
                            <option value="Select" selected>Please select a level</option>
                            <option value="Step 1: Crypto Basics">Step 1: Crypto Basics</option>
                            <option value="Step 2: How to earn crypto">Step 2: How to earn crypto</option>
                            <option value="Step 3: How to trade crypto">Step 3: How to trade crypto</option>
                            <option value="Step 4: How to use crypto">Step 4: How to use crypto</option>
                            <option value="Step 5: How to build crypto">Step 5: How to build crypto</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <label>Information Title Description</label>
                        <textarea name="infoTitleDes" id="infoTitleDes" type="text" placeholder="Enter the information title description" required></textarea>
                    </div>

                    <button type="submit" name="submit" id="idsumit">Post</button>
                </form>
            </section>
        </div>
    </div>

    <script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
            document.getElementById("main").style.filter = "grayscale(1) blur(2px)";
        }

        function closeForm() {
            document.getElementById("myForm").style.display = "none";
            document.getElementById("main").style.filter = "grayscale(0) blur(0)";
        }
    </script>

</body>

</html>