<?php
// Assuming you have a database connection already established
include_once("dbconnect.php");


if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Update the user's status in the database
    $sql = "UPDATE tbl_users SET otp = 1 WHERE user_id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    $success = false;
    if ($stmt->execute()) {
        $success = true;
    }

    // Return the response as JSON
    echo json_encode(['success' => $success]);
}
?>