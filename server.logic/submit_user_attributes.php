
<?php
	#POST["?"]
	#user -> mysqli
	//$mysqli = new mysqli('localhost' , 'core5429_sms' , 'SLnrx29n6sKb' , 'core5429_smservice' );
	$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
	$query = "INSERT INTO receiver_category(UPhone,ACode) values( " .$_POST["uphone"]. " , " .$_POST["sex"]. " )";
	$mysqli->query( $query );
	$query = "INSERT INTO receiver_category(UPhone,ACode) values(" .$_POST["uphone"]. "," .$_POST["education"]. " )";
	$mysqli->query( $query );
	$query = "INSERT INTO receiver_category(UPhone,ACode) values(" .$_POST["uphone"]. "," .$_POST["age"]. " ); " ;
	$mysqli->query( $query );
	echo $query;
?>

