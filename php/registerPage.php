<?php

if (isset($_POST['submit'])) {
    include_once("dbconnect.php");

    $email = $_POST["email"];
    $name = $_POST["name"];
    $role = 'User';
    $otp = rand(10000, 99999);
    $password = sha1($_POST["password"]);

    $sqlregister = "INSERT INTO `tbl_users`(`user_name`, `user_email`, `user_password`, `user_role`, `otp`) 
    VALUES ('$name','$email','$password','$role','$otp')";
    try {
        $conn->exec($sqlregister);
        if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
            $last_id = $conn->lastInsertId();
            uploadImage($last_id, $email);
            echo "<script>alert('Registration successful')</script>";
            echo "<script>window.location.replace('login.php')</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Registration failed')</script>";
        echo "<script>window.location.replace('registerPage.php')</script>";
    }
}

function uploadImage($filename, $email)
{
    $target_dir = "../photo/registerPhoto/";
    $target_file = $target_dir . $email . ".png";
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
}

function sendMail($email, $otp, $password)
{
    $to = $email;
    $subject = "CryptoGet";
    $message = "
    <html>
    <head>
    <title></title>
    </head>
    <body>
    <h3>Thank you for your registration - DO NOT REPLY TO THIS EMAIL</h3>
    <p>U</p><br><br>
        <a href='https://yuelle.com/qingyun/cryptoget/php/verify.php?email=$email&otp=$otp'>Click here to verify your account</a><br><br>
    </p>
    <table>
    <tr>
    <th>Your Email</th>
    <th>Key/Password</th>
    </tr>
    <tr>
    <td>$email</td>
    <td>$password</td>
    </tr>
    </table>
    <br>
    <p>TERMS AND CONDITION <br>Single license for the person who made the purchase. The publication and it resources are protected by Copyright law. No part of this publication may be reproduced, 
        shared, distributed, or transmitted in any form or by any means, including, photocopying, recording, or other electronic or mechanical methods with 
        the prior written permission of the author. By downloading this copy you are agreeing to the terms and conditions and can be subjected to law if violated and permanent ban from accessing the resource</p>
    </body>
    </html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: <qingyun@yuelle.com>' . "\r\n";

    mail($to, $subject, $message, $headers);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>

    <link rel="stylesheet" href="../css/register.css">
    <script src="../js/register.js" defer></script>
    <script src="../js/script.js"></script>
</head>

<body>

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
                    <form name="registerForm" action="registerPage.php" method="post" onsubmit="return confirmDialog()" enctype="multipart/form-data">
                        <h1>Sign Up</h1><br>

                        <div class="avatar">
                            <img class="avatar_image" src="../photo/profile_icon.png"><br>
                            <input class="inputs" type="file" onchange="previewFile()" name="fileToUpload" id="fileToUpload"><br>
                        </div>

                        <div class="input-items">
                            <span class="input-tips">Name</span>
                            <input class="inputs" type="name" name="name" id="idname" placeholder="Enter your name" required>
                        </div>

                        <div class="input-items">
                            <span class="input-tips">Email</span>
                            <input class="inputs" type="email" name="email" id="idemail" placeholder="Enter your email" required>
                        </div>

                        <div class="input-items">
                            <span class="input-tips">Password</span>
                            <input class="inputs" type="password" name="password" id="idpassword" placeholder="Enter your password" required>
                        </div>

                        <div class="btn">
                            <input type="submit" name="submit" id="idsubmit" value="Sumbit">
                        </div>

                        <div class="siginup-tips">
                            <span>Don't Have An Account?</span>
                            <span><a href="login.php" style="text-decoration:none"><u>Signup</u></a></span>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>