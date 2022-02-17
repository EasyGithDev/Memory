<?php

require_once(__DIR__ . '/Config.php');

class Connection
{

    /**
     * @var Connection
     * @access private
     * @static
     */
    private static $_instance = null;
    protected $dbh = null;

    /**
     * Constructeur de la classe
     *
     * @param void
     * @return void
     */
    private function __construct()
    {
        $config = Config::getInstance()->get('db');
        $dsn = "mysql:host=$config->host;port=$config->port;dbname=$config->db;charset=$config->charset";
        $this->dbh = new PDO($dsn, $config->user, $config->password);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     *
     * @param void
     * @return Connection
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Connection();
        }

        return self::$_instance;
    }

    /**
     *
     * @param void
     * @return $dbh la connexion PDO
     */
    public function getPdo()
    {
        return $this->dbh;
    }
}
