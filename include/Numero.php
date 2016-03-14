<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Numero
 *
 * @author Sergio
 */
class Numero {
    private $id_numero;
    private $numero;
    
    function __construct($id_numero, $numero) {
        $this->id_numero = $id_numero;
        $this->numero = $numero;
    }
    
    function getId_numero() {
        return $this->id_numero;
    }

    function getNumero() {
        return $this->numero;
    }

    function setId_numero($id_numero) {
        $this->id_numero = $id_numero;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }






}


