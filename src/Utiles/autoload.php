<?php
/**
 * AUTOLOADER -> Cargará las clases que se requieran SÓLO cuando se instancie un objeto de ellas (new ...)
 * Comprueba antes si la clase existe en el directorio indicado
 */
    function load_model($className) {
        $path_to_file = "Repositories/" . $className . ".php";

        if (file_exists($path_to_file)) {
            require $path_to_file;
        } elseif (file_exists("../" . $path_to_file)) {
            require "../" . $path_to_file;
        }
    }

    spl_autoload_register('load_model');