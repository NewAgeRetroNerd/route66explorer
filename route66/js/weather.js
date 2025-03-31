async function getWeather(city) {
    const apiKey = "21ae6f1c84aa3e6d6c42b1ac6e309425";
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=imperial&appid=${apiKey}`;

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`Error: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();

        document.getElementById("weather-info").textContent =
            `${data.weather[0].description}, ${data.main.temp}°F`;
    } catch (error) {
        document.getElementById("weather-info").textContent = "Unable to load weather data.";
        console.error("Weather API Error:", error);
    }
}