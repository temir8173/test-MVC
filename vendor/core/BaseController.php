<?php

namespace liw\vendor\core;

/**
 * 
 */
class BaseController
{

	public $controllerName;
	public $actionName;
	public $layout = 'main';
	public $title;
	public $viewPath;
	

	public function render($view, $params = []) {

        require(ROOT.'/views/layouts/'.$this->layout.'.php');

	}

	public function renderPartial($view, $params = []) {

		$this->getViewPath($view);
		extract($params);
        require($this->viewPath);

	}

	public function getViewPath($view)
    {
        if ($this->viewPath === null) {
            $this->viewPath = ROOT.'/views/'.$this->controllerName.'/'.$view.'.php';
        }

        return $this->viewPath;
    }

	public function pagination($page, $perPage, $total) {
		$pagination['offset'] = ($page - 1) * $perPage;
		$pagination['countPages'] = ceil($total / $perPage);
		$pagination['current'] = $page;

		return $pagination;
	}

}