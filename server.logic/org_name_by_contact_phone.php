
<?php
	//$mysqli = new mysqli('localhost' , 'core5429_sms' , 'SLnrx29n6sKb' , 'core5429_smservice' );
	$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );

	$query = "SELECT OrgName FROM client where ContactPhone = " . $_POST["phone"];
	$QResult = $mysqli->query( $query );

	if( $QResult->errno )	// Если ошибка
	{	echo -1; 	return;	}	
	// Получает название организации
	$QResult = $QResult->fetch_row();
	echo $QResult[0];
	$mysqli->close();
?>