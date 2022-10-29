<?php
require_once "eventos.php";
$eventos = new Eventos();

$accion = $_GET['Accion'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
} else {
    $data = $_GET;
    unset($data["Accion"]);
}

switch($accion) {
    case 'crear':
        $eventos->agregar($data);
        break;
    case 'modificar':
        $eventos->modificar($data);
        break;
    case 'listar':
        $eventos = $eventos->listar($data);
        echo $eventos;
        break;
    case 'borrar':
        $eventos->eliminar($data["id"]);
        break;
    default: 
        break;
}
