<?php
session_start(); // Start session to access user data
include("header.php"); 
include("navigation.php"); 
include("db_connect.php"); 

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get post ID from query string
$post_id = $_GET['id'] ?? null;
if (!$post_id) {
    echo "Invalid post ID.";
    exit();
}

// Fetch the post data for the logged-in user
$stmt = $conn->prepare("SELECT * FROM meetup_posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Deny access if post not found or not owned by user
if ($result->num_rows === 0) {
    echo "Post not found or access denied.";
    exit();
}

$post = $result->fetch_assoc(); // Get post data

// Handle form submission for updating post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $city = $_POST['city'];
    $event_type = $_POST['event_type'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $is_21plus = isset($_POST['is_21plus']) ? 1 : 0;
    $description = $_POST['description'];

    // Update the post in the database
    $update_stmt = $conn->prepare("UPDATE meetup_posts SET city = ?, event_type = ?, start_time = ?, end_time = ?, is_21plus = ?, description = ? WHERE id = ? AND user_id = ?");
    $update_stmt->bind_param("ssssssii", $city, $event_type, $start_time, $end_time, $is_21plus, $description, $post_id, $_SESSION['user_id']);
    $update_stmt->execute();

    // Redirect after update
    header("Location: social.php");
    exit();
}
?>
<main>
    <section class="form-container">
        <h2>Edit Meetup</h2>
        <form method="POST" class="create-form">
            <label for="city">City:</label>
            <select id="city" name="city" required>
                <?php
                // Populate city dropdown with selection retained
                $cities = ["Chicago, IL", "Springfield, IL", "St. Louis, MO", "Joplin, MO", "Tulsa, OK", "Oklahoma City, OK", "Amarillo, TX", "Tucumcari, NM", "Santa Fe, NM", "Albuquerque, NM", "Gallup, NM", "Holbrook, AZ", "Winona, AZ", "Flagstaff, AZ", "Kingman, AZ", "Needles, CA", "Barstow, CA", "San Bernardino, CA", "Los Angeles, CA"];
                foreach ($cities as $c) {
                    $selected = ($post['city'] == $c) ? "selected" : "";
                    echo "<option value=\"$c\" $selected>$c</option>";
                }
                ?>
            </select>

            <label for="event_type">Event Type:</label>
            <select id="event_type" name="event_type" required>
                <?php
                // Populate event type dropdown with selection retained
                $types = ["Eating", "Drinking", "Concert", "Game", "Attraction"];
                foreach ($types as $type) {
                    $selected = ($post['event_type'] == $type) ? "selected" : "";
                    echo "<option value=\"$type\" $selected>$type</option>";
                }
                ?>
            </select>

            <label for="start_time">Start Time:</label>
            
            <input type="datetime-local" id="start_time" name="start_time" value="<?= date('Y-m-d\TH:i', strtotime($post['start_time'])) ?>" required>

            <label for="end_time">End Time:</label>
            
            <input type="datetime-local" id="end_time" name="end_time" value="<?= date('Y-m-d\TH:i', strtotime($post['end_time'])) ?>" required>

            <label for="is_21plus">21+ Event:</label>
            
            <input type="checkbox" id="is_21plus" name="is_21plus" <?= $post['is_21plus'] ? 'checked' : '' ?> >

            <label for="description">Description:</label>
            
            <textarea id="description" name="description" required><?= htmlspecialchars($post['description']) ?></textarea>

            <button type="submit" class="submit-btn">Update Meetup</button>
        </form>
    </section>

<?php include("footer.php"); ?>
