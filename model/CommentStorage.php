<?php
set_include_path("./");
class CommentStorage{


  private $db;


  //recupérer un commentaire spécifié
  function read($id){
      $connexion = SqlTool::getInstance()->getConnexion();
      $query = $connexion->prepare("SELECT * FROM comments WHERE id = ?");
      $data=array($id);
      $query->execute($data);

      $ligne = $query->fetchAll();
      return $ligne[0];
  }
  //recupérer tous les commentaires
  function readAll($id){
      $connexion = SqlTool::getInstance()->getConnexion();
      $query = $connexion->prepare("SELECT * FROM comments where id='$id'");
      $query->execute();
      $postList =$query->fetchall();
      return $postList;
  }
  //créer un commentaire 
  public function create($id){
      $user=$_SESSION["account"]->getUsername();
      $bd = SqlTool::getInstance()->getConnexion();
      $query = $bd->prepare("INSERT INTO comments VALUES (:id,:user,:content,:cdate)");
      $query->bindValue(':id',$id,PDO::PARAM_STR);
      $query->bindValue(':user',$user,PDO::PARAM_STR);
      $query->bindValue(':content',$_POST["LeCommentaire"],PDO::PARAM_STR);
      $query->bindValue(':cdate',date('j M Y, G:i:s'),PDO::PARAM_STR);
      $query->execute();
  }

}

?>
