<?php

include 'dbconnect.php';

session_start();
session_unset();
session_destroy();

echo "<script>alert('Logout Already');</script>";
echo "<script> window.location.replace('login.php')</script>";

?>