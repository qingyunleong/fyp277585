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

if (isset($_POST['how'])) {

  $postid = $_POST['data'];
  $sqlUpdatecount = "UPDATE `tbl_post` SET `count`= count+1 WHERE post_id = $postid";

  try {
    $conn->beginTransaction();

    // Check if the user has already liked the post
    $sqllike = "SELECT * FROM `tbl_like` WHERE user_id = $user_id AND post_id = $postid";
    $stmt = $conn->prepare($sqllike);
    $stmt->execute();
    $number_of_result = $stmt->rowCount();

    if ($number_of_result == 0) {
      $sqlInsertLike = "INSERT INTO `tbl_like`(`user_id`, `post_id`) VALUES ($user_id, $postid)";
      $conn->exec($sqlInsertLike);
      $conn->exec($sqlUpdatecount);
      echo "liked";
    } else if ($number_of_result == 1) {
      $sqlUpdatecountdelete = "UPDATE `tbl_post` SET `count`= count-1 WHERE post_id = $postid";
      $sqlDeleteLike = "DELETE FROM `tbl_like` WHERE user_id = $user_id AND post_id = $postid";
      $conn->exec($sqlDeleteLike);
      $conn->exec($sqlUpdatecountdelete);
      echo "disliked";
    } else {
    }

    $conn->commit();
  } catch (PDOException $e) {
    $conn->rollback();
    echo "disliked";
  }
}
