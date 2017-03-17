<?php
	$path_file = '../Packages/LAST_MD5_SNAPSHOT';

	if(isset($_POST['md5_content'])){
		file_put_contents($path_file, $_POST['md5_content']);
	}
?>

<form method="post" action="edit_md5.php">
	<input type="submit" value="Sauvegarder"/><br />
	<textarea style="width: 100%; height: 750px;" name="md5_content"><?php if(file_exists($path_file)){echo file_get_contents($path_file);}?></textarea>
</form>