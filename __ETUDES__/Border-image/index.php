<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<style>
			* {font-family: sans-serif;}
			#container {
				width: 400px;
				height: 250px;
				position: relative;
				top: 20px;
				left: 50px;
				background: blue;
				border-width: 14px;
				border-image: url('../../Images/Interface/borders.png') 14 14 14 14 round;
				border-image-outset: 0px;
			}
			#content {
				width: 100%;
				height: 100%;
				background: red;
			}
		
		</style>
	</head>
	<body>
		<!-- TITLE -->
		<h1>
			Etude de construction de border à l'aide de border-image
		</h1>
		
		
		<!-- OBJECTIF -->
		<h2>
			Objectif : Supprimer toutes constructions par tableau
		</h2>
		
		
		<!-- I - Ressources -->
		<h3>
			Image source :
		</h3>
		<img src="../../Images/Interface/borders.png" />
		
		<h3>
			Formule Générale :
		</h3>
		
		<img src="border-image.png" />
		
		<ul>
			<li>border-image: url(path) h_slice_1 v_slice_2 h_slice_1 v_slice_1 border_repeat</li>
			<li>border-width: x_slice_y</li>
		</ul>
		
		<h3>
			Notes :
		</h3>
		
		<ul>
			<li>La bordure semble s'inscrire à l'intérieur contraiment aux bordure classique, la largeur du bloc reste égale à tot_width = width + border_width</li>
			<li>Le background s'applique derriere la bordure et donc en cas de bordure arrondie, il se voit.</li>
		</ul>
		
		<h3>
			Résultat :
		</h3>
		
		<div id="container">
			<div id="content">
				
			</div>
		</div>
	</body>
</html>