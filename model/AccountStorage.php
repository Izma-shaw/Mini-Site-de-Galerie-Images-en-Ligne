<?php
set_include_path("./");
class AccountStorage {



    public function create(Account $account) {
        $bd = SqlTool::getInstance()->getConnexion();
        $query = $bd->prepare("INSERT INTO users values (:username, :password, :creation_date)");
        $query->bindValue(':username',$account->getUsername(),PDO::PARAM_STR);
        $query->bindValue(':password',password_hash($account->getPassword(),PASSWORD_BCRYPT),PDO::PARAM_STR);
        $query->bindValue(':creation_date',$account->getCreationDate(),PDO::PARAM_STR);
        $query->execute();
    }


    public function createPass() {
        $bd = SqlTool::getInstance()->getConnexion();
        if(isset($_POST['Connecter'])){
        $query = $bd->prepare("INSERT INTO users values (:username, :password, :creation_date)");
        $query->bindValue(':username',$_SESSION["account"]->getUsername(),PDO::PARAM_STR);
        $query->bindValue(':password',password_hash($_POST['newpassword'],PASSWORD_BCRYPT),PDO::PARAM_STR);
        $query->bindValue(':creation_date',$_SESSION['account']->getCreationDate(),PDO::PARAM_STR);
        $query->execute();
      }
    }


      //utilisée pour l'authentification de l'utilisateur
    public function checkAuth($username,$password){
        $bd = SqlTool::getInstance()->getConnexion();
        $query = $bd->prepare("SELECT * FROM users WHERE username = ?");
        $data=array($username);
        $query->execute($data);
        $ligne = $query->fetchAll();
        //vérifie si les renseignements figure dans la base de données
        if (!empty($ligne)){
        if(password_verify($password,$ligne[0]['password'])){
            return Account::initialize($ligne[0]);
        }
        else{
        return false;
      }
    }
    else{
      return false;
    }

    }

    public function checkLoginExistence($username){
        $bd = SqlTool::getInstance()->getConnexion();
        $query = $bd->prepare("SELECT username FROM users WHERE username = ?");
        $data=array($username);
        $query->execute($data);
        $ligne = $query->fetch(PDO::FETCH_ASSOC);
        //vérifie si les renseignements figure dans la base de données
        return $ligne;
    }



    public function readAll() {
        $bd = SqlTool::getInstance()->getConnexion();
        $query = $bd->prepare("SELECT * FROM users");
        $query->execute();
        $array=$query->fetchall();

        return $array ;
    }

    public function readAll2() {
        $bd = SqlTool::getInstance()->getConnexion();
        $query = $bd->prepare("SELECT * FROM users WHERE username != 'sow' AND username != 'arafat' AND username != 'vanier' AND username != 'lecarpentier'");
        $query->execute();
        $array=$query->fetchall();

        return $array ;
    }

    public function delete($username){
        $connexion = SqlTool::getInstance()->getConnexion();
        $query = $connexion->prepare("DELETE FROM users WHERE username=? ");
        $data=array($username);
        return $query->execute($data);
    }

    public function deleteOld($password){
        $connexion = SqlTool::getInstance()->getConnexion();
        $query = $connexion->prepare("DELETE FROM users WHERE password=? ");
        $data=array($password);
        return $query->execute($data);
    }


}
