<section>
	
	<div class="container">
		
		<?php if ( isset($result) && $result != NULL ) : ?>
			<div class="alert alert-<?= ($result == 'OK') ? 'success' : 'warning' ?>" role="alert">
				<?= ($result == 'OK') ? 'Данные успешно добавлены' : $result ?>
			</div>
		<?php endif; ?>

		<form action="" method="POST">
			<input type="text" name="login">
			<input type="password" name="password">
			<input type="submit" name="submit" value="Войти">
		</form>

	</div>

</section>