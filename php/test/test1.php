<?php

//echo 'NOMBRE DEL HOST: '. gethostname().'</br>';

//echo 'HACIENDO TEST DE PARAMETROS</br>';

function test($a,$b){

	$a = $a +30;
	$b = $b +130;
	return($b);

}

$a=20;
test($a=40, $b=1000);

echo  ' $a:', $a ,' $b:', $b;
?>