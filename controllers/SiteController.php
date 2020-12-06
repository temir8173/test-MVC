<?php

use liw\models\UserModel;
use liw\vendor\core\BaseController;

/**
 * 
 */
class SiteController extends BaseController
{

	public function actionIndex() {
		
		var_dump($_SESSION);

	}

	public function actionSignin() {

		$result = '';
		if( !empty($_POST) ) {
			$result = $this->login($_POST['login'], $_POST['password']);
		}

		$this->render('signin', ['result' => $result]);

	}

	public function login($login, $password) {

		if( UserModel::check($login, $password) ) {
		    $_SESSION['login'] = $login;
		    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
			header("Location: /",TRUE,301);
		} else {
			return 'Неправильные реквизиты доступа!';
		}

	}

	public function actionLogout() {
		session_destroy();
		header("Location: /",TRUE,301);
	}
	
	public function actionError() {
		header('HTTP/1.0 404 Not Found', true, 404);
		echo '404 Ошибка';
		die;
	}

}