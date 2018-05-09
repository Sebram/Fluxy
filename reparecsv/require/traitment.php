<?php

require('Obj.php');

$obj = new Obj();

$obj->setObj();

$get = $_POST;

$tablename = $_POST["tablename"]; 

$path = $_POST["path"];

$telhab=$_POST["csvref-0"];

$messagevalue = "";

$maj_form = "";

if(@$telhab != '' && @$telhab !== null )
{
	$query = $obj->initQuery($get, $path, $tablename, $telhab);

	$messagevalue = "<blockquote><em> ... ".explode('SET', $query)[1]."</em>";
	
	$messagevalue .= "<br>";

	if( $obj->getcnx()->insertLoadFileQuery($query) )
	{
		$messagevalue .= "<em> ... DATABASE $tablename UPDATED WITH SUCCESS ! </em></blockquote><br>";
		
		$messagevalue .= "<br>";
		
		$messagevalue .=  "<br>";
		
		$maj_form = file_get_contents("maj_form.php");	

	} else {
		
		$maj_form = "";
	}
}
else 
{
	echo "<strong>...ERROR merci d'entrer une valeur dans le premier champ</strong><br> <a href='../action.php'>back</a>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>import data</title>
<link rel="stylesheet" href="../css/bootstrap.css">
</head>
<style>
	#header{
		padding:10px;
		background:#333333;
		color: white;
		margin-bottom:50px;
	}
	.my-padding{
		margin-top:100%;
		background:#333333;
		width: 100%;
		height: 40px;
	}
</style>
<body>
	<div id='header'> &lt;/IMPORT&gt; </div>
	<div class="container">
		
		<?=$messagevalue?>
		<?=$maj_form?>

		<div id="debug"></div>
	</div>
	<div class="my-padding"></div>
	<script src="../js/jquery.js"></script>
	<script src="../js/maj.js"></script>
</body>
</html>