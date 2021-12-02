<?php

require ('../classes/conectionDb.php');

// CONECTO CON BD

$con = new Conection (gethostname());
$con->conect();
 

// PARÁMETRO PASADO POR AJAX
$periodo = $_POST['periodo'];



// CONSULTA SEGUN PERIODO PASADO POR POST
$periodo = $_POST['periodo'];
//echo '---- CRITERIO PASADO POR POST $timeline: '.$periodo;

// CONSULTA SEGUN PERIODO PASADO POR POST

switch ($periodo) {
    case 'all':
        $consulta_claves = 'select * from elgordo';
        break;
    case 'anio':
        $consulta_claves = 'select * from elgordo  where fecha >= date_sub(curdate(), interval 12 month)';
        break;
    case 'six':
        $consulta_claves = 'select * from elgordo  where fecha >= date_sub(curdate(), interval 6 month)';
        break;
    case 'three':
        $consulta_claves = 'select * from elgordo  where fecha >= date_sub(curdate(), interval 3 month)';
        break;           
}
    
$gametabname = 'numero_clave' ;
$enlace = $con->enlace;
$result = mysqli_query($enlace,$consulta_claves);

// COMPRUEBO SI LA CONSULTA DEVUELVE DATOS
$total = $result->num_rows;
if($total==0){
    //echo '<p>NO HAY DATOS PARA ESTA CONSULTA</p>';
}else{
    //echo '<p>HAY UN TOTAL DE '.$total.' FILAS</p>';
}


//CREO EL ARRAY CON LOS RESULTADOS
$tabla_clave = array();
while ($row = mysqli_fetch_object($result)) {
    // creo una tabla paralela para obtener la media arimética de los valores
    array_push($tabla_clave, $row->n_clave);
}


// ORDENO LA TABLA Y LA SEPARO CON COMAS
sort($tabla_clave); $array2string = implode(',', $tabla_clave);


// Creo tabla asociativa con el numero de repeticiones de cada numero
$tablavalores = array_count_values($tabla_clave);



/*// Ver los elementos de tablavalores
foreach ($tablavalores as $clave => $valor){
	echo $clave .'->'. $valor . '/';
}*/
	
// Mayor y Menor
$mayor = max($tablavalores);
$menor = min($tablavalores);
//echo '----------------- '.$mayor;
//echo '----------------- '.$menor;

// Averiguando la Media
$media =  0;
foreach ($tablavalores as $clave => $valor){
	$media += $valor;
}
$media = round($media / sizeof($tablavalores));
  //echo ('Media: ' . strval($media). '<br>');


// Creo el JSON de salida
$cadena = '[';
for ($k=0 ; $k <= 9; $k++){
		
        if (in_array($k, $tabla_clave) === false){
            $cadena.='{"x":'.$k.',"y":0}';
        }
        else{
			$cadena .=  '{"x":'
					."{$k},"
					.'"y":'
					."{$tablavalores[$k]}"
					.'}';
			}
			if ($k < 9)
				$cadena .= ',';
}

$cadena .= ']';

echo $cadena.'&'.strval($media);


$result->close();


//CIERRO CONEXION CON LA BASE DE DATOS
mysqli_close($enlace);



?>