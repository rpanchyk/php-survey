<?php

if (isset($_POST['send_vote'])) {
	require_once 'config.inc.php';
	define('CRLF', "\r\n");
	define('BR', '<br />');

	//echo '<pre>'; print_r($_POST); echo '</pre>';
	//echo '<pre>'; print_r($config); echo '</pre>';

	define('ERROR_EMAIL_EMPTY', 'Вы не заполнили поле E-mail!');
	define('ERROR_EMAIL_INVALID', 'Вы неправильно заполнили поле E-mail!');
	define('ERROR_CHECKCODE_INVALID', 'Вы неправильно заполнили проверочный код!');
	define('GOTOBACK', BR . BR . '<a href="javascript: history.go(-1)"> Вернитесь назад и повторите попытку </a>');
	define('GOTOMAIN', BR . BR . '<a href="/"> Перейти на главную </a>');
	define('SEND_OK', 'Ваше сообщение было успешно отправленно.');
	define('ERROR_SEND_FAILED', BR . 'Возникла ошибка при отправке данных.');
	
	// Process fields
	$email_from = trim($_POST['email']);
	$antibot = trim($_POST['anti_spam_code']);
	
	// Check error
	$error = '';
	$error .= (empty($email_from) ? ($error == '' ? '' : BR) . ERROR_EMAIL_EMPTY : '');
	$error .= ($_SESSION['AntiSpamImage'] != $antibot ? ($error == '' ? '' : BR) . ERROR_CHECKCODE_INVALID : '');
	
	$_SESSION['AntiSpamImage'] = rand(1,9999999);
	if (strpos($error, ERROR_EMAIL_EMPTY) === false && !preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/", $email_from)) {
		$error .= ($error == '' ? '' : BR) . ERROR_EMAIL_INVALID;
	}
	?>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title> Result </title>
	</head>
	<body>
	<p>
		<?php
		if (empty($error)) {
			// Process vote
			$strMessage = '';
			$questionPrev = '';
			foreach ($_POST as $k => $q) {
				// Process own answers
				if (strpos($k, 'own_answer') !== FALSE) {
					$ansKey = str_replace('own_answer', '', $k);
					$question = $config['vote'][$ansKey]['question'];
					$answer = $_POST[$k][0];
					
					// Add question
					if ($question != $questionPrev) {
						$strMessage .= (!empty($strMessage) ? (CRLF . CRLF) : '') . $question;
					}
					
					// Add answer
					$strMessage .= ': ' . $answer;
					
					// Save previous value
					$questionPrev = $question;
				}
				
				if (strpos($k, 'q') === FALSE) {
					continue;
				}
				
				// Process answers
				foreach ($q as $answerKey) {
					// Get question-answer IDs
					$ansKey = str_replace('q', '', $k);
					$ansVal = str_replace('a', '', $answerKey);
					
					// Get values
					$question = $config['vote'][$ansKey]['question'];
					$answer = $config['vote'][$ansKey]['answers'][$ansVal];
					
					// Add question
					if ($question != $questionPrev) {
						$strMessage .= (!empty($strMessage) ? (CRLF . CRLF) : '') . $question;
					}
					
					// Add answer
					$strMessage .= CRLF . '- ' . $answer;
					
					// Save previous value
					$questionPrev = $question;
				}
			}
			//echo $strMessage;
			
			$msg  = 'Сообщение с сайта ' . $_SERVER['SERVER_NAME'];
			$msg .= CRLF . 'Отправитель: ' . $email_from;
			$msg .= CRLF . '------------------------------' . CRLF . $strMessage . CRLF . '------------------------------' . CRLF;
				
			// Let's rock!
			$email_result = smtpmail($email_from, $config['smtp']['email_to'], $config['smtp']['email_subject'], $msg, 
				$config['smtp']['host'], $config['smtp']['port'], $config['smtp']['user'], $config['smtp']['pass'], 
				$config['smtp']['charset'], $config['smtp']['debug']);
			
			echo $email_result ? SEND_OK . GOTOMAIN : ERROR_SEND_FAILED . GOTOBACK;
		}
		else {
			echo $error . GOTOBACK;
		}
		?>
	</p>
	</body>
	</html>
	<?php
}

// http://i-leon.ru/smtp-php/
function smtpmail($emailFrom, $emailTo, $subject, $message, $smtpHost, $smtpPort = '25', $smtpUser = '', $smtpPass = '', $smtpCharset = 'UTF-8', $debug = false) {
	$SEND =	"Date: ".date("D, d M Y H:i:s") . " UT\r\n";
	$SEND .= 'Subject: =?'.$smtpCharset.'?B?'.base64_encode($subject)."=?=\r\n";
	$SEND .= "Reply-To: ".$emailFrom."\r\n";
	$SEND .= "MIME-Version: 1.0\r\n";
	$SEND .= "Content-Type: text/plain; charset=\"".$smtpCharset."\"\r\n";
	$SEND .= "Content-Transfer-Encoding: 8bit\r\n";
	$SEND .= "From: \"".$emailFrom."\"\r\n";
	$SEND .= "To: $emailTo\r\n";
	$SEND .= "X-Priority: 3\r\n\r\n";
	$SEND .=  $message."\r\n";
	
	if( !$socket = fsockopen($smtpHost, $smtpPort, $errno, $errstr, 30) ) {
		if ($debug) echo $errno."&lt;br&gt;".$errstr;
		return false;
	}

	if (!server_parse($socket, "220", __LINE__)) {
		return false;
	}

	fputs($socket, "HELO " . $smtpHost . "\r\n");
	if (!server_parse($socket, "250", __LINE__)) {
		if ($debug) echo '<p>Не могу отправить HELO!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "AUTH LOGIN\r\n");
	if (!server_parse($socket, "334", __LINE__)) {
		if ($debug) echo '<p>Не могу найти ответ на запрос авторизаци.</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, base64_encode($smtpUser) . "\r\n");
	if (!server_parse($socket, "334", __LINE__)) {
		if ($debug) echo '<p>Логин авторизации не был принят сервером!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, base64_encode($smtpPass) . "\r\n");
	if (!server_parse($socket, "235", __LINE__)) {
		if ($debug) echo '<p>Пароль не был принят сервером как верный! Ошибка авторизации!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "MAIL FROM: <".$smtpUser.">\r\n");
	if (!server_parse($socket, "250", __LINE__)) {
		if ($debug) echo '<p>Не могу отправить комманду MAIL FROM: </p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "RCPT TO: <" . $emailTo . ">\r\n");

	if (!server_parse($socket, "250", __LINE__)) {
		if ($debug) echo '<p>Не могу отправить комманду RCPT TO: </p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "DATA\r\n");

	if (!server_parse($socket, "354", __LINE__)) {
		if ($debug) echo '<p>Не могу отправить комманду DATA</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, $SEND."\r\n.\r\n");

	if (!server_parse($socket, "250", __LINE__)) {
		if ($debug) echo '<p>Не смог отправить тело письма. Письмо не было отправленно!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "QUIT\r\n");
	fclose($socket);
	return TRUE;
}

function server_parse($socket, $response, $line = __LINE__) {
	while (@substr($server_response, 3, 1) != ' ') {
		if (!($server_response = fgets($socket, 256))) {
			if ($debug) echo "<p>Проблемы с отправкой почты!</p>$response<br>$line<br>";
			return false;
		}
	}
	if (!(substr($server_response, 0, 3) == $response)) {
		if ($debug) echo "<p>Проблемы с отправкой почты!</p>$response<br>$line<br>";
		return false;
	}
	return true;
}
