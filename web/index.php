<?php 

	// Front controller


	// общие настройки
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	// подключение файлов системы
	define('ROOT', dirname(__FILE__).'/..');
	require_once(ROOT.'/vendor/autoload.php');
	
	session_start();

	// вызов роутера
	$router = new liw\components\Router;
	$router->run();