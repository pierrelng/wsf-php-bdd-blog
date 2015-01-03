<?php 
namespace MVC;

class Comment 
{
	public function getByArticle($id)
	{
		// Je récupère mon instance singleton de la class Sql
		$sql = Sql::getInstance();

		// Création de la requête
		$sqlQuery = 'SELECT comment.body, user.login, comment.create_at, comment.id
					 FROM comment
					 JOIN user
					 		ON comment.id_user = user.id
					 WHERE
					 	comment.id_article = :idArticle';

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats
		$statement = $sql->pdo->prepare($sqlQuery);

		// Exécution de la requête sur le serveur mysql
		$statement->execute(array(
			':idArticle' => $id
		));

		// Récupération des résultats
		return $statement->fetchAll();
	}

	public function add($body, $id_article, $id_user)
	{
		$date = new \DateTime(); // http://php.net/manual/fr/class.datetime.php#class.datetime
		$date = $date->format('Y-m-d H:i:s'); // http://php.net/manual/en/function.date.php#refsect1-function.date-parameters

		// Je récupère mon instance singleton de la class Sql
		$sql = Sql::getInstance();

		// Création de la requête
		// Les paramètres de la requête sont sous la forme :variable
		// et seront initialisés dans la méthode execute();
		$sqlQuery = 'INSERT INTO comment(body, id_article, id_user, create_at)
					 VALUES (:body, :idArticle, :idUser, :date)';

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats
		$statement = $sql->pdo->prepare($sqlQuery);

		// Exécution de la requête sur le serveur mysql
		// Initialisation des paramètres de la requête
		// La valeur de la clé :id correspond au :id dans la requête sql
		$statement->execute(array(
			':body' => $body,
			':idArticle' => $id_article,
			':idUser' => $id_user,
			':date' => $date
		));

		return $statement->rowCount();
	}

	public function delete($idComment)
	{
		// Je récupère mon instance singleton de la class Sql
		$sql = Sql::getInstance();

		// Création de la requête
		// Les paramètres de la requête sont sous la form :variable
		// et seront initialisé dans la méthode execute();
		$sqlQuery = 'DELETE FROM comment
					 WHERE id = :id';

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats
		$statement = $sql->pdo->prepare($sqlQuery);

		// Exécution de la requête sur le serveur mysql
		// Initialisation des paramètres de la requête
		// La valeur de la clé :id correspond au :id dans la requête sql
		$statement->execute(array(
			':id' => $idComment
		));

		return $statement->rowCount();
	}
}