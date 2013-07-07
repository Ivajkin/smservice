<?php
	require_once("transport.php");
	$api = new Transport();
	
	$balance = $api->balance();
	
	if($balance > 10) {
	
		// Из базы данных грузим messageQueue
		//mysql грузим часть messageQueue
	
		$params = array(	
			array('number' => '79992223333', 'text' => 'Текст для первого контакта'),
			array('number' => '79991114444', 'text' => 'Текст для второго контакта') 
		);
		$send = $api->send($params);
	
		если $send.code!=1
			ошибка
	
		удаляем что отправили загрузили
	}
?>