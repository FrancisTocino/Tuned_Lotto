<?php

require ('../classes/conectionDb.php');
require ('../classes/clasesvarias.php');

$clasesvarias = new ClasesVarias() ;

// CONECTO CON BD

$con = new Conection();
$con->conect();
 

// CONSULTA SEGUN PERIODO PASADO POR POST

$timeline = $_POST['timeline'];


//echo '---- CRITERIO PASADO POR POST $timeline: '.$timeline;

switch ($timeline) {
    case 'all':
        $consulta = 'select * from primitiva';
        break;
    case 'anio':
        $consulta = 'select * from primitiva p where p.fecha >= date_sub(curdate(), interval 12 month)';
        break;
    case 'six':
       $consulta = 'select * from primitiva p where p.fecha >= date_sub(curdate(), interval 6 month)';
        break;
    case 'three':
        $consulta = 'select * from primitiva p where p.fecha >= date_sub(curdate(), interval 3 month)';
        break;
}

$gametabname = 'primitiva' ;
$enlace = $con->enlace;
$result = mysqli_query($enlace,$consulta);

/*/ COMPRUEBO SI LA CONSULTA DEVUELVE DATOS
$total = $result->num_rows;
if($total==0){
    //echo '<p>NO HAY DATOS PARA ESTA CONSULTA</p>';
}else{
    //echo '<p>HAY UN TOTAL DE '.$total.' FILAS</p>';
} */



//Construyo la tabla con el total de los numeros extraidos
$tabla_primi = array();

while ($row = mysqli_fetch_object($result)) {
    //echo ($row->fecha.'||');
	//echo ($row->n1.'-'.$row->n2.'-'.$row->n3.'-'.$row->n4.'-'.$row->n5.'-'.$row->n6.'-'.$row->complementario.'</br>');
    array_push($tabla_primi, $row->n1, $row->n2, $row->n3, $row->n4, $row->n5, $row->n6, $row->complementario);
}


// Veo los Resultados
//$clasesvarias->imprime_tabla($tabla_primi, '$tabla_primi');

// Averiguando la Media de $tabla_clave;
$tablavalores = array_count_values($tabla_primi);

// Recorriendo $tablaValores
//$clasesvarias->$clasesvarias->imprime_tabla($tablavalores,'$tablavalores');


$media = 0;
foreach ($tablavalores as $clave => $valor){
	$media += $valor;
}
$media = $media / sizeof($tablavalores);


//Asigno el valor de la media a lo números que no han salido
for ($x=0; $x<=9; $x++) {
	if (in_array($x, $tabla_primi)){
		//no hago nada
	}else {
		for ($y=0 ;$y<=$numero_elementos; $y++){
			array_push($tabla_primi, $x);
		}	
	}	
}



// ORDENAO LA TABLA Y LA SEPARO CON COMAS
sort($tabla_primi); $array2string = implode(',', $tabla_primi);


// FUNCION QUE ME  DA LAS VECES QUE SE REPITEN CADA ELEMENTOS
$tablavalores = array_count_values($tabla_primi);


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
$numero_bolas = 5; //aunque son 6 bolas se empieza a contar desde 0 SORTEo DE LA  PRIMITIVA
$tabla_ganadora = array();
//echo '$tabla_definitiva tiene un total de ' . $numero_elementos . ' números<br>';

for ($x=0;$x<=$numero_bolas;$x++){
    
	$numero_aleatorio = mt_rand(0,($numero_elementos-1));
	$NumeroGanador= $tabla_definitiva[$numero_aleatorio];
    //echo($x.'numero aleatorio '.$numero_aleatorio.' Numero Ganador'.$NumeroGanador.'</br>');
	if (in_array($NumeroGanador,$tabla_ganadora,true) or ($NumeroGanador==0) or ($NumeroGanador=='')){
		//echo('<p> El numero '.$tabla_definitiva[$numero_aleatorio].' está repetido o es 0 o está vacio</p>');
		$x--;
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



//CIERRO CONEXION CON LA BASE DE DATOS
mysqli_close($enlace);

?>