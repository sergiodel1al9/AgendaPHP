<!DOCTYPE html>

<?php
require_once './include/DB.php';
require_once './include/Numero.php';
require_once './include/Registro.php';

$cliente = new DB();

$cliente->altaRegistro('Sergio', 'Jimenez');
$cliente->altaNumero('680342538', 1);







?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>        
    </body>
</html>
