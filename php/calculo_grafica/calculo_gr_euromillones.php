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
            $consulta_principal = 'select n1, n2, n3, n4, n5 from euromillones';
        break;
        case 'anio':
            $consulta_principal = 'select n1, n2, n3, n4, n5 from euromillones e where e.fecha >= date_sub(curdate(), interval 12 month)';
        break;
        case 'six':
            $consulta_principal = 'select n1, n2, n3, n4, n5 from euromillones e where e.fecha >= date_sub(curdate(), interval 6 month)';
        break;
        case 'three':
            $consulta_principal = 'select n1, n2, n3, n4, n5 from euromillones e where e.fecha >= date_sub(curdate(), interval 3 month)';
        break;
    }

    $gametabname = 'euromillones' ;
    $enlace = $con->enlace;
    $result = mysqli_query($enlace,$consulta_principal);

    // COMPRUEBO SI LA CONSULTA DEVUELVE DATOS
    //$total = $result->num_rows;
    //if($total==0){
    //echo '<p>NO HAY DATOS PARA ESTA CONSULTA</p>';
    //}else{
    //echo '<p>HAY UN TOTAL DE '.$total.' FILAS</p>';
    //}
    //Construyo la tabla con el total de los numeros extraidos
    
    $tabla_numeros = array();
    while ($row = mysqli_fetch_object($result)) {
        //echo ($row->fecha.'||');
        //echo ($row->n1.'-'.$row->n2.'-'.$row->n3.'-'.$row->n4.'-'.$row->n5.'-'.$row->n6.'-'.$row->complementario.'</br>');
        array_push($tabla_numeros, $row->n1, $row->n2, $row->n3, $row->n4, $row->n5);
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

    // Creo el JSON de salida
    $cadena = '[';
    for ($k=1 ; $k <= 50; $k++){
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
        if ($k < 50)
        $cadena .= ',';
    }
    $cadena .= ']';

    echo $cadena.'&'.strval($media);

    $result->close();
    //CIERRO CONEXION CON LA BASE DE DATOS
    mysqli_close($enlace);
?>