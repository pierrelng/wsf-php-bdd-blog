<?php
namespace MVC;

/**
* 
*/
class Sql
{
	public static $instance; // Les variables statiques sont partagées dans toutes les instances d'une classe, pareil pour les fonctions statiques.
	
	public $pdo;

	public function __construct()
	{
		$dsn = 'mysql:dbname=silex-blog;host=127.0.0.1'; // http://php.net/manual/fr/pdo.construct.php#refsect1-pdo.construct-examples
		$user = 'root';
		$password = '';

		try {
		    $this->pdo = new \PDO($dsn, $user, $password);
		} catch (\PDOException $e) {
		    echo 'Connexion échouée : ' . $e->getMessage();
		}
	}

	public static function getInstance()
	{
		if (empty(self::$instance)) { //classname::variableàaccéder
			$className = __CLASS__;
			self::$instance = new $className();
		}

		return self::$instance;
	}
}