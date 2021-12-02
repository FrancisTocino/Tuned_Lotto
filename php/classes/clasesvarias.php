<?php

class ClasesVarias{
	

	public function imprime_tabla ($tabla=array(),$nombre_array) {
		$num = count($tabla);
		$tabla_ordenada = $tabla;
		//sort($tabla_ordenada);
		echo('</br>Imprimiendo Tabla:'.$nombre_array.'</br>');
		echo('</br>Número de elementos de la Tabla:'.$num.'</br>');
		foreach ( $tabla_ordenada as $clave => $valor){
		echo ('Indice: ' .$clave. ' >-> Valor: ' . $valor . '</br>');
		}		
		echo('</br> =========== </br>');
	}



} // end of class




?>	