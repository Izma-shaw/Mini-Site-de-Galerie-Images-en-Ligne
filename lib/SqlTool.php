<?php
set_include_path("./");
require_once 'defineDB.php';

class SqlTool {
    /* pattern singleton */
    private static $instance;

    /* connexion to sql  */
    protected $connexion;

    /*  initialising the connexion */
    private function __construct() {

        /* création d'un objet PDO avec les constantes définies dans la configuration */

        try {
            $this->connexion = new PDO(DSN,USER,PASSWORD);
        } catch (PDOException $exc) {
            echo 'Connexion échouée : '.$exc->getMessage();
        }

    }

    /**
     * get the uniq instance
     */
    static public function getInstance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * connexion accessor
     */
    public function getConnexion() {
        return $this->connexion;
    }

}

?>
