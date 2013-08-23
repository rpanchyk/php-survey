<?php require_once 'config.inc.php'; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Language" content="ru-RU">
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="keywords" content="">
	<meta name="description" content="">

	<title>Анкета</title>

	<script type="text/javascript" src="<?=$config['web_path']?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?=$config['web_path']?>/js/firetooltip/fttp.js"></script>

	<link type="text/css" rel="stylesheet" href="<?=$config['web_path']?>/css/font.css" charset="utf-8" />
	<link type="text/css" rel="stylesheet" href="<?=$config['web_path']?>/css/style.css" charset="utf-8" />
</head>
<body>
	<div style="text-align:center;"><h4>Типа супер опрос</h4></div>
	<table cellspacing="0" cellpadding="0" border="0" style="width:100%;">
		<tr><td style="padding-left:30%;"><?php require_once 'form.inc.php'; ?></td></tr>
	</table>
</body>
</html>