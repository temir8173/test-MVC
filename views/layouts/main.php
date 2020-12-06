<!DOCTYPE html>
<html>
<head>
	<title><?= $this->title ?></title>
	<link rel="stylesheet" type="text/css" href="../../assets/css/main.css">
	<link rel="stylesheet" type="text/css" href="../../assets/bootstrap/bootstrap.min.css">
</head>
<body>
	<header>
		<div class="container">
			<?php if ( !empty($_SESSION['login']) ) : ?>
				<?= 'Здравствуйте, ' . $_SESSION['login'] ?>
			<?php endif; ?>
			<ul class="nav nav-pills">
				<li role="presentation">
					<a href="/" >Задачи</a>
				</li>
				<?php if( empty($_SESSION['login']) ) : ?>
					<li role="presentation">
						<a href="/site/signin" >Войти</a>
					</li>
				<?php else : ?>
					<li role="presentation">
						<a href="/site/logout" >Выйти</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</header>

	<?php $this->renderPartial($view, $params); ?>

</body>
</html>