<?php 
namespace MVC;

class Article 
{
	/**
	 * Renvoi tous mes articles
	 * @return array
	 */
	public function getAll()
	{
		// Je récupère mon instance singleton de la class Sql
		$sql = Sql::getInstance();

		// Création de la requête
		$sqlQuery = 'SELECT
						a.id,
						a.title,
						a.body,
						t.name as tagName
					 FROM article a
					 LEFT JOIN article_tag at
					 	ON a.id = at.id_article
					 LEFT JOIN tag t
					 	ON t.id = at.id_tag'; // http://php.net/manual/fr/pdo.prepare.php#refsect1-pdo.prepare-examples

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats
		$statement = $sql->pdo->prepare($sqlQuery);

		// Exécution de la requête sur le serveur mysql
		$statement->execute();

		// Récupération des résultats
		/*var_dump($statement->fetchAll());die();*/
		$datas = $statement->fetchAll();
		
		$result = array();
		foreach ($datas as $row) {
			if (empty($result[$row['id']])) {
				$result[$row['id']] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'body' => $row['body'],
					'tags' => array(
						$row['tagName']
					)
				);
			}
			else {
				$result[$row['id']]['tags'][] = $row['tagName'];
			}
		}

		/*var_dump($result);die();*/
		return $result;
	}

	/**
	 * Renvoi un article
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

	public function add($title, $body)
	{
		// Je récupère mon instance singleton de la class Sql
		$sql = Sql::getInstance();

		// Création de la requête
		// Les paramètres de la requête sont sous la forme :variable
		// et seront initialisés dans la méthode execute();
		$sqlQuery = 'INSERT INTO article(title, body)
					 VALUES (:title, :body)';

		// Préparation de la requête, pdo renvoi un objet PDOStatement
		// qui exécutera la requête et contiendra les résultats
		$statement = $sql->pdo->prepare($sqlQuery);

		// Exécution de la requête sur le serveur mysql
		// Initialisation des paramètres de la requête
		// La valeur de la clé :id correspond au :id dans la requête sql
		$statement->execute(array(
			':title' => $title,
			':body' => $body
		));

		return $statement->rowCount();
	}
}