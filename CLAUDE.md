# CLAUDE.md

## funciones.php

Este proyecto está migrando las funciones de acceso a datos en `funciones.php`, tabla por tabla, de un patrón antiguo (condición SQL como string) a un patrón nuevo (columnas/filtros como parámetros estructurados).

Antes de crear una función nueva en `funciones.php` para una tabla:

1. Comprueba primero si ya existe una función con el patrón nuevo para esa tabla.
2. Si existe, lee su firma exacta (nombres, orden y tipos de parámetros tal como está definida en el código) y reutilízala respetando esa firma real. No asumas que el orden o los nombres de parámetros son iguales entre funciones de tablas distintas — cada una puede tener su propia convención.
3. Si la tabla no tiene todavía una función con el patrón nuevo, créala siguiendo ese mismo patrón (parámetros estructurados, no strings de condición).

## Ampliar una función ya existente usada en varios archivos

Cada vez que se amplíe o modifique una función ya existente que se usa en varios archivos/llamadas:

1. Buscar todas las llamadas existentes a esa función en todo el proyecto.
2. Asegurar que el cambio sea compatible hacia atrás: los parámetros nuevos deben ser opcionales, con valor por defecto, añadidos al final, sin cambiar el orden ni los nombres de los parámetros existentes.
3. Confirmar que ninguna llamada existente se rompe con el cambio (revisar cada una de las llamadas encontradas en el paso 1).

Aplicar siempre esta regla, no solo en un caso puntual.

## Endpoints ajax

Si ya existe un endpoint `ajax/*.php` genérico que se pueda reutilizar para lo que se necesita, se usará ese antes de crear un endpoint ajax nuevo.

## Empezar a trabajar en una pantalla nueva

Cuando se pida trabajar en un archivo/pantalla dando solo su nombre (ej. "vamos con prefactura.php"), antes de modificar nada:

1. Analiza y mapea todos los archivos implicados en esa pantalla: el PHP de la pantalla, su JS asociado, los endpoints `ajax/*.php` que llama, y las funciones de `Archivos Comunes/funcionesAntiguo.php` que esos endpoints usan (junto con las tablas que consultan).
2. Indica si ya existen en `funciones.php` funciones equivalentes con el patrón nuevo (ver sección anterior) para esas mismas tablas.
3. Muestra el plan (qué se tocaría y por qué) y espera confirmación explícita antes de modificar ningún archivo.

No modifiques nada hasta que se confirme el plan explícitamente.

## Resumen

Registro de lo hecho hasta ahora en la migración, la lógica seguida, las decisiones de estructura tomadas y las correcciones recibidas. Esto es un histórico, no añade reglas nuevas a las secciones anteriores.

### Lógica seguida en la migración

- Patrón antiguo: `Archivos Comunes/funcionesAntiguo.php`, objeto `$conexion` (`datosBD`), condición SQL armada como string, tablas partidas por año (`facturas{año}`, `facturasClayma{año}`, `facturasRecDiferencias{año}`, `facturasRecSustitucion{año}`, etc.).
- Patrón nuevo: `Archivos Comunes/funciones.php`, `$conn`/`$bbddSql` obtenidos con `conectarSQL($conexion)`, tablas unificadas (`facturacion`, `facturacionClayma`, `presupuestos`, ...) con parámetros estructurados: `campos` (whitelist `camposPermitidos` para el SELECT), `joins` (`joinsPermitidos` opcionales), `filtros` (igualdad exacta), `filtrosOperadores` (comparaciones campo-valor o campo-campo, validadas contra `camposComparablesPermitidos` y `$operadoresPermitidos`), `filtrosLike` (LIKE envuelto en `%...%` contra `camposLikePermitidos`), `order` (`camposOrdenPermitidos`).
- Las funciones de lectura devuelven `{error, datos, sql, params}`; las de escritura `{error, ok, ..., sql, params}`.
- La unificación de tablas por año introdujo el discriminador `serieFactura` (`FAC`, `RECT`, `SUST`, `NEG`, `AB`) y el campo `origenFactura` para enlazar una factura derivada (rectificativa, sustitución, negativa) con la original vía `numeroFacturaCompleto`.
- Pantallas/funciones migradas hasta ahora: `crearFacRecSustitucion.php`/`js_crearFacRecSustitucion.js` (con `anularProvisionFondoAplicadaAFactura`, `duplicarFacturaANegativa`), listado y export Excel de `admFacturasVisualizar.php` (incluyendo vista "Agente Comercial" y el checkbox "Liquidado"), `noFacturables.php`/`js_noFactura.js` (incluye filtro por año y export Excel), y `botonFechaFinFacturacion()`/`botonFechaFinFacturacionClayma()` de `js_admFacturacion.js` junto con `ajax/verEstadoFacturacionFinMes.php`/`...Clayma.php`.

### Decisiones de estructura tomadas

- Sargabilidad: filtrar siempre por la columna real (ej. `presupuesto LIKE '25%'`) en vez de por una expresión calculada sobre la columna (ej. `SUBSTRING(presupuesto,1,2)+2000`), porque lo segundo impide el uso de índices y es más lento.
- `duplicarFacturaANegativa`: copia la fila COMPLETA de la factura original (no se recalculan los datos del cliente), con los importes negados y `serieFactura='NEG'`; en las líneas de detalle se niegan `unidades`/`total` (no `precio`) y cada línea conserva su propio `presupuesto` (no el del encabezado), para soportar facturas combinadas con varios presupuestos.
- Antes de generalizar un endpoint compartido (ej. añadir `filtros.numNoFactura` como alternativa a `filtros.presupuesto` en `ajax/modificarPresupuesto.php`), se listan y verifican todas las llamadas existentes para confirmar que el cambio es aditivo y no rompe a nadie (aplicación concreta de la regla de "Ampliar una función ya existente").
- Al detectar funciones estructuradas ya existentes pero no conectadas todavía (ej. `mostrarFacturarFechaActual`/`Clayma`), se reutilizan directamente en vez de duplicarlas.
- Las inconsistencias que ya existían entre la versión Cibeles y la versión Clayma de una misma función (formato de fecha, comprobaciones extra) se han preservado tal cual al migrar cada una, en vez de unificarlas, salvo que se pida explícitamente lo contrario.
- Corrupción de ficheros Excel: causada por `display_errors=TRUE` dejando pasar avisos de PHP al flujo binario de salida; solución aplicada en todos los export: `ob_start()` al principio del archivo (antes de cualquier require/consulta) + `ob_end_clean()` justo antes de los `header()`, y `display_errors=FALSE`.
- El servidor no soporta el operador `<=>` (PHP 7+); usar comparaciones explícitas (`<`, `==`) en su lugar.
- Importes de facturación (`iva`/`precioTotal`/`irpf`/`aPagar`) deben calcularse siempre con la misma fórmula y a partir del mismo origen (líneas de detalle) en todas las pantallas, nunca "casi iguales".

### Correcciones recibidas

- Antes de crear una función nueva, comprobar si ya existe una con el patrón nuevo para esa tabla y reutilizarla respetando su firma real (no asumir que la firma es igual entre tablas distintas).
- No dar por hecho equivalencias entre campos antiguos y nuevos (ej. `inicialComercial` no es lo mismo que el comercial del cliente): hay que leer la función legacy real y su cadena de JOIN antes de mapear un campo.
- Al añadir un filtro nuevo, evitar comparaciones no sargables aunque parezcan más directas (ver filtro por año en `noFacturables.php`).
- No preservar comportamiento legacy "extra" (ej. el log de auditoría "Motivo: ..." al eliminar un no-facturable) si no se pide explícitamente; preguntar antes de añadir alcance no solicitado.
- Antes de tocar un endpoint compartido por varias pantallas, enumerar y verificar todas sus llamadas existentes.
- Al corregir un bug, no tocar código que "ya funciona" salvo que sea estrictamente necesario: si hay que arreglar una comparación de fechas, no reformatear ni tocar los literales de formato (`t/m/Y`, `d/m/Y`) usados para guardar/mostrar la fecha; aislar el arreglo a la comparación puntual.
- Siempre indicar qué función o archivo se va a modificar antes de editarlo.
- Si existe un ajax genérico reutilizable, usarlo antes de crear uno nuevo (ver sección "Endpoints ajax").
