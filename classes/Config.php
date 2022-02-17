<?php

class Config
{

    /**
     * @var Config
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * Le registre
     */
    private $container = [];

    /**
     * Les fichiers de configuration
     */
    private $files = ['db', 'app'];

    /**
     * Constructeur de la classe
     *
     * @param void
     * @return void
     */
    private function __construct()
    {
        foreach ($this->files as $file) {
            $this->container[$file] = (object) require_once(__DIR__ . '/../config/' . $file . '.php');
        }
    }

    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     *
     * @param void
     * @return Config
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Config();
        }

        return self::$_instance;
    }

    /**
     *
     * @param mixed la clé
     * @return mixed la valeur associée à la clé, sinon null
     */
    public function get(mixed $key): mixed
    {
        return (array_key_exists($key, $this->container)) ? $this->container[$key] : null;
    }
}
