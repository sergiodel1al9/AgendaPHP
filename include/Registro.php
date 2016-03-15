<?php

include_once 'Numero.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Registro
 *
 * @author Sergio
 */
class Registro {

    private $id_registro;
    private $nombre;
    private $apellidos;
    private $numeros = array();

    public function __construct($id_registro, $nombre, $apellidos, $numeros) {
        $this->id_registro = $id_registro;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->numeros = $numeros;
    }

    function getId_registro() {
        return $this->id_registro;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellidos() {
        return $this->apellidos;
    }

    function setId_registro($id_registro) {
        $this->id_registro = $id_registro;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    public function getNumeros() {
        return $this->numeros;
    }

    public function setNumeros($numeros) {
        $this->numeros = $numeros;
    }

    public function getNumerosString() {
        
        $salida = "";
        
        for ($index = 0; $index < count($this->numeros); $index++) {
            $salida .= " ";
            $salida .= $this->numeros[$index]->getNumero();
        }
        
        return ltrim($salida);
    }

    public function mostrarRegistro() {
        $salida = "";

        $salida .= $this->nombre;
        $salida .= " ";
        $salida .= $this->apellidos;

        for ($index = 0; $index < count($this->numeros); $index++) {
            $salida .= " ";
            $salida .= $this->numeros[$index]->getNumero();
        }

        return $salida;
    }

}
