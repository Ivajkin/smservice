<?php

	function getReceiversArray( $OrgName )
	{
		// Соединяемся с базой
		$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
		
		// Составляем запрос на выборку номеров получателей
		// 1. Определяем идентификатор шаблона
		$query = "SELECT DISTINCT UPhone FROM ( SELECT * FROM receiver_category where ACode = " . $decoded[0] ;
		for( $i = 1 ; $i < $array_size ; $i++ )
		{	$query .= " or ACode = " . $decoded[$i];		}
		$query .= ') AS ACount';
		$query_result = $mysqli->query($query);
		$amount_of_result_records = $query_result-> fetch_row();
		$mysqli->close();
	}

	require_once("transport.php");
	$api = new Transport();
	
	$balance = $api->balance();
	
	$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
	
	if($balance > 10) 
	{
		$QResult = $mysqli.query("SELECT UPhone");
		$params = array
		(	
			array('number' => '79992223333', 'text' => 'Текст для первого контакта'),
			array('number' => '79991114444', 'text' => 'Текст для второго контакта') 
		);
		$send = $api->send($params);
	
		if( $send.code!=1 )
			printf($send[descr]);
	}
	

?>