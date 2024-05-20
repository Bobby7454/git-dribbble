<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the "avatar" file was uploaded successfully
    if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] === UPLOAD_ERR_OK) {
        // Database connection
        $servername = "localhost"; // Change to your database server name
        $username = "root"; // Change to your database username
        $password = ""; // Change to your database password
        $dbname = "dribbble"; // Change to your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Read the image file as binary data
        $avatar = file_get_contents($_FILES["avatar"]["tmp_name"]);

        // Get location data (sanitizing input)
        $location = isset($_POST["location"]) ? htmlspecialchars($_POST["location"]) : "";

        // Prepare SQL statement to insert data into the database
        $sql = "INSERT INTO user_data (avatar, location) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $avatar, $location);

        // Execute the statement
        if ($stmt->execute() === TRUE) {
            $response = array("success" => true, "message" => "New record created successfully");
        } else {
            $response = array("success" => false, "message" => "Error: " . $stmt->error);
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Handle file upload error
        $uploadErrorCode = isset($_FILES["avatar"]["error"]) ? $_FILES["avatar"]["error"] : "Unknown";
        $response = array("success" => false, "message" => "File upload error: " . $uploadErrorCode);
    }
} else {
    // If request method is not POST
    $response = array("success" => false, "message" => "Invalid request method");
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
