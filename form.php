<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to your MySQL database
    $servername = "localhost";
    $username = "root"; // Replace with your actual username
    $password = ""; // Replace with your actual password
    $dbname = "dribbble";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        // Connection failed, return error response
        echo json_encode(array("error" => "Connection failed: " . $conn->connect_error));
        exit();
    }

    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $user = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';

    // Prepare SQL query for username check
    $check_username_sql = "SELECT * FROM user_data WHERE username = ?";
    $check_username_stmt = $conn->prepare($check_username_sql);
    $check_username_stmt->bind_param("s", $user);
    $check_username_stmt->execute();
    $result = $check_username_stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists, return error response
        echo json_encode(array("error" => "Username already exists"));
        exit();
    } else {
        // Proceed with data insertion
        $insert_sql = "INSERT INTO user_data (name, username, email, password) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssss", $name, $user, $email, $pass);
        $pass = password_hash($pass, PASSWORD_DEFAULT);

        if ($insert_stmt->execute()) {
            // Data inserted successfully, return success response
            echo json_encode(array("success" => true));
            exit();
        } else {
            // Error in data insertion, return error response
            echo json_encode(array("error" => "Error in data insertion"));
            exit();
        }
    }

    // Close statement and connection
    $check_username_stmt->close();
    $conn->close();
}
?>
