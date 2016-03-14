<!DOCTYPE html>

<?php

require_once './include/DB.php';
require_once './include/Registro.php';
require_once './include/Numero.php';

$cliente = new DB();


$listaNumeros = array();

$listaNumeros[] = new Numero('0','950555339');
$listaNumeros[] = new Numero('0','617699191');


$registro = new Registro(0, "Luis", "Cabrerizo", $listaNumeros);


$array = $cliente->altaRegistro($registro);



?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>        
    </body>
</html>
