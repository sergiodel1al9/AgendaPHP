<!DOCTYPE html>

<?php
require_once './include/DB.php';
require_once './include/Registro.php';
require_once './include/Numero.php';

$cliente = new DB();


$mensaje = "";
$nombre = "";
$apellido = "";
$telefonos = "";
$idReg = "";

// Comprobamos si ya se ha pulsado el botón de Añadir
if (isset($_POST['añadir'])) {

    // Verificamos que se hayan introducido nombre y apellidos
    if (empty($_POST['nombre']) || empty($_POST['apellidos']) || empty($_POST['numero'])) {
        $mensaje = "No se han introducido los datos";
    } else {

        $numero = new Numero(0, $_POST['numero']);

        $numeros = array();

        $numeros[] = $numero;

        if (isset($_POST['idRegistro']) && empty($_POST['idRegistro'])) {

            $registro = new Registro(0, $_POST['nombre'], $_POST['apellidos'], $numeros);

            // Realizamos el alta del artista usando el servicio y comprobamos el exito de la acción
            if ($cliente->altaRegistro($registro)) {

                // Si es correcto, mostraremos un mensaje
                $mensaje = "correcto";
            } else {
                // Si la acción no ha sido correcta mostramos un mensaje
                $mensaje = "Se ha producido un error a dar de alta el Registro";
            }


            $nombre = "";
            $apellido = "";
            $telefonos = "";
            $idReg = "";
        } else {
            $registro = new Registro($_POST['idRegistro'], $_POST['nombre'], $_POST['apellidos'], $numeros);

            // Realizamos el alta del artista usando el servicio y comprobamos el exito de la acción
            if ($cliente->modificarRegistro($registro)) {

                // Si es correcto, mostraremos un mensaje
                $mensaje = "correcto";
            } else {
                // Si la acción no ha sido correcta mostramos un mensaje
                $mensaje = "Se ha producido un error al modificar el Registro";
            }

            $nombre = "";
            $apellido = "";
            $telefonos = "";
            $idReg = "";
        }
    }
}

// Comprobamos si ya se ha pulsado el botón de Eliminar
if (isset($_POST['eliminar'])) {

    // Verificamos que se hayan introducido nombre y apellidos
    if (empty($_POST['idregistro'])) {
        $mensaje = "Fallo al eliminar registro";
    } else {


        if ($cliente->bajaRegistro($_POST['idregistro']) === 0) {

            // Si es correcto, mostraremos un mensaje
            $mensaje = "Correcto";
        } else {
            // Si la acción no ha sido correcta mostramos un mensaje
            $mensaje = "Se ha producido un error a eliminar el registro";
        }
    }
}

// Comprobamos si se ha pulsado el botón de modificar
if (isset($_POST['modificar'])) {

    // Comprobamos si se ha psado un id
    if (!empty($_POST['idregistro'])) {

        $registro = $cliente->listaRegistro($_POST['idregistro']);

        $nombre = $registro->getNombre();
        $apellido = $registro->getApellidos();
        $telefonos = $registro->getNumerosString();
        $idReg = $registro->getId_registro();
    } else {
        // Si no tenemos id, mostramos un mensaje
        $mensaje = 'No se ha podido identificar el id del artista a modificar';
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action='index.php' method='post'>


            <input type="text" name="nombre" placeholder="Nombre" id="nombre" value="<?php echo $nombre ?>">

            <input type="text" name="apellidos" placeholder="Apellidos" id="apellidos" value="<?php echo $apellido ?>"></input>

            <input type="text" name="numero" placeholder="Número" id="numero" value="<?php echo $telefonos ?>"></input>

            <input type='submit' name='añadir' value='Añadir'/>

            <input type="hidden" name="idRegistro" value="<?php echo $idReg ?>"/>

        </form>

        <div>
            <?php
            echo $mensaje;
            echo '<br>';
            ?>
        </div>
        <div>
            <table>
                <tr>
                    <th>
                        Nombre
                    </th>
                    <th>
                        Apellidos
                    </th>
                    <th>
                        Teléfono
                    </th>
                </tr>
                <?php
                $registros = array();

                $registros = $cliente->listaRegistros();

                for ($index = 0; $index < count($registros); $index++) {
                    echo '<tr>';
                    echo '<td>';
                    echo $registros[$index]->getNombre();
                    echo '</td>';

                    echo '<td>';
                    echo $registros[$index]->getApellidos();
                    echo '</td>';

                    echo '<td>';
                    echo $registros[$index]->getNumerosString();
                    echo '</td>';

                    echo '<td>';

                    echo "<form action='index.php' method='post'>";
                    echo "<input type='submit' name='eliminar' value='Eliminar'/>";
                    echo "<input type='hidden' name='idregistro' value='" . $registros[$index]->getId_registro() . "' />";
                    echo '</form>';

                    echo '<td>';

                    echo '<td>';

                    echo "<form action='index.php' method='post'>";
                    echo "<input type='submit' name='modificar' value='Modificar'/>";
                    echo "<input type='hidden' name='idregistro' value='" . $registros[$index]->getId_registro() . "' />";
                    echo '</form>';

                    echo '<td>';
                    echo "</tr>";
                }
                ?>

            </table>
        </div>
    </body>
</html>
