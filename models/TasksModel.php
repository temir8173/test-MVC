<?php

namespace liw\models;
use liw\components\Db;
use PDO;

/**
 * 
 */
class TasksModel
{

	public $id;
	public $name;
	public $email;
	public $text;
	public $done;
	public $edited;
	public $error;

	public static function attributeLabels()
    {
        return [
            'name' => 'Название',
            'email' => 'Email',
            'text' => 'Текст',
            'done' => 'Выполнено',
            'edited' => 'Отредактировано администратором',
        ];
    }
	
	public static function getCount() {
			
		$db = Db::getConnection();


		$result = $db->query('SELECT count(*) FROM tasks');
		$result->setFetchMode(PDO::FETCH_NUM);
		$count = $result->fetch();

		return intval($count[0]);

	}

	public static function getFields() {
		$sql = "SHOW FIELDS FROM tasks";
		$db = Db::getConnection();
		$result = $db->query($sql);
		$labels = TasksModel::attributeLabels();
		if ( $result != false ) {

			while( $row = $result->fetch(PDO::FETCH_ASSOC) ) {
				$fields[ $row['Field'] ] = (!empty($labels[$row['Field']])) ? $labels[$row['Field']] : $row['Field'];
			}

			return $fields;
			
		} else {
			return false;
		}
	}

	public static function getTaskByPk($id) {

		$db = Db::getConnection();
		$result = $db->query('SELECT * FROM tasks  WHERE id = ' . addslashes($id) . '  LIMIT 1');

		if ( $result != false ) {

			$row = $result->fetch(PDO::FETCH_ASSOC);

			$model = new TasksModel;
			$model->id = $row['id'];
			$model->name = $row['name'];
			$model->email = $row['email'];
			$model->text = $row['text'];
			$model->done = $row['done'];
			$model->edited = $row['edited'];

			return $model;
			
		} else {
			return false;
		}

	}

	public static function getTasksList( $params = array() ) {

		$db = Db::getConnection();

		$tasksList = array();

		if ( isset( $params['orderby'] ) && $params['orderby'] !== '' ) {
			$orderby = addslashes($params['orderby']);
			$order = addslashes($params['order']);
		} else {
			$orderby = 'id';
			$order = 'DESC';
		}

		$result = $db->query('SELECT * '
				. 'FROM tasks '
				. 'ORDER BY `' . $orderby . '` ' . $order
				. ' LIMIT ' . addslashes($params['limit'])
				. ' OFFSET ' . addslashes($params['offset']) );

		if ( $result != false ) {

			while( $row = $result->fetch(PDO::FETCH_ASSOC) ) {

				$tasksList[] = $row;
			}
		}

		return $tasksList;

	}

	public function update() {

		$db = Db::getConnection();

		$sql = "UPDATE tasks SET" 
				. " name = :name,"
				. " email = :email,"
				. " text = :text,"
				. " done = :done,"
				. " edited = :edited"
				. " WHERE id = :id";
		$result = $db->prepare($sql);
		$result->bindParam(':id', $this->id, PDO::PARAM_STR);
		$result->bindParam(':name', $this->name, PDO::PARAM_STR);
    	$result->bindParam(':email', $this->email, PDO::PARAM_STR);
   		$result->bindParam(':text', $this->text, PDO::PARAM_STR);
   		$result->bindParam(':done', $this->done, PDO::PARAM_STR);
   		$result->bindParam(':edited', $this->edited, PDO::PARAM_STR);

   		if ( $this->validate() )
			$result->execute();
		return $this->error;
	}

	public function save() {

		$db = Db::getConnection();

		$sql = "INSERT INTO tasks (name, email, text) VALUES (:name, :email, :text)";

		$result = $db->prepare($sql);
		$result->bindParam(':name', $this->name, PDO::PARAM_STR);
    	$result->bindParam(':email', $this->email, PDO::PARAM_STR);
   		$result->bindParam(':text', $this->text, PDO::PARAM_STR);
   		if ( $this->validate() )
			$result->execute();
		return $this->error;

	}


	public function validate() {

		$return = true;
		$this->error = 'OK';

		if ( $this->text == '' ) {
			$return = false;
			$this->error = 'Заполните поле text';
		}
		if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
			$return = false;
			$this->error = 'email некорректен';
		}
		if ( $this->email == '' ) {
			$return = false;
			$this->error = 'Заполните поле email';
		}
		if ( $this->name == '' ) {
			$return = false;
			$this->error = 'Заполните поле name';
		}

		return $return;

	}

}