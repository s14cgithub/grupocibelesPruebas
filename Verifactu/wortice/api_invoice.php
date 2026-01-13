<?php
// api_invoice.php
require_once __DIR__ . '/config.php';

function vf_crear_factura($invoice) {
    // Construir URL
    $url  = rtrim(VF_BASE_URL, '/') . '/api-demo/invoice';

    // Cuerpo JSON de la factura
    $body = json_encode(array('invoice' => $invoice), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // Enviar petición
    $res = http_post($url, $body, vf_headers());

    // Si la API responde error HTTP (>=400)
    if ($res['status'] >= 400) {
        throw new RuntimeException("Error crear factura: HTTP " . $res['status'] . "; " . $res['raw']);
    }

    // Devolver respuesta completa (status, json, raw)
    return $res;
}






/////////ejemplo 
/*
<?php
require __DIR__ . '/api_invoice.php';

$invoice = [
  'description' => [
    'text'          => "Factura 'normal' - Destinatario español, identificado mediante NIF",
    'operationDate' => '2025-07-21'
  ],
  'recipient' => [
    'irsId'   => 'B75944827',
    'name'    => 'WORTICE',
    'country' => 'ES'
  ],
  'id' => [
    'number'     => 'A-000001',
    'issuedTime' => '2025-07-21'
  ],
  'type'     => 'F1',
  'vatLines' => [
    ['base'=>100,'rate'=>21,'amount'=>21,'vatOperation'=>'S1','vatKey'=>'01']
  ],
  'total'  => 121,
  'amount' => 21
];

try {
  $res = vf_crear_factura($invoice);
  // Ejemplo: obtener QR (data URL) si lo devuelve así
  $qrDataUrl = $res['json']['qrcode'] ?? null;
  var_dump($res['json']); // id, estado, qrcode, etc.
} catch (Throwable $e) {
  echo "ERROR: ".$e->getMessage();
}

*/

/////////////////////