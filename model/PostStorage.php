<?php

    set_include_path("./DM_Mini_Site");

class PostStorage {

  


    //recupérer un post spécifié
    function read($id){
        $connexion = SqlTool::getInstance()->getConnexion();
        $query = $connexion->prepare("SELECT * FROM post WHERE id = ?");
        $data=array($id);
        $query->execute($data);

        $ligne = $query->fetchAll();
        return ($ligne)?(Post::initialize($ligne[0])):false;
    }

    //recupérer tous les posts
    function readAll(){
        $connexion = SqlTool::getInstance()->getConnexion();
        $query = $connexion->prepare("SELECT * FROM post");
        $query->execute();
        $postList = array();
        foreach ($query->fetchall() as $id => $postData) {
            $post = Post::initialize($postData);
            $postList[$id] = $post;
        }
	     return $postList;
     }
    //créer un post
    public function create(Post $p){
        $user=$_SESSION["account"]->getUsername();
        $bd = SqlTool::getInstance()->getConnexion();
        $query = $bd->prepare("INSERT INTO post VALUES (0,:user, :title, :legend, :img_date, :img_name)");
        $query->bindValue(':user',$user,PDO::PARAM_STR);
        $query->bindValue(':title',$p->getTitre(),PDO::PARAM_STR);
        $query->bindValue(':legend',$p->getLegend(),PDO::PARAM_STR);
        $query->bindValue(':img_date',$p->getimg_date(),PDO::PARAM_STR);
        $query->bindValue(':img_name',$p->getimg_name(),PDO::PARAM_STR);
        $query->execute();
    }

    //supprimer un article de la base
    public function delete($id){
        $connexion = SqlTool::getInstance()->getConnexion();
        $query = $connexion->prepare("DELETE FROM post WHERE id=? ");
        $data=array($id);
        return $query->execute($data);
    }

    public function deleteWithName($user){
        $connexion = SqlTool::getInstance()->getConnexion();
        $query = $connexion->prepare("DELETE FROM post WHERE user=? ");
        $data=array($user);
        return $query->execute($data);
    }


}
