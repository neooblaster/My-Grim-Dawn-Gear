<pre>
<?php

	$package = $_GET['file']; 

	$zip = new ZipArchive();

	$zip->open("../Packages/$package");

	$zip->extractTo('../Temps/Viewer/', '__MANIFEST__/configs.ini');

	$zip->close();

	echo file_get_contents('../Temps/Viewer/__MANIFEST__/configs.ini');

?>
</pre>