<?php
//User “btcaeupu_mailart” was added to the database “btcaeupu_maila”.
//Rji3Xff];]xn
//
$host = "localhost";
$dbname = "btcaeupu_maila";
$user = "root";
$password = "";
try{
$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//create contact table
$DBH->query("CREATE TABLE mail_acc (
id INT NOT NULL AUTO_INCREMENT,
company_n VARCHAR (100) NOT NULL,
email VARCHAR (100) NOT NULL,
passwords VARCHAR (100) NOT NULL,
setfrom VARCHAR (50) NOT NULL,
mailtype VARCHAR(100)  NOT  NULL,
replyto VARCHAR(100) NOT NULL,
m_host VARCHAR (50) NOT NULL,
m_port VARCHAR (20) NOT NULL,
url VARCHAR (100) NOT NULL,
img VARCHAR(50) NOT NULL ,
status INT (2) NOT NULL,
date VARCHAR (20) NOT NULL,
PRIMARY KEY (id)
)
");
	echo"Contact Table created successfully";

}
catch(PDOException $e){
	echo"Contact Table not created!";
	echo $e->getMessage();
}// end catch

?>