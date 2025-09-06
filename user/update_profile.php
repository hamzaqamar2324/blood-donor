<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $contact = $_POST['contact'];
    $blood_type = $_POST['blood_type'];

    $sql = "UPDATE donors SET 
        user_name = '$user_name',
        user_email = '$user_email',
        contact = '$contact',
        blood_type = '$blood_type'
        WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['user_name'] = $user_name;
        header("Location: profile_page.php");
        exit;
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
} else {
    header("Location: profile_page.php");
    exit;
}
