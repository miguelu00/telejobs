<?php

$zip = new ZipArchive();

$DelFilePath="plantilla.zip";

$carpeta = $_REQUEST['nombre_plantilla'];
if ($carpeta == "Plantilla_1") {
    $fileHTML = "plantilla1.html";
} elseif ($carpeta == "Plantilla_2") {
    $fileHTML = "plantilla3.html";
} elseif ($carpeta == "Plantilla_3") {
    $fileHTML = "plantilla3.html";
}

//Eliminamos el fichero .ZIP si ya existe
if(file_exists($_SERVER['DOCUMENT_ROOT']."/".$DelFilePath)) {

    unlink ($_SERVER['DOCUMENT_ROOT']."/".$DelFilePath);

}
if ($zip->open($_SERVER['DOCUMENT_ROOT']."/".$DelFilePath, ZipArchive::CREATE) !== TRUE) {
    die ("Could not open archive");
}
//Se hace un fichero .ZIP a partir de los ficheros de plantilla HTML
hacerZipR($zip, $carpeta);
$zip->addFile($fileHTML);

// close and save archive

$zip->close();
//ATENCIÓN: Función RECURSIVA
function hacerZipR($zip, $ruta) {
    if (is_dir($ruta)) {
        $lista = scandir($ruta);
        unset($lista[array_search('.', $lista)]);
        unset($lista[array_search('..', $lista)]);
        if (sizeof($lista)>0) {
            foreach ($lista as $elem) {
                //con Este foreach, recursivamente recogeremos ficheros y directorios hasta que lleguemos
                // a un directorio vacío/nos quedemos sin ficheros a añadir.
                if (is_file($ruta . '/' . $elem)) {
                    $zip->addFile($ruta . $elem, $elem);
                }
                if (is_dir($ruta . '/' . $elem)) {
                    if (count(scandir($ruta . '/' . $elem)) > 2) {
                        hacerZipR($zip, $ruta. '/' . $elem);
                    } else {
                        $zip->addEmptyDir($ruta. '/' . $elem);
                    }
                }
            }
        } else {
            $zip->addEmptyDir($ruta);
        }
    }
}