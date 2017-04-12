<?php

	$package = $_POST['file']; 

	$zip = new ZipArchive();

	$zip->open("../Packages/$package");

	$zip->extractTo('../Temps/', '__MANIFEST__/THIS_MD5_SNAPSHOT');

	$zip->close();

	copy('../Temps/__MANIFEST__/THIS_MD5_SNAPSHOT', '../Packages/LAST_MD5_SNAPSHOT');

	echo "La référence MD5 est désormais celle du package $package";

?>