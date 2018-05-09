<?php
# ----------------- USING OBJ AND REPARECSV CLASS -----------------
# ==================================================================
require_once "require/RepareCsv.php";
require_once "require/Obj.php";

$id_primaire = "telhab";
$tablename =	"habitation";
$path 		 =	"archive/ANNONCE.csv";

$newlines  =	[];
$separator =	";";
$lines 		 =	RepareCsv::lireLigne($path);
$nbch			 =	""; // nombre de champs
$newline 	 =	""	;

$newfile	 =	"/Users/Sebram/Weborium/reparecsv/archive/foo.txt";


$Obj =  new Obj();

$Obj->setObj('habitation');


# ----------------------------------- VERIF CSV -------------------------------------

foreach ( $lines as $i => $line ) {	
	if($i == 0) {
		$nbch = RepareCsv::compterChamps( $lines[0][0], $separator, $i );
	}
	$testline = RepareCsv::traiterLigne ( $lines, $i, $nbch , $separator , $newline);
  $nline 		=  RepareCsv::compterChamps( $testline, $separator, $i );	
	if($nline==$nbch) {
		$newlines[]=$testline;
	}
	else { 
		$newline .= RepareCsv::traiterLigne ( $lines, $i, $nbch , $separator , $newline);
		$nline 		= RepareCsv::compterChamps( $testline, $separator, $i ) ;
		if ($nline==$nbch) {
			$newlines[]=$testline;
			$newline="";
		}
	}
}

$message =  "<br><br><blockquote>Csv file is ok ! " . count($newlines) . " lignes </blockquote>";

$i=0;

if(file_exists($newfile)){
	unlink($newfile);
}


$Obj->setPathToWrite($newfile);

$Obj->write($newlines);



/*if( file_exists( $Obj->getclassname() ) ) {
	require_once $Obj->getclassname();
	$message .=   " &  ".$Obj->getclassname() . " existe.</blockquote>";
	$bien = new DBHabitation();*/
	/*$types_values = array( 'int', 'float', 'bool', 'varchar', 'text' );*/
/*} */
?>
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

		<div class="col-lg-12 col-md-12">
			<?=$message?>
			<hr>
		</div>
		<?php include "require/form.php"; ?>

</div>

<br>

</body>
</html>