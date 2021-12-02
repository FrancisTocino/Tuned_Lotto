<?php

class Conection{
	
	public  $host_name;
	public  $host_ip;
	public  $db_server;
    public  $db_user;
    public  $db_pass;
    public  $db_name;
	public  $enlace;

	
	public function conect(){
		// Averiguar la IP LOCAL DEL SERVIDOR
		$host_ip = $_SERVER['REMOTE_ADDR']; 
		//echo "IP Address is:".$host_ip."<br>";
		

		switch ($host_ip) {
			case '127.0.0.1':
				//CONECT WITH LOCALHOST
				$this->enlace = mysqli_connect('localhost', 'tunedlotto', '12345678', 'id7001381_lottomath'); //SERVER ,USER, PASS, DBNAME
				if (!$this->enlace) {
					echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
					echo "Error de depuracion: " . mysqli_connect_errno() . PHP_EOL;
					echo "Error de depuración: " . mysqli_connect_error() . PHP_EOL;
					exit;
				}else {
					//echo "He conectado";
			    }
				break;
			default: 
				//CONECT WITH SERVER infinity free 
				$this->enlace = mysqli_connect('sql213.epizy.com', 'epiz_21896676', 'rqcvTzSlvXoG', 'epiz_21896676_lottomath'); //SERVER ,USER, PASS, DBNAME
				//echo 'Me he conectado con la BD';
				break;
		} 
	 } // END function conect()

	
	public function user_check ($usuario,$password){

		$sql = 'SELECT user,pass FROM users WHERE user = "' . $usuario .'" AND pass = "' . $password . '"' ;

        $result = mysqli_query($this->enlace,$sql);
		
		//echo 'NUMERO DE LINEAS DEVUELTAS->'.mysqli_num_rows($result);

        if (mysqli_num_rows($result)===0) {
			echo("NO");
			exit;
        }else{
			echo('opciones.html');
		}
    
        mysqli_free_result($result);
		
	} // user_check


	public function tables_check (){
		//COMPRUEBO SI EXISTEN LAS DIFERENTES TABLAS SI NO  EXISTEN ERROR  
			$dbName = 'epiz_21896676_lottomath';
      		echo ('</br>' . $dbName);
      		$enlace = $this->enlace;
			echo "</br>NOMBRE DE LA BASE DE DATOS: ". $dbName;
			$sql = 'SHOW TABLES FROM '. $dbName;
			echo '</br> $sql: '.$sql.'</br>';
			$result = mysqli_query($enlace,$sql);

			if (!$result) {
				echo 'DB Error, could not list tables\n';
				echo 'MySQL Error: ' . mysqli_error($enlace);
				exit;
			}
			echo('[+] Listado de las Tablas de la BD');
			while ($row = mysqli_fetch_row($result)) {
				echo 'Table: '.$row[0].'-';
			}

			mysqli_free_result($result);
	
	} // END TABLA_CHECK



	public function debug_to_console( $data, $context = 'Debug in Console' ) {

		// Buffering to solve problems frameworks, like header() in this and not a solid return.
		ob_start();
	
		$output  = 'console.info( \'' . $context . ':\' );';
		$output .= 'console.log(' . json_encode( $data ) . ');';
		$output  = sprintf( '<script>%s</script>', $output );
	
		echo $output;
	}


} // end of class




?>	