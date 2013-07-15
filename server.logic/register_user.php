
<?php
	#POST["?"]
	#user -> mysqli
	$salt = 'mgtJFizR7oBxqqL6fG1Dq9';
	
	//$mysqli = new mysqli('localhost' , 'core5429_sms' , 'SLnrx29n6sKb' , 'core5429_smservice' );
	$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
	$p_hash = crypt($_POST["password"],'$2a$10$'.$salt);
	$query = "INSERT INTO receiver(UName,UPhone,VKAccount,Password) values( '" . $_POST["name"] . "' , " . $_POST["phone"] . " , 'ignored' , '" .$p_hash. "')";
	$mysqli->query( $query );
	if( $mysqli->errno )	echo $mysqli->error;
	$mysqli->close();
	echo $query;
?>