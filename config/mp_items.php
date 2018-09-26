<?php
include_once '/var/www/html/enkargo/config/pdo_connector.php';
include_once '/var/www/html/enkargo/config/lib/mp.php';



class MePa {
    public $access_token;
    public $user_name;
    public $source;
    public $target;
    public $categories_list = array();
    public $conn;
    

    public function __construct() {
        $i = func_num_args();
        if ($i == 1) {
            $this->access_token = func_get_arg(0);
        }
        if ($i == 2) {
            $this->client_id = func_get_arg(0);
            $this->client_secret = func_get_arg(1);
        }
        $this->conn         = new DataBase();
        $this->source       = 'en';
        $this->target       = 'es';
        
    }
    

    public function build_query($params) {
        foreach ($params as $name => $value) {
            $elements[] = "{$name}=" . urlencode($value);
        }
        return implode("&", $elements);
    }

    public function balanc($id) {
        $url = "https://api.mercadopago.com/users/".$id."/mercadopago_account/balance?access_token=".$this->access_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

        $validation = json_decode(curl_exec($ch));
        curl_close($ch);

        return $validation;
    }

    public function payment() {
        $url = "https://api.mercadopago.com/v1/payments/search?limit=10&offset=0&access_token=".$this->access_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

        $validation = json_decode(curl_exec($ch));
        curl_close($ch);

        return $validation;
    }

    public function payment1() {
        $mp = new MP ($this->client_id,$this->client_secret);
        $filters = array (
            "status" => "approved"
        );
        $search_result = $mp->search_payment ($filters, 0, 10);
        print_r ($search_result);
    }

    public function payment_by_id($id) {
        $url = "https://api.mercadopago.com/v1/payments/".$id."?access_token=".$this->access_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

        $validation = json_decode(curl_exec($ch));
        curl_close($ch);

        return $validation;
    }

   


    public function report() {
        $mp = new MP($this->access_token);
        $request = array(
                "uri" => "/v1/account/bank_report",
                "data" => array(
                    "begin_date" => "2018-05-01T00:00:00Z",
                    "end_date" => "2018-09-17T00:00:00Z"
                )
            );
        return $mp->post($request);
    }

    public function ver_report() {
        $mp = new MP($this->access_token);
        $request = array("uri" => "/v1/account/bank_report/list");
        return $mp->get($request);
    }

    public function ver_report1() {
        $mp = new MP($this->access_token);
        $request = array("uri" => "/v1/account/bank_report/list");
        return $mp->get($request);
    }

    public function get_url_print($file_name) {
        $show_url = "https://api.mercadopago.com/v1/account/bank_report/".$file_name."?access_token=".$this->access_token;
        return $show_url;
    }














































    public function validate($item) {
        $validation_url = "https://api.mercadolibre.com/items/validate?access_token=".$this->access_token;
        $ch             = curl_init();
        curl_setopt($ch, CURLOPT_URL, $validation_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

        $validation = json_decode(curl_exec($ch));
        curl_close($ch);
        return $validation;
    }
    public function banner($item_id, $item) {
        $update_url = "https://api.mercadolibre.com/items/".$item_id."/description?access_token=".$this->access_token;
        $ch         = curl_init();
        $item       = json_encode($item);
        curl_setopt($ch, CURLOPT_URL, $update_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $item);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $update = json_decode(curl_exec($ch));
        curl_close($ch);
        return $update;
    }

    public function update($item_id, $item) {
        $update_url = "https://api.mercadolibre.com/items/".$item_id."?access_token=".$this->access_token;
        $ch         = curl_init();
        $item       = json_encode($item);
        curl_setopt($ch, CURLOPT_URL, $update_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $item);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $update = json_decode(curl_exec($ch));
        curl_close($ch);
        return $update;
    }

    public function relist($item_id, $item) {
        $update_url = "https://api.mercadolibre.com/items/".$item_id."/relist?access_token=".$this->access_token;
        $ch         = curl_init();
        $item       = json_encode($item);
        curl_setopt($ch, CURLOPT_URL, $update_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $item);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $update = json_decode(curl_exec($ch));
        curl_close($ch);
        return $update;
    }
    public function create($item) {
        $show_url = "https://api.mercadolibre.com/items?access_token=".$this->access_token;
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $show_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $show = json_decode(curl_exec($ch));
        curl_close($ch);
        return $show;
    }

    public function show($item) {
        $show_url = "https://api.mercadolibre.com/items?ids=".$item."?access_token=".$this->access_token;
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $show_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $show = json_decode(curl_exec($ch));
        curl_close($ch);
        return $show;
    }


    public function visits($item,$star_time) {


        $show_url = "https://api.mercadolibre.com/items/".$item."/visits?date_from=".$star_time."T23:59:59Z&date_to=".date('Y-m-d',time())."T00:00:00.000Z";
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $show_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $show = json_decode(curl_exec($ch));
        curl_close($ch);
        return $show;
    }

    public function show_by_sku($item, $shop) {
        $show_url = "https://api.mercadolibre.com/users/".$shop."/items/search?sku=".$item."&access_token=".$this->access_token;
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $show_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $show = json_decode(curl_exec($ch));
        curl_close($ch);
        return $show;
    }


    public function order_recent($shop) {
        $show_url = "https://api.mercadolibre.com/orders/search/recent?seller=".$shop."&sort=date_desc&access_token=".$this->access_token;
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $show_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $show = json_decode(curl_exec($ch));
        curl_close($ch);
        return $show;
    }

    public function order_by_id($shop,$id) {
        $show_url = "https://api.mercadolibre.com/orders/search?seller=".$shop."&q=".$id."&access_token=".$this->access_token;
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $show_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $show = json_decode(curl_exec($ch));
        curl_close($ch);
        return $show;
    }

    public function label_by_ship($id) {
        $show_url = "https://api.mercadolibre.com/shipment_labels?shipment_ids=".$id."&savePdf=Y&access_token=".$this->access_token;
        return $show_url;
    }

    public function send_message($status, $shop, $order, $access_token, $name, $user_name){
        $message = "Gracias por su compra";
        $date = date('Y-m-d H:i:s');
        $subject = $name;
        #Validación del tipo de mensasje según es tipo de estatus de la orden
        switch ($status) {
            case 1:
            $message ="Hola 😄, muy buen día, espero te encuentres muy bien, mi nombre es Sebastian y voy a acompañarte en todo el proceso de tu compra. 😎 \n
            Primero que todo, gracias por preferirnos, te comentamos que ya está acreditado tu pago 💰 y el numero de compra es el  ,a partir de hoy realizaremos la orden de importación de tu producto, recuerda que el tiempo de entrega es de ✈ 4 a 10 días hábiles (como máximo) ✈ , esto se debe a que trabajamos directamente con la marca en Estados Unidos. 😄\n
            Por favor ten en cuenta que MercadoLibre maneja una fecha de entrega estimada diferente a la nuestra, por lo tanto te llegaran diferentes correos de MercadoLibre preguntándote como va el proceso de tu compra, estos correos solo debes omitirlos, yo te estaré informando todo el tiempo el estado de tu pedido, si tienes alguna duda, pregunta, queja o reclamo, no dudes primero en comunicarte conmigo por este medio o si gustas puedes comunicarte vía teléfono al PBX 7535495 Opción 1 📞 donde te atenderé personalmente para responder todas tus inquietudes. 😄\n
            Gracias nuevamente por tu compra y que tengas un día increíble. 😄";
            break;
            case 2:
            $message ="Hola, muy buenos días, te informamos que tu producto ya esta ingresando a Colombia exitosamente, esperamos poder realizarte el envío del producto lo mas antes posible, es un placer para nosotros poder servirte, por favor has caso omiso a los correo de Mercado Libre con respecto a los tiempos de entrega o \"envio demorado\", esto sucede ya que ellos no saben sobre nuestros tiempos de entrega,recuerda que es de 4 a 10 dias habiles como maximo, muchas gracias por tu comprension y paciencia, espero tengas un excelente día.";
            break;
            case 3:
            $message ="Muy buen día, me alegra informarte que tu producto esta en proceso de nacionalización y estamos a la espera de que llegue a nuestra oficina para hacerte el despacho, nosotros te notificamos cuando esto pase para que estés atento a recibirlo";
            break;
            case 4:
            $message ="Muy buen día,Es un gusto saludarte, te cuento tu producto ya esta en Colombia esta en revisión aduanera pasando los respectivos controles colombianos esperamos que este llegando lo más pronto posible a tu hogar. Gracias por tu paciencia te deseamos un Feliz día.";
            break;
            case 5:
            $message ="Buen día, espero estés muy bien, ya tenemos tu producto listo para ser enviado en nuestras oficinas, lo entregaremos hoy al transportador para que te entreguen en la dirección que nos confirmaste a través de la plataforma, te agradecemos nuevamente por tu compra, y esperamos tenga sun muy buen día.";
            break;
            default:
            $message = "Gracias por su compra";
            break;
        }

        $payer_id =$this->conn->prepare("select o.id_payer,p.first_name, p.last_name from system.orders as o join system.payer as p on p.id_payer = o.id_payer where o.id_order= '$order'");
        $payer_id->execute();
        $payer_id = $payer_id->fetch();
        if($payer_id){
            $order_message = $this->conn->prepare("select * from system.orders_messages where order_id = '$order' and status = '$status';");
            $order_message->execute();
            $order_message = $order_message->fetch();
            if ($order_message['id']) {
                return "Not sent message duplicated";
            }else{
                $url = "https://api.mercadolibre.com/messages?access_token=$access_token";
                $messages_structure = array(
                    'from'=>array(
                        'user_id'=> $user_name
                    ),
                    'to' =>array(array(
                        'user_id'=> $payer_id['id_payer'],
                        'resource' => 'orders',
                        'resource_id' => $order,
                        'site_id' => 'MCO'
                    )),
                    'subject' => $subject,
                    'text' =>array(
                        'plain' => $message
                    )
                );
                $ch             = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messages_structure));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                $validation = json_decode(curl_exec($ch));
                curl_close($ch);
                $message_id = $validation[0]->message_id;
                if(!isset($validation->error)){
                    $resource = $this->conn->exec("insert into system.orders_messages (order_id, message_id, send_date,status) values ('$order','$message_id','$date','$status');");
                    return "Success stored message Id:"+$message_id;
                }else{
                    $resource = $this->conn->exec("insert into system.orders_messages (order_id, message_id, send_date) values ('$order','UNABLE SENT','$date','$status');");       
                    return "Error at send message";
                }
            }
        }else{
            return "Not sent";
        }
    }

    public function search_item($params) {

    }

    public function paused_item($status, $mpid, $type) {
        $temp = array();
        if ($status != "closed") {
            $result = $this->update($mpid, array('status' => 'paused'));
        }
        return 1;
    }

    public function delete_item($status, $mpid, $type) {
        $temp = array();
        if ($type == "delete_item") {
            $this->update($mpid, array('deleted' => 'true'));
            $this->conn->exec("DELETE from meli.items where mpid ='".$mpid."';");

        } else {
            if ($status != "closed") {
                $result = $this->update($mpid, array('status' => 'closed'));
            }
        }
        return 1;
    }


    public function replace_amazon($string) {
        $to_replace = array("Amazon", "amazon", "Prime", "prime","LIFETIME WARRANTY","100% SATISFACTION GUARANTEED","your money back.", "LIFETIME WARRANTY - 100% SATISFACTION GUARANTEED or your money back.");
        $string     = str_replace($to_replace, ' ', $string);
        return $string;
    }
}
#$test = new MP(1234);
#print_r($test->liquidador());

function eliminar_simbolos($string) {

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C', ),
        $string
    );

    $string = str_replace(
        array("\\", "¨", "º", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "/",
            "?", "'", "¡", "(", ")",
            "¿", "[", "^", "<code>", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";",
            " "),
        ' ',
        $string
    );
    return $string;
}