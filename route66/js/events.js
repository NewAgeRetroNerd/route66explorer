async function getEvents(city) {
    const apiKey = "L8WCftHoWTV4uj5GYFoKtcFn1baiKffn";
    const url = `https://app.ticketmaster.com/discovery/v2/events.json?city=${city}&apikey=${apiKey}`;

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

        const data = await response.json();

        if (data._embedded && data._embedded.events) {
            let eventsHTML = data._embedded.events
                .slice(0, 20)
                .map(event => `<p><strong>${event.name}</strong> - ${event.dates.start.localDate}</p>`)
                .join("");

            document.getElementById("events-info").innerHTML = eventsHTML;
        } else {
            document.getElementById("events-info").textContent = "No events found.";
        }
    } catch (error) {
        document.getElementById("events-info").textContent = "Unable to load events.";
        console.error("Events API Error:", error);
    }
}