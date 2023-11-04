<?php
require_once "../Utiles/nuSoap/lib/nusoap.php";
//Declaramos el objeto cliente
$cliente = new nusoap_client('https://ovc.catastro.meh.es/ovcservweb/ovcswlocalizacionrc/ovccallejero.asmx?WSDL', true);

$error = $cliente->getError();
//si no hay errores, seguir con la ejecución
if ($error) {
    die();
}

if (isset($_REQUEST['prov'])) {
    $metodoCall = "ObtenerMunicipios";
    $provi = $_REQUEST['prov'];
    $provincia = utf8_decode($provi);

    $resultado = $cliente->call($metodoCall, array('Provincia' => $provincia, array()));
    $arrDatos = array();
    foreach ($resultado['consulta_municipiero']['municipiero']['muni'] as $clave => $dato) {
        $arrDatos[] = utf8_encode($dato['nm']);
    }
    $datos = json_encode($arrDatos);
    echo $datos;
    die();
}