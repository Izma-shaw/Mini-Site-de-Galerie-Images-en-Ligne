<?php

class Comment{


    private $id;
    private $user;
    private $content;





    public function __construct($id,$user,$content){
        $this->id=$id
        $this->user=$user;
        $this->content = $content;

    }
    //initialisation des donnant venant de la base de données
    public static function initialize($data=array()) {
        
        $id=$data["id"];
        $user=$data["user"];
        $content = $data["content"];

        return new Post($id,$user,$content);
    }

    //getters


    public function getId(){
      return $this->id;
    }
    public function getUser(){
      return $this->user;
    }
      public function getContent(){
        return $this->content;
    }



}
