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

// Retrieve form data
$carType = $_POST['carType'];
$name = $_POST['name'];
$phone1 = $_POST['phone1'];
$phone2 = $_POST['phone2'];
$ssn = $_POST['ssn'];
$paymentMethod = $_POST['paymentMethod'];
$govFrom = $_POST['govFrom'];
$locationFrom = $_POST['locationFrom'];
$zipFrom = $_POST['zipFrom'];
$govTo = $_POST['govTo'];
$locationTo = $_POST['locationTo'];
$zipTo = $_POST['zipTo'];
$promoCode = $_POST['promoCode'];
$instructions = $_POST['instructions'];
$distanceInKm = $_POST['distanceInKm'];

// Function to calculate the total amount based on the promo code, distance, and car type
function calculateTotalAmount($promoCode, $distanceInKm, $carType, $additionalValue) {
    // Default price per kilometer for different car types
    $pricesPerKm = [
        'Box Truck' => 1.5,
        'Quarter Truck' => 1.3,
        'Semi Truck' => 1.8,
        'Van' => 1.1
    ];

    // Default additional values for different car types (if any)
    $additionalValues = [
        'Box Truck' => 10,
        'Quarter Truck' => 8,
        'Semi Truck' => 12,
        'Van' => 6
    ];

    // Get the price per kilometer for the selected car type
    $pricePerKm = isset($pricesPerKm[$carType]) ? $pricesPerKm[$carType] : 1.3;
    
    // Get the additional value for the selected car type
    $additionalValue = isset($additionalValues[$carType]) ? $additionalValues[$carType] : 0;

    // Calculate the total amount
    $totalAmount = $distanceInKm * $pricePerKm + $additionalValue;

    // Check if the promo code is "0000" or "1111" and set the total amount accordingly
    if ($promoCode === "0000" || $promoCode === "1111") {
        $totalAmount = 0; // Apply the promo code discount
    }

    return $totalAmount;
}

// Calculate the total amount
$totalAmount = calculateTotalAmount($promoCode, $distanceInKm, $carType, $additionalValue);

// Insert data into database
$sql = "INSERT INTO `order` (carType, name, phone1, phone2, ssn, paymentMethod, govFrom, locationFrom, zipFrom, govTo, locationTo, zipTo, promoCode, instructions, distanceInKm, totalAmount)
        VALUES ('$carType', '$name', '$phone1', '$phone2', '$ssn', '$paymentMethod', '$govFrom', '$locationFrom', '$zipFrom', '$govTo', '$locationTo', '$zipTo', '$promoCode', '$instructions', '$distanceInKm', '$totalAmount')";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id; // Get the ID of the last inserted record
    $params = http_build_query(array(
        'id' => $last_id,
        'carType' => $carType,
        'name' => $name,
        'phone1' => $phone1,
        'phone2' => $phone2,
        'ssn' => $ssn,
        'paymentMethod' => $paymentMethod,
        'govFrom' => $govFrom,
        'locationFrom' => $locationFrom,
        'zipFrom' => $zipFrom,
        'govTo' => $govTo,
        'locationTo' => $locationTo,
        'zipTo' => $zipTo,
        'promoCode' => $promoCode,
        'instructions' => $instructions,
        'distanceInKm' => $distanceInKm,
        'totalAmount' => $totalAmount
    ));
    header("Location: invoice.html?$params");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
