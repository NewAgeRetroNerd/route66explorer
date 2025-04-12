<?php
session_start(); // Start the session to access user authentication

include("db_connect.php"); 

// Check if the request method is POST and required data is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']) && isset($_SESSION['user_id'])) {
    $post_id = intval($_POST['post_id']); // Get the post ID from the form and sanitize it
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Verify that the logged-in user owns the post
    $check = $conn->prepare("SELECT * FROM meetup_posts WHERE id = ? AND user_id = ?");
    $check->bind_param("ii", $post_id, $user_id); 
    $check->execute(); 
    $result = $check->get_result(); 

    // If the post exists and belongs to the user, proceed with deletion
    if ($result && $result->num_rows > 0) {
        $stmt = $conn->prepare("DELETE FROM meetup_posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute(); 

        // Redirect to social page with a success flag
        header("Location: social.php?deleted=1");
        exit;
    } else {
        // If post not found or doesn't belong to user, show error
        echo "Unauthorized or post not found.";
    }
} else {
    // If request is invalid or required data is missing, show error
    echo "Invalid request.";
}
?>

