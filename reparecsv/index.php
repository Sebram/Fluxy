<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>import data</title>
<link rel="stylesheet" href="css/bootstrap.css">
<style>
	#header{
		padding:10px;
		background:#333333;
		color: white;
	}
</style>
</head>
<body>
<div id='header'> &lt;/IMPORT&gt; </div>
<div class="container">
	<br><br>
	<div class="row">
		<form action="">
		<label for="">Import fichier</label><br>
		<input type="text"><br><br>
		<label for="">Lier un fichier à une table</label><br>
		<input type="text"><br>
		</form>
	</div>


	<?php
	$addr = "Référence:02390R897";
	?>
	<br>
	
	<?php
	echo strtr($addr, array("Référence:"=> ""));
	?>
</div>

<br>

</body>
</html>