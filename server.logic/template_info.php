
<?php
	$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
	//$mysqli = new mysqli('localhost' , 'core5429_sms' , 'SLnrx29n6sKb' , 'core5429_smservice' );
	$query_result = $mysqli->query("SELECT TName,TText,TTotalReceivers,TConfirmedCount FROM Template where (SELECT CID FROM Client WHERE OrgName = '" .$_POST["OrgName"]."')" );
	if($mysqli->errno) {	echo $mysqli->error; return;	}
	$array_size = $mysqli->num_rows;
	$fetched_row;
	$html_output = '<table align = center >';
	while( $fetched_row = $query_result->fetch_row() )
	{
		$html_output .= "<tr><td><b>Название шаблона :</b>" .$fetched_row[0]. 
			"</td></tr><tr><td><b>Текст:</b>" .$fetched_row[1]. 
			"</td></tr><tr><td><b>Отправлено:</b>" .$fetched_row[2]. 
			"</td></tr><tr><td><b>Подтверждено:</b> " .$fetched_row[3]. "</td></tr><br><br>";
	}
	$html_output .= '</table>';
	echo $html_output;
	$mysqli->close();
?>