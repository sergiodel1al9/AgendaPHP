<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Agenda
 *
 * @author Sergio
 */
class Agenda {

    private $nombre;
    private $registros;

    function __construct($nombre, $registros) {
        $this->nombre = $nombre;
        $this->registros = $registros;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getRegistros() {
        return $this->registros;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setRegistros($registros) {
        $this->registros = $registros;
    }

}
