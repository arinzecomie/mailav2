<?php
$url = "http://localhost/mailav2/mailapi.php"; 

$data = [
  "action" => 1,
  "email" => "ezeozuearinzekizito@gmail.com",
  "subj" => "johndoe",
  "user"=> "Arinzek",
  "msg" => "Replace with your data Replace with your data",
]; // Replace with your data

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo "Error: " . curl_error($ch);
} else {
  // Process the response
  echo $response;
}

curl_close($ch);


?>