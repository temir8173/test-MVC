<section>
	<div class="container">
		<?php if ( isset($result) && $result != NULL ) : ?>
			<div class="alert alert-<?= ($result == 'OK') ? 'success' : 'warning' ?>" role="alert">
				<?= ($result == 'OK') ? 'Данные успешно добавлены' : $result ?>
			</div>
		<?php endif; ?>
		<?php if ( isset($resultEdit) && $resultEdit != NULL ) : ?>
			<div class="alert alert-<?= ($resultEdit == 'OK') ? 'success' : 'warning' ?>" role="alert">
				<?= ($resultEdit == 'OK') ? 'Данные успешно обновлены' : $resultEdit ?>
			</div>
		<?php endif; ?>
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<?php foreach ( $taskFields as $field => $label ) : ?>
						<th scope="col"><a href="?orderby=<?= $field; ?>&order=<?= $orderNext ?>"><?= $label; ?></a></th>
					<?php endforeach; ?>
					<th scope="col"></th>
				</tr>
			</thead>
		  	<tbody>
		  			<tr>
		  				<form method="POST" action="/tasks/add">
							<th scope="row"></th>
							<td><input type="text" name="Form[name]"></td>
							<td><input type="text" name="Form[email]"></td>
							<td><input type="text" name="Form[text]"></td>
							<td></td>
							<td></td>
							<td><input type="submit" name="" value="Добавить новую"></td>
						</form>
				    </tr>
			  	<?php foreach ( $tasksList as $task ) : ?>

			  		<?php if ( !empty($_SESSION['login']) && !empty($_GET['edit']) && $_GET['edit'] == $task['id'] ) : ?>
					    <tr class="<?= ($task['done'] == 1) ? 'alert-success' : '' ?>" >
					    	<form action="/tasks/edit" method="POST">
					    		<input type="text" class="hidden" name="Edit[id]" value="<?= $_GET['edit'] ?>">
					    		<input type="text" class="hidden" name="GetParams[page]" value="<?= $pagination['current'] ?>">
					    		<input type="text" class="hidden" name="GetParams[order]" value="<?= $order ?>">
					    		<input type="text" class="hidden" name="GetParams[orderby]" value="<?= $orderby ?>">
								<th scope="row"><?= $task['id'] ?></th>
								<td><?= $task['name'] ?></td>
								<td><?= $task['email'] ?></td>
								<td><input type="text" name="Edit[text]" value="<?= $task['text'] ?>"></td>
								<td>
									<select name="Edit[done]">
										<option value="1">Выполнено</option>
										<option value="0" <?= ($task['done'] == 0) ? 'selected' : '' ?>>Не выполнено</option>
									</select>
								</td>
								<td><?= ($task['edited'] == 1) ? 'Отредактировано' : 'Нет' ?></td>
								<td><input type="submit" name="submit" value="Сохранить"></td>
							</form>
					    </tr>
				    <?php else : ?>
				    	<tr class="<?= ($task['done'] == 1) ? 'alert-success' : '' ?>" >
							<th scope="row"><?= $task['id'] ?></th>
							<td><?= $task['name'] ?></td>
							<td><?= $task['email'] ?></td>
							<td><?= $task['text'] ?></td>
							<td><?= ($task['done'] == 1) ? 'Да' : 'Нет' ?></td>
							<td><?= ($task['edited'] == 1) ? 'Отредактировано' : 'Нет' ?></td>
							<?php if ( !empty($_SESSION['login']) ) : ?>
								<td><a href="<?= (!empty($_GET) ? $_SERVER['REQUEST_URI'].'&' : '?') ?>edit=<?= $task['id'] ?>">Редактировать</a></td>
							<?php else: ?>
								<td></td>
							<?php endif; ?>
							
					    </tr>
				    <?php endif; ?>

				<?php endforeach; ?>
		    
			</tbody>
		</table>


		<?php 

		if ( isset($pagination) && !empty($pagination) && $pagination['countPages'] > 1 ) {

			echo '<nav aria-label="...">';
			echo '<ul class="pagination">';
			$pages = 0;

			while( $pages < $pagination['countPages'] ) {
				$pages++;
				$active = ($pages == $pagination['current']) ? 'active' : '';
				$orderUrl = (!empty($_GET['orderby'])) ? '?orderby=' . $_GET['orderby'] . '&order=' . $_GET['order'] : '';

				echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $pages . $orderUrl . '">' . $pages . '</a></li>';

			}

			echo '</ul>';
			echo '</nav>';

		} 

		?>

	</div>
</section>