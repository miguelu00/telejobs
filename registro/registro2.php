<?php
    session_start();
    require_once "../Utiles/mySQL.php";

//NOTA IMPORTANTE: El primer registro, en este punto, ya se habrá hecho con AJAX
    if (isset($_POST['registroDem'])) {
        $correoUser = $_POST['emailHid'];
        if (!checkRegister($correoUser, array("demandantes"))) {
            die("ERROR FATAL");
        }
        //Si esta registrado ya, completar el registro
        update("demandantes",
            "skill_ids = '" . $_POST['habils'] . ", " . $_POST['masHabils'] . "', 
            nombre = '" . $_POST['nom1'] . "', 
            apellidos = '" . $_POST['apels1'] . "', 
            fechaNac = '" . $_POST['fnac1'] . "', 
            tlf = " . $_POST['tlf1'] . ", 
            cPost = " . $_POST['cpos1'] . ", 
            munip = '" . $_POST['munip1'] . "'', 
            NIF = '" . $_POST['nif1'] . "'",
        "email='" . $correoUser . "'");
        //Enviamos correo de verificación
        header("Location: ../Utiles/enviarMail.php?mail=" . $correoUser . "&nombre=" . $_POST['nom1'] . " " . $_POST['apels1'] . "&tipo=DEMANDANTE");
        die();
    }

    if (isset($_POST['registroEmp'])) {
        $correoUser = $_POST['emailHid'];
        $ettCheck = 0;
        if (isset($_POST['checkETT'])) {
            $ettCheck = 1;
        }
        if (!checkRegister($correoUser, array("empresas"))) {
            header("Location: ../index.php?checkRegister=true");
            die("ERROR FATAL");
        }
        //Si esta registrado ya, completar el registro
        update("empresas",
            "nombre = '" . $_POST['nomEmpresa'] . "', 
            nombre_Prop = '" . $_POST['nom3'] . "', 
            apels = '" . $_POST['apels2'] . "', 
            actividad_p = '" . $_POST['actividad1'] . "', 
            f_apertura = '" . $_POST['fnac2'] . "', 
            tlf = " . $_POST['tlf2'] . ", 
            cPostal = " . $_POST['cpos2'] . ", 
            municipio_sede = '" . $_POST['munip2'] . "'', 
            CIF = '" . $_POST['cif1'] . "', 
            descripcion = '" . $_POST['desc1'] . "', 
            direccion = '" . $_POST['dir1'] . "' , 
            emp_ETT = " . $ettCheck,
            "email='" . $correoUser . "'");
        //Enviamos correo de verificación
        header("Location: ../Utiles/enviarMail.php?mail=" . $correoUser . "&nombre=" . $_POST['nom1'] . " " . $_POST['apels1'] . "&tipo=DEMANDANTE");
        die();
    }