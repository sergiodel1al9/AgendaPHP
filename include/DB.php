<?php

require_once('Numero.php');
require_once('Registro.php');

class DB {

    /**
     * Objeto que almacenará la base de datos PDO
     * @var type PDO Object
     */
    private $dwes;

    /**
     * Constructor de la clase DB
     * @throws Exception Si hay un error se lanza una excepción
     */
    public function __construct() {
        try {
            $serv = "localhost";
            $base = "agenda";
            $usu = "root";
            $pas = "";
            // Creamos un array de configuración para la conexion PDO a la base de 
            // datos
            $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            // Creamos la cadena de conexión con la base de datos
            $dsn = "mysql:host=$serv;dbname=$base";
            // Finalmente creamos el objeto PDO para la base de datos
            $this->dwes = new PDO($dsn, $usu, $pas, $opc);
            $this->dwes->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Método que nos permite realizar consultas a la base de datos
     * @param string $sql Sentencia sql a ejecutar
     * @return array Resultado de la consulta
     * @throws Exception Lanzamos una excepción si se produce un error
     */
    private function ejecutaConsulta($sql) {
        try {
            // Comprobamos si el objeto se ha creado correctamente
            if (isset($this->dwes)) {
                // De ser así, realizamos la consulta
                // De ser así, realizamos 
                $resultado = $this->dwes->query($sql);
                // Devolvemos el resultado
                return $resultado;
            }
        } catch (Exception $ex) {
            // Si se produce un error, lanzamos una excepción
            throw $ex;
        }
    }
    

    
    public function altaRegistro($nombre, $apellidos) {
        $sql = "INSERT INTO registro";
        $sql .= " VALUES (0, '$nombre, $apellidos')";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    
    public function altaNumero($numero, $id_registro) {
        $sql = "INSERT INTO numero";
        $sql .= " VALUES (0, '$numero', '$id_registro')";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }
                     

    
    public function listaRegistros() {
        $sql = "SELECT * FROM registro;";
        $resultado = self::ejecutaConsulta($sql);
        $generos = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $generos[] = new Genero($row);
                $row = $resultado->fetch();
            }
        }

        return $generos;
    }

    
    public function listaRegistro($id_registro) {
        $sql = "SELECT * FROM registro WHERE id_registro =" . $id_registro . ";";
        $resultado = self::ejecutaConsulta($sql);

        if ($resultado) {
            $row = $resultado->fetch();
        }

        return $row;
    }

    
    public function listaNumeros() {
        $sql = "SELECT * FROM numero;";
        $resultado = self::ejecutaConsulta($sql);
        $artistas = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $artistas[] = new Artista($row);
                $row = $resultado->fetch();
            }
        }

        return $artistas;
    }

    public function listaNumero($id_numero) {
        $sql = "SELECT * FROM numero WHERE id_numero =" . $id_numero . ";";
        $resultado = self::ejecutaConsulta($sql);

        if ($resultado) {
            $row = $resultado->fetch();
        }

        return $row;
    }                    
}
