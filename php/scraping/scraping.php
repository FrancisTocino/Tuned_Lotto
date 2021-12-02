<?php



//Libreria para scrapin-web
require './simple_html_dom.php';

$fechaVerdadera = date('d/m/Y A G:i:s');

// Abro fichero de Log
$file = fopen("scraping.log", "a");
fwrite($file,'[=====================================================================]'.PHP_EOL);
fwrite($file,'[                                                                     ]'.PHP_EOL);
fwrite($file,'[                  COMIENZO DEL PROCESO DE SCRAPING                   ]'.PHP_EOL);
fwrite($file,'[                        '.$fechaVerdadera.'                          ]'.PHP_EOL);
fwrite($file,'[=====================================================================]'.PHP_EOL);


//ME CONECTO CON LA BASE DE DATOS
$conex = conexion_db();

// ME ENTERO EN QUE DIA ESTAMOS
$fecha_actual = getdate();
fwrite($file,'Día de la Semana del Sistema: '.$fecha_actual['weekday'] .PHP_EOL);
fwrite($file,PHP_EOL);
fclose($file);

// Vemos los casos
switch ($fecha_actual['weekday']) {
    case 'Monday':
        scraping_Elgordo($conex);
        break;
    case 'Tuesday':
        scraping_Bonoloto($conex); 
        break;
    case 'Wednesday':
        scraping_Bonoloto($conex);
        scraping_Euromillones($conex);
        break;
    case 'Thursday':
        scraping_Bonoloto($conex);
        break;
    case 'Friday':
        scraping_Bonoloto($conex);
        scraping_Primitiva($conex);
        break;
    case 'Saturday':
        scraping_Bonoloto($conex);
        scraping_Euromillones($conex);
        break;
    case 'Sunday':
        scraping_Bonoloto($conex);
        scraping_Primitiva($conex);
        break;

}

mysqli_close($conex);



// ------------------------------------------------------------------------------------
function scraping_Primitiva($enlace){ 

	// Abrimos dl fichero log
	$file = fopen("scraping.log", "a");
	
	$fechaVerdadera = date('d/m/Y A G:i:s');
	fwrite($file,'[========================]'.PHP_EOL);
	fwrite($file,'[      PRIMITIVA         ]'.PHP_EOL);
	fwrite($file,'[  '.$fechaVerdadera.'   ]'.PHP_EOL);
	fwrite($file,'[                        ]'.PHP_EOL);

	//Restar 1 dia a la fecha actual
	$hoy = date('D Y-m-j');
	$nuevafecha = strtotime ( '-1 day' , strtotime ( $hoy ) ) ;
	$nuevafecha = date ('D j/m/Y' , $nuevafecha );
	fwrite($file,date('D j/m/Y G:i:s').' [+] Fecha a guardar: ' .$nuevafecha.PHP_EOL);



	//BUSQUEDA DE LOS NUMEROS DENTRO DE LA WEB DE LOTERIAS Y APUESTAS
	$url = 'https://www.loteriasyapuestas.es/es/resultados';
	$html = file_get_html( $url );

	//BUSQUEDA DE LA COMBINACIÓN GANADORA
	$busqueda1 = $html->find('li.c-ultimo-resultado__combinacion-li--primitiva');

	$n1 = $busqueda1[0]->plaintext; fwrite($file,date('d/m/Y G:i:s').'[+] N1: '.$n1.' - ');
	$n2 = $busqueda1[1]->plaintext; fwrite($file,'N2: '.$n2.' - ');
	$n3 = $busqueda1[2]->plaintext; fwrite($file,'N3: '.$n3.' - ');
	$n4 = $busqueda1[3]->plaintext; fwrite($file,'N4: '.$n4.' - ');
	$n5 = $busqueda1[4]->plaintext; fwrite($file,'N5: '.$n5.' - ');
	$n6 = $busqueda1[5]->plaintext; fwrite($file,'N6: '.$n6.' - ');

	//BUSQUEDA DEL COMPLEMENTARIO
	$busqueda3 = $html->find('li.c-ultimo-resultado__complementario-li--primitiva');
	$comp = $busqueda3[0]->plaintext;
	fwrite($file,'Complementario: '.$comp.PHP_EOL);
	fwrite($file,PHP_EOL);


	// ASIGNO LAS VARIABLES				
	$id_primitiva = NULL;
	$fecha_primitiva = strtotime ( '-1 day' , strtotime ( $hoy ) ) ; //RESTO 1 dia al la fecha actual
	$fecha_primitiva = date('Y-m-d', $fecha_primitiva) ; // Le doy Formato de Mysql
	
	//QUERY ------- 
	$db_query1 ='insert into primitiva values(Null,"'.$fecha_primitiva.'","'.$n1.'","'.$n2.'","'.$n3.'","'.$n4.'","'.$n5.'","'.$n6.'","'.$comp.'");';
	
	//------------

	fwrite($file,date('d/m/Y G:i:s').' Query: '.preg_replace("/[\r\n|\n|\r]+/", PHP_EOL,$db_query1).PHP_EOL);

	$resultado = mysqli_query($enlace,$db_query1);
	if (!$resultado) {
		$message  = '[*] ERROR MYSQL: ' . mysqli_error($enlace) . "\n";
		$message .= '|||||||||| Query COMPLETA: ' . $db_query1;
		
		fwrite($file,'[*]ERROR MYSQL: ' . mysqli_error($enlace) . PHP_EOL);
		fwrite($file,'[                        ]'.PHP_EOL);
        fwrite($file,'[        ERROR           ]'.PHP_EOL);
        fwrite($file,'[========================]'.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		die($message);
		
	}
	fwrite($file,date('d/m/Y G:i:s').'[+] Registro: '.$fecha_primitiva.' Creado en BD (OK)'.PHP_EOL);
	fwrite($file,'[                        ]'.PHP_EOL);
    fwrite($file,'[  SCRAPING PTIMITIVA OK ]'.PHP_EOL);
    fwrite($file,'[========================]'.PHP_EOL);
	fwrite($file,' '.PHP_EOL);
	fwrite($file,PHP_EOL);


	fclose($file);
}


// ------------------------------------------------------------------------------------
function scraping_Bonoloto($enlace){
	
	
	// Abrimos dl fichero log
	$file = fopen("scraping.log", "a");
	
	$fechaVerdadera = date('d/m/Y A G:i:s');
	fwrite($file,'[========================]'.PHP_EOL);
	fwrite($file,'[      BONOLOTO          ]'.PHP_EOL);
	fwrite($file,'[  '.$fechaVerdadera.'   ]'.PHP_EOL);
	fwrite($file,'[                        ]'.PHP_EOL);


	//REstar 1 dia a la fecha actual
	$hoy = date('D Y-m-j');
	$nuevafecha = strtotime ( '-1 day' , strtotime ( $hoy ) ) ;
	$nuevafecha = date ('D j/m/Y' , $nuevafecha );
	fwrite($file,date('D j/m/Y G:i:s').' [+] Fecha a guardar: ' .$nuevafecha.PHP_EOL);


	//BUSQUEDA DE LOS NUMEROS DENTRO DE LA WEB DE LOTERIAS Y APUESTAS
	$url = 'https://www.loteriasyapuestas.es/es/resultados';
	$html = file_get_html( $url );

	//BUSQUEDA DE LA COMBINACIÓN GANADORA
	$busqueda1 = $html->find('li.c-ultimo-resultado__combinacion-li--bonoloto');

	$n1 = $busqueda1[0]->plaintext; fwrite($file,date('d/m/Y G:i:s').'[+] N1: '.$n1.' - ');
	$n2 = $busqueda1[1]->plaintext; fwrite($file,'N2: '.$n2.' - ');
	$n3 = $busqueda1[2]->plaintext; fwrite($file,'N3: '.$n3.' - ');
	$n4 = $busqueda1[3]->plaintext; fwrite($file,'N4: '.$n4.' - ');
	$n5 = $busqueda1[4]->plaintext; fwrite($file,'N5: '.$n5.' - ');
	$n6 = $busqueda1[5]->plaintext; fwrite($file,'N6: '.$n6.' - ');

	//BUSQUEDA DEL COMPLEMENTARIO
	$busqueda3 = $html->find('li.c-ultimo-resultado__complementario-li--bonoloto');
	$comp = $busqueda3[0]->plaintext;
	fwrite($file,'Complementario: '.$comp.PHP_EOL);
	
	// ASIGNO LAS VARIABLES				
	$id_bonoloto = Null;
	$fecha_bonoloto = strtotime ( '-1 day' , strtotime ( $hoy ) ) ; //RESTO 1 dia al la fecha actual
	$fecha_bonoloto = date('Y-m-d', $fecha_bonoloto) ; // Le doy Formato de Mysql
	
    //QUERY ------- 
	$db_query1 ='insert into bonoloto values(Null,"'.$fecha_bonoloto.'","'.$n1.'","'.$n2.'","'.$n3.'","'.$n4.'","'.$n5.'","'.$n6.'","'.$comp.'");';
	// -------------

	fwrite($file,date('d/m/Y G:i:s').' Query: '. $db_query1 .PHP_EOL);

	$resultado = mysqli_query($enlace,$db_query1);
	if (!$resultado) {
		$message  = '[*]ERROR MYSQL: ' . mysqli_error($enlace) . ' ';
		$message  .= '|||||||||| Query COMPLETA: ' . $db_query1 . '';
		
		fwrite($file,'[*]ERROR MYSQL: ' . mysqli_error($enlace) . PHP_EOL);
		fwrite($file,'[                        ]'.PHP_EOL);
        fwrite($file,'[        ERROR           ]'.PHP_EOL);
        fwrite($file,'[========================]'.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		die($message);
	}
	fwrite($file,date('d/m/Y G:i:s') . '	[+] Registro: ' . $fecha_bonoloto . ' Creado en BD (OK) ' . PHP_EOL);
	fwrite($file,'[                        ]'.PHP_EOL);
    fwrite($file,'[  SCRAPING BONOLOTO OK  ]'.PHP_EOL);
    fwrite($file,'[========================]'.PHP_EOL);
	fwrite($file,' '.PHP_EOL);
	fwrite($file,PHP_EOL);

	fclose($file);
}


// ------------------------------------------------------------------------------------
function scraping_Elgordo($enlace){

	// Abrimos dl fichero log
	$file = fopen("scraping.log", "a");

	$fechaVerdadera = date('d/m/Y A G:i:s');
	fwrite($file,'[========================]'.PHP_EOL);
	fwrite($file,'[EL GORDO DE LA PRIMITIVA]'.PHP_EOL);
	fwrite($file,'[  '.$fechaVerdadera.'   ]'.PHP_EOL);
	fwrite($file,'[                        ]'.PHP_EOL);
	
	//REstar 1 dia a la fecha actual
	$hoy = date('D Y-m-j');
	$nuevafecha = strtotime ( '-1 day' , strtotime ( $hoy ) ) ;
	$nuevafecha = date ('D j/m/Y' , $nuevafecha );
	fwrite($file,date('D j/m/Y G:i:s').' [+] Fecha a guardar: ' .$nuevafecha.PHP_EOL);


	//BUSQUEDA DE LOS NUMEROS DENTRO DE LA WEB DE LOTERIAS Y APUESTAS
	$url = 'http://www.loteriasyapuestas.es/es/gordo-primitiva';
	$html = file_get_html( $url );

	//BUSQUEDA DE LA COMBINACIÓN GANADORA
	$busqueda1 = $html->find('li.c-ultimo-resultado__combinacion-li--elgordo');

	$n1 = $busqueda1[0]->plaintext; fwrite($file,date('d/m/Y G:i:s').'[+] N1: '.$n1.' - ');
	$n2 = $busqueda1[1]->plaintext; fwrite($file,'N2: '.$n2.' - ');
	$n3 = $busqueda1[2]->plaintext; fwrite($file,'N3: '.$n3.' - ');
	$n4 = $busqueda1[3]->plaintext; fwrite($file,'N4: '.$n4.' - ');
	$n5 = $busqueda1[4]->plaintext; fwrite($file,'N5: '.$n5.' - ');


	//BUSQUEDA DEL COMPLEMENTARIO
	$busqueda3 = $html->find('li.c-ultimo-resultado__reintegro-li--elgordo');
	$clave = $busqueda3[0]->plaintext;
	fwrite($file,'Nº Clave: '.$busqueda3[0]->plaintext.PHP_EOL);
	fwrite($file,PHP_EOL);


	// ASIGNO LAS VARIABLES				
	$id_elgordo = NULL;
	$fecha_elgordo = strtotime ( '-1 day' , strtotime ( $hoy ) ) ; //RESTO 1 dia al la fecha actual y ole
	$fecha_elgordo = date('Y-m-d', $fecha_elgordo) ; // Le doy Formato de Mysql
	
	//--------- QUERY
	$db_query1 ='insert into elgordo values(Null,"'.$fecha_elgordo.'","'.$n1.'","'.$n2.'","'.$n3.'","'.$n4.'","'.$n5.'","'.$clave.'");';
	//----------

	fwrite($file,date('d/m/Y G:i:s').' Query: '. preg_replace("/[\r\n|\n|\r]+/", PHP_EOL,$db_query1).PHP_EOL);

	$resultado = mysqli_query($enlace,$db_query1);
	if (!$resultado) {
		$message  = '[*]ERROR MYSQL: ' . mysqli_error($enlace) . "\n";
		$message .= '|||||||||| Query COMPLETA: ' . $db_query1;
		fwrite($file,'[*]ERROR MYSQL: ' . mysqli_error($enlace) . PHP_EOL);
		fwrite($file,'[                        ]'.PHP_EOL);
        fwrite($file,'[        ERROR           ]'.PHP_EOL);
        fwrite($file,'[========================]'.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		die($message);
	}
	fwrite($file,date('d/m/Y G:i:s').'[+] Registro: '.$fecha_elgordo.' Creado en BD (OK)'.PHP_EOL);
	fwrite($file,'[                        ]'.PHP_EOL);
    fwrite($file,'[  SCRAPING EL GORDO OK  ]'.PHP_EOL);
    fwrite($file,'[========================]'.PHP_EOL);
	fwrite($file,' '.PHP_EOL);
	fwrite($file,PHP_EOL);

	fclose($file);

}

// ------------------------------------------------------------------------------------
function scraping_Euromillones($enlace){

// Abrimos dl fichero log
	$file = fopen("scraping.log", "a");
	$fechaVerdadera = date('d/m/Y A G:i:s');
	fwrite($file,'[========================]'.PHP_EOL);
	fwrite($file,'[      EUROMILLONES      ]'.PHP_EOL);
	fwrite($file,'[  '.$fechaVerdadera.'   ]'.PHP_EOL);
	fwrite($file,'[                        ]'.PHP_EOL);

	//REstar 1 dia a la fecha actual
	$hoy = date('D Y-m-j');
	$nuevafecha = strtotime ( '-1 day' , strtotime ( $hoy ) ) ;
	$nuevafecha = date ('D j/m/Y' , $nuevafecha );
	fwrite($file,date('D j/m/Y G:i:s').' [+] Fecha a guardar: ' .$nuevafecha.PHP_EOL);


	//BUSQUEDA DE LOS NUMEROS DENTRO DE LA WEB DE LOTERIAS Y APUESTAS


	$url = 'http://www.loteriasyapuestas.es/es/euromillones';
	$html = file_get_html( $url );

	//BUSQUEDA DE LA COMBINACIÓN GANADORA
	$busqueda1 = $html->find('li.c-ultimo-resultado__combinacion-li--euromillones');

	$n1 = $busqueda1[0]->plaintext; fwrite($file,date('d/m/Y G:i:s').'[+] N1: '.$n1.' - ');
	$n2 = $busqueda1[1]->plaintext; fwrite($file,'N2: '.$n2.' - ');
	$n3 = $busqueda1[2]->plaintext; fwrite($file,'N3: '.$n3.' - ');
	$n4 = $busqueda1[3]->plaintext; fwrite($file,'N4: '.$n4.' - ');
	$n5 = $busqueda1[4]->plaintext; fwrite($file,'N5: '.$n5.' - ');


	//BUSQUEDA DEL COMPLEMENTARIO
	$busqueda3 = $html->find('li.c-ultimo-resultado__estrellas-li');
	$estrella1 = $busqueda3[0]->plaintext;
	$estrella2 = $busqueda3[1]->plaintext;

	fwrite($file,'Estrella1: '.$estrella1.' - ');
	fwrite($file,'Estrella2: '.$estrella2.PHP_EOL);
	fwrite($file,PHP_EOL);

	// ASIGNO LAS VARIABLES				
	$id_euromillones = NULL;
	$fecha_euromillones = strtotime ( '-1 day' , strtotime ( $hoy ) ) ; //RESTO 1 dia al la fecha actual
	$fecha_euromillones = date('Y-m-d', $fecha_euromillones) ; // Le doy Formato de Mysql
	
	//------------ QUERY
	$db_query1 ='insert into euromillones values(Null,"'.$fecha_euromillones.'","'.$n1.'","'.$n2.'","'.$n3.'","'.$n4.'","'.$n5.'","'.$estrella1.'","'.$estrella2.'");';
	//------
	
	fwrite($file,date('d/m/Y G:i:s').' Query: '. preg_replace("/[\r\n|\n|\r]+/", PHP_EOL,$db_query1).PHP_EOL);

	$resultado = mysqli_query($enlace,$db_query1);
	if (!$resultado) {
		$message  = '[*]ERROR MYSQL: ' . mysqli_error($enlace) . "\n";
		$message .= '|||||||||| Query COMPLETA: ' . $db_query1;
		fwrite($file,'[*]ERROR MYSQL: ' . mysqli_error($enlace) . PHP_EOL);
		fwrite($file,'[                        ]'.PHP_EOL);
        fwrite($file,'[        ERROR           ]'.PHP_EOL);
        fwrite($file,'[========================]'.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		fwrite($file,' '.PHP_EOL);
		die($message);
	}
	fwrite($file,date('d/m/Y G:i:s').'[+] Registro: '.$fecha_euromillones.' Creado en BD (OK)'.PHP_EOL);
	fwrite($file,'[                        ]'.PHP_EOL);
    fwrite($file,'[SCRAPING EUROMILLONES OK]'.PHP_EOL);
    fwrite($file,'[========================]'.PHP_EOL);
	fwrite($file,' '.PHP_EOL);
	fwrite($file,PHP_EOL);


fclose($file);

}

// ------------------------------------------------------------------------------------
function conexion_db(){

	// Abrimos dl fichero log
	$file = fopen("scraping.log", "a");

	// Me entero del nobre del Host
	$nombre_host = gethostname();

	//echo 'NOBRE DEL HOST: '.$nombre_host;



	switch ($nombre_host) {
		case 'HP-ENVY-15-Notebook':
			//VARIABLES PARA LA CONEXIÓN EN EL SOBREMESA
			$db_server = 'localhost';
			$db_user   = 'tunedlotto';
			$db_pass   = '12345678';
			$db_name   = 'id7001381_lottomath';
    	   break;
		default :
			//VARIABLES PARA LA CONEXIÓN PARA 000WEBHOST.COM
			$db_server = 'sql213.epizy.com';
			$db_user   = 'epiz_21896676';
			$db_pass   = 'rqcvTzSlvXoG';
			$db_name   = 'epiz_21896676_lottomath';
            break;
     }

	//CONEXION CON SERVIDOR
	$enlace = mysqli_connect($db_server, $db_user, $db_pass, $db_name); //SERVER ,USER, PASS, DBNAME

	if (!$enlace) {
		fwrite($file,date('d/m/Y G:i:s').'	[*] Error: No se pudo conectar a MySQL.'. PHP_EOL);
		fwrite($file,date('d/m/Y G:i:s').'	[*] errno de depuración: ' . mysqli_connect_errno() . PHP_EOL);
		fwrite($file,date('d/m/Y G:i:s').'	[*] error de depuración: ' . mysqli_connect_error() . PHP_EOL);
		exit;
	}
	fwrite($file,date('d/m/Y G:i:s').'	[+] Conexión con la BD realizado con éxito..'.PHP_EOL); 
	

	//COMPRUEBO SI EXISTEN LAS DIFERENTES TABLAS SI NO  EXISTEN ERROR  
	$sql = 'SHOW TABLES FROM '.$db_name ;
	fwrite($file,date('d/m/Y G:i:s').'	[+] $Cadena sql: '.$sql.PHP_EOL);
	$result = mysqli_query($enlace,$sql);

	if (!$result) {
		fwrite($file,date('d/m/Y G:i:s').'	[*] DB Error, could not list tables\n'.PHP_EOL);
		fwrite($file,date('d/m/Y G:i:s').'	[*] MySQL Error: ' . mysqli_error($enlace).PHP_EOL);
		exit;
	}
	fwrite($file,date('d/m/Y G:i:s').'	[+] Listado de las Tablas de la BD'.PHP_EOL);
	fwrite($file,date('d/m/Y G:i:s').'	[+] Tables: ');
	while ($row = mysqli_fetch_row($result)) {
		fwrite($file, $row[0].'-');
	}
	fwrite($file, PHP_EOL);

	mysqli_free_result($result);

	fclose($file);

	return($enlace);

}


?>