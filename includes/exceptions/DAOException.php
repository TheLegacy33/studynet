<?php

class DAOException extends Exception
{
    public function __construct(){
        $this->message = 'Erreur de base de données !';
    }
}