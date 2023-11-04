<?php
session_start();

if (isset($_SESSION['userData']) && $_REQUEST['accion'] == 1) {
    $data = $_SESSION['userData'];
    echo json_encode($data);
}
