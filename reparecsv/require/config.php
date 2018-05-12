<?php # config.php
# ---------------------------------------------------------------------
set_time_limit ( 30000 );
class Config
{
	private $dbname;
	private $dbusername;
	private $dbpass;
	
	# define( '_dbconfig_', array( 'dbname', 'host', 'user', 'userpass' ) );
	private function setDefine($post="")
	{
		if(isset($post["Pass"])) 
		{
				if(explode('.',phpversion ())[0] >= 7) {
						define( '_dbconfig_', array( $post["Database"],$post["Host"],$post["User"],$post["Pass"] ) ); 
						return _dbconfig_;
				}		
				else { 
					$arraydefine = array( $post["Database"],$post["Host"],$post["User"],$post["Pass"] );  
					return $arraydefine ;
				} 
		}
		else 
		{
				
				//$arraydefine = array( 'passimmolocal', 'localhost', 'root', '' );  
			$arraydefine = array( 'database', 'host', 'user', 'password' );  
				return $arraydefine ;
		}
	}
	
	
	public function setdbConfig($post="")
	{
			if(@$post)$arraydbconf  =  $this->setDefine($post);
			else $arraydbconf  =  $this->setDefine();
			$this->dbname = $arraydbconf[0];
			$this->dbhost = $arraydbconf[1];
			$this->dbusername = $arraydbconf[2];
			$this->dbpass = $arraydbconf[3];
	}
	public function getDbname () {
		return $this->dbname;
	}
	public function getDbhost () {
		return $this->dbhost;
	}
	public function getDbuser () {
		return $this->dbusername;
	}
	public function getDbpass () {
		return $this->dbpass;
	}
	public function handleException( $exception )  {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
	}
	public function getConfig () {
		return array("mysql:host=".$this->getDbhost().";dbname=".$this->getDbname()."", $this->getDbuser(), $this->getDbpass());
	}
}


# ---------------------------------------------------------------------
class Model
{
	public $cnx ;
	public $_cnx ;
	public $confobj;
	public function __construct( Config $C, $post='' ) {	
		$this->confobj = $C;
		if(@$post) { $C->setdbConfig($post); }
		else { $C->setdbConfig(); }
		$this->cnx = $C->getConfig ();
	}
	public function getCnx() {
		return $this->cnx;
	}
	public function connected() {
		if( $this->_cnx ) { return TRUE; }
		else return $this->_cnx->errorInfo(); 
	}
	public function set() {
		$this->_cnx = new PDO( $this->getCnx()[0], $this->getCnx()[1], $this->getCnx()[2], array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ));
	}
	public function closeCnx() {
		$this->_cnx = null; 
	}


	public function doQuery( $query ) {
 	$query 	= $query;
 	$result = $this->_cnx->prepare( $query );
 	$result->execute();	
 	$row 	= $result->fetchAll( PDO::FETCH_ASSOC ) ;
  // echo json_encode($row, JSON_FORCE_OBJECT);
 	return 	$row;
	}


	public function update( $query ) {
	 	$query 	= $query;
	 	$result = $this->_cnx->prepare( $query );
	 	if($result->execute()){
	 		return true;
	 	}
		else return $result->errorInfo();
	}
	public function getAllFromTableName($table, $DESC="", $id="") {
 	$query 	= "SELECT * FROM ".$table;
 	if($DESC!="" && $id!=""){ $query 	= "SELECT * FROM ".$table ." ORDER BY $id DESC"; }
 	$result = $this->_cnx->prepare( $query );
 	$result->execute();	
 	$row 	= $result->fetchAll( PDO::FETCH_ASSOC ) ;
 	return 	$row;
	}


	public function getFromTableNameWhere( $newtable, $habit_mandat ) {
		$query 	= "SELECT * FROM ".$newtable." WHERE tmp0 ='".$habit_mandat."'";
 		$result = $this->_cnx->prepare( $query );
	 	if($result->execute()){
		 	$row 			= $result->fetchAll( PDO::FETCH_ASSOC ) ;
		 	//print_r($result->errorInfo());
		 	if(count($row)>0)echo ".";
		 	return 	$row;
	 	}else{
	 		print_r($result->errorInfo());
	 	}
	}
	
	public function deleteTableHabProp($table,$idhabit){
		$query ="DELETE FROM $table WHERE id_habit = $idhabit";
		$sql=$this->_cnx->prepare($query);
		if($sql->execute()){
			return true;
		}else{
			print_r($sql->errorInfo()); 
		}
	}

	public function dropTable($table){
		$query ="DROP TABLE $table";
		$sql=$this->_cnx->prepare($query);
		if($sql->execute()){
			return true;
		}else{
			return false; #$sql->errorInfo(); 
		}
	}

	public function createTable( $table, $nbfields ) {
   //$this->_cnx->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling	
			$sql ="CREATE TABLE $table (
    id INT( 11 ) AUTO_INCREMENT PRIMARY KEY ";
    for($i=0; $i<$nbfields; $i++) {
			$sql .= ", tmp".$i." VARCHAR( 500 )";
		}
		$sql .= ");";

		if($this->_cnx->exec($sql)) {
			return true;
		}
		else{ 
			if($this->_cnx->errorInfo()[2] != ""){
				echo "<em>";
				print_r($this->_cnx->errorInfo()[2]); //return false;  #$sql->errorInfo(); 
				echo "</em>";
			}
			else echo "<em>Photos $table ok !</em>";
			
		}
	}

	public function getRefTable($tablename )
	{
		$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$tablename."'";
		$tmp_result = $this->_cnx->prepare( $query );
		$tmp_result->execute();	
		$row 	= $tmp_result->fetchAll( PDO::FETCH_ASSOC ) ;
		return 	$row;
	} 



	public function getIdhabit( $ref, $telhab )
	{
		$query = "SELECT idhabit FROM habitation WHERE telhab='".$telhab."' AND numero='".$ref."' AND idedit='".$ref."'";
		$tmp_result = $this->_cnx->prepare( $query );
		$tmp_result->execute();	
		$row 	= $tmp_result->fetch( PDO::FETCH_ASSOC ) ;
		print_r($tmp_result->errorInfo()[2]);
		return 	$row;
	} 



	public function insertLoadFileQuery($query) {
			$server = $this->confobj->getDbhost(); 
			$username = $this->confobj->getDbuser(); 
			$password = $this->confobj->getDbpass(); 
			$bdd = $this->confobj->getDbname();
			$sqlcnx = mysqli_connect($server,$username,$password) or die ("No Connection");
			if($sqlcnx) {
			 mysqli_select_db($sqlcnx, $bdd) or die ( "No Database found!" );
				$db = mysqli_query($sqlcnx, $query) or die ("<H4> ERROR ...".mysqli_error($sqlcnx)."</H4>"); 
				if( $db ){ return "registred"; }
			}
			else return "<h3> ERROR ...".mysqli_error($sqlcnx) ;
	}

	public function getLastInsertedHabit($table, $limit, $telhab) {
	 	$query 	= "SELECT idhabit, numero, idedit, telhab FROM ".$table." WHERE telhab ='".$telhab."' ORDER BY idhabit DESC LIMIT ".$limit."";
	 	$result = $this->_cnx->prepare( $query );
	 	$result->execute();	
	 	$row 			= $result->fetchAll( PDO::FETCH_ASSOC ) ;
	 	return 	$row;
	}

	public function getLastInserted( $table, $limit , $idname="id" ) {
	 	$query 	= "SELECT * FROM ".$table." ORDER BY ".$idname." DESC LIMIT ".$limit."";
	 	$result = $this->_cnx->prepare( $query );
	 	$result->execute();	
	 	$row 			= $result->fetchAll( PDO::FETCH_ASSOC ) ;
	 	return 	$row;
	}

	public function insertUrlphoto($idhabit,  $urlphoto ) {
			// TODO INSERT idhabit into csvimagetmp where tmp0 = ref
				$stmt = $this->_cnx->prepare("UPDATE habitation SET urlphoto = :urlphoto WHERE idhabit=:idhabit");
				$stmt->bindParam(":urlphoto", $urlphoto);
				$stmt->bindParam(":idhabit", $idhabit);
				if($stmt->execute()){
					return true;
				}
				else return false;
	}
	
	public function insertDescriptif($idhabit,  $text ) {
			
				$stmt = $this->_cnx->prepare("UPDATE habitation SET descriptif = :texte WHERE idhabit=:idhabit");
				$stmt->bindParam(":texte", $text);
				$stmt->bindParam(":idhabit", $idhabit);
				if($stmt->execute()){
					return true;
				}
				else return false;
	}


	public function insertHabProp($idhabit,  $idproprietaire ) {
			$stmt = $this->_cnx->prepare( "INSERT INTO habitation_proprietaire SET id_habit = :idhabit, id_proprietaire=:idproprietaire" );
			$stmt->bindParam(":idhabit", $idhabit);
			$stmt->bindParam(":idproprietaire", $idproprietaire);
			if($stmt->execute()){
				return true;
			}
			else print_r( "<h3> ERROR ...".$stmt->errorInfo()[2] ." </h3> ");
	}



	public function insertProprietaire( $proprietaires ) {
			
		$query = "INSERT INTO proprietaire SET ";
		$query .= "nom_proprietaire=:nom_proprietaire,";
		$query .= "idpers_proprietaire=:idpers_proprietaire,";
		$query .= "adresse_proprietaire=:adresse_proprietaire,";
		$query .= "ville_proprietaire=:ville_proprietaire,";
		$query .= "departement_proprietaire=:departement_proprietaire,";
		$query .= "telm_proprietaire=:telm_proprietaire,";
		$query .= "telf_proprietaire=:telf_proprietaire,";
		$query .= "mail_proprietaire=:mail_proprietaire ON DUPLICATE KEY UPDATE ";
		$query .= "nom_proprietaire=:nom_proprietaire,";
		$query .= "idpers_proprietaire=:idpers_proprietaire,";
		$query .= "adresse_proprietaire=:adresse_proprietaire,";
		$query .= "ville_proprietaire=:ville_proprietaire,";
		$query .= "departement_proprietaire=:departement_proprietaire,";
		$query .= "telm_proprietaire=:telm_proprietaire,";
		$query .= "telf_proprietaire=:telf_proprietaire,";
		$query .= "mail_proprietaire=:mail_proprietaire; ";

		$stmt = $this->_cnx->prepare($query);
		$stmt->bindParam(":nom_proprietaire" ,$proprietaires ["nom_proprietaire"]);
		$stmt->bindParam(":idpers_proprietaire" ,$proprietaires ["idpers_proprietaire"]);
		$stmt->bindParam(":adresse_proprietaire" ,$proprietaires ["adresse_proprietaire"]);
		$stmt->bindParam(":ville_proprietaire" ,$proprietaires ["ville_proprietaire"]);
		$stmt->bindParam(":departement_proprietaire" ,$proprietaires ["departement_proprietaire"]);
		$stmt->bindParam(":telm_proprietaire" ,$proprietaires ["telm_proprietaire"]);
		$stmt->bindParam(":telf_proprietaire" ,$proprietaires ["telf_proprietaire"]);
		$stmt->bindParam(":mail_proprietaire" ,$proprietaires ["mail_proprietaire"]);
		if($stmt->execute()){
			return true;
		}
		else print_r($stmt->errorInfo()[2]);
	}



	public function insertAcheteur( $acheteur ) 
	{
		$query = "INSERT INTO acquereur SET ";
		$query .= "idpers=:idpers,";
		$query .= "nomacquereur=:nomacquereur,";
		$query .= "adresse=:adresse,";
		$query .= "cp=:cp,";
		$query .= "ville=:ville,";
		$query .= "telacquereur=:telacquereur,";
		$query .= "portacquereur=:portacquereur,";
		$query .= "emailacquereur=:emailacquereur,";
		$query .= "visible=:visible ON DUPLICATE KEY UPDATE ";
		$query .= "idpers=:idpers,";
		$query .= "nomacquereur=:nomacquereur,";
		$query .= "adresse=:adresse,";
		$query .= "cp=:cp,";
		$query .= "ville=:ville,";
		$query .= "telacquereur=:telacquereur,";
		$query .= "portacquereur=:portacquereur,";
		$query .= "emailacquereur=:emailacquereur,";
		$query .= "visible=:visible";

		$stmt = $this->_cnx->prepare($query);
		$stmt->bindParam(":idpers" ,$acheteur["idpers"]);
		$stmt->bindParam(":nomacquereur" ,$acheteur["nomacquereur"]);
		$stmt->bindParam(":adresse" ,$acheteur["adresse"]);
		$stmt->bindParam(":cp" ,$acheteur["cp"]);
		$stmt->bindParam(":ville" ,$acheteur["ville"]);
		$stmt->bindParam(":telacquereur" ,$acheteur["telacquereur"]);
		$stmt->bindParam(":portacquereur" ,$acheteur["portacquereur"]);
		$stmt->bindParam(":emailacquereur" ,$acheteur["emailacquereur"]);
		$stmt->bindParam(":visible" ,$acheteur["visible"]);
		if($stmt->execute())
		{
		//	echo $query;
			return true;
		}
		else print_r($stmt->errorInfo()[2]);
	}


	public function putFile( $path, $table ) {
		$query      = "SELECT * INTO OUTFILE '$path' FROM $table";
		$stmt = $this->_cnx->prepare($query);
		if($stmt->execute()){return true;}
		else {
		 echo  "<h4 style='background-color:blue; color:white'><em>";
		 print_r($stmt->errorInfo()[2]);
		 echo  "</em></h4>";
		}
	}
	

	# (! visible2 devra être remplacer par "etat" pour passimmo )
	public function updateVisible($up = true, $idhabit) 
	{
		if($up) 
		{
			$stmt = $this->_cnx->prepare( "UPDATE habitation SET visible = 1, visible2 = '' WHERE idhabit = '$idhabit'" );
			if($stmt->execute()){
				return true;
			}
			else print_r( "<h3> ERROR ...".$stmt->errorInfo()[2] ." </h3> ");
		}
		else {
			$stmt = $this->_cnx->prepare( "UPDATE habitation SET visible = 3, visible2 = '', archivage_statut = 3, vendu_loue = 1 WHERE idhabit = '$idhabit'" );
			if($stmt->execute()){
				return true;
			}
			else print_r( "<h3> ERROR ...".$stmt->errorInfo()[2] ." </h3> ");	
		}
	}
	
	public function updateLoc($loc, $idhabit) 
	{
		$stmt = $this->_cnx->prepare( "UPDATE habitation SET loc = '$loc' WHERE idhabit = '$idhabit'" );
		if($stmt->execute()){
			return true;
		}
		else print_r( "<h3> ERROR ...".$stmt->errorInfo()[2] ." </h3> ");
	}

}
# ---- Exemple to use Config & Model 
# $C = new Config();
# $cnx = new Model( $C, $_POST );
# $cnx->set();
# -----------------------------------


