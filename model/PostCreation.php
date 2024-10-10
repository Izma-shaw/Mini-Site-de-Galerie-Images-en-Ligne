<?php

class PostCreation{

    private $data;
    private $error;



    const ID="id";
    const USER="user";
    const TITRE= "titre";
    const LEGEND= "legend";
    const IMG_DATE= "img_date";
    const IMG_NAME= "img_name";


    public function __construct($data=null){
     /* Crée une nouvelle instance, avec les données passées en argument si
 	 * elles existent, et sinon avec
 	 * les valeurs par défaut des champs de création d'une couleur. */
		if ($data === null || $data===array()) {
			$data = array(
                "id"=>"",
                "user"=>"",
                "titre" => "",
                "legend" => "",
                "img_date" => "",
                "img_name" => "",
			);
		}
		$this->data = $data;
		$this->error = array(
            "id"=> null,
            "user"=> null,
            "titre" => null,
            "legend" => null,
            "img_date" => null,
            "img_name" => null,
            'loginOuPasswdIncorrect'=>null,
        );
	}

    //crée un compte à partir des donnée du formulaire
    public function createPost(){
      $extension="";
      $extension = pathinfo($_FILES["ImageToUpload"]["name"], PATHINFO_EXTENSION);
      if($extension == "jpg" || $extension == "png" || $extension == "gif" || $extension == "jpeg" ||  $extension == "JPG" || $extension == "PNG" || $extension == "GIF" || $extension == "JPEG")
      {
          //Set le timezone pour que la fonction date retourne les bonnes valeurs
          //date_default_timezone_set("Europe/France");
          //Ajoute un unique id a la photo pour son enregistrement
          $uploadfile = uniqid("").".".$extension;
              //Copie le file choisis dans le dossier image
          if(move_uploaded_file($_FILES['ImageToUpload']['tmp_name'], "DM_Mini_Site/image/".$uploadfile))
          {
            // redirige a index
           $new_post = new Post(0,$_SESSION['account']->getUsername(),$this->data[self::TITRE],$this->data[self::LEGEND],date('j M Y, G:i:s'),$uploadfile);
           return $new_post;
          }
      }

    }

    //verifie la validité du formulaire remplit par l'utilisateur
    public function isValid(){
        if(key_exists(self::TITRE,$this->data) && $this->data[self::TITRE]==='' ){
            $this->error[self::TITRE]="<p>remplissez <strong>le titre</strong></p>";}

        if(key_exists(self::LEGEND,$this->data) && $this->data[self::LEGEND]===''){
            $this->error[self::LEGEND] = "remplissez <strong>la legende</strong>";}


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
