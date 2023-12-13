<?php 
   
    $Producttype= $_POST['Producttype'];
    $code = $_POST['code'];
    $merchantId = $_POST['merchantId'];
    $transactionId = $_POST['transactionId'];
    $amount = $_POST['amount'];
    $providerReferenceId = $_POST['providerReferenceId'];
    $checksum = $_POST['checksum'];
    
    writeToLog($code | $merchantId | $transactionId | $amount | $providerReferenceId | $checksum);
   
    if ($code === 'PAYMENT_SUCCESS') {
        if ($Producttype === 'DELTADASH') {
            
            $hostname = "localhost";
            $username = "db_contactform";
            $password = "db_contactform";
            $database = "web_database";
            // Create a connection
            $connection = mysqli_connect($hostname, $username, $password, $database);
            
            // Check connection
            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }
            // SQL query
            
                
            $query = "SELECT * FROM db_deltadashpayment WHERE `PaymentStatus` = 'PAYMENT_SUCCESS' AND `MerTxnId` = '$transactionId' AND `Producttype` = 'DELTADASH'";
            
            // Execute the query
            $result = mysqli_query($connection, $query);
            
            // Check if the query was successful
            if ($result) {
                // Create an empty array to store fetched data
                $data = array();
            
                // Fetch data and store it in the array
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = "ID: " . $row["Id"] . "\n" .
                    "Name: " . $row["Name"] . "\n" .
                    "Amt: " . $row["Amt"] . "\n" .
                    "MobNo: " . $row["MobNo"] . "\n" .
                    "EmailId: " . $row["EmailId"] . "\n" .
                    "Address: " . $row["Address"] . "\n" .
                    "Pincode: " . $row["Pincode"] . "\n" .
                    "State: " . $row["State"] . "\n" .
                    "GSTNo: " . $row["GSTNo"] . "\n" .
                    "CoupanCode: " . $row["CoupanCode"] . "\n" .
                    "MerTxnId: " . $row["MerTxnId"] . "\n" .
                    "MerUserId: " . $row["MerUserId"] . "\n" .
                    "TxnDate: " . $row["TxnDate"] . "\n" .
                    "PaymentType: " . $row["PaymentType"] . "\n" .
                    "PaymentStatus: " . $row["PaymentStatus"] . "\n" .
                    "StatusDesc: " . $row["StatusDesc"] . "\n" .
                    "RespMerId: " . $row["RespMerId"] . "\n" .
                    "TxnId: " . $row["TxnId"] . "\n" .
                    "UPIRefNo: " . $row["UPIRefNo"] . "\n" .
                    "NetbankSerTxnId: " . $row["NetbankSerTxnId"] . "\n" .
                    "VendorTxnId: " . $row["VendorTxnId"] . "\n" .
                    "CardType: " . $row["CardType"] . "\n" .
                    "VendorAuthCode: " . $row["VendorAuthCode"] . "\n" .
                    "CardBankame: " . $row["CardBankame"] . "\n" .
                    "RespTime: " . $row["RespTime"] . "\n\n";
                }
                writeToLog($data);
                // Free the result set
                mysqli_free_result($result);
            } else {
                echo "Error: " . mysqli_error($connection);
            }
            
            // Close the connection
            mysqli_close($connection);
            
            // Prepare the data for email
            $email_data = implode("<br>", $data);
            
            $to = "sumit.p@spidersoftwareindia.com";
            $subject = "Gokyo Payment Page";
            $from = "info@gokyoanalytics.com";
            $from_name = "Gokyo";
    
            // Create email headers
            
            $headers = "From: $from_name <$from>\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
    
    
            
            // Create the email message
            $email_body = "$email_data";
    
            try {
                if (mail($to, $subject, $email_body, $headers)) {
                    echo "Email sent successfully. We will get back to you shortly.";
                } else {
                    echo "Error sending email. Please try again later.";
                }
            }
            catch (Exception $e) {
                echo "An error occurred: " . $e->getMessage();
            }
       
        }
        header("Location: thankyou.php?&transactionId=$transactionId&referenceId=$providerReferenceId");
        exit(); 
        
    } else {
        header("Location: paymentfailed.php?transactionId=$transactionId&referenceId=$providerReferenceId");
        exit();
    }

    
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