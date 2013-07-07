
<?php
	#POST["?"]
	#user -> mysqli
	$db_link = mysql_connect( "localhost" , "root" , "") or die("Cannot connect to database");
	mysql_select_db( "SMS_Service" , $db_link );
	$query = "INSERT INTO receiver(UName,UPhone,VKAccount) values( '" . $_POST["name"] . "' , " . $_POST["phone"] . " , 'ignored')";
	mysql_query( $query , $db_link );
	mysql_close( $db_link );
	echo $query;
?>