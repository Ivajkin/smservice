
<?php
	#POST["?"]
	#user -> mysqli
	$db_link = mysql_connect( "localhost" , "root" , "") or die("Cannot connect to database");
	mysql_select_db( "SMS_Service" , $db_link );
	$query = "INSERT INTO receiver_category(UPhone,ACode) values( " .$_POST["uphone"]. " , " .$_POST["sex"]. " )";
	mysql_query( $query , $db_link );
	$query = "INSERT INTO receiver_category(UPhone,ACode) values(" .$_POST["uphone"]. "," .$_POST["education"]. " )";
	mysql_query( $query , $db_link );
	$query = "INSERT INTO receiver_category(UPhone,ACode) values(" .$_POST["uphone"]. "," .$_POST["age"]. " ); " ;
	mysql_query( $query , $db_link );
	mysql_close( $db_link );
	echo $query;
?>

