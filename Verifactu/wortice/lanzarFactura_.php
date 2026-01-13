<?php
// Incluimos los ficheros que ya tienes
//require __DIR__ . '/config.php';
require __DIR__ . '/api_invoice.php';
require __DIR__ . '/api_state.php';
require __DIR__ . '/api_cancel.php';

// Definimos la factura a enviar
$invoice = [
    "description" => [
        "text" => "Factura normal con NIF",
        "operationDate" => date("Y-m-d")   // hoy
    ],
    "recipient" => [
        "irsId"   => "B12345678",          // NIF/CIF/NIE del cliente
        "name"    => "Cliente Ejemplo SL",
        "country" => "ES"
    ],
    "id" => [
        "number"     => "F2025-0001",      // número de factura
        "issuedTime" => date("Y-m-d")
    ],
    "type" => "F1",
    "vatLines" => [
        [
            "base"         => 100,
            "rate"         => 21,
            "amount"       => 21,
            "vatOperation" => "S1",
            "vatKey"       => "01"
        ]
    ],
    "total"  => 121,
    "amount" => 21
];

// 1) Crear factura
try {
    $res = vf_crear_factura($invoice);   // función definida en api_invoice.php

    echo "<h2>Factura enviada</h2>";
    echo "<pre>";
    print_r($res['json']);
    echo "</pre>";

    // 2) Si el backend devuelve ID, consultamos estado
    if (!empty($res['json']['id'])) {
        $facturaId = $res['json']['id'];
        $estado = vf_estado_factura($facturaId);  // api_state.php
        echo "<h2>Estado de la factura</h2>";
        echo "<pre>";
        print_r($estado['json']);
        echo "</pre>";
    }

    // 3) Si devuelve QR, lo mostramos
    if (!empty($res['json']['qrcode'])) {
        $qrBase64 = explode(",", $res['json']['qrcode'])[1];
        echo "<h2>QR de la factura</h2>";
        echo '<img src="data:image/png;base64,' . $qrBase64 . '" />';
    }

} catch (Throwable $e) {
    echo "<h2>Error</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
