<?php
include_once("dbconnect.php");

if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Perform the deletion logic using the $userId
    $sql = "DELETE FROM tbl_users WHERE user_id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Check if any rows were affected by the deletion
    if ($stmt->rowCount() > 0) {
        // Respond with a success message as JSON
        echo json_encode(array('success' => true));
    } else {
        // Respond with an error message as JSON
        echo json_encode(array('success' => false));
    }
} else {
    // Respond with an error message as JSON
    echo json_encode(array('success' => false));
}
?>