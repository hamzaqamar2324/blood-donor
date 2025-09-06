<?php
include 'db.php'; // yahan apni DB connection wali file ka naam dalna

if (isset($_POST['user_id']) && isset($_POST['status'])) {
    $userId = intval($_POST['user_id']);
    $status = intval($_POST['status']);

    $stmt = $conn->prepare("UPDATE registration SET is_online = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $status, $userId);
    $stmt->execute();
}
?>
