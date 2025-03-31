<?php
include("header.php");
include("navigation.php");
?>

<main>
    <section class="city-header">
        <h2>Discover Kingman, AZ</h2>
        <img class="city-img" src="images/kingman.jpg" alt="Kingman">
        <p>Step back in time and experience the charm of Kingman, Arizona!</p>
    </section>

    <section class="api-data" id="weather">
        <h3>Current Weather</h3>
        <div class="data-box" id="weather-info">Loading weather...</div>
    </section>

    <section class="api-data" id="events">
        <h3>Upcoming Events</h3>
        <div class="data-box" id="events-info">Loading events...</div>
    </section>

    <section class="api-data" id="attractions">
        <h3>Top Attractions</h3>
        <div class="data-box" id="attractions-info">Loading attractions...</div>
    </section>
</main>

<script src="js/weather.js"></script>
<script src="js/events.js"></script>
<script src="js/attractions.js"></script>
<script>
    getWeather("Kingman");
    getEvents("Kingman");
    getAttractions("Kingman");
</script>

<?php
include("footer.php");
?>
