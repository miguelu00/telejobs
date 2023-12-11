<?php

    $letras = ['A', 'J', 'Q', 'N', 'M', 'S', 'O', 'H', 'D', 'L', 'C'];
    $passwrd = "";

    for ($i = 0; $i<sizeof($letras); $i++) {
        $passwrd .= array_rand($letras, 1);
        if ($i % 2 == 0) {
            //si es par el numero de indice, introducir otro número randomizado
            $passwrd .= rand(0, 10);
        }
    }
    
    echo password_hash($passwrd, PASSWORD_DEFAULT);