async function getAttractions(city) {
    console.log(`Fetching attractions for ${city} using Overpass API...`);

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

    const url = "https://overpass-api.de/api/interpreter?data=" + encodeURIComponent(query);

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
        
        const data = await response.json();

        if (data.elements.length === 0) {
            document.getElementById("attractions-info").textContent = "No attractions found.";
            return;
        }

        let attractionsHTML = data.elements
            .slice(0, 20)
            .map(attraction => `<p>${attraction.tags.name || "Unnamed Attraction"}</p>`)
            .join("");

        document.getElementById("attractions-info").innerHTML = attractionsHTML;
    } catch (error) {
        document.getElementById("attractions-info").textContent = "Unable to load attractions.";
        console.error("Overpass API Error:", error);
    }
}


