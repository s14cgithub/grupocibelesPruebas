<?php


/*

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


   

//////////////////////////////////////////////////////////////////////
// Codificación del array de la factura en JSON
   // $json_invoice = json_encode("11692");



    // Solicitud mediante cURL a la API Wórtice Verifactu :: inicio
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://wortice.es/api-demo/invoice_state/11778',
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

    echo '<pre>';
    echo htmlspecialchars(
        json_encode($json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
    echo '</pre>';
///////////////////////////////////////////////////////////////////////







// Uso:
// $estado = vf_estado_factura(1480);


/*
// api_state.php
require_once __DIR__ . '/config.php';

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


// Uso:
// $estado = vf_estado_factura(1480);


*/



