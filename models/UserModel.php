<?php

namespace liw\models;

/**
 * 
 */
class UserModel
{

	public $login;
	public $password;

	public static function check($login, $password) {

		// Здесь должна быть авторизация по базе

		$users = array(
			'admin' => '202cb962ac59075b964b07152d234b70',
			'test' => '098f6bcd4621d373cade4e832627b4f6'
		);

		foreach ($users as $user => $pass) {
			if ( $login == $user && $pass == md5($password) )
				return true;
		}

		return false;

	}
	
	
}