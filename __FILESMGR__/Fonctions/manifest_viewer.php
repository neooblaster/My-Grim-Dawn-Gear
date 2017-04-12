<pre>
<?php

	$package = $_GET['file']; 

	$zip = new ZipArchive();

	$zip->open("../Packages/$package");

	$zip->extractTo('../Temps/Viewer/', '__MANIFEST__/manifest');

	$zip->close();

	echo file_get_contents('../Temps/Viewer/__MANIFEST__/manifest');

?>
</pre>