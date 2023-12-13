<?php

$response = $_POST['g-recaptcha-response'];
$secretKey = '6Lc3bsooAAAAAO5_KBFgR3kzOjWqJxOhvgG2TNXZ';
$remoteIp = $_SERVER['REMOTE_ADDR'];


$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
  'secret' => $secretKey,
  'response' => $response,
  'remoteip' => $remoteIp
);

$options = array(
  'http' => array(
    'header'  => 'Content-type: application/x-www-form-urlencoded',
    'method'  => 'POST',
    'content' => http_build_query($data)
  )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$responseData = json_decode($result);

//Database Insert
//opening a connection


$host = "localhost";
$user = "db_contactform";
$password = "db_contactform";
$dbname= "web_database";


//opening a connection

$conn = mysqli_connect($host,$user,$password,$dbname);
if (!$conn)
  {
  die('Could not connect: ' . mysqli_error($conn));
  }
else{
  echo '';
}

//selecting to the database
mysqli_select_db($conn,$dbname); 

$name = $_POST['name'];
$email = $_POST['email'];
$number = $_POST['number'];
$city = $_POST['city'];
$Control = $_POST['Control'];//state
$message = $_POST['message'];


if(isset($_POST['MailSubject'])){
	$MailSubject = $Reference." - ".$Control;
}

date_default_timezone_set("Asia/Kolkata");
$currenttime = time();
$t = date("Y-m-d H:i:s", $currenttime);

$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
}

if (empty($email)) {
    $errors[] = "Email is required.";
}

if (empty($number)) {
    $errors[] = "Number is required.";
}

if (empty($city)) {
    $errors[] = "City is required.";
}

if (empty($Control)) {
    $errors[] = "Control is required.";
}


// If there are validation errors, display them
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
} else if($responseData->success){
       $sql="INSERT INTO contact(name,email,number,city,sources,product,message,reference,date)
          VALUES('$name','$email','$number','$Control','$city','','$message','','$t')";
        
        if ($conn->query($sql) === TRUE) {
          $resp = "Thank you for contacting us.  Our team shall get back in touch with you soon";
        } else {
          $resp = "Error: " . $sql . "<br>" . $conn->error;
        }
}
else{
   
 echo "Invalid Recaptcha";

}

//inserting data into database

$data = "Name: $name\nEmail: $email\nPhone: $number\nControl: $Control\nCity: $city  \nMessage: $message \nDate: $t";

$response = array(
  'alertmsg' => $resp,
  'reqbody' => $data
);
echo json_encode($response);

//closing the connection
mysqli_close($conn);




?>