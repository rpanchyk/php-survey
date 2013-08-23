<?php
if (isset($_POST['send_vote']))
{
	require_once 'config.inc.php';
	define('CRLF', "\r\n");
	define('BR', '<br />');

	//echo '<pre>'; print_r($_POST); echo '</pre>';
	//echo '<pre>'; print_r($config); echo '</pre>';

	// Send email to moderator
	require_once 'external' . '/Net_SMTP/send.php';
	
	define('ERROR_EMAIL_EMPTY', 'Вы не заполнили поле E-mail!');
	define('ERROR_EMAIL_INVALID', 'Вы неправильно заполнили поле E-mail!');
	define('ERROR_CHECKCODE_INVALID', 'Вы неправильно заполнили проверочный код!');
	define('GOTOBACK', BR . BR . '<a href="javascript: history.go(-1)"> Вернитесь назад и повторите попытку </a>');
	define('GOTOMAIN', BR . BR . '<a href="/"> Перейти на главную </a>');
	define('SEND_OK', "Ваше сообщение было успешно отправленно.");
	define('ERROR_SEND_FAILED', "Спасибо!");
	
	// Process fields
	$email_from = trim($_POST['email']);
	$antibot = trim($_POST['anti_spam_code']);
	
	// Check error
	$error = '';
	$error .= (empty($email_from) ? ($error == '' ? '' : BR) . ERROR_EMAIL_EMPTY : '');
	$error .= ($_SESSION['AntiSpamImage'] != $antibot ? ($error == '' ? '' : BR) . ERROR_CHECKCODE_INVALID : '');
	
	$_SESSION['AntiSpamImage'] = rand(1,9999999);
	if (strpos($error, ERROR_EMAIL_EMPTY) === false && !preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/", $email_from))
		$error .= ($error == '' ? '' : BR) . ERROR_EMAIL_INVALID;
	?>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title> Result </title>
	</head>
	<body>
	<p>
		<?php
		if (empty($error))
		{
			// Process vote
			$strMessage = '';
			$questionPrev = '';
			foreach ($_POST as $k => $q)
			{
				// Process own answers
				if (strpos($k, 'own_answer') !== FALSE)
				{
					$ansKey = str_replace('own_answer', '', $k);
					$question = $config['vote'][$ansKey]['question'];
					$answer = $_POST[$k][0];
					
					// Add question
					if ($question != $questionPrev)
						$strMessage .= (!empty($strMessage) ? (CRLF . CRLF) : '') . $question;
					
					// Add answer
					$strMessage .= ': ' . $answer;
					
					// Save previous value
					$questionPrev = $question;
				}
				
				if (strpos($k, 'q') === FALSE)
					continue;
				
				// Process answers
				foreach ($q as $answerKey)
				{
					// Get question-answer IDs
					$ansKey = str_replace('q', '', $k);
					$ansVal = str_replace('a', '', $answerKey);
					
					// Get values
					$question = $config['vote'][$ansKey]['question'];
					$answer = $config['vote'][$ansKey]['answers'][$ansVal];
					
					// Add question
					if ($question != $questionPrev)
						$strMessage .= (!empty($strMessage) ? (CRLF . CRLF) : '') . $question;
					
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
			$email_result = custom_mail($email_from, $config['smtp']['email_address'], $config['smtp']['email_subject'], $msg, 
				$config['smtp']['server_host'], $config['smtp']['server_port'],$config['smtp']['server_user'],$config['smtp']['server_pass']);
			
			if ($email_result)
				echo SEND_OK . GOTOMAIN;
			else
				echo ERROR_SEND_FAILED . GOTOBACK;
		}
		else
			echo $error . GOTOBACK;
		?>
	</p>
	</body>
	</html>
	<?php
}
