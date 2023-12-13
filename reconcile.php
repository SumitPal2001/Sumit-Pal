<?php

include 'callback.php';

writeToLog("test");
$servername = "localhost";
$username = "db_contactform";
$password = "db_contactform";
$dbname = "web_database";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM db_deltadashpayment WHERE PaymentStatus = 'PAYMENT_PENDING' OR PaymentStatus = 'INTERNAL_SERVER_ERROR' order by TxnDate Desc";
$result = $conn->query($query);

if ($result) {
    $pendingTransactions = $result->fetch_all(MYSQLI_ASSOC);
    writeToLog(json_encode($pendingTransactions));
} else {
    echo "Error executing query: " . $conn->error;
}

$conn->close();

foreach ($pendingTransactions as $transaction) {
    $requestData = array(
        'merchantId' => 'M10E5K33PUIN',
        'transactionId' => $transaction['MerTxnId']
    );
    
    $apiResponse = TransactionStatusAPI($requestData);
    writeToLog($apiResponse);
    //UpdateCallbackReconcileResp($apiResponse);
}


function TransactionStatusAPI($input) {
    writeToLog("1");
    $saltKey = "9ddaef4e-ff37-4aed-bbd6-562687e3e285";
    $saltIndex = 1;

    $finalXHeader = hash('sha256', '/pg/v1/status/' . $input['merchantId'] . '/' . $input['transactionId'] . $saltKey) . '###' . $saltIndex;

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.phonepe.com/apis/hermes/pg/v1/status/' . $input['merchantId'] . '/' . $input['transactionId'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "X-VERIFY: " . $finalXHeader,
            "X-MERCHANT-ID: " . $input['merchantId'],

        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    UpdateCallbackReconcileResp(json_decode($response));

    var_dump(json_decode($response));
}



function writeToLog($message) {
    $logFolder = "PaymentLog/";
    if (!file_exists($logFolder)) {
        mkdir($logFolder, 0777, true); 
    }
    
    date_default_timezone_set('Asia/Kolkata'); 

    $currentDate = date("Y-m-d");
    $logMessage = "[" . date("Y-m-d H:i:s") . "] " . $message . "\n";
    $logFile = $logFolder . "Reconcile_" . $currentDate . ".txt";
    $separator = "--------------------------------------------------\n";
    file_put_contents($logFile, $logMessage . $separator, FILE_APPEND);
}
?>