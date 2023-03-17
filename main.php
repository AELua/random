<?php
// Database connection variables
$servername = "sql212.epizy.com";
$username = "epiz_33800546";
$password = "XsB7iA0aPRc";
$dbname = "epiz_33800546_aeadmin";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process incoming POST request to add new player data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and decode the JSON payload from the request body
    $postData = json_decode(file_get_contents('php://input'), true);
    
    // Check for errors in decoding JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400); // Bad Request
        echo json_encode("Invalid request data");
        exit;
    }
    
    // Extract player data from decoded payload
    $newUserId = $postData["UserId"];
    
    // SQL query to add new player data
    $sql = "INSERT INTO players (UserId) VALUES ('$newUserId')";
    
    // Execute SQL query
    if ($conn->query($sql) === TRUE) {
        http_response_code(201); // Created
        echo json_encode("New player data added successfully");
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode("Error adding new player data: " . $conn->error);
    }
    exit;
}

// SQL query to retrieve data
$sql = "SELECT `UserId` FROM `players` WHERE 1";
$result = $conn->query($sql);

// Check for errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Output data as a JSON page
header('Content-Type: application/json');
if ($result->num_rows > 0) {
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = array("UserId" => (int)$row["UserId"]);
    }
    echo json_encode($data);
} else {
    echo json_encode("0 results");
}
?>
