<?php

	require_once(dirname(__FILE__)."/smsPHPClass/transport.php");

	$send_api = new Transport();
	
	$balance = $send_api->balance();
	
	if($balance > 10) 
	{
		$json = ini_get('magic_quotes_gpc') == 1 ? stripslashes( $_POST['data'] ) : $_POST['data'];
		$decoded_data = json_decode($json); // Идентификаторы аудиторий
	
		$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
		// Запись шаблона в БД
			// Определяем идентификатор клиента ( CID ) 
			$QResult = $mysqli->query("SELECT CID FROM Client where OrgName = '" . $_POST['OrgName'] ."'");
			$QResult = $QResult->fetch_row();
			$CID = $QResult[0];
			
			// Добавляем запись о шаблоне в таблицу шаблонов
			$query = "INSERT INTO Template VALUES( 0 , 0 , '" . $_POST['TName'] . "' , '" . $_POST['MessageText'] . "' , " .$CID. " , 0 )";
			$mysqli->query($query);
			
			// Получаем идентификатор только что созданного шаблона
			// !!!!! Необходимо программно обеспечить уникальность названия шаблона для каждого конкретного клиента + добавить это свойство в базу
			$query = "SELECT TID FROM Template WHERE TName = '" .$_POST['TName']. "' and CID = '" .$CID. "' ";
			$QResult = $mysqli->query($query);
			$QResult = $QResult->fetch_row();
			$TID = $QResult[0];	// Идентификатор шаблона
			
			// Добавление записей для хранения выбранной аудитории шаблона
			$array_size = count($decoded_data);
			if($array_size != 0)
			{
				for( $i = 1 ; $i < $array_size ; $i++ )
				{	$mysqli->query("INSERT INTO TemplateAudience VALUES(" .$TID. " , " .$decoded_data[$i]." ) ");	}
			}
			
			// Составляем запрос на выборку номеров получателей		
			$query = "SELECT DISTINCT UPhone FROM ( SELECT * FROM receiver_category where ACode = " . $decoded_data[0] ;
			for( $i = 1 ; $i < $array_size ; $i++ )
			{	$query .= " or ACode = " . $decoded_data[$i];		}
			$query .= ') AS ReceiversList';
			$query_result = $mysqli->query($query);
			$receivers_amount = $query_result->num_rows;
			printf("Receivers Amount : %d\n",$receivers_amount);

			$MAX_ATTEMPTS = 5; // Максимальное количество попыток обращения на перезапись сообщения в базу
			$blocker = $MAX_ATTEMPTS ;	// Ограничивает количество попыток записи в базу данных 
			
			$send_params_array;
			$not_sended_sms = 0;
			
			mt_srand($TID);
			for($i = 0 ; $i < $receivers_amount ; $i++ )
			{
				// Генерируем коды подтверждения и записываем в базу сообщения по одному
				$lastCode = rand( 0 , 99999 );
				printf("ConfCode : %d\n", $lastCode);
				$lastUPhone = $query_result->fetch_row();
				$lastUPhone = $lastUPhone[0];
				printf("Phone : %s\n", $lastUPhone);
				$mysqli->query("INSERT INTO UnconfirmedSms(TID, ConformCode , UPhone) values( " .$TID. " , " .$lastCode. " , " .$lastUPhone. ")" );
				
				// После записи, записываем сообщение в массив на отслыку , если запись в базу прошла успешно. Иначе - пропускаем
				if( $mysqli->errno == 0 )
				{	$send_params_array[] =	array('number' => sprintf("%s",$lastUPhone), 'text' => $_POST['MessageText'] . " Код подтверждения : " .$lastCode );		}
				else 
				{
					printf($mysqli->error);
					if( !(--$blocker) )	// Если все попытки исчерпаны - пропускаем данную запись и увеличиваем счетчик ошибок
					{	$not_sended_sms++ ; $blocker = $MAX_ATTEMPTS ;	printf("Too many attempts! Skipped!");}
					else {	$i-- ; } 
				}
			}
			var_dump($send_params_array);
			$send = $send_api->send($send_params_array);
			if( $send['code']!=1 )
			{ echo("Сообщение не отправлено. Ошибка\n ".($send['descr'])."\n Код ".$send['code']);}
			else echo "Succsess!";
	}
?>