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

    $id = $_POST['id'];
    $name = $_POST['username'];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];

    $sqlUpdateUser = "UPDATE `tbl_users` SET `user_name`='$name',`user_email`='$email',`user_phone`='$phone',`user_gender`='$gender' WHERE user_id = $id";
    try {
        $conn->exec($sqlUpdateUser);
        echo "<script>alert('Update successful')</script>";
        echo "<script>window.location.replace('userProfile.php')</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Update failed')</script>";
        echo "<script>window.location.replace('userProfile.php')</script>";
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $passwordold = sha1($_POST['passwordold']);
    $passworda = sha1($_POST["passworda"]);
    $passwordb = sha1($_POST["passwordb"]);

    $sqlGetPassword = "SELECT user_password FROM `tbl_users` WHERE user_id = $id";
    $stmt = $conn->prepare($sqlGetPassword);
    $stmt->execute();
    $existingPassword = $stmt->fetchColumn();

    if ($existingPassword === $passwordold && $passworda === $passwordb) {

        $sqlUpdatePassword = "UPDATE `tbl_users` SET `user_password`='$passworda' WHERE user_id = $id";
        try {
            $conn->exec($sqlUpdatePassword);
            echo "<script>alert('Password updated successfully')</script>";
            echo "<script>window.location.replace('userProfile.php')</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Password update failed')</script>";
            echo "<script>window.location.replace('userProfile.php')</script>";
        }
    } else {
        echo "<script>alert('Invalid password')</script>";
        echo "<script>window.location.replace('userProfile.php')</script>";
    }
}

$sqluser = "SELECT * FROM `tbl_users` WHERE user_id = $user_id";
$stmt = $conn->prepare($sqluser);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
foreach ($rows as $user) {
    $userid = $user['user_id'];
    $username = $user['user_name'];
    $useremail = $user['user_email'];
    $userphone = $user['user_phone'];
    $usergender = $user['user_gender'];
    $userpassword = $user['user_password'];
    $userdatereg = $user['user_datereg'];
    $userrole = $user['user_role'];
    $otp = $user['otp'];
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
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="../css/userProfile.css">
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

    <section class="py-5 my-5">
        <div class="container">
            <h1 class="mb-5">Account Settings</h1>

            <div class="bg-white shadow rounded-lg d-block d-sm-flex">
                <div class="profile-tab-nav border-right">

                    <div class="p-4">
                        <div class="img-circle text-center mb-3">
                            <img class="shadow" alt="Image" src='../photo/registerPhoto/<?php echo $useremail ?>.png' onerror=this.onerror=null; this.src='../photo/profile_icon.png'>
                        </div>
                        <h4 class="text-center"><?php echo $username ?></h4>
                    </div>

                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="account-tab" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="true">
                            <i class="fa fa-home text-center mr-1"></i>
                            Account
                        </a>
                        <a class="nav-link" id="password-tab" data-toggle="pill" href="#password" role="tab" aria-controls="password" aria-selected="false">
                            <i class="fa fa-key text-center mr-1"></i>
                            Password
                        </a>
                    </div>
                </div>

                <div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <h3 class="mb-4">Account Settings</h3>

                        <form action="userProfile.php" class="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">

                            <input type="hidden" name="id" value="<?php echo $userid; ?>">

                            <!-- <div class="avatar">
                                <img class="avatar_image" src="../photo/registerPhoto/<?php echo $useremail ?>.png"><br>
                                <input class="inputs" type="file" onchange="previewFile()" name="fileToUpload" id="fileToUpload"><br>
                            </div> -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control" value="<?php echo $username ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" class="form-control" value="<?php echo $useremail ?>" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone number</label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo $userphone ?>" placeholder="<?php echo $userphone ? '' : 'Please enter phone number' ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" id="gender" name="gender">
                                            <?php if ($usergender === "") : ?>
                                                <option value="none" selected>Please Select</option>
                                            <?php else : ?>
                                                <option value="none">Please Select</option>
                                            <?php endif; ?>
                                            <option value="Male" <?php if ($usergender === "Male") echo ' selected="selected"'; ?>>Male</option>
                                            <option value="Female" <?php if ($usergender === "Female") echo ' selected="selected"'; ?>>Female</option>
                                            <option value="Other" <?php if ($usergender === "Other") echo ' selected="selected"'; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <input class="btn btn-primary" type="submit" name="submit" value="Update">
                            </div>

                        </form>
                    </div>

                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <h3 class="mb-4">Password Settings</h3>

                        <form action="userProfile.php" class="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">

                            <input type="hidden" name="id" value="<?php echo $userid; ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Old password</label>
                                        <input type="password" name="passwordold" id="passwordold" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>New password</label>
                                        <input type="password" name="passworda" id="passworda" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm new password</label>
                                        <input type="password" name="passwordb" id="passwordb" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <input class="btn btn-primary" type="submit" name="update" value="Update">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

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