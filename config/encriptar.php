<?php 

function GeraHash($qtd,$opc){ //generador de string aleatorio
	$Caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
	$CantidadeCaracteres = strlen($Caracteres); 
	$CantidadeCaracteres--; 
	$Hash=NULL; 
	    for($x=1;$x<=$qtd;$x++){ 
	    	if ($opc=1){ 
	    		$Posicion = rand(10,$CantidadeCaracteres); // si opc es 1 solo toma las letras
	    	}else{
	    		$Posicion = rand(0,$CantidadeCaracteres); // sino toma letras y numeros
	    	}
	    	$Hash .= substr($Caracteres,$Posicion,1); 
	    } 
	return $Hash; 
}

function encryptar($string, $key) {
	
   $result = '';
   $texto = $string;
   

   #$texto = base64_encode($texto); 	// codifico el archivo 
   #$texto = GeraHash(1,1).$texto; 		// añado 2 caracteres aleatorios al comienzo de la cadena encriptada
   $texto = 'u'.$texto;                // añado 2 caracteres aleatorios al comienzo de la cadena encriptada
   $texto = strrev($texto); 			   // invierto la cadena encriptada
   #$texto = GeraHash(1,1).$texto; 		// añado 2 caracteres aleatorios al comienzo de la cadena encriptada invertida
   $texto = 'W'.$texto;     // añado 2 caracteres aleatorios al comienzo de la cadena encriptada invertida
   #$texto = gzdeflate($texto,9); 		// comprimo el archivo en nivel 9
   
   
   for($i=0; $i<strlen($texto); $i++) {   // recorremos la longitud del codigo
	   
      $char = substr($texto, $i, 1);   //  seleccionamos letra por letra 
      $keychar = substr($key, ($i % strlen($key))-1, 1);  // selecionamos la letra de la llave   
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
	  
   }
   return base64_encode($result);
   #return $result;
}



function decryptar($string, $key) {
   $result = '';
   
   $string = base64_decode($string);
   
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
	$dato=$result;
	#$dato=gzinflate($dato);
  	$dato=substr($dato,1,strlen($dato));
	$dato=strrev($dato);
	$dato=substr($dato,1,strlen($dato));
	#$dato=base64_decode($dato);
	
   return $dato;
}

?>

