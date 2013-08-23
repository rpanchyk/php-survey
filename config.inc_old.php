<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', E_ALL);

date_default_timezone_set("Europe/Kiev");

// Start session
session_start();

$config = array();

// General
$config['web_path'] = '/survey';

// SMTP
$config['smtp']['server_host'] = 'smtp.freenet.com.ua';
$config['smtp']['server_port'] = '25';
$config['smtp']['server_user'] = '';
$config['smtp']['server_pass'] = '';
$config['smtp']['email_address'] = 'email_to';
$config['smtp']['email_subject'] = 'Опрос';

// Vote
$qa['question'] = 'Вопрос 1';
$qa['answers'] = array('Адин', 'Тва', '3', 'Шетыре');
$qa['type'] = 'radio';
$qas[] = $qa;

$qa['question'] = 'Вопрос 2';
$qa['answers'] = array('Шэльдэ', 'Жожольдэ', 'Бэшэльмэ', 'Нама');
$qa['type'] = 'checkbox';
$qas[] = $qa;

$qa['question'] = 'Вопрос 3??????';
$qa['answers'] = array('Неа', 'Да', 'Может быть', 'Может и не быть');
$qa['type'] = 'radio';
$qa['own_answer'] = 3;
$qas[] = $qa;

$config['vote'] = $qas;
