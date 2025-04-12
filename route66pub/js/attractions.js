// Asynchronous function to fetch tourist attractions for a given city using the Overpass API
async function getAttractions(city) {
    console.log(`Fetching attractions for ${city} using Overpass API...`);

    // Overpass QL query to find nodes in the specified city area that are:
    // - tourist attractions
    // - historic monuments
    // - museums
    const query = `
        [out:json];
        area[name="${city}"]->.searchArea;
        (
            node["tourism"="attraction"](area.searchArea);
            node["historic"="monument"](area.searchArea);
            node["tourism"="museum"](area.searchArea);
        );
        out body;
    `;

    // Construct the URL with the encoded query
    const url = "https://overpass-api.de/api/interpreter?data=" + encodeURIComponent(query);

    try {
        // Send request to Overpass API and wait for the response
        const response = await fetch(url);

        // Throw an error if the response is not OK (e.g., 404 or 500)
        if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
        
        // Parse the JSON response
        const data = await response.json();

        // If no attractions are found, update the UI with a message and exit the function
        if (data.elements.length === 0) {
            document.getElementById("attractions-info").textContent = "No attractions found.";
            return;
        }

        // Extract up to 20 attractions and convert them into HTML <p> elements
        // Use a fallback label if the attraction has no name tag
        let attractionsHTML = data.elements
            .slice(0, 20)
            .map(attraction => `<p>${attraction.tags.name || "Unnamed Attraction"}</p>`)
            .join("");

        // Display the generated HTML in the element with ID 'attractions-info'
        document.getElementById("attractions-info").innerHTML = attractionsHTML;
    } catch (error) {
        // Handle errors (e.g., network issues, JSON parsing errors)
        document.getElementById("attractions-info").textContent = "Unable to load attractions.";
        console.error("Overpass API Error:", error);
    }
}


