<?php
//Connect to the database
$servername = "localhost";
$username = "db_contactform";
$password = "db_contactform";
$dbname = "web_database";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

$couponCode = $_POST['couponCode']; // Get coupon code from the AJAX request

// Prepare and execute the query
$query = "SELECT * FROM deltacoupan WHERE code = :couponCode";
$stmt = $conn->prepare($query);
$stmt->bindParam(':couponCode', $couponCode);
$stmt->execute();

// Fetch the coupon details
$couponDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Close the database connection
$conn = null;

// Check if coupon details were fetched
if ($couponDetails) {
    // Check if the coupon is still valid based on expiry date
    $currentDate = date("Y-m-d");
    if ($couponDetails['expiry_date'] >= $currentDate) {
        $response = array(
            "valid" => true,
            "discount_percentage" => $couponDetails['discount_percentage']
        );
    } else {
        $response = array(
            "valid" => false,
            "message" => "Coupon has expired"
        );
    }
} else {
    $response = array(
        "valid" => false,
        "message" => "Coupon not found"
    );
}

echo json_encode($response);
?>
