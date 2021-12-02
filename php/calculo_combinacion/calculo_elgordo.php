<?php 

require ('../classes/conectionDb.php');
require ('../classes/clasesvarias.php');



$clasesvarias = new ClasesVarias() ;

// CONECTO CON BD
$con = new Conection (gethostname());
$con->conect();
 

// CONSULTA SEGUN PERIODO PASADO POR POST
$timeline = $_POST['timeline'];
//$timeline = 'all';
//echo '---- CRITERIO PASADO POR POST $timeline: '.$timeline;


switch ($timeline) {
    case 'all':
        $consulta_principal = 'select n1, n2, n3, n4, n5 from elgordo';
        $consulta_clave = 'select n_clave from elgordo order by n_clave';
        break;
    case 'anio':
        $consulta_principal = 'select n1, n2, n3, n4, n5 from elgordo e where e.fecha >= date_sub(curdate(), interval 12 month)';
        $consulta_clave = 'select n_clave from elgordo e where e.fecha >= date_sub(curdate(), interval 12 month) order by n_clave';
        break;
    case 'six':
       $consulta_principal = 'select n1, n2, n3, n4, n5 from elgordo e where e.fecha >= date_sub(curdate(), interval 6 month)';
       $consulta_clave = 'select n_clave from elgordo e where e.fecha >= date_sub(curdate(), interval 6 month) order by n_clave';
        break;
    case 'three':
        $consulta_principal = 'select n1, n2, n3, n4, n5 from elgordo e where e.fecha >= date_sub(curdate(), interval 3 month)';
        $consulta_clave = 'select n_clave from elgordo e where e.fecha >= date_sub(curdate(), interval 3 month) order by n_clave';
        break;           
}
    
$gametabname = 'elgordo' ;
$enlace = $con->enlace;
$result = mysqli_query($enlace,$consulta_principal);

/*COMPRUEBO SI LA CONSULTA DEVUELVE DATOS
	$total = $result->num_rows;
	if($total==0){
		echo '<p>NO HAY DATOS PARA ESTA CONSULTA</p>';
	}else{
		echo '<p>HAY UN TOTAL DE '.$total.' FILAS</p>';
	}

	echo ('RESULTADOS </br>');
*/


//Construyo la tabla DE LOS NUMEROS PRIMCIPALES con el total de los numeros extraidos
$tabla_5bolas = array();
while ($row = mysqli_fetch_object($result)) {
    //echo ($row->fecha.'||');
    //echo ($row->n1.'-'.$row->n2.'-'.$row->n3.'-'.$row->n4.'-'.$row->n5.'-'.$row->n6.'-'.$row->complementario.'</br>');
    array_push($tabla_5bolas, $row->n1, $row->n2, $row->n3, $row->n4, $row->n5);    
}

//Libero el conjunto de resultados
$result->close(); 

// Veo los Resultados
//$clasesvarias->imprime_tabla($tabla_5bolas, '$tabla_5bolas');

// Averiguando la Media de $tabla_clave;
$tablavalores = array_count_values($tabla_5bolas);

// Recorriendo $tablaValores
//$clasesvarias->$clasesvarias->imprime_tabla($tablavalores,'$tablavalores');


$media = 0;
foreach ($tablavalores as $clave => $valor){
	$media += $valor;
}
$media = $media / sizeof($tablavalores);


//Asigno el valor de la media a lo números que no han salido
for ($x=0; $x<=9; $x++) {
	if (in_array($x, $tabla_5bolas)){
		//no hago nada
	}else {
		for ($y=0 ;$y<=$numero_elementos; $y++){
			array_push($tabla_5bolas, $x);
		}	
	}	
}



// ORDENO LA TABLA Y LA SEPARO CON COMAS
sort($tabla_5bolas); $array2string = implode(',', $tabla_5bolas);


// FUNCION QUE ME  DA LAS VECES QUE SE REPITEN CADA ELEMENTOS
$tablavalores = array_count_values($tabla_5bolas);


// Valores Mayores y Menores 
$mayor = max($tablavalores);
$menor = min($tablavalores);
//echo '- VALOR MAYOR ---------------- '. $mayor .'<br>';
//echo '- VALOR MENOR ---------------- '. $menor .'<br>';

// Averiguando la Media
$media = 0;
foreach ($tablavalores as $clave => $valor){
	$media += $valor;
}
$media = $media / sizeof($tablavalores);
//echo ('Media: ' . strval($media). '<br>');


// Recorriendo $tablaValores
//$clasesvarias->imprime_tabla($tablavalores,'$tablavalores despues de contar los valores');



//Creo Tabla Definitiva.
//Cada numero está repetido peso veces Es decir el % que representa 
$tabla_definitiva = array();

foreach ($tablavalores as $clave => $valor){
	$peso = round(($valor*100)/$mayor);
	//echo ('</br>numero: '.$clave.' peso: '.$peso.' %');
	for ($i=1 ; $i <= $peso ; $i++){
		 array_push($tabla_definitiva, $clave);
	}
}

//$clasesvarias->imprime_tabla($tabla_definitiva,'$tabla_definitiva');


//MEZCLO LA TABLA DEFINITIVA

// shuffle($tabla_definitiva);
//foreach ($tabla_definitiva as $clave){
    //echo ('<br>$clave: ' . $clave);
//} 



//echo 'De la tabla_definitiva elegimos los números ganadores<br>';

//Ahora contruyo la tablas de los numeros ganadores
$numero_elementos =count($tabla_definitiva);
$numero_bolas = 4; //aunque son 5 bolas se empieza a contar desde 0 SORTEO EUROMILLONES 
$tabla_ganadora = array();
//echo '$tabla_definitiva tiene un total de ' . $numero_elementos . ' números<br>';

for ($x=0;$x<=$numero_bolas;$x++){
	$numero_aleatorio = mt_rand(0,($numero_elementos-1));
	$NumeroGanador= $tabla_definitiva[$numero_aleatorio];
	//echo($x.'numero aleatorio '.$numero_aleatorio.' Numero Ganador'.$NumeroGanador.'</br>');
	if (in_array($NumeroGanador,$tabla_ganadora,true) or ($NumeroGanador==0) or ($NumeroGanador=='')){
		//echo('<p> El numero '.$tabla_definitiva[$numero_aleatorio].' está repetido o es 0 o está vacio</p>');
		$x--;
		$numero_elementos++;
	}
	else{
		$tabla_ganadora[$x]=$NumeroGanador;
	}
    $numero_aleatorio = mt_rand(0,0);
}


//PRESENTO LA COMBINACIÓN GANADORA
sort($tabla_ganadora);
for ($y=0;$y<count($tabla_ganadora);$y++){
	//echo ' '.$tabla_ganadora[$y].' ';
     echo '&nbsp<span class="bola animated zoomInDown">'.$tabla_ganadora[$y].'</span>&nbsp';
}


	
	

//****************************************************************************
//******************* NUMEROS CLAVE ******************************************
//****************************************************************************

$result2 = mysqli_query($enlace,$consulta_clave);

 
//Construyo la tabla DE LAS ESTRELLAS con el total de los numeros extraidos
$tabla_clave = array();
while ($row = mysqli_fetch_object($result2)) {
	array_push($tabla_clave, $row->n_clave); 
}

$numero_elementos = count($tabla_clave);
//echo ('numero de elementos de $tabla_clave = '.$numero_elementos);

// Averiguando la Media de $tabla_clave;
$tablavalores = array_count_values($tabla_clave);
$media = 0;
foreach ($tablavalores as $clave => $valor){
	$media += $valor;
}
$media = $media / sizeof($tablavalores);
//echo ('$media= '.$media);

//Asigno el valor de la media a lo números que no han salido
for ($x=0; $x<=9; $x++) {
	if (in_array($x, $tabla_clave)){
		//no hago nada
	}else {
		for ($y=0 ;$y<$media; $y++){
			array_push($tabla_clave, $x);
		}	
	}	
}


// ORDENO LA TABLA Y LA SEPARO CON COMAS
sort($tabla_clave); $array2string = implode(',', $tabla_clave);


// FUNCION QUE ME  DA LAS VECES QUE SE REPITEN CADA ELEMENTOS
$tablavalores = array_count_values($tabla_clave);

// Valores Mayores y Menores 
$mayor = max($tablavalores);
$menor = min($tablavalores);
//echo '- VALOR MAYOR ---------------- '. $mayor .'<br>';
//echo '- VALOR MENOR ---------------- '. $menor .'<br>'; 

// Averiguando la Media
$media = 0;
foreach ($tablavalores as $clave => $valor){
	$media += $valor;
}
$media = $media / sizeof($tablavalores);
//echo ('Media: ' . strval($media). '<br>');


//Convierto los valores de repetición por los valores de peso
$tabla_definitiva = array();

foreach ($tablavalores as $clave => $valor){
	$peso = round(($valor*100)/$mayor);
	for ($i=0 ; $i <= $peso ; $i++){
		 array_push($tabla_definitiva, $clave);
	}
}


//echo 'Tabla definitiva con el nº de repeticiones<br>';
$tabla_definitiva_salida = array_count_values($tabla_definitiva);

//$clasesvarias->imprime_tabla($tabla_definitiva_salida,'Tabla_definitiva_salida en Numeros Clave');


//MEZCLO LA TABLA DEFINITIVA . Para qué?
//shuffle($tabla_definitiva);
//foreach ($tabla_definitiva as $clave){
    //echo ('<br>$clave rebujada: ' . $clave);
//} 


//echo '</br>De la tabla_definitiva elegimos los números ganadores<br>';

//Ahora contruyo la tablas de los numeros ganadores
$numero_elementos =count($tabla_definitiva);
$numero_bolas = 0; // Una sola Bola en el Numero Clave
$tabla_ganadora = array();
//echo '$tabla_definitiva tiene un total de ' . $numero_elementos . ' números<br>';


for ($x=0;$x<=$numero_bolas;$x++){
	$numero_aleatorio = mt_rand(0,($numero_elementos));
	$NumeroGanador= $tabla_definitiva[$numero_aleatorio];
	//echo('numero aleatorio '.$numero_aleatorio.' y el Numero Ganador'.$NumeroGanador.'</br>');
	if (in_array($NumeroGanador,$tabla_ganadora,true) or ($NumeroGanador==0) or ($NumeroGanador=='')){
		//echo('<p> El numero '.$tabla_definitiva[$numero_aleatorio].' está repetido o es 0 o está vacio</p></br>');
		$x--;
		$numero_elementos++;
	}
	else{
		$tabla_ganadora[$x]=$NumeroGanador;
	}
	$numero_aleatorio = mt_rand(0,0);
}


//PRESENTO LA COMBINACIÓN GANADORA
sort($tabla_ganadora);
echo'<br>Nº Clave: ';
for ($y=0;$y<count($tabla_ganadora);$y++){
	echo ' '.$tabla_ganadora[$y].' ';
	//Esto es para los efectos del movil
    //echo '&nbsp<span class="bola animated zoomInDown">'.$tabla_ganadora[$y].'</span>&nbsp';
}


//CIERRO CONEXION CON LA BASE DE DATOS
mysqli_close($enlace);

?>