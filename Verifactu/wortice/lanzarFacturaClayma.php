<?php

//urlCibeles = 'https://wortice.es/api-demo/';
//$apiKeyCibeles ='CCqXXdxBa1ZlO4rDTyLEPt3Yw833tI4l9ZZi1zxCvE7zgf7yVs7g8iPEUPNonVzK7DZJXqDqH2fLNaN9Jjv5zE'; //pruebas

$urlClayma = 'https://vf1.boldsoftware.es/v1/';
$apiKeyClayma ='8WfSPiiIGTyB2h5wEB30xc4jGX0FP229TBXFfhDDlptbvW6ZbR4QRDobM6jmC1SomyK17difXmhIr2kj2hJ0NJ'; // produccion

function lanzarFacturaClayma ($conexion,$numFactura,$anioSeleccionado,$urlClayma,$apiKeyClayma)//15, 2025
{
    $pruebas="Pruebas1_Cla ";
    echo "aqui1";
    //session_start(); 
    //$ruta = '../../';
    
    //require_once($ruta."Archivos Comunes/constantes.php");
	//require_once($ruta."Archivos Comunes/codigoInclude.php");

    $datosFactura = verFacturaClayma($conexion,$numFactura,$anioSeleccionado);
    $totalesPorIva = verFacturaDetalleTotalesPorIVAClayma($conexion,$numFactura,$anioSeleccionado); //hay un registro por cada tipo de iva (cibeles solo tienes 2 opciones de iva: 21% y 0%)


    $mostrarDatos="";
    $mostrarInformacion="";

    if (!empty($datosFactura)) 
    {
        // Hay registros
        // Ejemplo: primera fila
        
        
        
        $nifCliente = $datosFactura[0]["nif"];
        $nombreCliente = $datosFactura[0]["cliente"];
        $codigoPais = $datosFactura[0]["codigoPais1"];
        //$codigoPais = "ES";
        $fechaFacturacion = $datosFactura[0]["fecha"]->format('Y-m-d');
        //echo "\nfecha: ".$fechaFacturacion;;
        $numeroFacturaCompleto = $datosFactura[0]["numeroFacturaCompleto"];

        $precioIva = $datosFactura[0]["iva"];
        $precioIrpf= $datosFactura[0]["irpf"];
        //$precioTotal= $datosFactura[0]["precioTotal"];
        $precioTotal= $datosFactura[0]["precioTotalSinIrpf"]; 

        $precioIva = str_replace(",",".",$precioIva);
        $precioIrpf = str_replace(",",".",$precioIrpf);
        $precioTotal = str_replace(",",".",$precioTotal);

        if ($precioIva==".00")$precioIva=0;
        if ($precioIrpf==".00")$precioIrpf=0;
        if ($precioTotal==".00")$precioTotal=0;
        
        if ($precioIva=="" || $precioIva=="NULL" || $precioIva == null) $precioIva=0;
        if ($precioIrpf=="" || $precioIrpf=="NULL" || $precioIrpf == null) $precioIrpf=0;
        if ($precioTotal=="" || $precioTotal=="NULL" || $precioTotal == null) $precioTotal=0;

        if (isset($precioIva[0]) && $precioIva[0] === '.') $precioIva = '0' . $precioIva;
        if (isset($precioIrpf[0]) && $precioIrpf[0] === '.') $precioIrpf = '0' . $precioIrpf;
        if (isset($precioTotal[0]) && $precioTotal[0] === '.') $precioTotal = '0' . $precioTotal;




        $clienteSinIva = $datosFactura[0]["sinIva"];
        $clienteConIrpf = $datosFactura[0]["retencion"];



        
        



        if ($clienteSinIva=="" || $clienteSinIva=="NULL" || $clienteSinIva == null) $clienteSinIva=0;
        if ($clienteConIrpf=="" || $clienteConIrpf=="NULL" || $clienteConIrpf == null) $clienteConIrpf=0;


        //$detallesFactura = verFacturaDetalle($conexion,$numFactura,$anioSeleccionado);
        

       
        

        $baseConIva = 0;
        $baseSinIva = 0;

        if (count($totalesPorIva)>1) //2 TIPOS DE IVA 21% y 0% ; Aquí no debe entrar los extranjeros
        {  //echo "\nEntra1";
            if ($totalesPorIva[0]["exentoIva"]==1)
            {  //echo "\nEntra2";
                $baseSinIva = $totalesPorIva[0]["base"];
                $baseConIva = $totalesPorIva[1]["base"];
            }
            else
            { //echo "\nEntra3";
                $baseSinIva = $totalesPorIva[1]["base"];
                $baseConIva = $totalesPorIva[0]["base"];
            }

            $baseSinIva = str_replace(",",".",$baseSinIva);
            $baseConIva = str_replace(",",".",$baseConIva);


            if ($baseConIva==".00" || $baseConIva=="" || $baseConIva=="NULL" || $baseConIva == null) $baseConIva=0;
            if ($baseSinIva==".00" || $baseSinIva=="" || $baseSinIva=="NULL" || $baseSinIva == null) $baseSinIva=0;

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
        else if (count($totalesPorIva)==1) //un tipo de via 21% o 0%
        { //echo "\nEntra4";
            
            if ($clienteSinIva=="1") //toda la factura sin IVA (0%). Esto se indica en la fiche de clientes; aquí entran los extranjeros
            {  //echo "\nEntra5";
                
                $baseSinIva = $totalesPorIva[0]["base"];
                $baseSinIva = str_replace(",",".",$baseSinIva);

                if ($baseSinIva==".00" || $baseSinIva=="" || $baseSinIva=="NULL" || $baseSinIva == null) $baseSinIva=0;                
                if (isset($baseSinIva[0]) && $baseSinIva[0] === '.') $baseSinIva = '0' . $baseSinIva;

                $valorVarOperacion="N1";
                if ($codigoPais!="ES")
                    $valorVarOperacion="N2";

                $vatLines = array(
                    array(
                        "base" => (float)$baseSinIva,
                        "rate" => 0,
                        "amount" => 0,
                        "vatOperation" => $valorVarOperacion,
                        "vatKey" => "01"
                    ),
                );
            }
            else //toda la factura con 21% ; Aquí no debe entrar los extranjeros
            {   //echo "\nEntra6";
                $baseConIva = $totalesPorIva[0]["base"];
                $baseConIva = str_replace(",",".",$baseConIva);
                if ($baseConIva==".00" || $baseConIva=="" || $baseConIva=="NULL" || $baseConIva == null) $baseConIva=0;
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

        //PARA PRUEBAS
        
        $mostrarDatos .=  "\nnifCliente: ".$nifCliente;
        $mostrarDatos .=  "\nnombreCliente: ".$nombreCliente;
        $mostrarDatos .= "\ncodigoPais: ".$codigoPais;
        $mostrarDatos .= "\nfechaFacturacion: ".$fechaFacturacion;
        $mostrarDatos .= "\nnumeroFacturaCompleto: ".$numeroFacturaCompleto;
        $mostrarDatos .= "\nprecioIva: ".$precioIva;
        $mostrarDatos .= "\nprecioIrpf: ".$precioIrpf;
        $mostrarDatos .= "\nprecioTotal: ".$precioTotal;
        $mostrarDatos .= "\nclienteSinIva: ".$clienteSinIva;
        $mostrarDatos .= "\nclienteConIrpf: ".$clienteConIrpf;        
        $mostrarDatos .= "\ncodigoPais: ".$codigoPais;
        $mostrarDatos .= "\nbaseConIva: ".$baseConIva;
        $mostrarDatos .= "\nbaseSinIva: ".$baseSinIva;
        
        

    } 
    else { // No hay registros
       echo  'ERROR: NO SE HA ENCONTRADO LA FACTURA EN LA BBDD DE CLAYMA';
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    // Serie y número de factura. Añadimos un sufijo aleatorio (número entero aleatorio 1-1000)
    //$invoice_number = "A-" . date("Ymd") . "-" . mt_rand(1, 1000);
    //$invoice_number = "A-" . date("Ymd") . "-1"; 


    // Construcción del array con info de la factura :: inicio
    
    
    if ($codigoPais!="ES")
    {
        $invoice = [
                "invoice" => 
                [
                    "recipient" =>
                    [
                        "id" => $nifCliente,
                        "idType" => "06", //ver esto con marian
                        "name" => $nombreCliente, 
                        "country" => $codigoPais
                    ],
                    "description" => 
                    [
                        "text" => "Factura",
                        "operationDate" => $fechaFacturacion 
                    ],
                    "id" => 
                    [
                        "number"  =>  $pruebas.$numeroFacturaCompleto, 
                        "issuedTime" => $fechaFacturacion 
                    ],
                    "type" => "F1", 
                    "vatLines" => $vatLines,
                    "total" => (float)$precioTotal,
                    "amount" => (float)$precioIva
                ]
            ];
    }
    else
    {
    
    
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
                    "text" => "Factura",
                    "operationDate" => $fechaFacturacion 
                ],
                "id" => 
                [
                    "number"  => $pruebas.$numeroFacturaCompleto, 
                    "issuedTime" => $fechaFacturacion 
                ],
                "type" => "F1", 
                "vatLines" => $vatLines,
                "total" => (float)$precioTotal,
                "amount" => (float)$precioIva
            ]
        ];

    }
    // Construcción del array con info de la factura :: fin


    // Codificación del array de la factura en JSON
    $json_invoice = json_encode($invoice);



    // Solicitud mediante cURL a la API Wórtice Verifactu :: inicio
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlClayma.'invoice',
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
            'API-KEY: '.$apiKeyClayma,
            'Expect:',            // evita 100-continue
            'Connection: close'   // cierra TCP al final
        ),
    ));

   

    $response = curl_exec($curl);

    $respuestaFinal = "\n<br>fac: ".$numFactura;


    if ($response === false)
    {   /*
        echo "\nEntra7";
        echo "Error cURL: " . curl_error($curl);
        echo " Código: " . curl_errno($curl);
        */
         $respuestaFinal .= " Error cURL: " . curl_error($curl)." Código: " . curl_errno($curl);
    }
    else
    {  //echo "\nEntra8";
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //echo "<br><br>" . $http_code . "<br><br>";
        $respuestaFinal .= " codigoHTTP: " . $http_code;
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
    {  echo "\nEntra9";
        $message = $json_response['message'];
        $code =  $json_response['code'];
        $requestId =  $json_response['requestId'];


       /*
        echo "\n<br>ErrorMensaje: ".$message;
        echo "\n<br>Codigo: ".$code;
        echo "\n<br>Id de la Solicitud: " .$requestId;

        */
        $respuestaFinal .= " message: " . $json_response['message']. "codigo: ".$json_response['code'];
        
        guardarVerifactuErroresClayma($conexion,$numFactura,$anioSeleccionado,$message, $code, $requestId);

    } 
    else 
    {  echo "\nEntra10";
        $qr_code = $json_response["qrcode"]; 
        //echo "<br>Codigo QR:" . $qr_code; 


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

        $respuestaFinal .= " correcto";
        
        guardarVerifactuRespuestaClayma($conexion,$numFactura,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId);
    }

    echo $mostrarDatos;
    echo $respuestaFinal;
    
}

function lanzarFacturaRecDiferenciaClayma ($conexion,$numeroFacturaRec,$anioSeleccionado,$urlClayma,$apiKeyClayma)//RECT 6/25, 2025
{
    $pruebas="PruebasCla ";
   
    $datosFactura = verFacturaRectificativaClayma($conexion,$numeroFacturaRec,$anioSeleccionado);
    $totalesPorIva = verFacturaRecDetalleTotalesPorIVAClayma($conexion,$numeroFacturaRec,$anioSeleccionado); //hay un registro por cada tipo de iva (cibeles solo tienes 2 opciones de iva: 21% y 0%)


    $mostrarDatos="";
    $mostrarInformacion="";

    if (!empty($datosFactura)) 
    {
        // Hay registros
        // Ejemplo: primera fila
        
        
        //se averigua la fecha de la factura original
        $origenFactura = $datosFactura[0]["origenFactura"];

        $datosOrigenFactura = explode('/',$origenFactura);  //RECT 6     y    25
        $anioFacturaOriginalDosDigitos = $datosOrigenFactura[1];//25
        $anioFacturaOriginal = $anioFacturaOriginalDosDigitos+2000; //2025
        $datosOrigenFactura1 = explode(' ',$datosOrigenFactura[0]); //RECT 6 
        $tipoFacturaOriginal = $datosOrigenFactura1[0]; //RECT
        $numeroFacturaOriginal = $datosOrigenFactura1[1]; //6

        echo "\nnumero de la factura Origen: ".$numeroFacturaRec;
        echo "\nanio de la factura Origen: ".$anioSeleccionado;
        
        if (trim($tipoFacturaOriginal)=="FAC")
        {
             echo "\nEntra en Fac1";
            $datosFacturaOriginal = verFacturaClayma($conexion,$numeroFacturaOriginal,$anioFacturaOriginal);
        }
        else if (trim($tipoFacturaOriginal)=="RECT")
        {
            echo "\nEntra en Rect1";
            $datosFacturaOriginal = verFacturaRectificativaClayma($conexion,$origenFactura,$anioFacturaOriginal);
        }
        else if (trim($tipoFacturaOriginal)=="SUST")
        {
            echo "\nEntra en Sust";
            $datosFacturaOriginal = verFacturaRectificativaSustitucionClayma($conexion,$origenFactura,$anioFacturaOriginal);
        }

        echo "\nnumero de filas: ".count($datosFacturaOriginal);
        echo "\nnumero FacturaOriginal: ".$numeroFacturaOriginal;
        echo "\nanio factura Original: ".$anioFacturaOriginal;

        $fechaFacturaOriginal = $datosFacturaOriginal[0]["fecha"]->format('Y-m-d');        


        $nifCliente = $datosFactura[0]["nif"];
        $nombreCliente = $datosFactura[0]["cliente"];
        $codigoPais = $datosFactura[0]["codigoPais1"];
        //$codigoPais = "ES";
        $fechaFacturacion = $datosFactura[0]["fecha"]->format('Y-m-d');
        echo "\nfecha: ".$fechaFacturacion;;
        $numeroFacturaCompleto = $datosFactura[0]["numeroFacturaCompleto"];

        $precioIva = $datosFactura[0]["iva"];
        $precioIrpf= $datosFactura[0]["irpf"];
        //$precioTotal= $datosFactura[0]["precioTotal"];
        $precioTotal= $datosFactura[0]["precioTotalSinIrpf"]; 

        $precioIva = str_replace(",",".",$precioIva);
        $precioIrpf = str_replace(",",".",$precioIrpf);
        $precioTotal = str_replace(",",".",$precioTotal);

        if ($precioIva==".00")$precioIva=0;
        if ($precioIrpf==".00")$precioIrpf=0;
        if ($precioTotal==".00")$precioTotal=0;
        
        if ($precioIva=="" || $precioIva=="NULL" || $precioIva == null) $precioIva=0;
        if ($precioIrpf=="" || $precioIrpf=="NULL" || $precioIrpf == null) $precioIrpf=0;
        if ($precioTotal=="" || $precioTotal=="NULL" || $precioTotal == null) $precioTotal=0;

        if (isset($precioIva[0]) && $precioIva[0] === '.') $precioIva = '0' . $precioIva;
        if (isset($precioIrpf[0]) && $precioIrpf[0] === '.') $precioIrpf = '0' . $precioIrpf;
        if (isset($precioTotal[0]) && $precioTotal[0] === '.') $precioTotal = '0' . $precioTotal;


        $clienteSinIva = $datosFactura[0]["sinIva"];
        $clienteConIrpf = $datosFactura[0]["retencion"];


        if ($clienteSinIva=="" || $clienteSinIva=="NULL" || $clienteSinIva == null) $clienteSinIva=0;
        if ($clienteConIrpf=="" || $clienteConIrpf=="NULL" || $clienteConIrpf == null) $clienteConIrpf=0;


        //$detallesFactura = verFacturaDetalle($conexion,$numFactura,$anioSeleccionado);

        $baseConIva = 0;
        $baseSinIva = 0;

        if (count($totalesPorIva)>1) //2 TIPOS DE IVA 21% y 0%  ; Aquí no debe entrar los extranjeros
        {  //echo "\nEntra1";
            if ($totalesPorIva[0]["exentoIva"]==1)
            {  //echo "\nEntra2";
                $baseSinIva = $totalesPorIva[0]["base"];
                $baseConIva = $totalesPorIva[1]["base"];
            }
            else
            { //echo "\nEntra3";
                $baseSinIva = $totalesPorIva[1]["base"];
                $baseConIva = $totalesPorIva[0]["base"];
            }

            $baseSinIva = str_replace(",",".",$baseSinIva);
            $baseConIva = str_replace(",",".",$baseConIva);


            if ($baseConIva==".00" || $baseConIva=="" || $baseConIva=="NULL" || $baseConIva == null) $baseConIva=0;
            if ($baseSinIva==".00" || $baseSinIva=="" || $baseSinIva=="NULL" || $baseSinIva == null) $baseSinIva=0;

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
        else if (count($totalesPorIva)==1) //un tipo de via 21% o 0%
        { //echo "\nEntra4";
            
            if ($clienteSinIva=="1") //toda la factura sin IVA (0%). Esto se indica en la fiche de clientes; aquí entran los extranjeros
            {  //echo "\nEntra5";
                
                $baseSinIva = $totalesPorIva[0]["base"];
                $baseSinIva = str_replace(",",".",$baseSinIva);

                if ($baseSinIva==".00" || $baseSinIva=="" || $baseSinIva=="NULL" || $baseSinIva == null) $baseSinIva=0;                
                if (isset($baseSinIva[0]) && $baseSinIva[0] === '.') $baseSinIva = '0' . $baseSinIva;

                $valorVarOperacion="N1";
                if ($codigoPais!="ES")
                    $valorVarOperacion="N2";

                $vatLines = array(
                    array(
                        "base" => (float)$baseSinIva,
                        "rate" => 0,
                        "amount" => 0,
                        "vatOperation" => $valorVarOperacion,
                        "vatKey" => "01"
                    ),
                );
            }
            else //toda la factura con 21% ; Aquí no debe entrar los extranjeros
            {   //echo "\nEntra6";
                $baseConIva = $totalesPorIva[0]["base"];
                $baseConIva = str_replace(",",".",$baseConIva);
                if ($baseConIva==".00" || $baseConIva=="" || $baseConIva=="NULL" || $baseConIva == null) $baseConIva=0;
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

        //PARA PRUEBAS
        
        $mostrarDatos .=  "\nnifCliente: ".$nifCliente;
        $mostrarDatos .=  "\nnombreCliente: ".$nombreCliente;
        $mostrarDatos .= "\ncodigoPais: ".$codigoPais;
        $mostrarDatos .= "\nfechaFacturacion: ".$fechaFacturacion;
        $mostrarDatos .= "\nnumeroFacturaCompleto: ".$numeroFacturaCompleto;
        $mostrarDatos .= "\nprecioIva: ".$precioIva;
        $mostrarDatos .= "\nprecioIrpf: ".$precioIrpf;
        $mostrarDatos .= "\nprecioTotal: ".$precioTotal;
        $mostrarDatos .= "\nclienteSinIva: ".$clienteSinIva;
        $mostrarDatos .= "\nclienteConIrpf: ".$clienteConIrpf;        
        $mostrarDatos .= "\ncodigoPais: ".$codigoPais;
        $mostrarDatos .= "\nbaseConIva: ".$baseConIva;
        $mostrarDatos .= "\nbaseSinIva: ".$baseSinIva;
        $mostrarDatos .= "\norigenFactura: ".$origenFactura;
        $mostrarDatos .= "\nfechaFacturaOriginal: ".$fechaFacturaOriginal;


        
        
        
        

    } 
    else { // No hay registros
       echo  'ERROR: NO SE HA ENCONTRADO LA FACTURA EN LA BBDD DE CLAYMA';
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    // Serie y número de factura. Añadimos un sufijo aleatorio (número entero aleatorio 1-1000)
    //$invoice_number = "A-" . date("Ymd") . "-" . mt_rand(1, 1000);
    //$invoice_number = "A-" . date("Ymd") . "-1"; 


    $facturaOriginal = [
            "style" => "I",
            "ids" => [
                [
                    "number"     => $pruebas.$origenFactura,
                    "issuedTime" => $fechaFacturaOriginal
                ]
            ]
        ];
    
    
    // Construcción del array con info de la factura :: inicio
    
    if ($codigoPais!="ES")
    {
        $invoice = [
                "invoice" => 
                [
                    "recipient" =>
                    [
                        "id" => $nifCliente,
                        "idType" => "06", //ver esto con marian
                        "name" => $nombreCliente, 
                        "country" => $codigoPais
                    ],
                    "description" => 
                    [
                        "text" => "Factura Rectificativa por Diferencia",
                        "operationDate" => $fechaFacturacion 
                    ],
                    "id" => 
                    [
                        "number"  =>  $pruebas.$numeroFacturaCompleto, 
                        "issuedTime" => $fechaFacturacion 
                    ],
                    "type" => "R1", //R1 RECTIFICATIVA
                    "creditNote"=> $facturaOriginal,            
                    "vatLines" => $vatLines,
                    "total" => (float)$precioTotal,
                    "amount" => (float)$precioIva
                ]
            ];
    }
    else
    {
    
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
                    "text" => "Factura Rectificativa por Diferencia",
                    "operationDate" => $fechaFacturacion 
                ],
                "id" => 
                [
                    "number"  => $pruebas.$numeroFacturaCompleto, 
                    "issuedTime" => $fechaFacturacion 
                ],
                "type" => "R1", //R1 RECTIFICATIVA
                "creditNote"=> $facturaOriginal,            
                "vatLines" => $vatLines,
                "total" => (float)$precioTotal,
                "amount" => (float)$precioIva
            ]
        ];
    }
    // Construcción del array con info de la factura :: fin

 

    // Codificación del array de la factura en JSON
    $json_invoice = json_encode($invoice);



    // Solicitud mediante cURL a la API Wórtice Verifactu :: inicio
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlClayma.'invoice',
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
            'API-KEY: '.$apiKeyClayma,
            'Expect:',            // evita 100-continue
            'Connection: close'   // cierra TCP al final
        ),
    ));

   

    $response = curl_exec($curl);

    $respuestaFinal = "\n<br>fac: ".$numeroFacturaCompleto;


    if ($response === false)
    {   /*
        echo "\nEntra7";
        echo "Error cURL: " . curl_error($curl);
        echo " Código: " . curl_errno($curl);
        */
         $respuestaFinal .= " Error cURL: " . curl_error($curl)." Código: " . curl_errno($curl);
    }
    else
    {  //echo "\nEntra8";
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //echo "<br><br>" . $http_code . "<br><br>";
        $respuestaFinal .= " codigoHTTP: " . $http_code;
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


       /*
        echo "\n<br>ErrorMensaje: ".$message;
        echo "\n<br>Codigo: ".$code;
        echo "\n<br>Id de la Solicitud: " .$requestId;

        */
        $respuestaFinal .= " message: " . $json_response['message']. "codigo: ".$json_response['code'];
        
        guardarVerifactuErroresRecClayma($conexion,$numeroFacturaCompleto,$anioSeleccionado,$message, $code, $requestId);

    } 
    else 
    {  "\nEntra10";
        $qr_code = $json_response["qrcode"]; 
        //echo "<br>Codigo QR:" . $qr_code; 


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

        $respuestaFinal .= " correcto";
        
        guardarVerifactuRespuestaRecClayma($conexion,$numeroFacturaCompleto,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId);
    }

    echo $mostrarDatos;
    echo $respuestaFinal;
    
}

function lanzarFacturaRecSustitucionClayma ($conexion,$numeroFacturaRec,$anioSeleccionado,$urlClayma,$apiKeyClayma)//RECT 6/25, 2025
{

    $pruebas="PruebasCla ";

    $datosFactura = verFacturaRectificativaSustitucionClayma($conexion,$numeroFacturaRec,$anioSeleccionado);
    $totalesPorIva = verFacturaRecSustDetalleTotalesPorIVAClayma($conexion,$numeroFacturaRec,$anioSeleccionado); //hay un registro por cada tipo de iva (cibeles solo tienes 2 opciones de iva: 21% y 0%)


    $mostrarDatos="";
    $mostrarInformacion="";

    if (!empty($datosFactura)) 
    {
        // Hay registros
        // Ejemplo: primera fila
        
        
        //se averigua la fecha de la factura original
        $origenFactura = $datosFactura[0]["origenFactura"];

        $datosOrigenFactura = explode('/',$origenFactura);  //RECT 6     y    25
        $anioFacturaOriginalDosDigitos = $datosOrigenFactura[1];//25
        $anioFacturaOriginal = $anioFacturaOriginalDosDigitos+2000; //2025
        $datosOrigenFactura1 = explode(' ',$datosOrigenFactura[0]); //RECT 6 
        $tipoFacturaOriginal = $datosOrigenFactura1[0]; //RECT
        $numeroFacturaOriginal = $datosOrigenFactura1[1]; //6
        echo "\nnumero de la factura Origen: ".$numeroFacturaRec;
        echo "\nanio de la factura Origen: ".$anioSeleccionado;

        if (trim($tipoFacturaOriginal)=="FAC")
        {
            $datosFacturaOriginal = verFacturaClayma($conexion,$numeroFacturaOriginal,$anioFacturaOriginal);
        }
        else if (trim($tipoFacturaOriginal)=="RECT")
        {
            echo "\nEntra en Rect";
            $datosFacturaOriginal = verFacturaRectificativaClayma($conexion,$origenFactura,$anioFacturaOriginal);
        }
        else if (trim($tipoFacturaOriginal)=="SUST")
        {
            echo "\nEntra en Sust";
            $datosFacturaOriginal = verFacturaRectificativaSustitucionClayma($conexion,$origenFactura,$anioFacturaOriginal);
        }
        
        $fechaFacturaOriginal = $datosFacturaOriginal[0]["fecha"]->format('Y-m-d'); 
        $netoFacturaOriginal = $datosFacturaOriginal[0]["precioNeto"]; 
        $ivaFacturaOriginal = $datosFacturaOriginal[0]["iva"]; 

        if ($netoFacturaOriginal==".00" || $netoFacturaOriginal=="" || $netoFacturaOriginal=="NULL" || $netoFacturaOriginal == null) $netoFacturaOriginal=0.00;
        if ($ivaFacturaOriginal==".00" || $ivaFacturaOriginal=="" || $ivaFacturaOriginal=="NULL" || $ivaFacturaOriginal == null) $ivaFacturaOriginal=0.00;

        $nifCliente = $datosFactura[0]["nif"];
        $nombreCliente = $datosFactura[0]["cliente"];
        $codigoPais = $datosFactura[0]["codigoPais1"];
        //$codigoPais = "ES";
        $fechaFacturacion = $datosFactura[0]["fecha"]->format('Y-m-d');
        //echo "\nfecha: ".$fechaFacturacion;;
        $numeroFacturaCompleto = $datosFactura[0]["numeroFacturaCompleto"];

        $precioIva = $datosFactura[0]["iva"];
        $precioIrpf= $datosFactura[0]["irpf"];
        //$precioTotal= $datosFactura[0]["precioTotal"];
        $precioTotal= $datosFactura[0]["precioTotalSinIrpf"]; 

        $precioIva = str_replace(",",".",$precioIva);
        $precioIrpf = str_replace(",",".",$precioIrpf);
        $precioTotal = str_replace(",",".",$precioTotal);

        if ($precioIva==".00")$precioIva=0;
        if ($precioIrpf==".00")$precioIrpf=0;
        if ($precioTotal==".00")$precioTotal=0;
        
        if ($precioIva=="" || $precioIva=="NULL" || $precioIva == null) $precioIva=0;
        if ($precioIrpf=="" || $precioIrpf=="NULL" || $precioIrpf == null) $precioIrpf=0;
        if ($precioTotal=="" || $precioTotal=="NULL" || $precioTotal == null) $precioTotal=0;

        if (isset($precioIva[0]) && $precioIva[0] === '.') $precioIva = '0' . $precioIva;
        if (isset($precioIrpf[0]) && $precioIrpf[0] === '.') $precioIrpf = '0' . $precioIrpf;
        if (isset($precioTotal[0]) && $precioTotal[0] === '.') $precioTotal = '0' . $precioTotal;


        $clienteSinIva = $datosFactura[0]["sinIva"];
        $clienteConIrpf = $datosFactura[0]["retencion"];


        if ($clienteSinIva=="" || $clienteSinIva=="NULL" || $clienteSinIva == null) $clienteSinIva=0;
        if ($clienteConIrpf=="" || $clienteConIrpf=="NULL" || $clienteConIrpf == null) $clienteConIrpf=0;


        //$detallesFactura = verFacturaDetalle($conexion,$numFactura,$anioSeleccionado);

        $baseConIva = 0;
        $baseSinIva = 0;

        if (count($totalesPorIva)>1) //2 TIPOS DE IVA 21% y 0% ; Aquí no debe entrar los extranjeros
        {  //echo "\nEntra1";
            if ($totalesPorIva[0]["exentoIva"]==1)
            {  //echo "\nEntra2";
                $baseSinIva = $totalesPorIva[0]["base"];
                $baseConIva = $totalesPorIva[1]["base"];
            }
            else
            { //echo "\nEntra3";
                $baseSinIva = $totalesPorIva[1]["base"];
                $baseConIva = $totalesPorIva[0]["base"];
            }

            $baseSinIva = str_replace(",",".",$baseSinIva);
            $baseConIva = str_replace(",",".",$baseConIva);


            if ($baseConIva==".00" || $baseConIva=="" || $baseConIva=="NULL" || $baseConIva == null) $baseConIva=0;
            if ($baseSinIva==".00" || $baseSinIva=="" || $baseSinIva=="NULL" || $baseSinIva == null) $baseSinIva=0;

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
        else if (count($totalesPorIva)==1) //un tipo de via 21% o 0%
        { //echo "\nEntra4";
            
            if ($clienteSinIva=="1") //toda la factura sin IVA (0%). Esto se indica en la fiche de clientes; aquí entran los extranjeros
            {  //echo "\nEntra5";
                
                $baseSinIva = $totalesPorIva[0]["base"];
                $baseSinIva = str_replace(",",".",$baseSinIva);

                if ($baseSinIva==".00" || $baseSinIva=="" || $baseSinIva=="NULL" || $baseSinIva == null) $baseSinIva=0;                
                if (isset($baseSinIva[0]) && $baseSinIva[0] === '.') $baseSinIva = '0' . $baseSinIva;

                $valorVarOperacion="N1";
                if ($codigoPais!="ES")
                    $valorVarOperacion="N2";

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
            else //toda la factura con 21% ; Aquí no debe entrar los extranjeros
            {   //echo "\nEntra6";
                $baseConIva = $totalesPorIva[0]["base"];
                $baseConIva = str_replace(",",".",$baseConIva);
                if ($baseConIva==".00" || $baseConIva=="" || $baseConIva=="NULL" || $baseConIva == null) $baseConIva=0;
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

        //PARA PRUEBAS
        
        $mostrarDatos .=  "\nnifCliente: ".$nifCliente;
        $mostrarDatos .=  "\nnombreCliente: ".$nombreCliente;
        $mostrarDatos .= "\ncodigoPais: ".$codigoPais;
        $mostrarDatos .= "\nfechaFacturacion: ".$fechaFacturacion;
        $mostrarDatos .= "\nnumeroFacturaCompleto: ".$numeroFacturaCompleto;
        $mostrarDatos .= "\nprecioIva: ".$precioIva;
        $mostrarDatos .= "\nprecioIrpf: ".$precioIrpf;
        $mostrarDatos .= "\nprecioTotal: ".$precioTotal;
        $mostrarDatos .= "\nclienteSinIva: ".$clienteSinIva;
        $mostrarDatos .= "\nclienteConIrpf: ".$clienteConIrpf;        
        $mostrarDatos .= "\ncodigoPais: ".$codigoPais;
        $mostrarDatos .= "\nbaseConIva: ".$baseConIva;
        $mostrarDatos .= "\nbaseSinIva: ".$baseSinIva;
        $mostrarDatos .= "\norigenFactura: ".$origenFactura;
        $mostrarDatos .= "\nfechaFacturaOriginal: ".$fechaFacturaOriginal;

        $mostrarDatos .="\nnetoFacturaOriginal: ".$netoFacturaOriginal;
        $mostrarDatos .="\nivaFacturaOriginal: ".$ivaFacturaOriginal;
        

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


    $facturaOriginal = [
            "style" => "S",
            "ids" => [
                [
                    "number"     => $pruebas.$origenFactura,
                    "issuedTime" => $fechaFacturaOriginal
                ]
                ],
            "creditBase" => (float)$netoFacturaOriginal,
            "creditVat" => (float)$ivaFacturaOriginal
             // "creditBase" => 1.2,
            //"creditVat"  => 0
        ];
    
    
    // Construcción del array con info de la factura :: inicio
    
    
    if ($codigoPais!="ES")
    {
        $invoice = [
                "invoice" => 
                [
                    "recipient" =>
                    [
                        "id" => $nifCliente,
                        "idType" => "06", //ver esto con marian
                        "name" => $nombreCliente, 
                        "country" => $codigoPais
                    ],
                    "description" => 
                    [
                        "text" => "Factura Rectificativa por Sustitucion",
                        "operationDate" => $fechaFacturacion 
                    ],
                    "id" => 
                    [
                        "number"  =>  $pruebas.$numeroFacturaCompleto, 
                        "issuedTime" => $fechaFacturacion 
                    ],
                    "type" => "R1", //R1 RECTIFICATIVA
                    "creditNote"=> $facturaOriginal,            
                    "vatLines" => $vatLines,
                    "total" => (float)$precioTotal,
                    "amount" => (float)$precioIva
                ]
            ];
    }
    else
    {   
    
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
                    "text" => "Factura Rectificativa por Sustitucion",
                    "operationDate" => $fechaFacturacion 
                ],
                "id" => 
                [
                    "number"  => $pruebas.$numeroFacturaCompleto,  
                    "issuedTime" => $fechaFacturacion 
                ],
                "type" => "R1", //R1 RECTIFICATIVA
                "creditNote"=> $facturaOriginal,            
                "vatLines" => $vatLines,
                "total" => (float)$precioTotal,
                "amount" => (float)$precioIva
            ]
        ];
    }
    // Construcción del array con info de la factura :: fin

 

    // Codificación del array de la factura en JSON
    $json_invoice = json_encode($invoice);



    // Solicitud mediante cURL a la API Wórtice Verifactu :: inicio
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlClayma.'invoice', 
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
            'API-KEY: '.$apiKeyClayma,
            'Expect:',            // evita 100-continue
            'Connection: close'   // cierra TCP al final
        ),
    ));

   

    $response = curl_exec($curl);

    $respuestaFinal = "\n<br>fac: ".$numeroFacturaCompleto;


    if ($response === false)
    {   /*
        echo "\nEntra7";
        echo "Error cURL: " . curl_error($curl);
        echo " Código: " . curl_errno($curl);
        */
         $respuestaFinal .= " Error cURL: " . curl_error($curl)." Código: " . curl_errno($curl);
    }
    else
    {  //echo "\nEntra8";
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //echo "<br><br>" . $http_code . "<br><br>";
        $respuestaFinal .= " codigoHTTP: " . $http_code;
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


       /*
        echo "\n<br>ErrorMensaje: ".$message;
        echo "\n<br>Codigo: ".$code;
        echo "\n<br>Id de la Solicitud: " .$requestId;

        */
        $respuestaFinal .= " message: " . $json_response['message']. "codigo: ".$json_response['code'];
        
        guardarVerifactuErroresRecSustClayma($conexion,$numeroFacturaCompleto,$anioSeleccionado,$message, $code, $requestId);

    } 
    else 
    {  //echo "\nEntra10";
        $qr_code = $json_response["qrcode"]; 
        //echo "<br>Codigo QR:" . $qr_code; 


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

        $respuestaFinal .= " correcto";
        
        guardarVerifactuRespuestaRecSustClayma($conexion,$numeroFacturaCompleto,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId);
    }

    echo $mostrarDatos;
    echo $respuestaFinal;
    
}
    // echo "\n";


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



function verEstadoClayma ($queueId)
{
 $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlClayma.'invoice_state/'.$queueId,
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
            'API-KEY: '.$apiKeyClayma,
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




	

	

function lanzarFacturaCibelesPruebaClayma ($urlClayma,$apiKeyClayma)
{  
   $pruebas="Pruebas ";
    
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
                    "number"     => $pruebas."CIB-FAC 4478/25",
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
                "number"  => $pruebas.$numeroFacturaCompleto, 
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
        CURLOPT_URL => $urlClayma.'invoice',
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
            'API-KEY: '.$apiKeyClayma,
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