<?php

require_once './config.php';

class SqlTool {
    /* pattern singleton */
    private static $instance;

    /* connexion to Postgres */
    protected $connexion;

    /*  initialising the connexion */
    private function __construct() {

        /* création d'un objet PDO avec les constantes définies dans la configuration */
        $this->connexion = new PDO(PDO_SQL_DSN);
    }

    /* no clone  */
    private function __clone() {

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
