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

$sqluser = "SELECT * FROM tbl_users WHERE user_role = 'User'";
$stmt = $conn->prepare($sqluser);
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

    <link rel="stylesheet" type="text/css" href="../css/adminManageUser.css">
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
                    <span class="text">Manage User</span>
                </div>

                <?php
                $i = 0;

                echo "<table class='content-table'><thead><tr><th>No</th><th>Username</th><th>Email</th><th>Date Register</th><th>Status</th><th>More</th></tr></thead>";

                foreach ($rows as $user) {
                    $i++;
                    $userid = $user['user_id'];
                    $username = $user['user_name'];
                    $useremail = $user['user_email'];
                    $userphone = $user['user_phone'];
                    $usergender = $user['user_gender'];
                    $userpassword = $user['user_password'];
                    $userdatereg = $user['user_datereg'];
                    $postdate_formatted = date('d-m-Y H:i', strtotime($userdatereg));
                    $userrole = $user['user_role'];
                    $otp = $user['otp'];

                    $status = ($otp == 1) ? "Active" : "Inactive";
                    $statusClass = ($otp == 1) ? "active-status" : "inactive-status";

                    echo "<tbody><tr><td>$i</td><td>$username</td><td>$useremail</td><td>$postdate_formatted</td><td><button class='$statusClass' role='button'>$status</button></td>
                    <td><button class='button' role='button' onclick='showDetails($userid)'><i class='uil uil-folder'></i></button></td>
                    </tr></tbody>";
                }
                echo "</table>";
                ?>
            </div>
        </div>
    </section>

    <div id="userDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal_main" id="userDetailsContainer">
                <h2 class="modal_title">User Details</h2>
                <table class="modal-table">
                    <tr>
                        <th>Username</th>
                        <td><span id="username"></span></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><span id="email"></span></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><span id="phone"></span></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><span id="gender"></span></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span id="status"></span><button class="changeStatus" role="button" onclick="changeStatus()" id="changeStatusBtn">Change Status</button></td>
                    </tr>
                </table>
            </div>
            <div class="deletebutton">
                <button class="delete" role="button" onclick="deleteUser()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        var userId; // Declare userId globally

        function showDetails(id) {
            event.preventDefault();

            userId = id; // Assign the value to userId

            var userDetailsRequest = new XMLHttpRequest();
            userDetailsRequest.open("GET", "adminGetUserDetails.php?userId=" + userId, true);
            userDetailsRequest.onreadystatechange = function() {
                if (userDetailsRequest.readyState === 4 && userDetailsRequest.status === 200) {
                    var userDetails = JSON.parse(userDetailsRequest.responseText);

                    if (userDetails.error) {
                        document.getElementById("username").textContent = userDetails.error;
                        document.getElementById("email").textContent = userDetails.error;
                        document.getElementById("phone").textContent = userDetails.error;
                        document.getElementById("gender").textContent = userDetails.error;
                        document.getElementById("status").textContent = userDetails.error;
                        document.getElementById("changeStatusBtn").style.display = "none";
                    } else {
                        document.getElementById("username").textContent = userDetails.user_name || 'User not filled in the data';
                        document.getElementById("email").textContent = userDetails.user_email || 'User not filled in the data';
                        document.getElementById("phone").textContent = userDetails.user_phone || 'User not filled in the data';
                        document.getElementById("gender").textContent = userDetails.user_gender || 'User not filled in the data';
                        document.getElementById("status").textContent = userDetails.status || 'User not filled in the data';

                        document.getElementById("username").style.color = userDetails.user_name ? "black" : "grey";
                        document.getElementById("email").style.color = userDetails.user_email ? "black" : "grey";
                        document.getElementById("phone").style.color = userDetails.user_phone ? "black" : "grey";
                        document.getElementById("gender").style.color = userDetails.user_gender ? "black" : "grey";
                        document.getElementById("status").style.color = userDetails.status ? "black" : "grey";
                        document.getElementById("changeStatusBtn").style.display = "block";
                    }

                    document.getElementById("userDetailsModal").style.display = "block";
                }
            };
            userDetailsRequest.send();
        }

        function closeModal() {
            document.getElementById("userDetailsModal").style.display = "none";
        }

        function changeStatus() {
            if (confirm("Are you sure you want to change the user's status?")) {
                var statusChangeRequest = new XMLHttpRequest();
                statusChangeRequest.open("POST", "adminChangeUserStatus.php", true);
                statusChangeRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                statusChangeRequest.onreadystatechange = function() {
                    if (statusChangeRequest.readyState === 4 && statusChangeRequest.status === 200) {
                        var response = JSON.parse(statusChangeRequest.responseText);
                        if (response.success) {
                            document.getElementById("status").textContent = "Active";
                            document.getElementById("changeStatusBtn").disabled = true;
                            alert("User status changed successfully.");
                            window.location.href = 'adminManageUser.php';
                        } else {
                            alert("Failed to change user status.");
                        }
                    }
                };
                statusChangeRequest.send("userId=" + userId);
            }
        }

        function deleteUser() {
            if (confirm("Are you sure you want to delete this user?")) {
                var deleteRequest = new XMLHttpRequest();
                deleteRequest.open("POST", "adminDeleteUser.php", true);
                deleteRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                deleteRequest.onreadystatechange = function() {
                    if (deleteRequest.readyState === 4 && deleteRequest.status === 200) {
                        var response = JSON.parse(deleteRequest.responseText);
                        if (response.success) {
                            alert("User deleted successfully.");
                            closeModal();
                            window.location.href = 'adminManageUser.php';
                        } else {
                            alert("Failed to delete user.");
                        }
                    }
                };
                deleteRequest.send("userId=" + userId);
            }
        }
    </script>

</body>

</html>