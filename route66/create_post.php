<?php
include("header.php");
include("navigation.php");
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $city = $_POST['city'];
    $event_type = $_POST['event_type'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $is_21plus = isset($_POST['is_21plus']) ? 1 : 0;
    $description = $_POST['description'];


    $stmt = $conn->prepare("INSERT INTO meetup_posts (city, event_type, start_time, end_time, is_21plus, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $city, $event_type, $start_time, $end_time, $is_21plus, $description);
    $stmt->execute();


    header("Location: social.php");
    exit();
}
?>

<main>
    <section class="form-container">
        <h2>Create a Meetup</h2>
        <form method="POST" class="create-form">
            <label for="city">City:</label>
            <select name="city" required>
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

            <label for="event_type">Event Type:</label>
            <select name="event_type" required>
                <option value="Eating">Eating</option>
                <option value="Drinking">Drinking</option>
                <option value="Concert">Concert</option>
                <option value="Game">Game</option>
                <option value="Attraction">Attraction</option>
            </select>

            <label for="start_time">Start Time:</label>
            <input type="datetime-local" name="start_time" required>

            <label for="end_time">End Time:</label>
            <input type="datetime-local" name="end_time" required>

            <label for="is_21plus">21+ Event:</label>
            <input type="checkbox" name="is_21plus">

            <label for="description">Description:</label>
            <textarea name="description" required></textarea>

            <button type="submit" class="submit-btn">Create Meetup</button>
        </form>
    </section>
</main>

<?php include("footer.php"); ?>
