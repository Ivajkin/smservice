<?php

	require_once(dirname(__FILE__)."/smsPHPClass/transport.php");

	$send_api = new Transport();
	
	//$balance = $send_api->balance();
	
	//if($balance > 10) 
	{
		$json = ini_get('magic_quotes_gpc') == 1 ? stripslashes( $_POST['data'] ) : $_POST['data'];
		$decoded_data = json_decode($json); // Идентификаторы аудиторий
		echo $_POST['OrgName'];
	
		//$mysqli = new mysqli('localhost' , 'core5429_sms' , 'SLnrx29n6sKb' , 'core5429_smservice' );
		$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
		// Запись шаблона в БД
			// Определяем идентификатор клиента ( CID ) 
			$QResult = $mysqli->query("SELECT CID FROM Client where OrgName = '" . $_POST['OrgName'] ."'");			
			$QResult = $QResult->fetch_row();
			$CID = $QResult[0];
			printf("\nCID = %s",$CID);
			
			// Добавляем запись о шаблоне в таблицу шаблонов
			$query = "INSERT INTO Template VALUES( 0 , 0 , '" . $_POST['TName'] . "' , '" . $_POST['MessageText'] . "' , " .$CID. " , 0 )";
			if($mysqli->errno)	{	echo $mysqli->error;return;	}
			$mysqli->query($query);
			
			// Получаем идентификатор только что созданного шаблона
			// !!!!! Необходимо программно обеспечить уникальность названия шаблона для каждого конкретного клиента + добавить это свойство в базу
			$query = "SELECT TID FROM Template WHERE TName = '" .$_POST['TName']. "' and CID = '" .$CID. "' ";
			$QResult = $mysqli->query($query);
			$QResult = $QResult->fetch_row();
			$TID = $QResult[0];	// Идентификатор шаблона
			printf("\nTID = %s",$TID);
			
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
			{	$query .= " or ACode = " . $decoded_data[$i];		}	// Доработать выборку на пересечение и объединения множест
			$query .= ') AS ReceiversList';
			$query_result = $mysqli->query($query);
			$receivers_amount = $query_result->num_rows;
			printf("Receivers Amount : %d\n",$receivers_amount);

			$MAX_ATTEMPTS = 5; // Максимальное количество попыток обращения на перезапись сообщения в базу
			$blocker = $MAX_ATTEMPTS ;	// Ограничивает количество попыток записи в базу данных 
			
			$send_params_array = array();
			$phones_array = array();
			$not_sended_sms = 0;
			$sended = 0;
			
			mt_srand($TID);
			for($i = 0 ; $i < $receivers_amount ; $i++ )
			{
				// Генерируем коды подтверждения и записываем в базу сообщения по одному
				$lastCode = rand( 0 , 99999 );
				$lastUPhone = $query_result->fetch_row();
				$lastUPhone = $lastUPhone[0];
				$mysqli->query("INSERT INTO UnconfirmedSMS(TID, ConfirmCode , UPhone) values( " .$TID. " , " .$lastCode. " , " .$lastUPhone. ")" );
				
				// После записи, записываем сообщение в массив на отслыку , если запись в базу прошла успешно. Иначе - пропускаем
				if( $mysqli->errno == 0 )
				{
						$phones_array[] = array( 'number' => sprintf("7%s",$lastUPhone), 'text' => $_POST['MessageText'] . " Код подтверждения : " .$lastCode , 'source' => 'SMS TEST');
						printf("\n Code: %s  UPhone: %s", $lastCode , $lastUPhone);
				}
				else 
				{
					printf($mysqli->error);
					if( !(--$blocker) )	// Если все попытки исчерпаны - пропускаем данную запись и увеличиваем счетчик ошибок
					{	$not_sended_sms++ ; $blocker = $MAX_ATTEMPTS ;	printf("Too many attempts! Skipped!");}
					else {	$i-- ; } 
				}
			}
			/*
			$send = $send_api->send($send_params_array,$phones_array);
			if( $send['code']!=1 )
			{ echo("Сообщение не отправлено. Ошибка\n ".($send['descr'])."\n Код ".$send['code']);}
			else 
			{
				$mysqli->query("UPDATE Template SET TTotalReceivers = " .$receivers_amount . " WHERE TID = ".$TID );
				echo "Succsess!";
			}
			 */
			$mysqli->query("UPDATE Template SET TTotalReceivers = " .$receivers_amount . " WHERE TID = ".$TID ); 
			$mysqli->close();
	}
?>