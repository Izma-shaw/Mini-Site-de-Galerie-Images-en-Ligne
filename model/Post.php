<?php

class Post{

    private $id;
    private $user;
    private $titre;
    private $legend;
    private $img_date;
    private $img_name;



    public function __construct($id,$user,$titre,$legend,$img_date,$img_name){
        $this->id=$id;
        $this->user=$user;
        $this->titre = $titre;
        $this->legend=$legend;
        $this->img_date=$img_date;
        $this->img_name=$img_name;

    }
    //initialisation des donnant venant de la base de données
    public static function initialize($data=array()) {
      
        $id=$data["id"];
        $user=$data["user"];
        $titre = $data["title"];
        $legend = $data["legend"];
        $img_date=$data["img_date"];
        $img_name= $data["img_name"];

        return new Post($id,$user,$titre,$legend,$img_date,$img_name);
    }

    //getters


    public function getId(){
      return $this->id;
    }
    public function getUser(){
      return $this->user;
    }
      public function getTitre(){
        return $this->titre;
    }
    public function getLegend(){
        return $this->legend;
    }
    public function getimg_date(){
        return $this->img_date;
    }
    public function getimg_name(){
        return $this->img_name;
    }

    //setters

    public function setTitre($titre){
        $this->titre= $titre;
    }
    public function setLegend($legend){
        $this->legend = $legend;
    }
    public function setImg_date($img_date){
        $this->img_date = $img_date;
    }
    public function setImg_name($img_name){
        $this->img_name = $img_name;
    }

}
