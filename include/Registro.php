<?php

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
    
    function __construct($id_registro, $nombre, $apellidos) {
        $this->id_registro = $id_registro;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
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




}
