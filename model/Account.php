<?php

// Créer une classe Account qui représente un compte utilisateur (nom, login, mot de passe et statut).
class Account{
    private $username;
    private $password;
    private $creation_date;

    function __construct($username, $password, $creation_date){
        $this->username = $username;
        $this->password=$password;
        $this->creation_date = $creation_date;
    }
    //initialisation des donnees de la base de données
    public static function initialize($data = array()) {

        $username = $data['username'];
        $password=$data['password'];
        $creation_date = $data['creation_date'];
        return new Account($username, $password, $creation_date);
    }

    //getters

    public function getUsername(){
        return $this->username;
    }

    public function  getPassword(){
        return $this->password;
    }

    public function getCreationDate(){
        return $this->creation_date;
    }

    //setters
    public function setUsername($username){
        $this->username=$username;
    }

    public function setPassword($password){
        $this->password=$password;
    }

    public function setCreationDate($creation_date){
        $this->creation_date=$creation_date;
    }
}



 ?>
