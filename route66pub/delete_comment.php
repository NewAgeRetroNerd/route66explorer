<?php
// delete_comment.php
session_start(); // Start the session to access user data

include("db_connect.php"); // Include database connection

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the comment ID from the POST request
$comment_id = $_POST['comment_id'];
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Prepare SQL statement to delete the comment only if it belongs to the logged-in user
$stmt = $conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $comment_id, $user_id); 
$stmt->execute(); 

// Redirect back to the referring page after deletion
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
