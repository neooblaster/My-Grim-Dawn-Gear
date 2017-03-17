
<?php


	$scan = scandir('.');

foreach($scan as $k => $val){
	if(preg_match('#^[a-zA-Z]#', $val)){
		echo "<img src='$val' />";
	}
}



?>