
<?php
	#POST["?"]
	#user -> mysqli
	
	$json = ini_get('magic_quotes_gpc') == 1 ? stripslashes( $_POST['data'] ) : $_POST['data'];
	$decoded = json_decode($json);
	
	$array_size = count($decoded);
	if($array_size != 0)
	{
		$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
		$query = "SELECT COUNT(DISTINCT UPhone) FROM ( SELECT * FROM receiver_category where ACode = " . $decoded[0] ;
		for( $i = 1 ; $i < $array_size ; $i++ )
		{	$query .= " or ACode = " . $decoded[$i];		}
		$query .= ') AS ACount';
		$query_result = $mysqli->query($query);
		$amount_of_result_records = $query_result-> fetch_row();
		$mysqli->close();
	}
	echo $amount_of_result_records[0];
?>

