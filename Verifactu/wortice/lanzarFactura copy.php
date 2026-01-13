<?php



function lanzarFacturaCibeles ($conexion,$numFactura,$anioSeleccionado)//15, 2025
{
    //session_start(); 
    //$ruta = '../../';
    
    //require_once($ruta."Archivos Comunes/constantes.php");
	//require_once($ruta."Archivos Comunes/codigoInclude.php");

    $datosFactura = verFactura($conexion,$numFactura,$anioSeleccionado);
    $totalesPorIva = verFacturaDetalleTotalesPorIVA($conexion,$numFactura,$anioSeleccionado);

    if (!empty($datosFactura)) 
    {
        // Hay registros
        // Ejemplo: primera fila
        
        
        
        $nifCliente = $datosFactura[0]["nif"];
        $nombreCliente = $datosFactura[0]["cliente"];
        $codigoPais = $datosFactura[0]["codigoPais"];
        $fechaFacturacion = $datosFactura[0]["fecha"]->format('Y-m-d');
        //echo "\nfecha: ".$fechaFacturacion;;
        $numeroFacturaCompleto = $datosFactura[0]["numeroFacturaCompleto"];

        $precioIva = $datosFactura[0]["iva"];
        $precioIrpf= $datosFactura[0]["irpf"];
        $precioTotal= $datosFactura[0]["precioTotal"];

        $precioIva = str_replace(",",".",$precioIva);
        $precioIrpf = str_replace(",",".",$precioIrpf);
        $precioTotal = str_replace(",",".",$precioTotal);

        if ($precioIva==".00")$precioIva=0;
        if ($precioIrpf==".00")$precioIrpf=0;
        if ($precioTotal==".00")$precioTotal=0;
        
        if ($precioIva=="" or $precioIva=="NULL" or $precioIva == null) $precioIva=0;
        if ($precioIrpf=="" or $precioIrpf=="NULL" or $precioIrpf == null) $precioIrpf=0;
        if ($precioTotal=="" or $precioTotal=="NULL" or $precioTotal == null) $precioTotal=0;

        if (isset($precioIva[0]) && $precioIva[0] === '.') $precioIva = '0' . $precioIva;
        if (isset($precioIrpf[0]) && $precioIrpf[0] === '.') $precioIrpf = '0' . $precioIrpf;
        if (isset($precioTotal[0]) && $precioTotal[0] === '.') $precioTotal = '0' . $precioTotal;




        $clienteSinIva = $datosFactura[0]["sinIva"];
        $clienteConIrpf = $datosFactura[0]["retencion"];



        
        



        if ($clienteSinIva=="" or $clienteSinIva=="NULL" or $clienteSinIva == null) $clienteSinIva=0;
        if ($clienteConIrpf=="" or $clienteConIrpf=="NULL" or $clienteConIrpf == null) $clienteConIrpf=0;


        //$detallesFactura = verFacturaDetalle($conexion,$numFactura,$anioSeleccionado);
        

       
        $codigoPais = "ES";

        $baseConIva = 0;
        $baseSinIva = 0;

        if (count($totalesPorIva)>1)
        {  echo "\nEntra1";
            if ($totalesPorIva[0]["exentoIva"]==1)
            {  echo "\nEntra2";
                $baseSinIva = $totalesPorIva[0]["base"];
                $baseConIva = $totalesPorIva[1]["base"];
            }
            else
            { echo "\nEntra3";
                $baseSinIva = $totalesPorIva[1]["base"];
                $baseConIva = $totalesPorIva[0]["base"];
            }

            $baseSinIva = str_replace(",",".",$baseSinIva);
            $baseConIva = str_replace(",",".",$baseConIva);

            if ($baseConIva==".00")$baseConIva=0;
            if ($baseSinIva==".00")$baseSinIva=0;

            if ($baseConIva=="" or $baseConIva=="NULL" or $baseConIva == null) $baseConIva=0;
            if ($baseSinIva=="" or $baseSinIva=="NULL" or $baseSinIva == null) $baseSinIva=0;       

            if (isset($baseConIva[0]) && $baseConIva[0] === '.') $baseConIva = '0' . $baseConIva;
            if (isset($baseSinIva[0]) && $baseSinIva[0] === '.') $baseSinIva = '0' . $baseSinIva;
        






            $vatLines = array(
                array(
                    "base" => (float)$baseConIva,
                    "rate" => 21,
                    "amount" => (float)$precioIva,
                    "vatOperation" => "S1",
                    "vatKey" => "01"
                ),
                array(
                    "base" => (float)$baseSinIva,
                    "rate" => 0,
                    "amount" => 0,
                    "vatOperation" => "N1",
                    "vatKey" => "01"
                ),
            );

        }
        else if (count($totalesPorIva)==1)
        { echo "\nEntra4";
            
            if ($clienteSinIva=="1")
            {  echo "\nEntra5";
                
                $baseSinIva = $totalesPorIva[0]["base"];
                $baseSinIva = str_replace(",",".",$baseSinIva);
                if ($baseSinIva==".00")$baseSinIva=0;
                if ($baseSinIva=="" or $baseSinIva=="NULL" or $baseSinIva == null) $baseSinIva=0;
                if (isset($baseSinIva[0]) && $baseSinIva[0] === '.') $baseSinIva = '0' . $baseSinIva;



                $vatLines = array(
                    array(
                        "base" => (float)$baseSinIva,
                        "rate" => 0,
                        "amount" => 0,
                        "vatOperation" => "N1",
                        "vatKey" => "01"
                    ),
                );
            }
            else
            {   echo "\nEntra6";
                $baseConIva = $totalesPorIva[0]["base"];
                $baseConIva = str_replace(",",".",$baseConIva);
                if ($baseConIva==".00")$baseConIva=0;                
                if ($baseConIva=="" or $baseConIva=="NULL" or $baseConIva == null) $baseConIva=0;
                if (isset($baseConIva[0]) && $baseConIva[0] === '.') $baseConIva = '0' . $baseConIva;
           
               
                $vatLines = array(
                    array(
                        "base" => (float)$baseConIva,
                        "rate" => 21,
                        "amount" => (float)$precioIva,
                        "vatOperation" => "S1",
                        "vatKey" => "01"
                    ),
                );
            }
        }

        
        echo "\nnifCliente: ".$nifCliente;
        echo "\nnombreCliente: ".$nombreCliente;
        echo "\ncodigoPais: ".$codigoPais;
        echo "\nfechaFacturacion: ".$fechaFacturacion;
        echo "\nnumeroFacturaCompleto: ".$numeroFacturaCompleto;
        echo "\nprecioIva: ".$precioIva;
        echo "\nprecioIrpf: ".$precioIrpf;
        echo "\nprecioTotal: ".$precioTotal;
        echo "\nclienteSinIva: ".$clienteSinIva;
        echo "\nclienteConIrpf: ".$clienteConIrpf;        
        echo "\ncodigoPais: ".$codigoPais;
        echo "\nbaseConIva: ".$baseConIva;
        echo "\nbaseSinIva: ".$baseSinIva;
        
        

    } 
    else { // No hay registros
       echo  'ERROR: NO SE HA ENCONTRADO LA FACTURA EN LA BBDD DE CIBELES';
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    // Serie y número de factura. Añadimos un sufijo aleatorio (número entero aleatorio 1-1000)
    //$invoice_number = "A-" . date("Ymd") . "-" . mt_rand(1, 1000);
    //$invoice_number = "A-" . date("Ymd") . "-1"; 


    // Construcción del array con info de la factura :: inicio
    $invoice = [
        "invoice" => 
        [
            "recipient" =>
            [
                "irsId" => $nifCliente,
                "name" => $nombreCliente, 
                "country" => $codigoPais
            ],
            "description" => 
            [
                "text" => "Factura simplificada (ticket)",
                "operationDate" => $fechaFacturacion 
            ],
            "id" => 
            [
                "number"  => $numeroFacturaCompleto, 
                "issuedTime" => $fechaFacturacion 
            ],
            "type" => "F1",
            "vatLines" => $vatLines,
            "total" => (float)$precioTotal,
            "amount" => (float)$precioIva
        ]
    ];
    // Construcción del array con info de la factura :: fin


    // Codificación del array de la factura en JSON
    $json_invoice = json_encode($invoice);



    // Solicitud mediante cURL a la API Wórtice Verifactu :: inicio
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://wortice.es/api-demo/invoice',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json_invoice,

        // Importante: API-KEY en cabeceras HTTP
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'API-KEY: CCqXXdxBa1ZlO4rDTyLEPt3Yw833tI4l9ZZi1zxCvE7zgf7yVs7g8iPEUPNonVzK7DZJXqDqH2fLNaN9Jjv5zE'
        ),
    ));

    $response = curl_exec($curl);




    if ($response === false)
    {  echo "\nEntra7";
        echo "Error cURL: " . curl_error($curl);
        echo " Código: " . curl_errno($curl);
    }
    else
    { echo "\nEntra8";
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        echo "<br><br>" . $http_code . "<br><br>";
    }



    // curl_close($curl);

    // var_dump($response);


    $json_response = json_decode($response, true);

    //var_dump($json_response);
    //exit;


    if (is_array($json_response) && array_key_exists('message', $json_response)) 
    {  echo "\nEntra9";
        $message = $json_response['message'];
        $code =  $json_response['code'];
        $requestId =  $json_response['requestId'];


       
        echo "\n<br>ErrorMensaje: ".$message;
        echo "\n<br>Codigo: ".$code;
        echo "\n<br>Id de la Solicitud: " .$requestId;

        //echo "\n<br>Estado: " .$json_response['state'];

        guardarVerifactuErrores($conexion,$numFactura,$anioSeleccionado,$message, $code, $requestId);

    } 
    else 
    {  echo "\nEntra10";
        $qr_code = $json_response["qrcode"]; 
        echo "<br>Codigo QR:" . $qr_code; 


        $issuerIrsId = $json_response["chainInfo"]["issuerIrsId"];
        $issuedTime = $json_response["chainInfo"]["issuedTime"];
        $number = $json_response["chainInfo"]["number"];
        $hash = $json_response["chainInfo"]["hash"];

        echo "<br>NIF del expedidor: " . $issuerIrsId . "<br>";
        echo "<br>Fecha de expedición: " . $issuedTime . "<br>";
        echo "<br>Número de factura: " . $number . "<br>";
        echo "<br>HAST: " . $hash . "<br>";

        $verifactuUrl = $json_response["verifactuUrl"];
        echo "<br>verifactuUrl: " . $verifactuUrl. "<br>";

        $queueId = $json_response["queueId"];
        echo "<br>id / Posicion del mensjae en la cola de envío: " . $queueId. "<br>";

        $requestId = $json_response["requestId"];
        echo "<br>Id de la Solicitud: " . $requestId . "<br>";


        guardarVerifactuRespuesta($conexion,$numFactura,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId);
    }





 echo "\n";


    /*
    ///////////////CON ESTO SE VE TODA LA INFORMACION QUE HAY EN chainInfo//////////////////////
    $chainInfo = $json_response['chainInfo'];
    echo '<pre>';
    echo htmlspecialchars(
        json_encode($chainInfo, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
    echo '</pre>';
    /////////////////////////////////////////////////////////////
    */

    ///////////////CON ESTO SE VE TODA LA INFORMACION QUE HAY EN json_responcse//////////////////////
    echo '<pre>';
    echo htmlspecialchars(
        json_encode($json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
    echo '</pre>';
    /////////////////////////////////////////////////////////////




    //$qr_code: codigo de respuesta: si es 200 es que va bien OK
    //

    }

    /*
vf_estado_factura("d148a19f-37c9-4781-affb-570135c547b7");


    function vf_estado_factura($id) {
    // Construir la URL con el ID de la factura
    $url = rtrim(VF_BASE_URL, '/') . '/api-demo/invoice_state/' . rawurlencode((string)$id);
         
    // Body vacío (tal y como está en la colección)
    $res = http_post($url, '', vf_headers());

    // Comprobar si la API devolvió error
    if ($res['status'] >= 400) {
        throw new RuntimeException("Error estado factura: HTTP " . $res['status'] . "; " . $res['raw']);
    }

    return $res; // status, json y raw
}
*/



function verEstado ($queueId)
{
 $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://wortice.es/api-demo/invoice_state/'.$queueId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        //CURLOPT_POSTFIELDS => $json_invoice,

        // Importante: API-KEY en cabeceras HTTP
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'API-KEY: CCqXXdxBa1ZlO4rDTyLEPt3Yw833tI4l9ZZi1zxCvE7zgf7yVs7g8iPEUPNonVzK7DZJXqDqH2fLNaN9Jjv5zE'
        ),
    ));

    $response = curl_exec($curl);




    if ($response === false)
    {  echo "\nEntra7";
        echo "Error cURL: " . curl_error($curl);
        echo " Código: " . curl_errno($curl);
    }
    else
    { echo "\nEntra8";
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        echo "<br><br>" . $http_code . "<br><br>";
    }

    $json_response = json_decode($response, true);

    if (is_array($json_response) && array_key_exists('message', $json_response)) 
    {  echo "\nEntra9";
        $message = $json_response['message'];
        $code =  $json_response['code'];
        $requestId =  $json_response['requestId'];

        
        echo "\n<br>Mensaje: ".$message;
        echo "\n<br>Codigo: ".$code;
        echo "\n<br>Id de la Solicitud: " .$requestId;       

    } 
    else 
    {  echo "\nEntra10";
        $state = $json_response["state"]; 
        echo "<br>Estado:" . $state;        

        $requestId = $json_response["requestId"];
        echo "<br>Id de la Solicitud: " . $requestId . "<br>";

    }


    echo '<pre>';
    echo htmlspecialchars(
        json_encode($json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
    echo '</pre>';
}



?>
	

	

