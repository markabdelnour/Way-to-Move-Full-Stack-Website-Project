<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "newdatabase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders from the database
$sql = "SELECT id, carType, name, phone1, phone2, govFrom, locationFrom, zipFrom, govTo, locationTo, zipTo, totalAmount FROM `order`";
$result = $conn->query($sql);

$orders = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    $orders[] = array('error' => 'No orders found');
}

$conn->close();

// Output JSON
header('Content-Type: application/json');
echo json_encode($orders);
?>
