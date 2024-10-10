<?php
set_include_path("./");
class LogsStorage {



    public function create() {
      if(isset($_SESSION['account'])){
        $user=$_SESSION['account']->getUsername();
        $bd = SqlTool::getInstance()->getConnexion();
        $query = $bd->prepare("INSERT INTO logs values (:username, :log_date, :ip)");
        $query->bindValue(':username',$user,PDO::PARAM_STR);
        $query->bindValue(':log_date',date('j M Y, G:i:s'),PDO::PARAM_STR);
        $query->bindValue(':ip',$_SERVER['REMOTE_ADDR'],PDO::PARAM_STR);
        $query->execute();
      }
    }

    public function readAll() {
        $bd = SqlTool::getInstance()->getConnexion();
        $query = $bd->prepare("SELECT * FROM logs");
        $query->execute();
        $array=$query->fetchall();

        return $array ;
    }

}
