<?php

require ('../classes/conectionDb.php');

// CONECTO CON BD

$con = new Conection (gethostname());
$con->conect();
 

// PARÁMETRO PASADO POR AJAX
$periodo = $_POST['periodo'];


// CONSULTA SEGUN EL PERIODO PASADO
switch ($periodo) {
    case 'all':
        $consulta = 'select * from bonoloto';
        break;
    case 'anio':
        $consulta = 'select * from bonoloto b where b.fecha >= date_sub(curdate(), interval 12 month)';
        break;
    case 'six':
        $consulta = 'select * from bonoloto b where b.fecha >= date_sub(curdate(), interval 6 month)';
        break;
    case 'three':
         $consulta = 'select * from bonoloto b where b.fecha >= date_sub(curdate(), interval 3 month)';
        break;
    default:
        //echo('No se  ha pasado periodo');
        break;
}

$enlace = $con->enlace;
$result = mysqli_query($enlace,$consulta);

// COMPRUEBO SI LA CONSULTA DEVUELVE DATOS
$total = $result->num_rows;
if($total==0){
    //echo '<p>NO HAY DATOS PARA ESTA CONSULTA</p>';
}else{
    //echo '<p>HAY UN TOTAL DE '.$total.' FILAS</p>';
}


//Construyo la tabla con el total de los numeros extraidos
$tabla_bonoloto = array();
while ($row = mysqli_fetch_object($result)) {
    //echo ($row->fecha.'||');
	//echo ($row->n1.'-'.$row->n2.'-'.$row->n3.'-'.$row->n4.'-'.$row->n5.'-'.$row->n6.'-'.$row->complementario.'</br>');
    array_push($tabla_bonoloto, $row->n1, $row->n2, $row->n3, $row->n4, $row->n5, $row->n6, $row->complementario);
}


// ORDENO LA TABLA Y LA SEPARO CON COMAS
sort($tabla_bonoloto); $array2string = implode(',', $tabla_bonoloto);


// FUNCION QUE ME  DA LAS VECES QUE SE REPITEN CADA ELEMENTOS
$tablavalores = array_count_values($tabla_bonoloto);



// Valores Mayores y Menores 
$mayor = max($tablavalores);
$menor = min($tablavalores);
 /*echo '- VALOR MAYOR ---------------- '. $mayor .'<br>';
   echo '- VALOR MENOR ---------------- '. $menor .'<br>'; */

// Averiguando la Media
$media =  0;
foreach ($tablavalores as $clave => $valor){
	$media += $valor;
}
$media = round($media / sizeof($tablavalores));
  //echo ('Media: ' . strval($media). '<br>');



//CREO LA CADENA DE SALIDA
$cadena = '[';
for ($k = 1 ; $k <= sizeof($tablavalores); $k++){
    if (in_array($k, $tabla_bonoloto) === false){
    	$cadena.='{"x":'.$k.',"y":0}';
    }
    else{
		$cadena .=  '{"x":'
		."{$k},"
		.'"y":'
		."{$tablavalores[$k]}"
		.'}';
    }
    if ($k < sizeof($tablavalores))
    	$cadena .= ',';
}
$cadena .= ']';



echo $cadena.'&'.strval($media);

$result->close();


//CIERRO CONEXION CON LA BASE DE DATOS

mysqli_close($enlace);


?>
