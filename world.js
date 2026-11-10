
document.addEventListener("DOMContentLoaded", function () {

    // Get references to the HTML elements
    const countryInput = document.getElementById("country");
    const lookupButton = document.getElementById("lookup");
    const citiesButton = document.getElementById("lookupCities");
    const resultDiv = document.getElementById("result");

    // Function to perform an AJAX request
    function searchWorld(lookupType) {

        // Get the country entered by the user
        const country = countryInput.value.trim();

        // Create the query string
        const params = new URLSearchParams();

        params.append("country", country);
        params.append("lookup", lookupType);

        // Construct the request URL
        const url = "world.php?" + params.toString();

        // Display a loading message
        resultDiv.innerHTML = "<p>Loading...</p>";

        // Send the AJAX request
        fetch(url)
            .then(function (response) {

                if (!response.ok) {
                    throw new Error("Request failed");
                }

                return response.text();
            })
            .then(function (data) {

                // Display the PHP response
                resultDiv.innerHTML = data;

            })
            .catch(function (error) {

                // Handle errors
                resultDiv.innerHTML =
                    "<p>Unable to retrieve data.</p>";

                console.error(error);
            });
    }

    // Listen for clicks on the country lookup button
    lookupButton.addEventListener("click", function () {

        searchWorld("countries");

    });

    // Listen for clicks on the cities lookup button
    citiesButton.addEventListener("click", function () {

        searchWorld("cities");

    });

});
