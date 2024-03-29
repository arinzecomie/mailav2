<?php
require 'SMTPUtils.php';
require_once 'DatabaseManager.php';
require_once 'InputValidation.php';

$db = DatabaseManager::getInstance(
    "localhost",
    "btcaeupu_maila",
    "root", 
    "");



          
   
function redirect($url) {
  if (headers_sent()) {
    echo "<script>window.location.href = '$url';</script>";
  } else {
    header("Location: $url");
  }
}


function toast($info){
   
    echo 
   '<script>
  Swal.fire({
  position: "top-end",
  icon: "success",
  title: "'.$info.'",
  showConfirmButton: false,
  timer: 1500 })
  setTimeout(function(){ window.location.href = "/newapplicant.php"; }, 5000);
  
  </script>';
}

function timers($inputDateString){
  $dateTime = new DateTime($inputDateString);
  return $formattedDate = $dateTime->format("l, F jS, Y, \a\\t g:ia");
}
//User “btcaeupu_jobinter” was added to the database “btcaeupu_jobinterv”.
?>