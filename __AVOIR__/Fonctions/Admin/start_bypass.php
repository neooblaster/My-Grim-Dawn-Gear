<?php

	session_start();

	$_SESSION['admin'] = true;

	header('location: ../');

?>