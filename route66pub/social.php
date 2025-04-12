<?php
session_start(); 
include("header.php");  
include("navigation.php");  
include("db_connect.php");  

// Get filter inputs from the URL, default to empty if not set
$city = $_GET['city'] ?? '';
$event_type = $_GET['event_type'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Base SQL query to fetch meetup posts and associated user information
$sql = "SELECT meetup_posts.*, users.username FROM meetup_posts JOIN users ON meetup_posts.user_id = users.id WHERE 1=1";

// Apply filters to the SQL query if filters are provided
if ($city) {
    $sql .= " AND city = '" . $conn->real_escape_string($city) . "'";  
}
if ($event_type) {
    $sql .= " AND event_type = '" . $conn->real_escape_string($event_type) . "'";  
}
if ($start_date) {
    $sql .= " AND DATE(start_time) >= '" . $conn->real_escape_string($start_date) . "'";  
}
if ($end_date) {
    $sql .= " AND DATE(end_time) <= '" . $conn->real_escape_string($end_date) . "'";  
}

// Order results by start time in ascending order
$sql .= " ORDER BY start_time ASC";

// Execute the query and get the result
$result = $conn->query($sql);
?>

<main>
    <section class="filter-section">
        <h2>Find a Meetup!</h2>
        <form method="GET" class="filter-form">
           
            <div class="form-group">
                <label for="city">City:</label>
                <select id="city" name="city">
                    <option value="">All</option>
                    <?php
                    // Define available cities and populate the dropdown
                    $cities = ["Chicago, IL", "Springfield, IL", "St. Louis, MO", "Joplin, MO", "Tulsa, OK", "Oklahoma City, OK", "Amarillo, TX", "Tucumcari, NM", "Santa Fe, NM", "Albuquerque, NM", "Gallup, NM", "Holbrook, AZ", "Winona, AZ", "Flagstaff, AZ", "Kingman, AZ", "Needles, CA", "Barstow, CA", "San Bernardino, CA", "Los Angeles, CA"];
                    foreach ($cities as $option) {
                        echo "<option value=\"$option\"" . ($city == $option ? " selected" : "") . ">$option</option>";  
                    }
                    ?>
                </select>
            </div>

            <!-- Event type filter dropdown -->
            <div class="form-group">
                <label for="event_type">Event Type:</label>
                <select id="event_type" name="event_type">
                    <option value="">All</option>
                    <?php
                    // Define available event types and populate the dropdown
                    $types = ["Eating", "Drinking", "Concert", "Game", "Attraction"];
                    foreach ($types as $type) {
                        echo "<option value=\"$type\"" . ($event_type == $type ? " selected" : "") . ">$type</option>";  
                    }
                    ?>
                </select>
            </div>

            
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
            </div>

            
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
            </div>

            
            <button type="submit" class="filter-btn">Filter</button>
        </form>
    </section>

    <section id="meetups">
        <h3>Meetup Listings</h3>
        <?php if ($result && $result->num_rows > 0): ?>  
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
                            <p class="post-meta">Posted by <?= htmlspecialchars($row['username']) ?></p>
                        </div>
                        <div class="meetup-footer">
                            
                            <a href="post.php?id=<?= $row['id'] ?>" class="btn">View & Comment</a>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']): ?>  
                                <a href="edit_post.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                                <form method="POST" action="delete_post.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn danger">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>  
            <p class="no-results">No meetups found. Try adjusting your filters!</p>
        <?php endif; ?>
    </section>

    
    <a href="create_post.php" class="post-btn">Create a Meetup</a>

<?php include("footer.php"); ?>  



