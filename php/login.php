<?php

if (isset($_POST['submit'])) {

    include 'dbconnect.php';

    $email = $_POST['email'];
    $pass = sha1($_POST['password']);

    $sqllogin = "SELECT * FROM tbl_users WHERE user_email = '$email' AND user_password = '$pass'";

    $stmt = $conn->prepare($sqllogin);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount()  > 0) {
        if ($row['user_role'] == 'Admin') {
            $id = $row['user_id'];
            $name = $row['user_name'];
            $email = $row['user_email'];

            session_start();
            $_SESSION['admin_id'] = session_id();
            $_SESSION["id"] = $id;
            $_SESSION["email"] = $email;
            $_SESSION["name"] = $name;

            echo "<script>alert('Login Success');</script>";
            echo "<script> window.location.replace('adminDashboard.php')</script>";
        } else if ($row['user_role'] == 'User') {
            $id = $row['user_id'];
            $name = $row['user_name'];
            $email = $row['user_email'];

            session_start();
            $_SESSION['user_id'] = session_id();
            $_SESSION["id"] = $id;
            $_SESSION["email"] = $email;
            $_SESSION["name"] = $name;

            echo "<script>alert('Login Success');</script>";
            echo "<script> window.location.replace('userMainpage.php')</script>";
        }
    } else {
        echo "<script>alert('Login Failed');</script>";
        echo "<script> window.location.replace('login.php')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link rel="stylesheet" href="../css/login.css">
    <script src="../js/login.js" defer></script>
</head>

<body onload="loadCookies()">
    <div class="content">

        <div class="login-wrapper">

            <div class="left-img">
                <div class="glass">
                    <div class="tips">
                        <div class="title">CryptoGet</div>
                        <h1>About Learn Crypto</h1>
                        <span>CryptoGet is a free education platform designed to help users easily learn about cryptocurrency, with simple, relevant and engaging content.</span>
                    </div>
                </div>
            </div>

            <div class="right-login-form">

                <div class="form-wrapper">
                    <form name="loginForm" action="login.php" method="post">
                        <h1>Log in</h1>
                        <div class="input-items">
                            <span class="input-tips">
                                Email Address
                            </span>
                            <input class="inputs" type="email" name="email" id="idemail" placeholder="Enter your email" required>
                        </div>

                        <div class="input-items">
                            <span class="input-tips">
                                Password
                            </span>
                            <input class="inputs" type="password" name="password" id="idpassword" placeholder="Enter your password" required>

                            <span class="forgot"><a href="#">Forgot password?</a></span>
                        </div>

                        <br>
                        <p>
                            <input class="w3-check" name="rememberme" type="checkbox" id="idremember" onclick="rememberMe()">
                            <label style="color: black"> Remember Me</label>
                        </p><br>

                        <div class="btn">
                            <input type="submit" name="submit" id="idsubmit" value="Sumbit">
                        </div>

                        <div class="siginup-tips">
                            <span>Don't Have An Account?</span>
                            <span><a href="registerpage.php" style="text-decoration:none"><u>Signup</u></a></span>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="cookieNotice" class="w3-right w3-block" style="display: none">
        <div class="" style="background-color: #4F6A8B ; color:white">
            <p>We use cookies to personalise your experience on the site. Let us know if you’re ok with this.
                <a style="color:lightGrey;" href="/privacy-policy">Privacy Policy</a>
            </p>
            <div class="w3-button">
                <button onclick="acceptCookieConsent();">Accept</button>
            </div>
        </div>
    </div>

    <script>
        let cookie_consent = getCookie("user_cookie_consent");
        if (cookie_consent != "") {
            document.getElementById("cookieNotice").style.display = "none";
        } else {
            document.getElementById("cookieNotice").style.display = "block";
        }
    </script>

</body>

</html>