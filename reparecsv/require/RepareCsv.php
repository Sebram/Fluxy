<?php
class RepareCsv
{

	# String file
	#
	public static function lireLigne(&$path)
	
	{
		$tabline=[];
		if(($handle = fopen ( $path, "r" )) !== FALSE ) {
			while (($line = fgetcsv( $handle, 0, "\n" )) !== FALSE ) {
				$tabline []= $line;
			}
		}
		return $tabline;
	}


	# String line
	#
	public static function compterChamps( &$line, &$separator, &$i)
	
	{
		//var_dump($line[0]);
		$tab_n = "";
		
		$tab_n = explode($separator, $line);
		
		return count($tab_n);
	}


	# Array lines
	#	Int nbch (nombre de références dans le csv)
	# Int indice
	# String $newline
	#
	public static function traiterLigne( &$lines, &$indice, &$nbch, &$separator, &$newline="" )
	
	{
		$nbc = Self::CompterChamps( $lines[$indice][0], $separator, $indice);

		if( $nbc == $nbch ) {

			return $lines[$indice][0];

		} else {

				$newline += '"'.$lines[$indice][0].'"';

				return $newline;
			}
	}


	public static function getCsvRef(&$path)

	{
		$cles = [];
	
		$handle = fopen($path, "r");
	
		if ($handle) {		
	
			while (($line = fgets($handle)) !== false) {
		
				$cles [] = explode(";", str_replace('"', '', $line) );
		
				break;
		
			}
		
			fclose($handle);
	
		} else {
			
			echo  " error opening the file.";
		
		}
		
		return $cles;
	}

}

