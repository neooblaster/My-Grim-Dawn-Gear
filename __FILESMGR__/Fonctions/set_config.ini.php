<?php

	$package = $_POST['file']; 

	$zip = new ZipArchive();

	$zip->open("../Packages/$package");

	$zip->extractTo('../Temps/', '__MANIFEST__/configs.ini');

	$zip->close();

	copy('../Temps/__MANIFEST__/configs.ini', '../configs.ini');

	echo "La référence config.ini est désormais celle du package $package";

?>