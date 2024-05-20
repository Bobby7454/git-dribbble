<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $servername = "localhost"; // Replace with your database server name
    $username = "root"; // Replace with your database username
    $password = ""; // Replace with your database password
    $dbname = "dribbble"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO user_data (user_id, selection_ids, selection_descriptions) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // Get user ID
    $user_id = $id; // Replace with the actual user ID

    // Variables to store selected IDs and descriptions
    $selected_ids = [];
    $selected_descriptions = [];

    // Check each checkbox individually
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_POST['selection_id_' . $i])) {
            // Get selection ID and description
            $selection_id = $_POST['selection_id_' . $i];
            $selection_description = $_POST['selection_description_' . $i];
            
            // Add to arrays
            $selected_ids[] = $selection_id;
            $selected_descriptions[] = $selection_description;
        }
    }

    // Convert arrays to comma-separated strings
    $selection_ids_str = implode(",", $selected_ids);
    $selection_descriptions_str = implode(",", $selected_descriptions);

    // Bind parameters and execute statement
    $stmt->bind_param("iss", $user_id, $selection_ids_str, $selection_descriptions_str);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // Close statement
    $stmt->close();

    // Close database connection
    $conn->close();

    // Redirect to selection.html after successful form submission
    header("Location: selection.html");
    exit(); // Make sure to exit after redirection
}
?>
