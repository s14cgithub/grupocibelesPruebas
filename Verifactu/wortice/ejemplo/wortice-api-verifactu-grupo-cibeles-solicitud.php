

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Serie y número de factura. Añadimos un sufijo aleatorio (número entero aleatorio 1-1000)
$invoice_number = "A-" . date("Ymd") . "-" . mt_rand(1, 1000);
//$invoice_number = "A-" . date("Ymd") . "-1"; 


// Construcción del array con info de la factura :: inicio
$invoice = [
    "invoice" => 
    [
        "recipient" =>
        [
            "irsId" => "A81339186",
            "name" => "CIBELES MAILING S.A.",
            "country" => "ES"
        ],
        "description" => 
        [
            "text" => "Factura simplificada (ticket)",
            "operationDate" => "2025-07-01"
        ],
        "id" => 
        [
            "number"  => $invoice_number,
            "issuedTime" => "2025-07-21"
        ],
        "type" => "F1",
        "vatLines" => 
        [
            [
                "base" => 10,
                "rate" => 21,
                "amount" => 2.1,
                "vatOperation" => "S1",
                "vatKey" => "01"
            ],
            [
                "base" => 25,
                "rate" => 0,
                "amount" => 0,
                "vatOperation" => "S1",
                "vatKey" => "01"
            ]

        ],
        "total" => 39.6,
        "amount" => 4.6
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
{
    echo "Error cURL: " . curl_error($curl);
    echo " Código: " . curl_errno($curl);
}
else
{
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    echo "<br><br>" . $http_code . "<br><br>";
}



// curl_close($curl);

// var_dump($response);


$json_response = json_decode($response, true);

//var_dump($json_response);
//exit;


if (is_array($json_response) && array_key_exists('message', $json_response)) {
    $message = $json_response['message'];
    $code =  $json_response['code'];
    $requestId =  $json_response['requestId'];


     
    echo "<br>Mensaje: ".$message;
    echo "<br>Codigo: ".$code;
    echo "<br>Id de la Solicitud: " .$requestId;
} 
else 
{
    $qr_code = $json_response["qrcode"]; 
    echo "<br>Codigo QR:" . $qr_code . "<br>";


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
}








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


?>