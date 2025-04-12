<?php
// create_post.php

session_start(); // Start the session to track user authentication

// Include common layout and database connection files
include("header.php");
include("navigation.php");
include("db_connect.php");

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data from POST request
    $city = $_POST['city'];
    $event_type = $_POST['event_type'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $is_21plus = isset($_POST['is_21plus']) ? 1 : 0; // Convert checkbox to binary value
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id']; // Get user ID from session

    // Prepare and execute SQL statement to insert the new meetup post
    $stmt = $conn->prepare("INSERT INTO meetup_posts (user_id, city, event_type, start_time, end_time, is_21plus, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssis", $user_id, $city, $event_type, $start_time, $end_time, $is_21plus, $description);
    $stmt->execute();

    // Redirect to social page after successful post creation
    header("Location: social.php");
    exit();
}
?>

<main>
    <section class="form-container">
        <h2>Create a Meetup</h2>

        <!-- Meetup creation form -->
        <form method="POST" class="create-form">
            <!-- City selection dropdown -->
            <label for="city">City:</label>
            <select id="city" name="city" required>
                <option value="">-</option>
                <!-- Cities along Route 66 -->
                <option value="Chicago, IL">Chicago, IL</option>
                <option value="Springfield, IL">Springfield, IL</option>
                <option value="St. Louis, MO">St. Louis, MO</option>
                <option value="Joplin, MO">Joplin, MO</option>
                <option value="Tulsa, OK">Tulsa, OK</option>
                <option value="Oklahoma City, OK">Oklahoma City, OK</option>
                <option value="Amarillo, TX">Amarillo, TX</option>
                <option value="Tucumcari, NM">Tucumcari, NM</option>
                <option value="Santa Fe, NM">Santa Fe, NM</option>
                <option value="Albuquerque, NM">Albuquerque, NM</option>
                <option value="Gallup, NM">Gallup, NM</option>
                <option value="Holbrook, AZ">Holbrook, AZ</option>
                <option value="Winona, AZ">Winona, AZ</option>
                <option value="Flagstaff, AZ">Flagstaff, AZ</option>
                <option value="Kingman, AZ">Kingman, AZ</option>
                <option value="Needles, CA">Needles, CA</option>
                <option value="Barstow, CA">Barstow, CA</option>
                <option value="San Bernardino, CA">San Bernardino, CA</option>
                <option value="Los Angeles, CA">Los Angeles, CA</option>
            </select>

            <!-- Event type dropdown -->
            <label for="event_type">Event Type:</label>
            <select id="event_type" name="event_type" required>
                <option value="">-</option>
                <option value="Eating">Eating</option>
                <option value="Drinking">Drinking</option>
                <option value="Concert">Concert</option>
                <option value="Game">Game</option>
                <option value="Attraction">Attraction</option>
            </select>

            <!-- Start time input -->
            <label for="start_time">Start Time:</label>
            <input type="datetime-local" id="start_time" name="start_time" required>

            <!-- End time input -->
            <label for="end_time">End Time:</label>
            <input type="datetime-local" id="end_time" name="end_time" required>

            <!-- Checkbox for 21+ event -->
            <label for="is_21plus">21+ Event:</label>
            <input type="checkbox" id="is_21plus" name="is_21plus">

            <!-- Description textarea -->
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <!-- Submit button -->
            <button type="submit" class="submit-btn">Create Meetup</button>
        </form>
    </section>

<?php include("footer.php"); // Include the footer layout ?>

