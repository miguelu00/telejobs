<?php

    require_once("../Utiles/autoload.php");
    require_once("../Utiles/mySQL.php");

    //Clave de API hardcoded
    //$_REQUEST['api_key'] = "$2y$10$pVCVv2EGOk3wEA7jPbGrlu2wHZg1Kv/GTPqKnGZGSkJI4yUycrPvG";

    $req_method = getenv('REQUEST_METHOD');
    // (se usa @ delante de la variable $_SERVER[...] para evitar/suprimir mensajes de error en caso de no estar setteada)
    $peticion = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
    

    if (isset($_REQUEST['registro'])) {
        if ($_REQUEST['registro'] == "true") {
            $_REQUEST['api_key'] = "\$2y\$10\$pVCVv2EGOk3wEA7jPbGrlu2wHZg1Kv/GTPqKnGZGSkJI4yUycrPvG";
        }
    }
    /**
     * autenticación para la api (COMPROBACION API KEY)
     * 
     * Para esto, se puede pasar, o bien una clave de API ó
     * directamente recoger los datos cuando el usuario se ha logeado
     * (La clave de API sólo se usará en caso de querer acceder a la API desde una aplicación externa como POSTMAN)
     */
    if (!isset($_REQUEST['api_key'])) {
        if (!isset($_SESSION['user'])) {
            devolverCodigoHTTP(CRUD_Int::UNAUTHORIZED);
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

    if (!isset($_REQUEST['tabla'])) {
        devolverCodigoHTTP(CRUD_Int::BAD_REQUEST);
        exit;
    }

    /**
     * SEGÚN EL MÉTODO HTTP CON QUE SE LLAMA A LA API, SE REALIZARÁ
     * UNA FUNCIÓN DE BBDD U OTRA.
     * 
     * Comprobación de variables en todos los campos.
     */
    switch ($req_method) {
        case 'PUT':
            // UPDATE : ACTUALIZACIÓN CON TODOS LOS DATOS
            if (!isset($_REQUEST['DATOS_SET']) || !isset($_REQUEST['tabla']) || !isset($_REQUEST['WHERE'])) {
                devolverCodigoHTTP(CRUD_Int::BAD_REQUEST);
                exit();
            }
            $result = update($_REQUEST['tabla'], $_REQUEST['DATOS_SET'], $_REQUEST['WHERE']);
            if ($result == 0) {
                devolverCodigoHTTP(CRUD_Int::SUCCESS_NOTDONE);
                exit();
            } else {
                echo json_encode([
                    "status" => 201,
                    "data" => select($_REQUEST['tabla'], "*", $_REQUEST['WHERE'])
                ]);
                devolverCodigoHTTP(CRUD_Int::SUCCESS_DONE);
                exit();
            }
        case 'POST':
            // CREATE : CREAR UN NUEVO REGISTRO / DEVUELVE ALGUN DATO
            if (isset($_REQUEST['accion'])) {
                switch($_REQUEST['accion']) {
                    case "DELETE":
                        if (!isset($_REQUEST['tabla']) || !isset($_REQUEST['WHERE'])) {
                            devolverCodigoHTTP(CRUD_Int::BAD_REQUEST);
                            exit();
                        } else {
                            $borrado = deleteFrom($_REQUEST['tabla'], $_REQUEST['WHERE']);
                            if ($borrado == 1) {
                                devolverCodigoHTTP(CRUD_Int::SUCCESS_ALLDONE);
                                exit();
                            } else {
                                devolverCodigoHTTP(CRUD_Int::SUCCESS_NOTDONE);
                                exit();
                            }
                        }
                    case "PATCH":
                        if (!isset($_REQUEST['tabla']) || !isset($_REQUEST['DATOS_SET']) || !isset($_REQUEST['WHERE'])) {
                            devolverCodigoHTTP(CRUD_Int::BAD_REQUEST);
                            exit();
                        } else {
                            $result = update($_REQUEST['tabla'], $_REQUEST['DATOS_SET'], $_REQUEST['WHERE']);
                            if ($result == 0) {
                                devolverCodigoHTTP(CRUD_Int::SUCCESS_NOTDONE);
                                exit();
                            } else {
                                echo json_encode([
                                    "status" => 201,
                                    "data" => select($_REQUEST['tabla'], "*", $_REQUEST['WHERE'])
                                ]);
                                devolverCodigoHTTP(CRUD_Int::SUCCESS_DONE);
                                exit();
                            }
                        }
                }
            }
            if (!isset($_REQUEST['DATOS'])) {
                devolverCodigoHTTP(CRUD_Int::BAD_REQUEST);
                exit();
            }
            if (isset($_REQUEST['CAMPOS'])) {
                if (insertINTO3($_REQUEST['tabla'], $_REQUEST['CAMPOS'], $_REQUEST['DATOS']) == 1) {
                    echo json_encode([
                        "status" => 201,
                        "data" => select($_REQUEST['tabla'], "*")
                    ]);
                    devolverCodigoHTTP(CRUD_Int::SUCCESS_DONE);
                    exit();
                } else {
                    devolverCodigoHTTP(CRUD_Int::SUCCESS_NOTDONE);
                    exit();
                }
            } else {
                devolverCodigoHTTP(CRUD_Int::BAD_REQUEST);
                exit();
            }
        case 'GET':
            // SELECT : CARGAR UNO O VARIOS REGISTROS
            $select = null;
            if (!isset($_REQUEST['WHERE']) && !isset($_REQUEST['CAMPOS'])) {
                $select = select($_REQUEST['tabla']);
            } else if (!isset($_REQUEST['CAMPOS'])) {
                $select = select($_REQUEST['tabla'], "*", $_REQUEST['WHERE']);
            } else if (!isset($_REQUEST['WHERE'])) {
                $select = select($_REQUEST['tabla'], $_REQUEST['CAMPOS']);
            } else {
                $select = select($_REQUEST['tabla'], $_REQUEST['CAMPOS'], $_REQUEST['WHERE']);
            }
            if ($select != null) {
                echo json_encode([
                    "status" => 200,
                    "data" => $select
                ]);
                devolverCodigoHTTP(CRUD_Int::SUCCESS);
                exit();
            } else {
                echo json_encode([
                    "status" => 202,
                    "data" => null
                ]);
                devolverCodigoHTTP(CRUD_Int::SUCCESS_NOTDONE); //la consulta se ha realizado, pero no se han devuelto datos
                exit();
            }
        case 'PATCH':
            // UPDATE : (SOLO CIERTOS DATOS DE UN REGISTRO)
            if (in_array($_REQUEST['tabla'], $tablasList)) {
                $return = update($_REQUEST['tabla'], $_REQUEST['CAMPOS_SET'], $_REQUEST['WHERE']);
                if ($return == 1) {
                    echo json_encode([
                        "status" => 201,
                        "data" => select($_REQUEST['tabla'], "*", $_REQUEST['WHERE'])
                    ]);
                    devolverCodigoHTTP(CRUD_Int::SUCCESS_DONE);
                    exit();
                } else {
                    devolverCodigoHTTP(CRUD_Int::SUCCESS_NOTDONE);
                    exit();
                }
            }
            break;
        case 'DELETE':
            //DELETE : UNO O VARIOS DATOS DE UN REGISTRO
            if (in_array($_REQUEST['tabla'], CRUD_Int::TABLASLIST)) {
                if (in_array($_REQUEST['tabla'], CRUD_Int::CRITICALTABLES)) {
                    //checkear antes si el usuario tiene privilegios
                    if ($_SESSION['user'] == "admin123@root") {
                        $borrado = deleteFrom($_REQUEST['tabla'], $_REQUEST['WHERE']);
                        if ($borrado == 1) {
                            devolverCodigoHTTP(CRUD_Int::SUCCESS_ALLDONE);
                            exit();
                        } else {
                            devolverCodigoHTTP(CRUD_Int::SUCCESS_NOTDONE);
                            exit();
                        }
                    }
                } else {
                    $borrado = deleteFrom($_REQUEST['tabla'], $_REQUEST['WHERE']);
                    if ($borrado == 1) {
                        devolverCodigoHTTP(CRUD_Int::SUCCESS_ALLDONE);
                        exit();
                    } else {
                        devolverCodigoHTTP(CRUD_Int::SUCCESS_NOTDONE);
                        exit();
                    }
                }
            } else {
                devolverCodigoHTTP(CRUD_Int::FORBIDDEN);
                exit();
            }
            break;
    }

    function devolverCodigoHTTP($codigo) {
        header('X-PHP-Response-Code: ' . $codigo, true, $codigo);
    }