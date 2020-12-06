<?php

use liw\models\TasksModel;
use liw\vendor\core\BaseController;

/**
 * 
 */
class TasksController extends BaseController
{
	
	public function actionIndex($page = 1) {

		$this->title = 'Tasks index';
		
		if ( !empty($_GET['orderby']) && !empty($_GET['order']) ) {
			$orderby = $_GET['orderby'];
			$order = $_GET['order'];
		}
		else {
			$orderby = '';
			$order = '';
		}

		$perPage = 3;
		$countTasks = TasksModel::getCount();
		$pagination = $this->pagination($page, $perPage, $countTasks);

		$taskFields = TasksModel::getFields();
		$tasksList = array();
		$tasksList = TasksModel::getTasksList([ 
			'limit' => $perPage, 
			'offset' => $pagination['offset'], 
			'orderby' => $orderby, 
			'order' => $order 
		]);

		$result = (!empty($_GET['result'])) ? $_GET['result'] : '';
		$resultEdit = (!empty($_GET['result_edit'])) ? $_GET['result_edit'] : '';
		$orderNext = ( $order == 'DESC' ) ? 'ASC' : 'DESC';

		$this->render('index', array(
				'taskFields' => $taskFields,
				'tasksList' => $tasksList, 
				'pagination' => $pagination, 
				'orderNext' => $orderNext,
				'result' =>  $result,
				'resultEdit' =>  $resultEdit,
				'order' => $order,
				'orderby' => $orderby
			)
		);

	}
	
	public function actionAdd() {

		if (isset($_POST)) {
			$model = new TasksModel;
			$model->name = htmlspecialchars($_POST['Form']['name']);
			$model->email = htmlspecialchars($_POST['Form']['email']);
			$model->text = htmlspecialchars($_POST['Form']['text']);
			$result = $model->save();
		}

		header("Location: /tasks/index?result=$result",TRUE,301);

	}
	
	public function actionEdit() {

		if ( !empty($_SESSION['login']) ) {

			if ( !empty($_POST) ) {

				$model = TasksModel::getTaskByPk($_POST['Edit']['id']);

				if( $model->text !== $_POST['Edit']['text'] )
					$model->edited = 1;
				$model->text = $_POST['Edit']['text'];
				$model->done = $_POST['Edit']['done'];
				$result = $model->update();
			}

			$order = (!empty($_POST['GetParams']['order'])) ? '?orderby='.$_POST['GetParams']['orderby'].'&order='.$_POST['GetParams']['order'].'&' : '?';
			$url = $_POST['GetParams']['page'].$order;

			header("Location: /".$url."result_edit=$result",TRUE,301);
		} else {
			header("Location: /site/signin",TRUE,301);
		}

	}
	
	public function actionView($id) {

		$this->title = 'Tasks view';

		$this->render('view', ['test' => 'qwerty']);

		return true;

	}

}