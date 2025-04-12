// Asynchronous function to fetch current weather data for a given city using OpenWeatherMap API
async function getWeather(city) {
    // OpenWeatherMap API key
    const apiKey = "";
    
    // Construct the API URL with city name, imperial units (Fahrenheit), and API key
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=imperial&appid=${apiKey}`;

    try {
        // Send HTTP request and wait for the response
        const response = await fetch(url);

        // If response is not OK, throw an error with status info
        if (!response.ok) {
            throw new Error(`Error: ${response.status} - ${response.statusText}`);
        }

        // Parse the JSON response body
        const data = await response.json();

        // Display the weather description and temperature in the element with ID 'weather-info'
        document.getElementById("weather-info").textContent =
            `${data.weather[0].description}, ${data.main.temp}°F`;
    } catch (error) {
        // Handle and display error if fetch or parsing fails
        document.getElementById("weather-info").textContent = "Unable to load weather data.";
        console.error("Weather API Error:", error);
    }
}
