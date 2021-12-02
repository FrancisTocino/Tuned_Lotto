<?php

require ('../classes/conectionDb.php');

// CONECTO CON BD

$con = new Conection (gethostname());
$con->conect();
 

// PARÁMETRO PASADO POR AJAX
$periodo = $_POST['periodo'];


// CONSULTA SEGUN PERIODO PASADO POR POST
$periodo = $_POST['periodo'];
//echo "---- CRITERIO PASADO POR POST PERIDO: ".$periodo."\n";

/// CONSULTA SEGUN PERIODO PASADO POR POST

switch ($periodo) {
    case 'all':
        $consulta_principal = 'select n1, n2, n3, n4, n5 from elgordo';
        break;
    case 'anio':
        $consulta_principal = 'select n1, n2, n3, n4, n5 from elgordo e where e.fecha >= date_sub(curdate(), interval 12 month)';
        break;
    case 'six':
        $consulta_principal = 'select n1, n2, n3, n4, n5 from elgordo e where e.fecha >= date_sub(curdate(), interval 6 month)';
        break;
    case 'three':
        $consulta_principal = 'select n1, n2, n3, n4, n5 from elgordo e where e.fecha >= date_sub(curdate(), interval 3 month)';
        break;           
}
    
$gametabname = 'elgordo' ;
$enlace = $con->enlace;
$result = mysqli_query($enlace,$consulta_principal);

// COMPRUEBO SI LA CONSULTA DEVUELVE DATOS
$total = $result->num_rows;
if($total==0){
    //echo "NO HAY DATOS PARA ESTA CONSULTA</br>";
}else{
    //echo "HAY UN TOTAL DE ".$total." FILAS</br>";
}


//CREO CADENA CON LOS RESULTADOS
$cadena_numeros='';
$tabla_numeros = array();
while ($row = mysqli_fetch_object($result)) {
    //creo un acadena para formar la cadena JSO
    $cadena_numeros.= $row->n1.' '.$row->n2.' '.$row->n3.' '.$row->n4.' '.$row->n5.' ';
    // creo una tabla paralela para obtener la media arimética de los valores
    array_push($tabla_numeros, $row->n1, $row->n2, $row->n3, $row->n4, $row->n5 );
}



// ORDENO LA TABLA Y LA SEPARO CON COMAS
sort($tabla_numeros); 
$array2string = implode(',', $tabla_numeros);

// FUNCION QUE ME  DA LAS VECES QUE SE REPITEN CADA ELEMENTOS
$tablavalores = array_count_values($tabla_numeros);

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


$cadena = '[';
for ($k=1 ; $k <= 54; $k++){
        if (in_array($k, $tabla_numeros) === false){
            $cadena.='{"x":'.$k.',"y":0}';
        }
        else{
        $cadena .=  '{"x":'
                ."{$k},"
                .'"y":'
                ."{$tablavalores[$k]}"
                .'}';
        }
        if ($k < 54)
            $cadena .= ',';
}

$cadena .= ']';

echo $cadena.'&'.strval($media);


$result->close();


//CIERRO CONEXION CON LA BASE DE DATOS
mysqli_close($enlace);

?>
