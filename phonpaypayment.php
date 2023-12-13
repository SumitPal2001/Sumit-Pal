<?php
$data = array(
    'merchantId' => 'M10E5K33PUIN',
    'merchantTransactionId' => 'MT'. MerchantTransactionId(),
    'merchantUserId' => 'MUID'. generateRandomNumeric(),
    'amount' => $_POST['TxnAmount']* 100,
    'redirectUrl' => 'https://www.gokyoanalytics.com/redirect.php',
    'redirectMode' => 'POST',
    'callbackUrl' => 'https://www.gokyoanalytics.com/callback.php',
    'mobileNumber' => $_POST['MobNo'],
    'paymentInstrument' => 
    array(
        'type' => 'PAY_PAGE',
    )
);

$encode = json_encode($data);
writeToLog($encode);
$encoded = base64_encode($encode); 
$salt_key = "9ddaef4e-ff37-4aed-bbd6-562687e3e285";
$salt_index = 1;
$string = $encoded . "/pg/v1/pay" . $salt_key;
$sha256 = hash("sha256", $string);
$final_x_header = $sha256 . '###' . $salt_index;

$request_json = json_encode([
    "request" => $encoded
]);

writeToLog($request_json);

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.phonepe.com/apis/hermes/pg/v1/pay",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $request_json,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "X-VERIFY: " . $final_x_header,
        "accept: application/json"
    ],
]);



$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

writeToLog($response);


// Database configuration
$servername = "localhost";
$username = "db_contactform";
$password = "db_contactform";
$dbname = "web_database";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['Name']) &&
        isset($_POST['MobNo']) &&
        isset($_POST['EmailId']) &&
        isset($_POST['Address']) &&
        isset($_POST['Pincode']) &&
        isset($_POST['State']) &&
        isset($_POST['GSTNo']) &&
        isset($_POST['CouponCode']) &&
        isset($_POST['Producttype'])
    ) {
        // Data to insert
        $Name = $_POST['Name'];
        $Amt = $data['amount'] / 100;
        $MobNo = $data['mobileNumber'];
        $EmailId = $_POST['EmailId'];
        $Address = $_POST['Address'];
        $Pincode = $_POST['Pincode'];
        $State = $_POST['State'];
        $CouponCode = $_POST['CouponCode'];
        $GSTNo = $_POST['GSTNo'];
        $Producttype= $_POST['Producttype'];
        $merchantTransactionId = $data['merchantTransactionId'];
        $MerUserId = $data['merchantUserId'] ;
        date_default_timezone_set('Asia/Kolkata');
        $TxnDateTime = date("Y-m-d H:i:s");

        // SQL query to insert data
        $sql = "INSERT INTO db_deltadashpayment (Name, Amt, MobNo, EmailId, Address, Pincode, State, GSTNo, CoupanCode,Producttype, MerTxnId, MerUserId, TxnDate) 
        VALUES ('$Name', $Amt, '$MobNo', '$EmailId', '$Address', '$Pincode', '$State', '$GSTNo','$CouponCode','$Producttype', '$merchantTransactionId', '$MerUserId', '$TxnDateTime')";

        if ($conn->query($sql) === TRUE) {
            $dbresp =  "New record created successfully";
        } else {
            $dbresp =  "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();

if ($err) {
    echo "Error #:" . $err;
} else {
    $res = json_decode($response);

    if ($res->code == 'PAYMENT_INITIATED') {
        $redirectUrl = $res->data->instrumentResponse->redirectInfo->url;
        echo json_encode(['redirectUrl' => $redirectUrl, 'DBResp' => $dbresp]);
    } else {
        redirect('web/checkout');
    }
}

function MerchantTransactionId($length = 9) {
    $timestamp = time();  
    $randomNumber = mt_rand(10000000, 99999999);  // Random 8-digit number

    // Combine timestamp and random number
    $uniqueNumber = $timestamp . $randomNumber;

    // Ensure the generated number is exactly 8 digits
    $uniqueNumber = substr($uniqueNumber, 0, $length);

    return $uniqueNumber;
}

function generateRandomNumeric($length = 5) {
    $min = pow(10, $length - 1);
    $max = pow(10, $length) - 1;
    return mt_rand($min, $max);
}

function writeToLog($message) {
    $logFolder = "PaymentLog/";
    if (!file_exists($logFolder)) {
        mkdir($logFolder, 0777, true); // You might adjust permissions based on your needs
    }
    else{

        if (!file_exists($logFolder)) {
            mkdir($logFolder, 0777, true); 
        }
        
        date_default_timezone_set('Asia/Kolkata'); 
        $currentDate = date("Y-m-d");
    
        $logMessage = "[" . date("Y-m-d H:i:s") . "] " . $message . "\n";
        $logFile = $logFolder . "RequestLog_" . $currentDate . ".txt";
        $separator = "--------------------------------------------------\n";
        file_put_contents($logFile, $logMessage . $separator, FILE_APPEND);
    }
       
}

?>


