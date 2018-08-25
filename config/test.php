<?php
$cadena = "prueba";

//Hacemos la petición por get a google
$url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=".
$cadena."&langpair=es%7CeN&key=API_KEY";
$ch = curl_init($url);

//Creamos la cabecera de petición
$headers = array(
"Host: ajax.googleapis.com",
"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.0.4)
Gecko/20060508 Firefox/1.5.0.4",
"Accept-Language: es;q=0.5",
"Accept-Charset: utf-8;q=0.7,*;q=0.7",
"Date: ".date(DATE_RFC822)
);

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_GET, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//Recuperamos el contenido
$contenido = curl_exec($ch);
$contenido = json_decode($contenido);
?>
