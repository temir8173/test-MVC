<?php

namespace liw\components;
use PDO;

/**
 * 
 */
class Db
{
	
	public static function getConnection()
	{
		$params = include(ROOT . '/config/db.php');

		$dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
		$db = new PDO($dsn, $params['user'], $params['password']);

		return $db;
	}
}