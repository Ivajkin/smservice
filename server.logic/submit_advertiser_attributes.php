
<?php
	#POST["?"]
	#user -> mysqli
	$db_link = mysql_connect( "localhost" , "root" , "") or die("Cannot connect to database");
	mysql_select_db( "SMS_Service" , $db_link );
	$query = "INSERT INTO client( OrgName , EMail , OrgActivity , OrgPhone , ContactPerson , ContactPhone ) values( '" 
		.$_POST["OrgName"]. "' , '" 
		.$_POST["email"]. "' ,
		'random' ,
		8855868 ,
		'James Hook' ,
		4459301 )";
	mysql_query( $query , $db_link );
	mysql_close( $db_link );
	echo $query;
?>

