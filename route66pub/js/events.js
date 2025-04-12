// Asynchronous function to fetch events for a given city using the Ticketmaster Discovery API
async function getEvents(city) {
    // Ticketmaster API key (public API key for accessing event data)
    const apiKey = "";
    
    // Construct the API URL with the specified city and API key
    const url = `https://app.ticketmaster.com/discovery/v2/events.json?city=${city}&apikey=${apiKey}`;

    try {
        // Send request to the Ticketmaster API and wait for the response
        const response = await fetch(url);

        // Throw an error if the response status is not OK
        if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

        // Parse the JSON response from the API
        const data = await response.json();

        // Check if the response contains events data
        if (data._embedded && data._embedded.events) {
            // Extract up to 20 events and format them into HTML paragraphs with name and date
            let eventsHTML = data._embedded.events
                .slice(0, 20)
                .map(event => `<p><strong>${event.name}</strong> - ${event.dates.start.localDate}</p>`)
                .join("");

            // Inject the generated HTML into the element with ID 'events-info'
            document.getElementById("events-info").innerHTML = eventsHTML;
        } else {
            // If no events are found, display a fallback message
            document.getElementById("events-info").textContent = "No events found.";
        }
    } catch (error) {
        // Handle any errors (e.g., network failure, bad API response)
        document.getElementById("events-info").textContent = "Unable to load events.";
        console.error("Events API Error:", error);
    }
}
