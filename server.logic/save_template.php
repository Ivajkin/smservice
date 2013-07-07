<?php

	$json = ini_get('magic_quotes_gpc') == 1 ? stripslashes( $_POST['data'] ) : $_POST['data'];
	$decoded_data = json_decode($json);

	$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
	// Запись шаблона в БД
		// Определяем идентификатор клиента ( CID ) 
		$QResult = $mysqli->query("SELECT CID FROM Client where OrgName = '" . $_POST['OrgName'] ."'");
		$QResult = $QResult->fetch_row();
		$CID = $QResult[0];
		echo "CID = " . $CID ;
		
		// Добавляем запись о шаблоне в таблицу шаблонов
		$query = "INSERT INTO Template VALUES( 0 , 0 , '" . $_POST['TName'] . "' , '" . $_POST['MessageText'] . "' , " .$CID. " , 0 )";
		$mysqli->query($query);
		echo $query;
		
		// Получаем идентификатор только что созданного шаблона
		// !!!!! Необходимо программно обеспечить уникальность названия шаблона для каждого конкретного клиента + добавить это свойство в базу
		$query = "SELECT TID FROM Template WHERE TName = '" .$_POST['TName']. "' and CID = '" .$CID. "' ";
		$QResult = $mysqli->query($query);
		$QResult = $QResult->fetch_row();
		$TID = $QResult[0];
		echo $TID;
		
		// Добавление записей для хранения выбранной аудитории шаблона
		$array_size = count($decoded_data);
		if($array_size != 0)
		{
			for( $i = 1 ; $i < $array_size ; $i++ )
			{	$mysqli->query("INSERT INTO TemplateAudience VALUES(" .$TID. " , " .$decoded_data[$i]." ) ");	}
		}
?>