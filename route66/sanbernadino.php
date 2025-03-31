<?php
include("header.php");
include("navigation.php");
?>

<main>
    <section class="city-header">
        <h2>Discover San Bernadino, CA</h2>
        <img class="city-img" src="images/sanbernadino.jpg" alt="San Bernadino">
        <p>Find adventure and history in the vibrant city of San Bernardino, California!</p>
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
    getWeather("San Bernadino");
    getEvents("San Bernadino");
    getAttractions("San Bernadino");
</script>

<?php
include("footer.php");
?>
