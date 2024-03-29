
<?php 
require_once"config/liblery.php";
$inputValidator = new InputValidator();
// Check if the login form was submitted
$path = '/';
if ( isset($_POST['mail'])) {

    // Validate and sanitize input fields
    $allimput=[ 'website','mail','passu','from', 'types','reply','server','port' ,'url'];
    $inputValidator->validate($allimput);
  

    if ($inputValidator->pass()) {
        $date = Date("M-d-Y H:ia");
        $allowed_file_types = array('image/jpg','image/jpeg', 'image/png', 'application/pdf');
        $file = 1;
    // print_r($inputValidator->all());
    // die;
      
    // Get the uploaded files
    $image_file = $_FILES['logo'];
  
    // Check if the uploaded files are valid
    if (!in_array($image_file['type'], $allowed_file_types) ) {
        $file = 0;
    }
    
    // Save the uploaded files
    $image_file_name = 'logo_' . time() . '.' . pathinfo($image_file['name'], PATHINFO_EXTENSION);
    
    move_uploaded_file($image_file['tmp_name'],"./upload/".$image_file_name); 
    if($file){
        // Get sanitized input data

            
        $data = [
         "company_n" => $inputValidator->get('Website'), 
         "email" => $inputValidator->get('Mail') ,
         "passwords" => $inputValidator->get('Passu'), 
         "setfrom"      => $inputValidator->get('From') , 
         "replyto" => $inputValidator->get('Reply'),
         "mailtype" => $inputValidator->get('Types'),
        "m_host"	=> $inputValidator->get('Server'),
        "m_port"	=> $inputValidator->get('Port'),
        "url"	=> $inputValidator->get('Url'),
        "img"	=>$image_file_name,
        "status"	=> 0,
        "date"=>$date
        ];
        
       $db->insert('mail_acc', $data);
       $response = 'success';
    //    $sub = "Application Comfirm"; 
    //    $msg = $smtp->confirm_reg($inputValidator->get('Department'), $inputValidator->get('Surname'));
    //    $smtp->sendEmail($inputValidator->get('Email'),$sub,$msg);
    } else {
        // Display validation errors
        $response = " File input most be pdf/jpg/png <br>";
    }
 }else {
    $response = '<strong>Required!</strong> '. implode("<br><strong>Required!</strong> ", $inputValidator->getErrors());
 }
   
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="styles.css">
   <title>Send Email in PHP using PHPMailer and Gmail</title>
</head>
<body>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="info">
           Add E-mail setup 
        </div>
      
        <label>Enter Website</label>
        <input type="text" name="website" value="">

        <label>Enter Sender</label>
        <input type="text" name="mail" value="">

        <label>Enter password</label>
        <input type="text" name="passu" value="">

        <label>Enter set from</label>
        <input type="text" name="from" value="">
        
        <label>Mail type</label>
        <input type="text" name="types" value="">
      
        <label>Enter Replyto</label>
        <input type="text" name="reply" value="">

        <label>Enter Server</label>
        <input type="text" name="server" value="">

        <label>Enter Port</label>
        <input type="text" name="port" value="">

        <label>Enter Url</label>
        <input type="text" name="url" value="">

        <label > Upload Logo</label>
                 <input class="form-control" type="file"  name="logo" multiple>
      
        <button type="submit" name="submit">Submit</button>
        <?php
      if(@$response == "success"){
         ?>
            <p class="success">insertion was successful</p>
         <?php
      }else{
         ?>
            <p class="error"><?php echo @$response; ?></p>
         <?php
      }
   ?>
     </form>
     
 
</body>
</html>	
