<?php
#header("HTTP/1.1 200 OK");
#header('HTTP/1.1 200 OK');
#header("HTTP/1.1 201 Created");
header("HTTP/1.1 202 Accepted");
#$notif = file_get_contents("php://input");





#echo "salida: ".$notif;
#exit();
#$x2    = json_decode($notif);
#Conexion a la Base de datos para extraer los datos de la cuenta (Queen Bee en este caso)

/*
$conn            = pg_connect('host=127.0.0.1 port=5432 dbname=enkargo user=u_enkargo password=#enkargo#');
$application     = pg_query($conn, "SELECT * FROM meli.shop WHERE id = '1';");
$application_det = pg_fetch_object($application);
*/

$access_token    = 'APP_USR-5901029757366125-092818-207abb518550a4326047bcc6d961f343__M_G__-275248481';
$application_id  = 5901029757366125;

/*
$ventas = file_get_contents('https://api.mercadolibre.com/orders/search?seller=275248481&order.status=paid&sort=date_desc&access_token='.$access_token);
$obj    = json_decode($ventas);
$x1     = $obj->{'results'};
*/

#echo 'Orden: '.$x1[0]->{'id'} .'<br>';

#Instanciando con CURLOPT - Llamada a funcion del API y retorno de la respuesta
$ch = curl_init();
#curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/orders/search?seller='.$application_det->user_name.'&order.status=confirmed&sort=date_desc&limit=1&access_token='.$application_det->access_token);

curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/orders/search?seller=275248481&order.status=paid&sort=date_desc&limit=1&access_token='.$access_token);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
$obj = json_decode(curl_exec($ch));
curl_close($ch);


#Ya aqui tienes luz verde
/*
echo "<pre>";
echo 'Total de ventas: '.count($obj->{'results'}).'<br>';
$x1 = $obj->{'results'};

echo "<pre>";
echo 'Total de ventas: '.count($obj->{'results'}).'<br>';
echo 'Orden: '.$x1[0]->{'id'}.'<br>';
echo 'seller id: '.$x1[0]->{'seller'}->{'id'}.'<br>';
echo 'seller email: '.$x1[0]->{'seller'}->{'email'}.'<br>';
echo 'buyer id: '.$x1[0]->{'buyer'}->{'id'}.'<br>';
echo 'buyer email: '.$x1[0]->{'buyer'}->{'email'}.'<br>';
echo 'item: '.$x1[0]->{'order_items'}[0]->{'item'}->{'id'}.'<br>';
echo 'title: '.$x1[0]->{'order_items'}[0]->{'item'}->{'title'}.'<br>';
 */
#var_dump(http_response_code());
#---------------------
$x1 = $obj->{'results'};
$vendedor  = $x1[0]->{'seller'}->{'id'};
#---------------------
#echo "---->".$vendedor; #conexion
#exit();
#---------------------

$comprador = $x1[0]->{'buyer'}->{'id'};
$orden     = $x1[0]->{'id'};
$mensaje1  = "Hola como vas ! , primero lo primero , mil y mil gracias por tu compra , ten presente que el tiempo de entrega oscila entre 7 y 12 días hábiles (si quieres acelerar al máximo el tiempo da click acá http://core.enkargo.com.co/enkargo/services/sac/mensaje.html?orden=".$orden." ), si aun así es  mucha espera para ti  tienes 24 horas a partir del envío de este mensaje para realizar tu retracto y realizaremos la devolución de tu dinero , de lo contrario iniciaremos el proceso de importación standard automáticamente , pero no te preocupes , uno de nuestros agentes del departamento comercial estará acompañándote durante todo el proceso para aclarar tus dudas y mantenerte al tanto , puedes pedir soporte en nuestra línea de wapp 3209170419 mil gracias por tu compra ,que tengas un feliz día.";

$mensaje2 = "¿Mucho tiempo?  No hay rollo podemos ir aún más rápido si quieres !! , te damos la bienvenida al  Snap by IMPPERA   la nueva herramienta que nos permitirá ser aun mas rápidos para ti , para activarla solo debes aceptar un cobro extra de 27.900 pesos y nosotros nos encargamos de que tu articulo este contigo en 3 a 6 días  de lo contrario continuaremos con el proceso standard";





exec("curl -i -X POST -H \"Content-Type: application/json\" -H \"X-Client-Id: {application id}\" -d'{ \"from\": { \"user_id\": ".$vendedor.", }, \"to\": [ { \"user_id\": ".$comprador.", \"resource\": \"orders\", \"resource_id\": ".$orden.", \"site_id\": \"MCO\" } ], \"text\": { \"plain\": \"".$mensaje1."\" } }' https://api.mercadolibre.com/messages?access_token=".$access_token."&application_id=".$application_id);

exec("curl -i -X POST -H \"Content-Type: application/json\" -H \"X-Client-Id: {application id}\" -d'{ \"from\": { \"user_id\": ".$vendedor.", }, \"to\": [ { \"user_id\": ".$comprador.", \"resource\": \"orders\", \"resource_id\": ".$orden.", \"site_id\": \"MCO\" } ], \"text\": { \"plain\": \"".$mensaje2."\" } }' https://api.mercadolibre.com/messages?access_token=".$access_token."&application_id=".$application_id);



# -------------------------------------------
// El mensaje
$titulo = "log.txt";
$nuevoarchivo = fopen($titulo, "a+"); 
fwrite($nuevoarchivo,"notificacion: ".$notif."con header --->".var_dump(http_response_code())."\n"); 
fclose($nuevoarchivo); 
# -------------------------------------------



#https://core.enkargo.com.co/enkargo/services/sac/php-sdk/examples/solicitud.php

#https://api.mercadolibre.com/orders/search?seller=275248481&access_token=$ACCESS_TOKEN

#/orders/search?seller={Seller_id}&access_token=$ACCESS_TOKEN

?>
