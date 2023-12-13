<?php 
   
    $Producttype= $_POST['Producttype'];
    $code = $_POST['code'];
    $merchantId = $_POST['merchantId'];
    $transactionId = $_POST['transactionId'];
    $amount = $_POST['amount'];
    $providerReferenceId = $_POST['providerReferenceId'];
    $checksum = $_POST['checksum'];
    
    writeToLog("Redirect Get for Reference Id : $providerReferenceId");
   
    if ($code === 'PAYMENT_SUCCESS') {
       
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
                $data[] = "ID: " . $row["Id"] . "<br>" .
                "Name: " . $row["Name"] . "<br>" .
                "Amt: " . $row["Amt"] . "<br>" .
                "MobNo: " . $row["MobNo"] . "<br>" .
                "EmailId: " . $row["EmailId"] . "<br>" .
                "Address: " . $row["Address"] . "<br>" .
                "Pincode: " . $row["Pincode"] . "<br>" .
                "State: " . $row["State"] . "<br>" .
                "GSTNo: " . $row["GSTNo"] . "<br>" .
                "Type: " . $row["Producttype"] . "<br>" .
                "CoupanCode: " . $row["CoupanCode"] . "<br>" .
                "MerTxnId: " . $row["MerTxnId"] . "<br>" .
                "MerUserId: " . $row["MerUserId"] . "<br>" .
                "TxnDate: " . $row["TxnDate"] . "<br>" .
                "PaymentType: " . $row["PaymentType"] . "<br>" .
                "PaymentStatus: " . $row["PaymentStatus"] . "<br>" .
                "StatusDesc: " . $row["StatusDesc"] . "<br>" .
                "RespMerId: " . $row["RespMerId"] . "<br>" .
                "TxnId: " . $row["TxnId"] . "<br>" .
                "UPIRefNo: " . $row["UPIRefNo"] . "<br>" .
                "NetbankSerTxnId: " . $row["NetbankSerTxnId"] . "<br>" .
                "VendorTxnId: " . $row["VendorTxnId"] . "<br>" . 
                "CardType: " . $row["CardType"] . "<br>" .
                "VendorAuthCode: " . $row["VendorAuthCode"] . "<br>" .
                "CardBankame: " . $row["CardBankame"] . "<br>" .
                "RespTime: " . $row["RespTime"] . "<br>";
                
                writeToLog($row["Producttype"]);
                if ($row["Producttype"] == "DELTADASH") {
                    
                 // Prepare the data for email
                    $email_data = implode("<br>", $data);
                    
                    $to = "nitesh.m@spidersoftwareindia.com, jr.mehta@kiranjadhav.com";
                    $subject = "Gokyo Payment Page";
                    $from = "info@gokyoanalytics.com";
                    $from_name = "Gokyo";
            
                    // Create email headers
                    
                    $headers = "From: $from_name <$from>\r\n";
                    $headers .= "Content-type: text/html; charset=utf-8\r\n";
            
                    $email_body = "$email_data";
                    try {
                        if (mail($to, $subject, $email_body, $headers)) {
                            echo "Email sent successfully. We will get back to you shortly.";
                            writeToLog("Mail Sent Successfully for $transactionId");
                        } else {
                            echo "Error sending email. Please try again later.";
                            writeToLog("Mail Sent failed for : $transactionId");
                        }
                    }
                    catch (Exception $e) {
                        echo "An error occurred: " . $e->getMessage();
                    }

                }
            }
            writeToLog($data);
            // Free the result set
            mysqli_free_result($result);
        } 
        else {
                echo "Error: " . mysqli_error($connection);
            }
        
        // Close the connection
        mysqli_close($connection);

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
        $logFile = $logFolder . "Redirect_" . $currentDate . ".txt";
        $separator = "--------------------------------------------------\n";
        file_put_contents($logFile, $logMessage . $separator, FILE_APPEND);
    }

?>