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
                $resultado = $this->dwes->query($sql);
                // Devolvemos el resultado
                return $resultado;
            }
        } catch (Exception $ex) {
            // Si se produce un error, lanzamos una excepción
            throw $ex;
        }
    }

    /**
     * Función que permite ejecutar una consulta transaccionalmente
     * @param string $sql La cadena sql a ejecutar
     * @param array $datos Los parámetros de la consulta
     * @return array El resultado de la consulta
     * @throws Exception Si hay un error se lanza una excepción
     */
    private function ejecutaConsultaTransaccion($sql, array $datos) {
        try {
            // Preaparamos una sentencia para la insercción del 
            // fichero en la tabla documentos            
            $stmt = $this->dwes->prepare($sql);
            // Creamos un contador para ir asignando valores a la sentencia
            $cont = 1;
            // Iteramos por el array
            foreach ($datos as $key) {
                // Verificamos si el valor es un recurso y si este recurso 
                // es de tipo stream, el cual habra que pasarlo como un campo 
                // BLOB. Despues vamos asignando los valores del array a cada 
                // posición de la sentencia. 
                if (gettype($key) === "resource" && get_resource_type($key) === "stream") {
                    // Asignamos el valor del fichero, especificando 
                    // que se trata de un fichero tipo BLOB, para que 
                    // modifique la información guardada en formato 
                    // stream en la base de datos adaptandolo en el 
                    // proceso
                    $stmt->bindValue($cont, $key, PDO::PARAM_LOB);
                } else {
                    // Si no es un recurso el valor, lo asignamos sin parámetros
                    $stmt->bindValue($cont, $key);
                }
                // Aumentamos el contador
                $cont++;
            }
            // Devolvemos el resultado
            return $stmt->execute();
        } catch (Exception $ex) {
            // Si se produce una excepción la lanzamos para que se ocupe de ella 
            // la función que haya invocado a esta
            throw $ex;
        }
    }

    public function altaRegistro(Registro $registro) {
        try {
            // Comprobamos si no estamos en una transacción, para evitar 
            // intentar una transacción dos veces si la función se invoca  ha 
            // invocado anteriormente
            if (!$this->dwes->inTransaction()) {
                // Si no es así, iniciamos una transacción
                $this->dwes->beginTransaction();
            }
            // Creamos la consulta sql que usaremos para introducir los valores
            $sql = "INSERT INTO REGISTRO VALUES (?, ?, ?)";
            // Creamos un array con los datos que pasaremos a cada una de los simbolos de interrogación en orden 
            $datos = ['id_registro' => 0, 'nombre' => $registro->getNombre(), 'apellidos' => $registro->getApellidos()];
            // Ejecutamos la consulta de forma transaccional y almacenamos el resultado
            $resultado = $this->ejecutaConsultaTransaccion($sql, $datos);
            // Comprobamos si el resultado es correcto, para continuar haciendo operaciones
            if ($resultado) {
                // Recuperamos el último id insertado en la base de datos, que se corresponde con el id del registro que acabamos de alamcenar
                $id_registro = $this->dwes->lastInsertId();
                // Iteramos por el array de números
                for ($index = 0; $index < count($registro->getNumeros()); $index++) {
                    // Creamos la cadena de insercción de números y el array de datos a pasar a la función de ejecutar transacciones
                    $sql = "INSERT INTO NUMERO VALUES (?, ?, ?)";
                    $datos = ['id_numero' => 0, 'numero' => $registro->getNumeros()[$index]->getNumero(), 'id_registro' => $id_registro];
                    // Realizamos la insercción de forma transaccional y almacenamos el resultado en una variable
                    $insercion = $this->ejecutaConsultaTransaccion($sql, $datos);
                    // Comprobamos que la insercción se haya realizado correctamente
                    if (!$insercion) {
                        // Si la inserccion no es correcta, hacemos un rollback a las trasacciones con el fin de no modificar la base de datos                        
                        $this->dwes->rollBack();
                        // Devolvemos un mensaje de error
                        return -3;
                    }
                }
                // Si no se ha producido ningún error durante las transacciones para almacenar la película y 
                // los integrantes de la misma realizamos un commit para hacer permanentes los datos 
                // almacenados en la base de datos
                $this->dwes->commit();
                // Devolvemos el id de la película almacenada en la base de datos
                return $id_registro;
            } else {
                // Si el resultado no es correcto, hacemos un rollback a las trasacciones con el fin de no modificar la base de datos
                $this->dwes->rollBack();
                // Devolvemos un dígito negativo como mensaje de error
                return -2;
            }
        } catch (Exception $ex) {
            // Si se produce una excepción hacemos un rollback a las trasacciones con el fin de no modificar la base de datos
            $this->dwes->rollBack();
            // Devolvemos un dígito negativo como mensaje de error
            return -1;
        }
    }

    public function bajaRegistro($id_registro) {
        $sql = "DELETE FROM registro";
        $sql .= " WHERE id_registro=$id_registro";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /*
     * Función que nos permite modificar el contenido de una pelicula dandola de baja para posteriormente darla de alta
     * @param Pelicula $pelicula La información de la película a modificar en un objeto Pelicula
     * @return int Devuelve 0 si va todo correctamente, -1 si se produce una excepción, 
     * -2 si no se puede eliminar la película antes de insertarla con los datos nuevos, 
     * -3 si se produce un error al insertar la película
     */

    public function modificarRegistro(Registro $registro) {
        try {
            // Iniciamos una transacción
            $this->dwes->beginTransaction();
            // Damos de baja la película y comprobamos si se ha borrado 
            // correctamente de la base de datos
            if ($this->bajaRegistro($registro->getId_registro()) === 0) {
                // Añadimos la película                
                $resultado = $this->altaRegistro($registro);
                // Comprobamos que se ha dado de alta correctamente comprobando 
                // el id que ha devuelto la función y comprobando que es mayor que 0
                if ($resultado > 0) {
                    // Si todo es correcto, devolvemos el resultado del alta de la película, 
                    // que corresponde al nuevo id de la misma
                    return $resultado;
                } else {
                    // Devolvemos -3 como código de error. En este caso no se 
                    // hace rollback de la transacción pq se encarga de ello 
                    // la función altaPelicula.
                    return -3;
                }
            } else {
                // Si no se puede eliminar la película, hacemos rollback de la transacción
                $this->dwes->rollBack();
                // Devolvemos -2 como código de error
                return -2;
            }
        } catch (Exception $ex) {
            // Si se produce una excepción, hacemos rollback de la transacción
            $this->dwes->rollBack();
            // Devolvemos -1 como código de error
            return -1;
        }
    }

    public function listaRegistros() {
        $sql = "SELECT * FROM registro;";

        $resultado = self::ejecutaConsulta($sql);

        $registros = array();

        if ($resultado) {

            $row = $resultado->fetch();

            while ($row != null) {

                $numeros = array();

                $id_registro = $row['id_registro'];

                $sql2 = "SELECT * FROM numero WHERE id_registro =" . $id_registro . ";";

                $resultado2 = self::ejecutaConsulta($sql2);

                if ($resultado2) {

                    $row2 = $resultado2->fetch();

                    while ($row2 != null) {

                        $numeros[] = new Numero($row2['id_numero'], $row2['numero']);
                        $row2 = $resultado2->fetch();
                    }

                    $registro = new Registro($row['id_registro'], $row['nombre'], $row['apellidos'], $numeros);
                    $registros[] = $registro;
                }

                $row = $resultado->fetch();
            }
        }
        return $registros;
    }

    public function listaNumeros() {
        $sql = "SELECT * FROM numero;";
        $resultado = self::ejecutaConsulta($sql);
        $numeros = array();
        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $numeros[] = new Numero($row);
                $row = $resultado->fetch();
            }
        }
        return $numeros;
    }

    public function listaNumero($id_numero) {
        $sql = "SELECT * FROM numero WHERE id_numero =" . $id_numero . ";";
        $resultado = self::ejecutaConsulta($sql);
        if ($resultado) {
            $row = $resultado->fetch();
        }
        return $row;
    }

    public function listaRegistro($id_registro) {
        $sql = "SELECT * FROM numero WHERE id_registro =" . $id_registro . ";";
        $resultado = self::ejecutaConsulta($sql);

        $numeros = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $numeros[] = new Numero($row['id_numero'], $row['numero']);
                $row = $resultado->fetch();
            }

            $sql = "SELECT * FROM registro WHERE id_registro =" . $id_registro . ";";

            $resultado = self::ejecutaConsulta($sql);

            if ($resultado) {
                $row = $resultado->fetch();

                $registro = new Registro($row['id_registro'], $row['nombre'], $row['apellidos'], $numeros);
                
                
                return $registro;
            }
        }
        
        return NULL;
    }

}
