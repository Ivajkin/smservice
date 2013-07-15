
<?php
	// Соль
	$salt = 'mgtJFizR7oBxqqL6fG1Dq9';
	//$mysqli = new mysqli('localhost' , 'core5429_sms' , 'SLnrx29n6sKb' , 'core5429_smservice' );
	$mysqli = new mysqli('localhost' , 'root' , '' , 'sms_service' );
	
	$receiver = 1;	// Изначально полагаем, что вход осуществляет получатель
	
	// Выбираем хеш-пароль из списка получателей
	$query = "SELECT Password FROM receiver where UPhone = " . $_POST["phone"];
	$QResult = $mysqli->query( $query );

	if( $QResult->errno )	// Если ошибка
	{	echo -1; 	return;	}
	
	if( $QResult->num_rows == 0 )	// Если нет результатов - проверяем в списек рекламодателей
	{
		$receiver = 0 ;	// Видимо, войти пытается все-таки рекламодатель
		$query = "SELECT Password FROM client where ContactPhone = " . $_POST["phone"];
		$QResult = $mysqli->query( $query );
		if( $QResult->num_rows == 0 )	// Если и в таблице рекламодателей отсутствует номер - логин неверен
		{	echo 2; 	return;	}
	}
	// Выхватываем Хеш пароля
	$QResult = $QResult->fetch_row();
	$hash = $QResult[0];
	$mysqli->close();
	
	// Проверяем хеши на соответствие
	$new_hash = crypt( $_POST["pass"] , '$2a$10$'.$salt );
	if( $new_hash == $hash )
	{
		if( $receiver )
			echo 0; 
		else echo 1;
	}
	else {	echo 3; }
?>