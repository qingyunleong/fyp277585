<?php
// getUserDetails.php
include_once("dbconnect.php");

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    
    $sql = "SELECT * FROM tbl_users WHERE user_id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userDetails) {
        // Determine the status based on OTP value
        $status = ($userDetails['otp'] == 1) ? 'Active' : 'Inactive';

        // Update the 'Status' key in the user details array
        $userDetails['status'] = $status;

        // Return the user details as JSON response
        echo json_encode($userDetails);
    } else {
        echo json_encode(array('error' => 'User not filled in the data'));
    }
}
