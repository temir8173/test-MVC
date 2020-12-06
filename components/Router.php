<?php

namespace liw\components;

/**
 * 
 */
class Router
{

	private $routes;
	
	public function __construct()
	{
		$routesPath = ROOT.'/config/routes.php';
		$this->routes = include($routesPath);
	}

	/**
	 * Returns request string
	 */
	private function getURI() {
		if ( !empty($_SERVER['REQUEST_URI']) ) {
			$uri = trim($_SERVER['REQUEST_URI'], '/');
			$pieces = explode("?", $uri);
			return $pieces[0];
		}
	}

	public function run() {

		// Получаем строку запроса
		$uri = $this->getURI();

		// Смотрим таблицу роутинга
		foreach ($this->routes as $uriPattern => $path) {

			if ( preg_match('~^'.$uriPattern.'$~', $uri) ) {

				// Получаем внутренний путь
				$internalRoute = preg_replace('~^'.$uriPattern.'$~', $path, $uri );
				
				if ( $internalRoute != null ) {
					break;
				}

			}

		}

		// Если запроса в таблице роутинга нет
		if ( !isset($internalRoute) ) {
			$internalRoute = $uri;
		}

		// Action & Controller & parametrs

		$segments = explode('/', $internalRoute);

		$controller = array_shift($segments);
		$controllerName = $controller.'Controller';
		$controllerName = ucfirst($controllerName);

		// Если не указан экшн дефолтный ставим index
		$actionName = ($action = array_shift($segments)) ? 'action'.ucfirst($action) : 'actionIndex';

		$parameters = $segments;

		// Подключение файла класса-контроллера
		$controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

		if ( file_exists($controllerFile) ) {
			include_once($controllerFile);
			$controllerObject = new $controllerName;
		} else {
			include_once(ROOT . '/controllers/SiteController.php');
			$controllerObject = new SiteController;
			$actionName = 'actionError';
		}
 
		$controllerObject->controllerName = $controller;
		$controllerObject->actionName = $action;
		
		call_user_func_array(array($controllerObject, $actionName), $parameters);

	}

}