
<?php
	//$mysqli = new mysqli('localhost' , 'core5429_sms' , 'SLnrx29n6sKb' , 'core5429_smservice' );
	if( $_POST["code"] != '' )
	{
		$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
		$query = "SELECT TID FROM UnconfirmedSms where UPhone = " . $_POST["phone"] . " AND ConfirmCode = " . $_POST["code"] ;
		$QResult = $mysqli->query( $query );
		if( $mysqli->errno )
		{
			echo $mysqli->error;
			return;
		}
		else
		{
			if( $QResult->num_rows )
			{
				$QResult = $QResult->fetch_row();
				$TID = $QResult[0];
				$mysqli->query( "UPDATE Template SET TConfirmedCount = TConfirmedCount+1 where TID = " . $TID );
				$mysqli->query( "DELETE FROM UnconfirmedSms where UPhone = " . $_POST["phone"] . " AND ConfirmCode = " . $_POST["code"] . " AND TID = " . $TID );
			}
			else echo "Invalid code";
		}
		$mysqli->close();
	}
	else echo "NULL Code";
?>