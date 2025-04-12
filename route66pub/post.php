<?php
// post.php
session_start();
include("header.php"); 
include("navigation.php"); 
include("db_connect.php");  

$post_id = $_GET['id'];  
// Fetch post data from the database based on post ID
$post = $conn->query("SELECT * FROM meetup_posts WHERE id = $post_id")->fetch_assoc();
// Fetch comments for the post, joined with the users table to get the username
$comments = $conn->query("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE post_id = $post_id ORDER BY created_at DESC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];  
    $comment = $_POST['comment']; 

    // Prepare and execute the query to insert the comment into the database
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $post_id, $user_id, $comment);
    $stmt->execute();
    header("Location: post.php?id=" . $post_id);  // Redirect back to the same post page
    exit();
}
?>

<main class="post-container">
    <h2><?= htmlspecialchars($post['event_type']) ?> Meetup in <?= htmlspecialchars($post['city']) ?></h2> 
    <p><?= htmlspecialchars($post['description']) ?></p>  

    <h3>Comments</h3>
    <?php while ($comment = $comments->fetch_assoc()): ?>  
        <div class="comment">
            <p><strong><?= htmlspecialchars($comment['username']) ?>:</strong> <?= htmlspecialchars($comment['comment']) ?></p>
            <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>  
                <form action="delete_comment.php" method="POST" onsubmit="return confirm('Delete this comment?');">
                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">                      
                    <button type="submit">Delete</button>  
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    <h4>Leave a Comment</h4>
    <form method="POST" class="comment-form">
        <textarea name="comment" placeholder="Leave a comment..." required></textarea>
        <button type="submit">Comment</button>  
    </form>

    <a href="social.php" class="back-btn">Back to Meetups</a> 

<?php include("footer.php"); ?>  
