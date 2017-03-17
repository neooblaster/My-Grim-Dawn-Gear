<?php

	$_FILES = fixFilesArray($_FILES);
	$tag_name = $_POST['tag_name'];

	foreach($_FILES as $key => $file){
		if($file['error'] === 0){
			if(preg_match('#\.png$#i', $file['name'])){
				move_uploaded_file($file['tmp_name'], "../../../Images/Items/$tag_name.png");
			}
		}
	}
















?>