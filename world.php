
<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    // Connect to the world database
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Enable PDO error reporting
    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Get the country search parameter
    $country = $_GET['country'] ?? '';

    // Get the lookup parameter
    $lookup = $_GET['lookup'] ?? 'countries';

    // Prepare the search pattern
    $search = "%" . $country . "%";

    // Determine which information to retrieve
    if ($lookup === 'cities') {

        // Retrieve cities belonging to matching countries
        $sql = "
            SELECT cities.name, cities.district,
                   cities.population
            FROM cities
            JOIN countries
            ON cities.country_code = countries.code
            WHERE countries.name LIKE ?
            ORDER BY cities.name
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$search]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display cities in a table
        if (count($results) > 0) {

            echo "<table>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>District</th>";
            echo "<th>Population</th>";
            echo "</tr>";

            foreach ($results as $row) {
                echo "<tr>";

                echo "<td>" .
                    htmlspecialchars($row['name']) .
                    "</td>";

                echo "<td>" .
                    htmlspecialchars($row['district']) .
                    "</td>";

                echo "<td>" .
                    number_format($row['population']) .
                    "</td>";

                echo "</tr>";
            }

            echo "</table>";

        } else {
            echo "<p>No cities found.</p>";
        }

    } else {

        // Retrieve matching countries
        $sql = "
            SELECT name, continent,
                   independence_year, head_of_state
            FROM countries
            WHERE name LIKE ?
            ORDER BY name
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$search]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display countries in a table
        if (count($results) > 0) {

            echo "<table>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Continent</th>";
            echo "<th>Independence</th>";
            echo "<th>Head of State</th>";
            echo "</tr>";

            foreach ($results as $row) {
                echo "<tr>";

                echo "<td>" .
                    htmlspecialchars($row['name']) .
                    "</td>";

                echo "<td>" .
                    htmlspecialchars($row['continent']) .
                    "</td>";

                echo "<td>" .
                    htmlspecialchars(
                        $row['independence_year'] ?? ''
                    ) .
                    "</td>";

                echo "<td>" .
                    htmlspecialchars(
                        $row['head_of_state'] ?? ''
                    ) .
                    "</td>";

                echo "</tr>";
            }

            echo "</table>";

        } else {
            echo "<p>No countries found.</p>";
        }
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " .
        htmlspecialchars($e->getMessage());
}
?>
