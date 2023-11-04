<?php
    session_start();
    require_once "../Utiles/mySQL.php";

    if (isset($_SESSION['user'])) {
        switch (tipoUsuario($_SESSION['user'])) {
            case 3:
                header("Location: ./empresa/");
                break;
            case 4:
                header("Location: ./demandantes/");
                break;
        }
    } else {
        header('Location: ../');
    }