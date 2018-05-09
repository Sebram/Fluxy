<?php 
require('Obj.php');
$obj = new Obj();
$obj->setObj();

if($_GET) {
	$get = $_GET;

	$i=0;
	if(@$get['champ'] !== null) 
	{
		$sql = "select distinct ".$get['champ']." from habitation";
		$val = $obj->getcnx()->doQuery($sql) ;
		$array = [];
		foreach($val as $value) {
			$array[]=$value[$get['champ']];
		}
		echo json_encode($array);
	}
 
	elseif( @$get['field'] !== null &&  @$get['field'] != "" )
	{
		
	  $sql = "UPDATE habitation SET ".$get['field']."='".$get['newval']."' WHERE ".$get['field']."='".$get['valtomodify']."'; ";
	  $val = $obj->getcnx()->update($sql);
		if($val){
			echo "<mark>updated with success ! </mark>";
		}
		else echo "error ...";
	
	} 
}
