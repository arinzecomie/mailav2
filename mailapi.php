<?php
require_once"config/liblery.php";
$V= new InputValidator();
if (@$_POST["action"]) {
    $allimput=[ 'msg','email','user','subj','action'];
    $V->validate($allimput);
  
if ($V->pass()) {

    $queryFields = [
        "fieldNames" => ["*"],
        'where' => ["id" ==  $V->get('action')]
      ];
      $maildata = $db->select('mail_acc', $queryFields)[0];
   
    
    $smtp  = new  SMTPUtils(
      $maildata["email"],
      $maildata["passwords"] ,
      $maildata["m_host"],
      $maildata["setfrom"],
      $maildata["company_n"],
      $maildata["replyto"],
      $maildata["m_port"],
      $maildata["img"]
      );
      

      $msg = $smtp->custom_mail($V->get('Msg'), $V->get('User') ,$V->get('Subj'));
      echo $smtp->sendEmail($V->get('Email'),$V->get('Subj'),$msg);   
    
  }
}

?>