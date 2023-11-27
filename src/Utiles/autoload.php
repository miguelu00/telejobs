<?php
//AUTOLOADER -> Cargará las clases que se requieran SÓLO cuando se instancie un objeto de ellas (new ...)
//  Comprueba además, si se encuentra en el mismo directorio que la clase antes de hacerle Include
spl_autoload_register(function ($className) {
    $path = sprintf('%s.php', $className);
    if (file_exists($path)) {
        include $path;
    } else {
        // NO SE ENCUENTRA LA CLASE
    }
});