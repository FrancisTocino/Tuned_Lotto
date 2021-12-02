<html>
<head>
<title>IMPORTACION_EUROMILLONES.PHP</title>
<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css"  />
<script type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>
</head>


<?php

function combierte_fecha($cadena)
{
	echo ('<br>Estoy en la funcion de combertir la cadena: '.$cadena.' --- ');
	$partes = explode("/", $cadena);
	echo($partes[0].$partes[1].$partes[2]);
	$fech=strval($partes[2]).'-'.strval($partes[1]).'-'.strval($partes[0]);
	echo (' ------  '.$fech);
	return($fech);
}

?>



<?php

function importacion_loterias( $fileName ){

	require ("../classes/conectionDb.php");

			
	$con = new Conection (gethostname());
	$con->conect();
	$con->tables_check ();


 //CONEXIÓN CON EL FICHERO PLANO Y LECTURA

		$fName=$fileName;
		$fp=fopen($fName,"r") or die('<li>Fallo al abrir el fichero: '.$fileName.'...'. mysql_error(). '</li><br>');
		echo '[+] FICHERO CSV ABIERTO</br>';

//VACIAMOS LA TABLA PARA IMPORTAR LOS DATOS NUEVOS

		$db_query1 ='truncate euromillones';
		$enlace = $con->enlace;
		$resultado = mysqli_query($enlace,$db_query1);
					if (!$resultado) {
						$message  = 'Invalid query: ' . mysqli_error() . "\n";
						$message .= 'Whole query: ' . $query;
						die($message);
					} 
		echo ('<li>[+] VACIANDO LA TABLA PARA VOLVER A POBLARLA</li><br>');

// EMPEZAMOS A LEER Y A CONVERTIR

		echo ('<li>Importando ...</li><br>');
		$id_sorteo=0;
		while(!feof($fp))
		{
			echo '</br>LEMEOS UNA LINEA';
			$linea = fgets($fp);
			echo '//EXTTRAEMOS LOS CAMPOS SEPRADOS POR' ;
			$campos = explode(",", $linea);
			echo '// LEO LA PRIMERA LINEA';
			$fecha_euromillones = $campos[0];
			echo '// Y VEMOS LOS CASOS';
			echo '</br> $fecha_Euromillones = ' . $fecha_euromillones;
			switch ($fecha_euromillones)

			{
				case (strpos($fecha_euromillones, 'FECHA') !== false):
					echo '<li>DETECTADO CABECERA, DESCARTADADA PARA LA IMPORTACION (OK)... </li><br>';
					echo('$fecha_euromillones: '.$fecha_euromillones);
					break;
				case NULL:  //LINEA VACIA				
					echo '<li>DETECTADO LINEA VACIA, DESCARTADA PARA LA IMPORTACION (OK)... </li><br>';
					break;
				default: //PASO LOS VALORES PARA IMPORTAR
						// ASIGNO LAS VARIABLES
						$id_sorteo++; 
						echo ('</br>id_sorteo: '.$id_sorteo);
						$fecha_euromillones= combierte_fecha($campos[0]);
						$numero1=			 $campos[1];
						$numero2=			 $campos[2];
						$numero3=		  	 $campos[3];
						$numero4=			 $campos[4];
						$numero5=			 $campos[5];
						$valorvacio=		 $campos[6];
						$estrella1 =		 $campos[7];
						$estrella2 =		 $campos[8];
					
						$db_query1 ='insert into euromillones values(
									"'.$id_sorteo.'",
									"'.$fecha_euromillones.'",
									"'.$numero1.'",
									"'.$numero2.'",
									"'.$numero3.'",
									"'.$numero4.'",
									"'.$numero5.'",
									"'.$estrella1.'",
									"'.$estrella2.'");';
								echo '<br>'.$db_query1.'<br>';
								$resultado = mysqli_query($enlace,$db_query1);
								if (!$resultado) {
									$message  = 'QUERY INCORRECTA: ' . mysqli_error($enlace) . "\n";
									$message .= 'Query COMPLETA: ' . $db_query1;
									die($message);
								}
								echo ('<li id="detalle">Importando dato fila: '.$id_sorteo.' (OK) ...</li>');
			}

		}

		echo ('<li id="detalle">Numeros de sorteos importados: '.$id_sorteo.' (ok) ...</li>');
		echo '<br />---- Importacion finalizada correctamente-------------';
		//CIERRO EL FICHERO PLANO
		fclose($fp);
		// CIERRO LA CONEXIÓN CON LA BASE DE DATOS
		mysqli_close($enlace);

}


?>



<body> <!--INICIO - - - - - - - - - - - - - - - -->

<div >

<p><u>IMPORTANDO DATOS</u><br>

 



<?php



if (!empty($_FILES)) {

   // echo('$tempFile = '.$tempFile = $_FILES['NombreFichero']['tmp_name']);
	//echo('<br />$file_name = '.$file_name = $_FILES['NombreFichero']['name']);   
   // echo('<br />$targetPath = '.$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder']);
   // echo('<br />$targetFile = '.$targetFile =  str_replace('//','/',$targetPath) . $file_name);


	$tempFile   = $_FILES['NombreFichero']['tmp_name'];
    $file_name  = $_FILES['NombreFichero']['name'];   
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/_tunedlotto/php/importacion/';
    $targetFile = $file_name;


	echo '</br>$targetPath: '.$targetPath; 	
	echo '</br>$targetFile: '.$targetFile;

	echo('<br />[+] Subiendo archivo al servidor...');   


    if (move_uploaded_file($tempFile,$targetFile)){
		echo '<br />[+] El archivo se subio correctamente ... [ok]';
        $fileName = $_FILES['NombreFichero']['name'];
		importacion_loterias( $fileName );
    } else {
        echo '<br />[x] Fallo en la subida del archivo...';
		echo '<br />[x] El archivo esta corrupto o no es el correcto.';
    }
}





?>





</div>

</body>

</html>

