<?php 
namespace MVC;

class User 
{
	public function getByLogin($login)
	{
		// Je récupère mon instance singleton de la class Sql
		$sql = Sql::getInstance();

		// Création de la requête
		// Les paramètres de la requête sont sous la forme :variable
		// et seront initialisés dans la méthode execute();
		$sqlQuery = 'SELECT * FROM user WHERE user.login = :login';

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats
		$statement = $sql->pdo->prepare($sqlQuery);

		// Exécution de la requête sur le serveur mysql
		// Initialisation des paramètres de la requête
		// La valeur de la clé :id correspond au :id dans la requête sql
		$statement->execute(array(':login' => $login));

		// Récupération des résultats
		$all = $statement->fetch();
		/*var_dump($all);*/
		return $all;
	}

	public function add($email, $password, $admin)
	{
		// Si getByLogin renvoie qqchose c'est que l'email existe déjà
		// Du coup je crée une exception qui sera catchée dans AdminController
		if ($this->getByLogin($email)) {
			throw new \Exception('Email already exist.');
		}

		// Je récupère mon instance singleton de la class Sql
		$sql = Sql::getInstance();

		// Création de la requête
		// Les paramètres de la requête sont sous la forme :variable
		// et seront initialisés dans la méthode execute();
		$sqlQuery = 'INSERT INTO user(login, password, admin)
					 VALUES (:login, :password, :admin)';

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats
		$statement = $sql->pdo->prepare($sqlQuery);

		// Exécution de la requête sur le serveur mysql
		// Initialisation des paramètres de la requête
		// La valeur de la clé :id correspond au :id dans la requête sql
		$statement->execute(array(
			':login' => $email,
			':password' => $password,
			':admin' => $admin
		));

		return $statement->rowCount();
	}
}