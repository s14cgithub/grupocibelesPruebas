<?php
// config.php (compatible con PHP 5.6/7.x)

// Mejor usar define() para máxima compatibilidad
if (!defined('VF_BASE_URL')) define('VF_BASE_URL', 'https://wortice.es');
if (!defined('VF_API_KEY'))  define('VF_API_KEY', 'CCqXXdxBa1ZlO4rDTyLEPt3Yw833tI4l9ZZi1zxCvE7zgf7yVs7g8iPEUPNonVzK7DZJXqDqH2fLNaN9Jjv5zE');

// Cabeceras por defecto (API-KEY en header, según tu colección)
function vf_headers($extra = array()) {
    return array_merge(array(
        'Content-Type: application/json',
        'API-KEY: ' . VF_API_KEY,
    ), $extra);
}

// POST genérico con cURL devolviendo array ['status'=>, 'json'=>, 'raw'=>]
function http_post($url, $rawBody, $headers = array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rawBody);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Si tu PHP/Windows 8 tiene problemas SSL viejos, puedes descomentar (solo para pruebas):
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    $raw = curl_exec($ch);
    if ($raw === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException('cURL error: ' . $err);
    }

    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($raw, true); // puede ser null si no es JSON
    return array('status' => $code, 'json' => $data, 'raw' => $raw);
}
