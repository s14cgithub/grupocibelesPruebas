<?php
// api_cancel.php
require_once __DIR__ . '/config.php';

function vf_anular_factura($id) {
    $url  = rtrim(VF_BASE_URL, '/') . '/api-demo/invoice_cancel';

    // Según la colección, el body es texto plano (ejemplo: "9999"), no JSON
    $rawBody = (string)$id;

    // Cabeceras por defecto
    $headers = vf_headers();
    // Si la API exigiera text/plain, podrías usar:
    // $headers = array('Content-Type: text/plain', 'API-KEY: ' . VF_API_KEY);

    // Ejecutar la petición
    $res = http_post($url, $rawBody, $headers);

    // Comprobar errores
    if ($res['status'] >= 400) {
        throw new RuntimeException("Error anular factura: HTTP " . $res['status'] . "; " . $res['raw']);
    }

    return $res; // status, json y raw
}


// Uso:
// $anulada = vf_anular_factura(9999);
