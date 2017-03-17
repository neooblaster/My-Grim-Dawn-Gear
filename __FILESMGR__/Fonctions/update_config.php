<?php

	$content = $_POST['content'];

	$content = str_replace("\r", "", $content); // "Conversion" dos2unix
	
	file_put_contents('../configs.ini', $content);

?>