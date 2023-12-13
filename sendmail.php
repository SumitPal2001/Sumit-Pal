<?php 

$to = "nitesh.m@spidersoftwareindia.com, nikunj@spidersoftwareindia.com";
$subject = "New Enquiry from your Contact Page";

// Set the headers
$headers = "From: Contact Page <nikunj@spidersoftwareindia.com>\r\n";
$headers .= "Reply-To: " . $_POST["email"] . "\r\n";

// Create the email message
$email_body =  "Gokyo Enquiry ". "\n".
    "" . $_POST["name"] . "\n" .
    "" . $_POST["email"] . "\n" .
    "" . $_POST["number"] . "\n" .
    "" . $_POST["city"] . "\n" .
    "" . $_POST["Control"] . "\n" .
    "" . $_POST["message"];

try {
    // Send the email
    if (mail($to, $subject, $email_body, $headers)) {
        echo "We will get back to you shortly";
    } else {
        echo "Error sending email. Please try again later.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
