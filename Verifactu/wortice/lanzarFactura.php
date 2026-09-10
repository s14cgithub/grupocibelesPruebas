<?php

// Convierte un importe en euros (string con coma o punto decimal, o null) a céntimos enteros.
// Toda la aritmética de importes de lanzarFactura() se hace en céntimos (enteros): las columnas de origen
// (facturacion.iva, facturacionDetalles.total, etc.) son DECIMAL(18,2) en BBDD, así que esta conversión
// es exacta y las sumas/comparaciones posteriores no pueden arrastrar ningún error de redondeo.
function euroACentimos($valor)
{
    $valor = str_replace(",", ".", $valor);
    if ($valor === "" || $valor === null || $valor === "NULL") return 0;
    return (int) round(((float)$valor) * 100);
}

// Redondeo al entero más cercano, "mitad siempre lejos de cero" -- el mismo criterio que usa round() de PHP
// (el que ya usan previsualizarFactura.php/imprimirFactura.php), en aritmética entera pura (equivalente a
// intdiv(), que no existe antes de PHP 7). Funciona igual para dividendos positivos y negativos: las líneas
// de una rectificativa pueden venir en negativo (una reducción), y un redondeo que solo funcione bien en
// positivo daría un céntimo de diferencia justo en el caso de empate exacto (X,50 céntimos).
function divisionEntera($dividendo, $divisor)
{
    $signo = ($dividendo < 0) ? -1 : 1;
    $dividendoAbs = abs($dividendo);
    $resto = $dividendoAbs % $divisor;
    $cociente = ($dividendoAbs - $resto) / $divisor;

    if ($resto * 2 >= $divisor) {
        $cociente++;
    }

    return $signo * (int) $cociente;
}

// Guarda en un fichero de texto la respuesta de cada llamada a lanzarFactura(), tanto si es correcta como
// si da error -- para tener rastro de todo lo que se ha intentado enviar a Hacienda. Un fichero para
// Cibeles y otro para Clayma. Si el fichero no existe se crea; si ya existe, se añade al final
// (file_put_contents con FILE_APPEND hace las dos cosas).
function guardarLogLanzarFactura($numeroFacturaCompleto, $clayma, $res)
{
    $rutaLog = 'C:/xampp/htdocs/' . ($clayma ? 'lanzarFactura_log_clayma.txt' : 'lanzarFactura_log_cibeles.txt');

    $linea = '[' . date('Y-m-d H:i:s') . '] '
        . 'factura=' . $numeroFacturaCompleto . ' '
        . json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . PHP_EOL;

    file_put_contents($rutaLog, $linea, FILE_APPEND | LOCK_EX);
}

// $clayma = true -> tabla facturacionClayma / constantes urlClayma,apiKeyClayma
// $clayma = false -> tabla facturacion / constantes urlCibeles,apiKeyCibeles
// Unifica lanzarFacturaCibeles + lanzarFacturaClayma + lanzarFacturaRecDiferencia(Cibeles/Clayma) +
// lanzarFacturaRecSustitucion(Cibeles/Clayma): una factura normal, una rectificativa por diferencia y una
// rectificativa por sustitución son la misma fila de facturacion/facturacionClayma -- solo cambia el
// serieFactura (FAC/RECT/SUST), así que no hace falta una función distinta por caso.
function lanzarFactura($conn, $bbddSql, $numeroFacturaCompleto, $clayma)
{
    $url    = $clayma ? urlClayma    : urlCibeles;
    $apiKey = $clayma ? apiKeyClayma : apiKeyCibeles;

    //TODO confirmar con el usuario: este prefijo ya se aplicaba en el código original tanto en pruebas como en producción
    // (se antepone siempre al numeroFacturaCompleto real enviado a Hacienda). Se mantiene igual para no cambiar el comportamiento actual.
    $pruebas = $clayma ? "Pruebas1a_Cla " : "Pruebas1a_Cib ";

    $filtros = array('numeroFacturaCompleto' => $numeroFacturaCompleto);
    $campos  = array('numeroFacturaCompleto','nif','cliente','fecha','fechaRealizacion','iva','precioTotalSinIrpf','dirPost_codigoPais','serieFactura','origenFactura');

    $resFactura = $clayma
        ? mostrarFacturacionClayma($conn, $bbddSql, $campos, [], $filtros, [], [])
        : mostrarFacturacion($conn, $bbddSql, $campos, [], $filtros, [], []);

    $datosFactura = $resFactura['datos'];

    if (empty($datosFactura))
    {
        $res = array('error' => 'ERROR: NO SE HA ENCONTRADO LA FACTURA '.$numeroFacturaCompleto.' EN LA BBDD DE '.($clayma ? 'CLAYMA' : 'CIBELES'));
        guardarLogLanzarFactura($numeroFacturaCompleto, $clayma, $res);
        return $res;
    }

    $nifCliente       = $datosFactura[0]["nif"];
    $nombreCliente    = $datosFactura[0]["cliente"];
    $codigoPais       = $datosFactura[0]["dirPost_codigoPais"];
    $fechaFacturacion = $datosFactura[0]["fecha"]->format('Y-m-d');
    $serieFactura     = $datosFactura[0]["serieFactura"];

    // operationDate (fecha de la operación real) se informa con fechaRealizacion; si por lo que sea no
    // estuviera rellena (factura antigua anterior a este cambio), se usa la fecha de la factura como reserva.
    $fechaRealizacion = !empty($datosFactura[0]["fechaRealizacion"])
        ? $datosFactura[0]["fechaRealizacion"]->format('Y-m-d')
        : $fechaFacturacion;

    $precioTotalCentimos = euroACentimos($datosFactura[0]["precioTotalSinIrpf"]);
    $ivaCabeceraCentimos = euroACentimos($datosFactura[0]["iva"]);

    // Si es una rectificativa (RECT = por diferencia, SUST = por sustitución), la factura origen es otra fila
    // de la misma tabla (origenFactura ya guarda directamente su numeroFacturaCompleto) -> se reutiliza la
    // misma consulta mostrarFacturacion(Clayma), no hace falta ninguna función aparte por tipo de factura origen.
    $esRectificativa = ($serieFactura == 'RECT' || $serieFactura == 'SUST');
    $creditNote = null;

    if ($esRectificativa)
    {
        $origenFactura = $datosFactura[0]["origenFactura"];
        $filtrosOrigen = array('numeroFacturaCompleto' => $origenFactura);
        $camposOrigen  = array('numeroFacturaCompleto','fecha','precioNeto','iva');

        $resOrigen = $clayma
            ? mostrarFacturacionClayma($conn, $bbddSql, $camposOrigen, [], $filtrosOrigen, [], [])
            : mostrarFacturacion($conn, $bbddSql, $camposOrigen, [], $filtrosOrigen, [], []);

        $datosOrigen = $resOrigen['datos'];

        if (empty($datosOrigen))
        {
            $res = array('error' => 'ERROR: NO SE HA ENCONTRADO LA FACTURA ORIGEN '.$origenFactura.' DE LA RECTIFICATIVA '.$numeroFacturaCompleto);
            guardarLogLanzarFactura($numeroFacturaCompleto, $clayma, $res);
            return $res;
        }

        $creditNote = array(
            "style" => ($serieFactura == 'SUST') ? "S" : "I",
            "ids" => array(
                array(
                    "number" => $pruebas.$origenFactura,
                    "issuedTime" => $datosOrigen[0]["fecha"]->format('Y-m-d')
                )
            )
        );

        if ($serieFactura == 'SUST')
        {
            $creditNote["creditBase"] = euroACentimos($datosOrigen[0]["precioNeto"]) / 100;
            $creditNote["creditVat"]  = euroACentimos($datosOrigen[0]["iva"]) / 100;
        }
    }

    // vatLines: un grupo por cada tipoIva realmente presente en las líneas de detalle (facturacionDetalles /
    // facturacionDetallesClayma) -- nunca se asume 21% fijo, igual que hace imprimirFactura.php.
    // vatOperation se calcula solo a partir de tipoIva y codigoPais (no se usa exentoIVA):
    // tipoIva=0 -> operación no sujeta/exenta (N1 si es España, N2 si es extranjero); tipoIva>0 -> S1.
    $resDetalle = $clayma
        ? cargarFacturacionDetallesClayma($conn, $bbddSql, array('total','tipoIva'), $filtros, [], [], [])
        : cargarFacturacionDetalles($conn, $bbddSql, array('total','tipoIva'), $filtros, [], [], []);

    $gruposIva = array(); // clave = tipoIva => base acumulada en céntimos
    foreach ($resDetalle['datos'] as $linea)
    {
        $tipo = (float)$linea['tipoIva'];

        if (!isset($gruposIva[$tipo])) $gruposIva[$tipo] = array('tipo' => $tipo, 'baseCentimos' => 0);
        $gruposIva[$tipo]['baseCentimos'] += euroACentimos($linea['total']); // suma de enteros, exacta
    }

    $vatLines = array();
    $ivaCalculadoCentimos = 0;

    foreach ($gruposIva as $grupo)
    {
        if ($grupo['tipo'] == 0)
        {
            $amountCentimos = 0;
            $vatOperation = ($codigoPais != "ES") ? "N2" : "N1";
        }
        else
        {
            // redondeo al céntimo más cercano (mitad lejos de cero, como round() de PHP), en enteros
            $amountCentimos = divisionEntera($grupo['baseCentimos'] * $grupo['tipo'], 100);
            $vatOperation = "S1";
        }

        $ivaCalculadoCentimos += $amountCentimos;

        $vatLines[] = array(
            "base" => $grupo['baseCentimos'] / 100,
            "rate" => $grupo['tipo'],
            "amount" => $amountCentimos / 100,
            "vatOperation" => $vatOperation,
            "vatKey" => "01"
        );
    }

    // Comprobación obligatoria: la suma de los grupos de tipoIva de facturacionDetalles debe coincidir
    // exactamente con el campo iva de la cabecera de facturacion/facturacionClayma. Si no coincide, no se
    // envía nada a Hacienda: son dos orígenes de datos que deben cuadrar siempre (igual que en toda pantalla
    // de facturación de esta aplicación -- ver imprimirFactura.php, que hace la misma comprobación aunque
    // solo avisa; aquí, al ser un envío a Hacienda, se bloquea en vez de solo avisar).
    if ($ivaCalculadoCentimos !== $ivaCabeceraCentimos)
    {
        $res = array(
            'error' => 'ERROR: el IVA calculado desde facturacionDetalles ('.($ivaCalculadoCentimos/100).') no coincide con el IVA de la cabecera de la factura '.$numeroFacturaCompleto.' ('.($ivaCabeceraCentimos/100).')'
        );
        guardarLogLanzarFactura($numeroFacturaCompleto, $clayma, $res);
        return $res;
    }

    $precioIvaCentimos = $ivaCalculadoCentimos;


    // Construcción del array con info de la factura :: inicio

    if ($codigoPais != "ES")
    {
        $recipient = array(
            "id" => $nifCliente,
            "idType" => "06", //ver esto con marian
            "name" => $nombreCliente,
            "country" => $codigoPais
        );
    }
    else
    {
        $recipient = array(
            "irsId" => $nifCliente,
            "name" => $nombreCliente,
            "country" => $codigoPais
        );
    }

    if ($esRectificativa)
    {
        $descripcionTexto = ($serieFactura == 'SUST') ? "Factura Rectificativa por Sustitucion" : "Factura Rectificativa por Diferencia";
    }
    else
    {
        $descripcionTexto = "Factura";
    }

    $datosInvoice = array(
        "recipient" => $recipient,
        "description" => array(
            "text" => $descripcionTexto,
            "operationDate" => $fechaRealizacion
        ),
        "id" => array(
            "number" => $pruebas.$numeroFacturaCompleto,
            "issuedTime" => $fechaFacturacion
        ),
        "type" => $esRectificativa ? "R1" : "F1"
    );

    if ($esRectificativa)
    {
        $datosInvoice["creditNote"] = $creditNote;
    }

    $datosInvoice["vatLines"] = $vatLines;
    $datosInvoice["total"] = $precioTotalCentimos / 100;
    $datosInvoice["amount"] = $precioIvaCentimos / 100;

    $invoice = array("invoice" => $datosInvoice);
    // Construcción del array con info de la factura :: fin

    $json_invoice = json_encode($invoice);


    // Solicitud mediante cURL a la API Wórtice Verifactu :: inicio
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url.'invoice',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 90, //0
        CURLOPT_FOLLOWLOCATION => false, //true
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json_invoice,
        CURLOPT_FRESH_CONNECT => true,// no reutilizar conexión
        CURLOPT_FORBID_REUSE=> true, // cerrar tras la petición
        CURLOPT_CONNECTTIMEOUT => 15, // Controlas el tiempo de conexión por separado (si no, puedes “quemar” todo el timeout total solo intentando conectar)

        // Importante: API-KEY en cabeceras HTTP
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'API-KEY: '.$apiKey,
            'Expect:',            // evita 100-continue
            'Connection: close'   // cierra TCP al final
        ),
    ));

    $response = curl_exec($curl);

    $respuestaFinal = "fac: ".$numeroFacturaCompleto;

    if ($response === false)
    {
        $respuestaFinal .= " Error cURL: " . curl_error($curl)." Código: " . curl_errno($curl);
    }
    else
    {
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $respuestaFinal .= " codigoHTTP: " . $http_code;
    }

    curl_close($curl);

    $json_response = json_decode($response, true);

    if (is_array($json_response) && array_key_exists('message', $json_response))
    {
        // escapado de comillas antes de guardar (evita romper el guardado si Wórtice devuelve un mensaje con comillas)
        $message   = str_replace("'", "''", $json_response['message']);
        $code      = $json_response['code'];
        $requestId = $json_response['requestId'];

        $respuestaFinal .= " message: " . $json_response['message']. " codigo: ".$code;

        $datosModificar = array(
            'verifactu_message' => $message,
            'verifactu_idSolicitud' => $requestId
        );
    }
    else
    {
        $qr_code   = $json_response["qrcode"];
        $requestId = $json_response["requestId"];
        $issuerIrsId = $json_response["chainInfo"]["issuerIrsId"];
        $issuedTime = $json_response["chainInfo"]["issuedTime"];
        $number = $json_response["chainInfo"]["number"];
        $hash = $json_response["chainInfo"]["hash"];
        $verifactuUrl = $json_response["verifactuUrl"];
        $queueId = $json_response["queueId"];
        $requestId = $json_response["requestId"];

        $respuestaFinal .= " correcto";

        $datosModificar = array(
            'verifactu_qrcode' => $qr_code,
            'verifactu_message' => null,
            'verifactu_idSolicitud' => $requestId,
            'verifactu_nifExpedidor' => $issuerIrsId,
            'verifactu_fechaExpedicion' => $issuedTime,
            'verifactu_numFactura' => $number,
            'verifactu_hast' => $hash,
            'verifactu_url' => $verifactuUrl,
            'verifactu_queueId' => $queueId

        );
    }

    if ($clayma)
        modificarFacturacionClayma($conn, $bbddSql, $datosModificar, $filtros, []);
    else
        modificarFacturacion($conn, $bbddSql, $datosModificar, $filtros, []);

    $res = array('error' => '', 'respuesta' => $respuestaFinal, 'enviado' => $invoice, 'json_response' => $json_response);
    guardarLogLanzarFactura($numeroFacturaCompleto, $clayma, $res);
    return $res;
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

    /*
    ///////////////CON ESTO SE VE TODA LA INFORMACION QUE HAY EN json_responcse//////////////////////
    echo '<pre>';
    echo htmlspecialchars(
        json_encode($json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
    echo '</pre>';
    /////////////////////////////////////////////////////////////
*/



    //$qr_code: codigo de respuesta: si es 200 es que va bien OK
    //


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



// Consulta el estado de una factura ya enviada a Verifactu (M03/R03 de la documentación de Wörtice).
// $clayma = true -> constantes urlClayma,apiKeyClayma
// $clayma = false -> constantes urlCibeles,apiKeyCibeles
function verEstado($queueId, $clayma)
{
    $url    = $clayma ? urlClayma    : urlCibeles;
    $apiKey = $clayma ? apiKeyClayma : apiKeyCibeles;

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url.'invoice_state/'.$queueId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',

        // Importante: API-KEY en cabeceras HTTP
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'API-KEY: '.$apiKey,
        ),
    ));

    $response = curl_exec($curl);

    if ($response === false)
    {
        $error = 'Error cURL: ' . curl_error($curl) . ' Código: ' . curl_errno($curl);
        curl_close($curl);
        return array('error' => $error);
    }

    curl_close($curl);

    $json_response = json_decode($response, true);

    return array('error' => '', 'json_response' => $json_response);
}


function anularFacturaCibeles ($queueId,$urlCibeles,$apiKeyCibeles)
{
    echo "<br>anulacion<br>";
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlCibeles.'invoice_cancel/'.$queueId,
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
            'API-KEY: '.$apiKeyCibeles,
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
        echo "<br><br>http code:" . $http_code . "<br><br>";
    }

    $json_response = json_decode($response, true);
    echo $response;
    if (is_array($json_response) && array_key_exists('message', $json_response))
    {   echo "\nEntra9";



        $message = $json_response['message'];
        $code = $json_response['code'];

        echo "\n<br>message: ".$message;
        echo "\n<br>code: ".$code;



    }
    else
    {

        echo "\nEntra10";
        $qrcode = $json_response['qrcode'];
        $charinfo =  $json_response['chainInfo'];
        $verifactuXml =  $json_response['verifactuXml'];
        $requestId =  $json_response['requestId'];
        $queueId =  $json_response['queueId'];



        echo "\n<br>qrcode: ".$qrcode;
        echo "\n<br>charinfo: ".$charinfo;
        echo "\n<br>verifactuXml: " .$verifactuXml;
        echo "\n<br>id de la solicitud: " .$requestId;
        echo "\n<br>id queue: " .$queueId;

    }


    echo '<pre>';
    echo htmlspecialchars(
        json_encode($json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
    echo '</pre>';

    echo '\nfin de la anulacion';
}

function anularFacturaCibeles1 ($queueId,$urlCibeles,$apiKeyCibeles)
{

    $url  = rtrim($urlCibeles, '/') . '/invoice_state';

    // Según la colección, el body es texto plano (ejemplo: "9999"), no JSON
    $rawBody = (string)$queueId;

    // Cabeceras por defecto
    $headers = vf_headers();
    // Si la API exigiera text/plain, podrías usar:
    $headers = array('Content-Type: text/plain', 'API-KEY: ' . $apiKeyCibeles);

    // Ejecutar la petición
    $res = http_post($url, $rawBody, $headers);

    // Comprobar errores
    if ($res['status'] >= 400) {
        throw new RuntimeException("Error anular factura: HTTP " . $res['status'] . "; " . $res['raw']);
    }

    echo $res; // status, json y raw
}







function lanzarFacturaCibelesPrueba ($urlCibeles,$apiKeyCibeles)
{


        $nifCliente = 'A81339186';
        $nombreCliente = "nombre del cliente. sl";
        $codigoPais = "ES";
        $fechaFacturacion = "2025-10-01"; //Y-m-d

        //$numeroFacturaCompleto = "A-" . date("Ymd") . "-" . mt_rand(1, 1000);
        $numeroFacturaCompleto = "SUST-" . date("Ymd") . "-" . mt_rand(1, 1000);

        $baseConIva = "100";
        $precioIva = "21";
        $rate = 21;    //%iva
        $precioTotal= "121";



        //$baseSinIva = 0;

        $facturaOriginal = [
            "style" => "I",
            "ids" => [
                [
                    "number"     => "CIB-FAC 4478/25",
                    "issuedTime" => "2025-09-30"
                ]
            ]
        ];

        $vatLines = array(
            array(
                "base" => (float)$baseConIva,
                "rate" => $rate,
                "amount" => (float)$precioIva,
                "vatOperation" => "S1",
                "vatKey" => "01"
            ),
        );






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
                "number"  =>  $pruebas.$numeroFacturaCompleto,
                "issuedTime" => $fechaFacturacion
            ],
            "type" => "R1", //F1 factura normal -
            "creditNote"=> $facturaOriginal,
            "vatLines" => $vatLines,
            "total" => (float)$precioTotal,
            "amount" => (float)$precioIva
        ]
    ];


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);



    $json_invoice = json_encode($invoice);



    // Solicitud mediante cURL a la API Wórtice Verifactu :: inicio
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlCibeles.'invoice',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 90, //0
        CURLOPT_FOLLOWLOCATION => false, //true
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json_invoice,
        CURLOPT_FRESH_CONNECT => true,// no reutilizar conexión
        CURLOPT_FORBID_REUSE=> true, // cerrar tras la petición
        CURLOPT_CONNECTTIMEOUT => 15, // Controlas el tiempo de conexión por separado (si no, puedes “quemar” todo el timeout total solo intentando conectar)


        // Importante: API-KEY en cabeceras HTTP
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'API-KEY: '.$apiKeyCibeles,
            'Expect:',            // evita 100-continue
            'Connection: close'   // cierra TCP al final
        ),
    ));



    $response = curl_exec($curl);

    $respuestaFinal = "\n<br>fac: ".$numeroFacturaCompleto;


    if ($response === false)
    {
         $respuestaFinal .= " <br>Error cURL: " . curl_error($curl)." <br>Código: " . curl_errno($curl);
    }
    else
    {  //echo "\nEntra8";
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //echo "<br><br>" . $http_code . "<br><br>";
        $respuestaFinal .= " <br>codigoHTTP: " . $http_code;
        //echo $respuestaFinal;

    }
    //echo "console.log('Factura: ".$numFactura." ;codigoHTTP: " . $http_code . "');";



    curl_close($curl);

    // var_dump($response);


    $json_response = json_decode($response, true);

    //var_dump($json_response);
    //exit;





    //PARA PRUEBAS
    if (is_array($json_response) && array_key_exists('message', $json_response))
    {  //echo "\nEntra9";
        $message = $json_response['message'];
        $code =  $json_response['code'];
        $requestId =  $json_response['requestId'];


        $respuestaFinal .= "<br>message: " . $json_response['message']. "<br>codigo: ".$json_response['code'] . "<br>";

        //guardarVerifactuErrores($conexion,$numFactura,$anioSeleccionado,$message, $code, $requestId);

    }
    else
    {  //echo "\nEntra10";
        $qr_code = $json_response["qrcode"];
        $respuestaFinal .= "<br>Codigo QR:" . $qr_code . "<br>";


        $issuerIrsId = $json_response["chainInfo"]["issuerIrsId"];
        $issuedTime = $json_response["chainInfo"]["issuedTime"];
        $number = $json_response["chainInfo"]["number"];
        $hash = $json_response["chainInfo"]["hash"];

        $respuestaFinal .= "<br>NIF del expedidor: " . $issuerIrsId . "<br>";
        $respuestaFinal .= "<br>Fecha de expedición: " . $issuedTime . "<br>";
        $respuestaFinal .= "<br>Número de factura: " . $number . "<br>";
        $respuestaFinal .= "<br>HAST: " . $hash . "<br>";

        $verifactuUrl = $json_response["verifactuUrl"];
        $respuestaFinal .= "<br>verifactuUrl: " . $verifactuUrl. "<br>";

        $queueId = $json_response["queueId"];
        $respuestaFinal .= "<br>id / Posicion del mensjae en la cola de envío: " . $queueId. "<br>";

        $requestId = $json_response["requestId"];
        $respuestaFinal .= "<br>Id de la Solicitud: " . $requestId . "<br>";

        $respuestaFinal .= "<br> correcto";

        //guardarVerifactuRespuesta($conexion,$numFactura,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId);
    }


    echo $respuestaFinal;

}


?>
