<?php

require ('../classes/conectionDb.php');

// CONECTO CON BD

$con = new Conection (gethostname());
$con->conect();
 

// PARÁMETRO PASADO POR AJAX
$periodo = $_POST['periodo'];
// CONSULTA SEGUN PERIODO PASADO POR POST



switch ($periodo) {
    case 'all':
        $consulta_estrellas = 'select estrella1, estrella2 from euromillones';
        break;
    case 'anio':
        $consulta_estrellas = 'select estrella1, estrella2 from euromillones e where e.fecha >= date_sub(curdate(), interval 12 month)';
        break;
    case 'six':
       $consulta_estrellas = 'select estrella1, estrella2 from euromillones e where e.fecha >= date_sub(curdate(), interval 6 month)';
        break;
    case 'three':
        $consulta_estrellas = 'select estrella1, estrella2 from euromillones e where e.fecha >= date_sub(curdate(), interval 3 month)';
        break;           
}
    
$gametabname = 'Numeros Estrellas' ;
$enlace = $con->enlace;
$result = mysqli_query($enlace,$consulta_estrellas);

// COMPRUEBO SI LA CONSULTA DEVUELVE DATOS
//$total = $result->num_rows;
//if($total==0){
    //echo '<p>NO HAY DATOS PARA ESTA CONSULTA</p>';
//}else{
    //echo '<p>HAY UN TOTAL DE '.$total.' FILAS</p>';
//}

//Construyo la tabla con el total de los numeros extraidos
$tabla_estrellas = array();
while ($row = mysqli_fetch_object($result)) {
    //echo ($row->fecha.'||');
	//echo ($row->n1.'-'.$row->n2.'-'.$row->n3.'-'.$row->n4.'-'.$row->n5.'-'.$row->n6.'-'.$row->complementario.'</br>');
    array_push($tabla_estrellas, $row->estrella1, $row->estrella2);
}

// ORDENO LA TABLA Y LA SEPARO CON COMAS
sort($tabla_estrellas); $array2string = implode(',', $tabla_estrellas);

// FUNCION QUE ME  DA LAS VECES QUE SE REPITEN CADA ELEMENTOS
$tablavalores = array_count_values($tabla_estrellas);

// CALCULO LA MEDIA ARITMÉTICA
$mayor = max($tablavalores);
$menor = min($tablavalores);
$media = ($mayor+$menor)/2;
//echo '----------------- '.$mayor;
//echo '----------------- '.$menor;

// Averiguando la Media
$media =  0;
foreach ($tablavalores as $clave => $valor){
	$media += $valor;
}
$media = round($media / sizeof($tablavalores));
  //echo ('Media: ' . strval($media). '<br>');



//CONTRULLO LA CADENA DE SALIDA
$cadena = '[';
for ($k=1 ; $k <= 12; $k++){
    if (in_array($k, $tabla_estrellas) === false){
    	$cadena.='{"x":'.$k.',"y":0}';
    }
    else{
		$cadena .=  '{"x":'
		."{$k},"
		.'"y":'
		."{$tablavalores[$k]}"
		.'}';
    }
    if ($k < 12)
    	$cadena .= ',';
}
$cadena .= ']';


//DEVUELVO LOS DOS PARAMETROS
echo $cadena.'&'.strval($media);


//CIERRO CONEXION CON LA BASE DE DATOS
mysqli_close($enlace);

?>