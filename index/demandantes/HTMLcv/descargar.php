<?php
if (!isset($_REQUEST['file'])) {
    die();
}
$filepath = $_REQUEST['file'];

/* Check if the file name includes illegal characters
 like "../" using the regular expression */
 /*
    Expresión regular para descartar nombres de archivo
    con carácteres "ilegales" como "../"
 */
if (preg_match('/^[^.][-a-z0-9_.]+[a-z]$/i', $filepath)) {

    // Process download
    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
        readfile($filepath);
        die("Downloading...");
    } else {
        http_response_code(404);
        die();
    }
} else {
    die("Invalid file name!"); //En caso de tener dichos caracteres, terminar el script
}