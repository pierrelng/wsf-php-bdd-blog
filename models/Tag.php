<?php 
namespace MVC;

class Tag
{
	/**
	 * Ajout d'un tag dans la BDD.
	 * 
	 * @param  string  $newTag  Le tag à ajouter
	 * @return array
	 */
	public function add($newTag)
	{
		// Je récupère mon instance singleton de la class Sql.
		$sql = Sql::getInstance();

		// Création de la requête.
		$sqlQuery = 'INSERT INTO tag(name)
					 VALUES (:name)';

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats.
		$statement = $sql->pdo->prepare($sqlQuery);

		// Initialisation des paramètres de la requête.
		// Exécution de la requête sur le serveur mySQL.
		$statement->execute(array(':name' => $newTag));

		// Je return le nombre de lignes affectées par la requête dans la table SQL (normalement 1 dans ce cas).
		return $statement->rowCount();
	}

	/**
	 * Renvoi tous les tags.
	 * 
	 * @return array
	 */
	public function getAll()
	{
		// Je récupère mon instance singleton de la class Sql
		$sql = Sql::getInstance();

		// Création de la requête
		$sqlQuery = 'SELECT t.name as tagName
					 FROM tag t';

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats
		$statement = $sql->pdo->prepare($sqlQuery);

		// Exécution de la requête sur le serveur mysql
		$statement->execute();

		// Récupération des résultats
		/*var_dump($statement->fetchAll());die();*/
		$results = $statement->fetchAll();

		return $results;
	}

	/**
	 * Renvoi un article.
	 * 
	 * @param  integer $id  identifiant de l'article
	 * @return array
	 */
	public function get($id)
	{
		// Je récupère mon instance singleton de la class Sql
		$sql = Sql::getInstance();

		// Création de la requête
		// Les paramètres de la requête sont sous la forme :variable
		// et seront initialisés dans la méthode execute();
		$sqlQuery = 'SELECT * FROM article WHERE article.id = :id';

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats
		$statement = $sql->pdo->prepare($sqlQuery);

		// Exécution de la requête sur le serveur mysql
		// Initialisation des paramètres de la requête
		// La valeur de la clé :id correspond au :id dans la requête sql
		$statement->execute(array(':id' => $id));

		// Récupération des résultats
		$all = $statement->fetch();
		/*var_dump($all);*/
		return $all;
	}
}