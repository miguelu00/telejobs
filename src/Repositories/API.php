<?php

    require_once("../Utiles/autoload.php");
    require_once("../Utiles/mySQL.php");

    //Clave de API hardcoded
    //$_REQUEST['api_key'] = "321admin";

    $req_method = getenv('REQUEST_METHOD');
    // (se usa @ delante de la variable $_SERVER[...] para evitar/suprimir mensajes de error en caso de no estar setteada)
    $peticion = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
    $tablasList = ["candidaturas", "categoria_emp", "demandantes", "empresas", "habilidades", "ofertas_trab", "recup_cuenta"];

    /**
     * autenticación para la api (COMPROBACION API KEY)
     * 
     * Para esto, se puede pasar, o bien una clave de API ó
     * directamente recoger los datos cuando el usuario se ha logeado
     * (La clave de API sólo se usará en caso de querer acceder a la API desde un método externo como POSTMAN)
     */
    if (!isset($_REQUEST['api_key'])) {
        if (!isset($_SESSION['user'])) {
            devolverCodigoHTTP(CRUD_Int::BAD_REQUEST);
            exit();
        } else {
            $_SESSION['perm'] = "rw";
        }
    } else if (!CRUD_Int::api_existe($_REQUEST['api_key'])) {
        devolverCodigoHTTP(CRUD_Int::FORBIDDEN);
        exit();
    } else {
        //si se ha proporcionado una clave de API correcta, dar todos los permisos
        $_SESSION['perm'] = "rwx";
        // rwx => read | write | delete
    }



    switch ($req_method) {
        case 'PUT':
            // UPDATE CON TODOS LOS DATOS
            if (!isset($_REQUEST['datos'])) {
                
            }
            break;
        case 'POST':
            // CREATE - CREAR UN NUEVO REGISTRO / DEVUELVE ALGUN DATO
            if (!isset($_REQUEST['datos'])) {
                devolverCodigoHTTP(CRUD_Int::BAD_REQUEST);
                exit();
            }
            if (!isset($_REQUEST['CAMPOS'])) {
                if (insertINTO2($_REQUEST['tabla'], $_REQUEST['CAMPOS'], $_REQUEST['DATOS'])) {
                    echo json_encode([
                        "status" => 201,
                        "data" => select($_REQUEST['tabla'], "*")
                    ]);
                } else {
                    
                }
            }
            break;
        case 'GET':
            // CARGAR UNO O VARIOS REGISTROS
            if (!isset($_REQUEST['WHERE'])) {
                echo json_encode([
                    "status" => 200,
                    "data" => select($_REQUEST['tabla'])
                ]);
            } else if (!isset($_REQUEST['CAMPOS'])) {
                echo json_encode([
                    "status" => 200,
                    "data" => select($_REQUEST['tabla'], "*". $_REQUEST['WHERE'])
                ]);
            } else {
                echo json_encode([
                    "status" => 200,
                    "data" => select($_REQUEST['tabla'], $_REQUEST['CAMPOS']. $_REQUEST['WHERE'])
                ]);
            }
            break;
        case 'PATCH':
            // UPDATE (SOLO CIERTOS DATOS DE UN REGISTRO)
            //Uso de POST para evitar cambios/acceso no autorizados
            if (in_array($_REQUEST['tabla'], $tablasList)) {
                $return = update($_POST['tabla'], "", "");
            }
            break;
        case 'DELETE':
            //DELETE UNO O VARIOS DATOS DE UN REGISTRO

            break;
    }

    if (isset($_POST['accion']) && isset($_POST['tabla'])) {
        switch ($_POST['accion']) {
            case "UPDATE":
                break;
            case "DELETE":
                $return = deleteFrom($_POST['tabla'], "");
        }
    } elseif (!isset($_REQUEST['tabla'])) {
        http_response_code(CRUD_Int::BAD_REQUEST);
        exit;
    }

    function devolverCodigoHTTP($codigo) {
        header('X-PHP-Response-Code: ' . $codigo, true, $codigo);
    }