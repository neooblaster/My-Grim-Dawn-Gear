<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<style>
			body {background: #231f1d;}
		</style>
	</head>
	<body>
		<?php

			ini_set('highlight.default', '#FFFFFF');
			ini_set('highlight.comment', '#17d617');
			ini_set('highlight.keyword', '#f92673');
			ini_set('highlight.string', '#e6db74');

			$code = file_get_contents('class');

			echo highlight_string($code);

		?>
	</body>
</html>