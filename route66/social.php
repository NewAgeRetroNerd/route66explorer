<?php
include("header.php");
include("navigation.php");
include("db_connect.php");

$city = $_GET['city'] ?? '';
$event_type = $_GET['event_type'] ?? '';
$time_filter = $_GET['time_filter'] ?? '';

$sql = "SELECT * FROM meetup_posts WHERE 1=1";

if ($city) $sql .= " AND city = '$city'";
if ($event_type) $sql .= " AND event_type = '$event_type'";
if ($time_filter === 'upcoming') $sql .= " AND start_time >= NOW()";
if ($time_filter === 'past') $sql .= " AND end_time < NOW()";

$result = $conn->query($sql);
?>

<main>
    <section class="filter-section">
        <h2>Find a Meetup!</h2>
        <form method="GET" class="filter-form">
            <div class="form-group">
                <label for="city">City:</label>
                <select name="city">
                    <option value="">All</option>
                    <option value="Chicago, IL" <?= $city == "Chicago, IL" ? "selected" : "" ?>>Chicago, IL</option>
                    <option value="Springfield, IL" <?= $city == "Springfield, IL" ? "selected" : "" ?>>Springfield, IL</option>
                    <option value="St. Louis, MO" <?= $city == "St. Louis, MO" ? "selected" : "" ?>>St. Louis, MO</option>
                    <option value="Joplin, MO" <?= $city == "Joplin, MO" ? "selected" : "" ?>>Joplin, MO</option>
                    <option value="Tulsa, OK" <?= $city == "Tulsa, OK" ? "selected" : "" ?>>Tulsa, OK</option>
                    <option value="Oklahoma City, OK" <?= $city == "Oklahoma City, OK" ? "selected" : "" ?>>Oklahoma City, OK</option>
                    <option value="Amarillo, TX" <?= $city == "Amarillo, TX" ? "selected" : "" ?>>Amarillo, TX</option>
                    <option value="Tucumcari, NM" <?= $city == "Tucumcari, NM" ? "selected" : "" ?>>Tucumcari, NM</option>
                    <option value="Santa Fe, NM" <?= $city == "Santa Fe, NM" ? "selected" : "" ?>>Santa Fe, NM</option>
                    <option value="Albuquerque, NM" <?= $city == "Albuquerque, NM" ? "selected" : "" ?>>Albuquerque, NM</option>
                    <option value="Gallup, NM" <?= $city == "Gallup, NM" ? "selected" : "" ?>>Gallup, NM</option>
                    <option value="Holbrook, AZ" <?= $city == "Holbrook, AZ" ? "selected" : "" ?>>Holbrook, AZ</option>
                    <option value="Winona, AZ" <?= $city == "Winona, AZ" ? "selected" : "" ?>>Winona, AZ</option>
                    <option value="Flagstaff, AZ" <?= $city == "Flagstaff, AZ" ? "selected" : "" ?>>Flagstaff, AZ</option>
                    <option value="Kingman, AZ" <?= $city == "Kingman, AZ" ? "selected" : "" ?>>Kingman, AZ</option>
                    <option value="Needles, CA" <?= $city == "Needles, CA" ? "selected" : "" ?>>Needles, CA</option>
                    <option value="Barstow, CA" <?= $city == "Barstow, CA" ? "selected" : "" ?>>Barstow, CA</option>
                    <option value="San Bernardino, CA" <?= $city == "San Bernardino, CA" ? "selected" : "" ?>>San Bernardino, CA</option>
                    <option value="Los Angeles, CA" <?= $city == "Los Angeles, CA" ? "selected" : "" ?>>Los Angeles, CA</option>
                </select>
            </div>

            <div class="form-group">
                <label for="event_type">Event Type:</label>
                <select name="event_type">
                    <option value="">All</option>
                    <option value="Eating" <?= $event_type == "Eating" ? "selected" : "" ?>>Eating</option>
                    <option value="Drinking" <?= $event_type == "Drinking" ? "selected" : "" ?>>Drinking</option>
                    <option value="Concert" <?= $event_type == "Concert" ? "selected" : "" ?>>Concert</option>
                    <option value="Game" <?= $event_type == "Game" ? "selected" : "" ?>>Game</option>
                    <option value="Attraction" <?= $event_type == "Attraction" ? "selected" : "" ?>>Attraction</option>
                </select>
            </div>

            <div class="form-group">
                <label for="time_filter">Time:</label>
                <select name="time_filter">
                    <option value="">All</option>
                    <option value="upcoming" <?= $time_filter == "upcoming" ? "selected" : "" ?>>Upcoming</option>
                    <option value="past" <?= $time_filter == "past" ? "selected" : "" ?>>Past</option>
                </select>
            </div>

            <button type="submit" class="filter-btn">Filter</button>
        </form>
    </section>

    <section id="meetups">
        <h3>Meetup Listings</h3>
        <?php if ($result->num_rows > 0): ?>
            <div class="meetup-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="meetup-card">
                        <div class="meetup-header">
                            <h4><?= htmlspecialchars($row['event_type']) ?> in <?= htmlspecialchars($row['city']) ?></h4>
                        </div>
                        <div class="meetup-body">
                            <p><strong>Time:</strong> <?= date("F j, Y, g:i A", strtotime($row['start_time'])) ?> - <?= date("g:i A", strtotime($row['end_time'])) ?></p>
                            <p><?= htmlspecialchars($row['description']) ?></p>
                            <p><strong>21+ Event:</strong> <?= $row['is_21plus'] ? 'Yes' : 'No' ?></p>
                        </div>
                        <div class="meetup-footer">
                            <a href="post.php?id=<?= $row['id'] ?>" class="btn">View & Comment</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-results">No meetups found. Try adjusting your filters!</p>
        <?php endif; ?>
    </section>

    <a href="create_post.php" class="post-btn">Create a Meetup</a>
</main>

<?php include("footer.php"); ?>
