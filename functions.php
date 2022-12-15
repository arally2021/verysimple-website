<?php

include('connect.php');
include('partial/header');

//function for sending email via SMTP using CURL.
//do not modify function variable as they read methods for reading the values.

function read_cb($ch, $email, $length) { 

  $firstname = $_POST['firstname'];
  $useremail = $_POST['email_ad'];
  

$email = fopen('php://temp', 'r+');
$str = "From: noreply@gmail.com\r\n";
$str .= "To:" . $email_ad. "r\n";
$str .= "Date: " . date('r') . "\r\n";
$str .= "Subject: Great Dovic Global Services Registration\r\n";
$str .= "\r\n";
// $str .= "This is the body of the email. \r\n";
$str .= "Thank you for Contacting us." .$firstname."\r\n";
$str .= "Please visit https://localhost/ayiti/bawahala/login.php?r=232443' to sigin in.\r\n";
$str .= "Your username name is " .$email_ad. "\r\n";

fwrite($email, $str);
rewind($email);

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt_array($ch, [
    CURLOPT_URL => 'smtps://smtp.gmail.com:465',
    CURLOPT_MAIL_FROM => 'noreply@gmail.com',
    CURLOPT_MAIL_RCPT => [$email_ad],
    //deactivated
    CURLOPT_USERNAME => 'Email',
    CURLOPT_PASSWORD => 'your password',
    
    CURLOPT_USE_SSL => CURLUSESSL_ALL,
    // CURLOPT_READFUNCTION => 'read_cb',
    CURLOPT_INFILE => $email,
    CURLOPT_UPLOAD => true,
    CURLOPT_VERBOSE => true,
]);


if (curl_exec($ch)){
echo "Thank you for Contacting GGS Great Dovic Global Services.Email has been sent to " .$email_ad;

echo "<br> <br> <hr>";
// header('Location: login.php?r=232443');
exit;
}else
{
      echo curl_errno($ch) . ' = ' . curl_strerror(curl_errno($ch)) . PHP_EOL;
  }

// $send = curl_exec($ch);

// // if ($send === false) {
// //     echo curl_errno($ch) . ' = ' . curl_strerror(curl_errno($ch)) . PHP_EOL;
// // }


curl_close($ch);
fclose($email);

return fread($email, $length);
}


//check for special characters in name inputs
function char_check($str)
{$validate = preg_match("/^[a-zA-Z]*$/",$str);
    return $validate;
}

//trim special characters before submitting to DB 
function checkspecialchar($str)
{ $replace = str_replace(array("@", "#", "(", ")", "*", "?", "/","$", "^", ".", "+","*"), "" ,$str);
    return $replace;
}

//Validate telephone number lenght and digits
function validate_phone($str){
  $validPhone = preg_match('/^[0-9ampp
  ]{11}+$/', $str);
  
   return $validPhone;
   
  }
  
  function validate_email(){
    $email_ad = filter_var($email_ad);
    return $email_ad;
  }


  //Query DB-Select * with id ONLY
function checklogin($conn){  
if (isset($_SESSION ['user_id'])){

  $id = $_SESSION ['user_id'];
  $user_details_sql = "SELECT * FROM `bahUsers` WHERE users_id = '$id' limit 1";
  
  $user_details_result = mysqli_query($conn, $user_details_sql);
  if ($user_details_result && mysqli_num_rows($user_details_result) > 0){

    $user_data = mysqli_fetch_assoc($user_details_result);
    return $user_data;
  }
}
}


//Query DB-Select * with firstname ONLY
function selectAll($conn){
  $user = $_SESSION ['firstname'];
  $user_details_sql = "SELECT * FROM `bahUsers` WHERE `firstname`= '$user'";
  
  $user_details_result = mysqli_query($conn, $user_details_sql);
  if ($user_details_result && mysqli_num_rows($user_details_result) > 0){

    $userinfo = mysqli_fetch_assoc($user_details_result);
    return $userinfo;
  }
}

//ceck password match and combination
function Regex($password){

  $uppercase = preg_match('@[A-Z]@', $password);
  $lowercase = preg_match('@[a-z]@', $password);
  $number    = preg_match('@[0-9]@', $password);
  $specialChars = preg_match('@[^\w]@', $password);
  $passlen1 = (strlen($password) <= 12);
  $passlen2 = (strlen($password) >= 8);
   
  return $uppercase && $lowercase && $number && $specialChars &&  $passlen2 && $passlen1;
  
  }




  //Query DB-Insert
  function insertDB ($conn){
  if (isset($_POST['submit'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
   

    $user_details_sql = "INSERT into bahUsers (`firstname`, `lastname`, `phone`, `email`, `passWord`, `status`) values ('" . $firstname . "','" .$lastname . "', '" . $phone . "','" . $email . "','" . md5($password) . "', 'enabled')"; 
    $user_details_result = mysqli_query($conn, $user_details_sql);

  }
  return $user_details_result;
}


// Query DB-Update
function updateDB ($conn){

  if (isset($_SESSION['firstname'])) {
  $changeID = $_SESSION['user'];
  $newpassW = $_POST['newpassword'];
   

  $user_details_sql = "UPDATE bahUsers SET `passWord`= '" . md5($newpassW) . "' WHERE users_id = '$changeID' ";
  $user_details_result = mysqli_query($conn, $user_details_sql);
}
  return $user_details_result;
}

function checkExisting($conn){
  $email = $_POST['email'];
  $user_details_sql = "SELECT * FROM `bahUsers` WHERE `email`= '$email'";
  $user_details_result = mysqli_query($conn, $user_details_sql);
  if (mysqli_num_rows($user_details_result) >0){
    $user_data = mysqli_fetch_assoc($user_details_result);
  
  }
  return $user_data;

}

function selectRow($conn){
  if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $user_details_sql = "SELECT * FROM `bahUsers` WHERE `email`='" . $email . "' AND `password`='" . md5($password) . "' AND `status`= 'enabled' ";
    $user_details_result = mysqli_query($conn, $user_details_sql);
   
  
     if (mysqli_num_rows($user_details_result) == 1) {
       //output data for each row
  
       $rowdata = mysqli_fetch_assoc($user_details_result);
  
     }
     return $rowdata;

  }
  
  }
