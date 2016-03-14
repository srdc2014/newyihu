<?php 
$_oc = ini_get('zlib.output_compression');
require dirname(__FILE__) . '/../config.php';
if (! $min_enableBuilder) {
    header('Location: /');
    exit();
}
if (isset($_GET['hello'])) {
    ini_set('zlib.output_compression', '0');
    require $min_libPath . '/HTTP/Encoder.php';
    HTTP_Encoder::$encodeToIe6  = true; 
    $he = new HTTP_Encoder(array(
        'content' => 'World!'
        ,'method' => 'deflate'
    ));
    $he->encode();
    $he->sendAll();
} else {
    header('Content-Type: text/plain');
    echo (int)$_oc;
}
