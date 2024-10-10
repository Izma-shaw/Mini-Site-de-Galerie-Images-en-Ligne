<?php

class AccountCreation{

    private $data;
    private $error;
    //la base de données
    private $accountStorage;

    const USERNAME= "username";
    const PASSWORD= "password";
    const CREATIONDATE= "creationdate";


    public function __construct($data=null){
     /* Crée une nouvelle instance, avec les données passées en argument si
 	 * elles existent, et sinon avec
 	 * les valeurs par défaut des champs de création d'une couleur. */
		if ($data === null || $data===array()) {
			$data = array(
				        "username" => "",
                "password" => "",
                "creationdate" => "",
			);
		}
		$this->data = $data;
		$this->error = array(
            "username" => null,
            "password" => null,
            "creationdate" => null,
            'loginOuPASSWORDIncorrect'=>null,
        );
	}

    //crée un compte à partir des donnée du formulaire
    public function createAccount(AccountStorage $accountStorage){
            $new_account = new Account($this->data[self::USERNAME],$this->data[self::PASSWORD],date('j M Y, G:i:s'));
            if($accountStorage->checkLoginExistence($new_account->getUsername())===false){
                return $new_account;
            }
            $this->error['loginExistence']='ce username existe déja veuillez en choisir un autre!';
            return false;
    }


    public function createAccountPass(AccountStorage $accountStorage){
            $new_account = new Account($this->data[self::USERNAME],$this->data[self::PASSWORD],date('j M Y, G:i:s'));
            if(password_verify($accountStorage->checkPassExistence($new_account->getPassword(),$new_account->getUsername()))===true){
                return $new_account;
            }
            $this->error['loginExistence']='mauvais mot de passe';
            return false;
    }
    //verifie la validité du formulaire remplit par l'utilisateur
    public function isValid(){
        if(key_exists(self::USERNAME,$this->data) && $this->data[self::USERNAME]==='' ){
            $this->error[self::USERNAME]="<p>remplissez votre <strong>nom d'utilisateur</strong></p>";}

        if(key_exists(self::PASSWORD,$this->data) && $this->data[self::PASSWORD]===''){
            $this->error[self::PASSWORD] = "remplissez votre <strong>mot de passe</strong>";}


        foreach($this->error as $key=>$val){
            //si le tableau d'erreur n'est pas vide, retourner faux;
            if($val!==null)
                return false;
        }
        return true;

    }



    //getters
    public function getData($data){
        return $this->data[$data];
    }
    public function getDatat(){
        return $this->data;
    }
    public function getError($data){
        return $this->error[$data];
    }

    public function getErrtable(){
        return $this->error;
    }

    //setters
    public function setdata($data){
        $this->data = $data;
    }
    public function setError($error){
        $this->error = $error;
    }
    public function addError($id,$error){
        $this->error[$id]=$error;
    }

}
