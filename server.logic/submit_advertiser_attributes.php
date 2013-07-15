
<?php
	#POST["?"]
	#user -> mysqli
	//$mysqli = new mysqli( 'localhost' , 'core5429_sms' , 'SLnrx29n6sKb' , 'core5429_smservice' );
	$salt = 'mgtJFizR7oBxqqL6fG1Dq9';
	$pass = crypt( $_POST["pass"] , '$2a$10$'.$salt );
	$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
	if( $mysqli->errno == 0 )
	$query = "INSERT INTO Client( OrgName , EMail , OrgActivity , OrgPhone , ContactPerson , ContactPhone , Password ) values( '" 
		.$_POST["OrgName"]. "' , '" 
		.$_POST["email"]. "' ,
		'random' ,
		000000 ,
		'James Hook' ,"
		.$_POST["contact_phone"]." , '"
		.$pass.	"' ) ";
	if( !$mysqli->query( $query ) )
		printf($mysqli->error);
	$mysqli->close();
	echo $query;
?>

