<?php
    
    
    
    $callbackData = file_get_contents('php://input');
    
    date_default_timezone_set('Asia/Kolkata');
    $CallbackTime = date("Y-m-d H:i:s");
    
    $decodedCallbackData = json_decode($callbackData, true);
    
    $response = $decodedCallbackData['response'];
    
    $payload  = base64_decode($response);
    writeToLog($payload);

    $data = json_decode($payload, true);

    $code = $data['code']; 
    $statusflag = $data['success'];
    $message = $data['message'];
    $PaymentResp = $data['data'];

    if ($statusflag == true) {
        $VendorResp = $PaymentResp['paymentInstrument'];
        $MerchantId = $PaymentResp['merchantId'];
        $MerTxnId = $PaymentResp['merchantTransactionId'];
        $TxnId = $PaymentResp['transactionId'];
        $PaymentType = $VendorResp['type'];
        if($PaymentType == "UPI"){
            $UniqueRefNo = $VendorResp['utr']; 
        }
        else if($PaymentType == "NETBANKING"){
            $PaymentTxnId = $VendorResp['pgTransactionId'];
            $NetbankServiceTxnId = $VendorResp['pgServiceTransactionId']; 
        } 
        else if($PaymentType == "CARD"){
            $cardType = $VendorResp['cardType'];
            $PaymentTxnId = $VendorResp['pgTransactionId'];
            $PgAuthorizationCode = $VendorResp['pgAuthorizationCode']; 
            $CardBankame = $VendorResp['bankId'];
        }
        
        // if($code == "PAYMENT_SUCCESS"){
        //     writeToLog("mailer function calling");
        //     sendEmailWithTransactionID($TxnId);
        // }
    }
    else
    {
        $VendorResp = $PaymentResp['paymentInstrument'];
        $MerchantId = $PaymentResp['merchantId'];
        $MerTxnId = $PaymentResp['merchantTransactionId'];
        $TxnId = $PaymentResp['transactionId'];
        $PaymentType = $VendorResp['type'];    
    } 
    

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
        $sql = "UPDATE db_deltadashpayment SET  PaymentType='$PaymentType',PaymentStatus = '$code', StatusDesc = '$message', 
        RespMerId='$MerchantId', TxnId='$TxnId', UPIRefNo = '$UniqueRefNo', VendorTxnId = '$PaymentTxnId', NetbankSerTxnId ='$NetbankServiceTxnId',
        CardType='$cardType', VendorAuthCode='$PgAuthorizationCode', CardBankame='$CardBankame', RespTime='$CallbackTime'  
        WHERE MerTxnId='$MerTxnId'";
        writeToLog($sql);

        if ($conn->query($sql) === TRUE) {
            $dbresp = "Record updated successfully";
        } else {
            $dbresp = "Error updating record: " . $conn->error;
        }   
}

// Close the database connection
$conn->close();


function writeToLog($message) {
    $logFolder = "PaymentLog/";
    if (!file_exists($logFolder)) {
        mkdir($logFolder, 0777, true); 
    }
    
    date_default_timezone_set('Asia/Kolkata'); 

    $currentDate = date("Y-m-d");
    $logMessage = "[" . date("Y-m-d H:i:s") . "] " . $message . "\n";
    $logFile = $logFolder . "Callback_" . $currentDate . ".txt";
    $separator = "--------------------------------------------------\n";
    file_put_contents($logFile, $logMessage . $separator, FILE_APPEND);
}

?>