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
        $infoid = $_GET['infoid'];
        $categoryid = $_GET['categoryid'];
        $sqldeletesub = "DELETE FROM `tbl_info` WHERE info_id = :infoid";
        $stmt = $conn->prepare($sqldeletesub);
        $stmt->bindParam(':infoid', $infoid);
        $stmt->execute();
        echo "<script>alert('Information deleted')</script>";
        echo "<script>window.location.replace('adminAddInformation.php?categoryid=$categoryid')</script>";
    }
}

if (isset($_POST['submit'])) {
    $sub = $_POST["sub"];
    $description = $_POST["description"];
    $infotile = $_POST["infotitle"];
    $infolevel = $_POST["infolevel"];
    $categoryid = $_POST["categoryid"];

    $sqlAddinfo = "INSERT INTO `tbl_info`(`info_level`, `info_title`, `info_sub`, `info_subDes`, `category_id`) VALUES (:infolevel, :infotile, :sub, :description, :categoryid)";
    $stmt = $conn->prepare($sqlAddinfo);
    $stmt->bindParam(':infolevel', $infolevel);
    $stmt->bindParam(':infotile', $infotile);
    $stmt->bindParam(':sub', $sub);
    $stmt->bindValue(':description', $description);
    $stmt->bindParam(':categoryid', $categoryid);

    try {
        $stmt->execute();
        echo "<script>alert('Add successful')</script>";
        echo "<script>window.location.replace('adminAddInformation.php?categoryid=$categoryid')</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Add failed')</script>";
        echo "<script>window.location.replace('adminAddInformation.php?categoryid=$categoryid')</script>";
    }
}

if (isset($_GET['categoryid'])) {

    $categoryid = $_GET['categoryid'];
    $sqlinfo = "SELECT * FROM `tbl_category` WHERE category_id = $categoryid";
    $stmt = $conn->prepare($sqlinfo);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();

    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        foreach ($rows as $category) {
            $categoryid = $category['category_id'];
            $categorylevel = $category['category_level'];
            $categoryname = $category['category_name'];
            $categorytitle = $category['category_title'];
            $categorytitledes = $category['category_titledes'];
        }
    } else {
        echo "<script>alert('Infromation not found.');</script>";
        echo "<script> window.location.replace('adminAddInformation.php?categoryid=$categoryid')</script>";
    }
} else {
    echo "<script>alert('Page Error');</script>";
    echo "<script> window.location.replace('adminInformation.php')</script>";
}

if (isset($_GET['categoryid'])) {

    $categoryid = $_GET['categoryid'];
    $sqlinfo1 = "SELECT * FROM `tbl_info` WHERE category_id = $categoryid";
    $stmt = $conn->prepare($sqlinfo1);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();

    if ($number_of_result > 0) {
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
    } else {
        $rows = array();
    }
} else {
    echo "<script>alert('Page Error');</script>";
    echo "<script> window.location.replace('adminInformation.php')</script>";
}

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

    <link rel="stylesheet" type="text/css" href="../css/adminAddInformation.css">
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

                <span class="level">Step <?php echo $categorylevel ?>: <?php echo $categoryname ?></span>

                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Add Sub of</span>
                    <span class="texta"><?php echo $categorytitle ?></span>
                </div>

                <div class="main-content">
                    <form action="adminAddInformation.php" class="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="categoryid" value="<?php echo $categoryid; ?>">
                        <input type="hidden" name="infotitle" value="<?php echo $categorytitle; ?>">
                        <input type="hidden" name="infolevel" value="Step <?php echo $categorylevel ?>: <?php echo $categoryname ?>">
                        <label>Sub</label>
                        <textarea name="sub" id="sub" required></textarea><br><br>
                        <label>Description</label>
                        <textarea name="description" id="description" required></textarea><br><br>
                        <input type="submit" name="submit" value="Add Sub">
                    </form>
                </div>

                <?php
                $i = 0;

                echo "<table class='content-table'><thead><tr><th>No</th><th>Infomation Sub Title</th><th>Information Sub Title Description</th><th>Operations</th></tr></thead>";

                foreach ($rows as $info) {
                    $i++;
                    $infoid = $info['info_id'];
                    $info_level = $info['info_level'];
                    $info_title = $info['info_title'];
                    $info_sub = $info['info_sub'];
                    $info_subDes = truncate($info['info_subDes'], 400);
                    $category_id = $info['category_id'];

                    echo "<tbody><tr><td>$i</td><td>$info_sub</td><td>$info_subDes</td>
                    <td><button class='button-39' role='button'><a href='adminEditInformation.php?categoryid=$categoryid&infoid=$infoid' class='uil-pen'></a></button>
                    <button class='button-39' role='button'><a href='adminAddInformation.php?submit=delete&categoryid=$categoryid&infoid=$infoid' onClick=\"return confirm('Confirm delete?')\" class='uil-multiply'></a></button></td>
                    
                    </tr></tbody>";
                }
                echo "</table>";
                ?>
            </div>
        </div>
    </section>
</body>

</html>