<?php 
namespace MVC;

class Comment 
{
	public function getByArticle($id)
	{
		// Getting singleton instance from the SQL class
		$sql = Sql::getInstance();

		// Request creation
		$sqlQuery = 'SELECT comment.body, user.login, comment.create_at, comment.id
					 FROM comment
					 JOIN user
					 		ON comment.id_user = user.id
					 WHERE
					 	comment.id_article = :idArticle';

		// Preparating the request, pdo returns an object PDOStatement
		// which will execute the request and contain the results
		$statement = $sql->pdo->prepare($sqlQuery);

		// Executing request on the mySQL server
		$statement->execute(array(
			':idArticle' => $id
		));

		// Fetching results
		return $statement->fetchAll();
	}

	public function add($body, $id_article, $id_user)
	{
		$date = new \DateTime(); // http://php.net/manual/fr/class.datetime.php#class.datetime
		$date = $date->format('Y-m-d H:i:s'); // http://php.net/manual/en/function.date.php#refsect1-function.date-parameters

		// Getting singleton instance from the SQL class
		$sql = Sql::getInstance();

		// Request creation.
		// The parameters are written under this form ':variable'
		// and will be initialised in the execute(); method
		$sqlQuery = 'INSERT INTO comment(body, id_article, id_user, create_at)
					 VALUES (:body, :idArticle, :idUser, :date)';

		// Preparating the request, pdo returns an object PDOStatement
		// which will execute the request and contain the results
		$statement = $sql->pdo->prepare($sqlQuery);

		// Executing request on the mySQL server
		// Initialising request parameters
		// The value of the key :id corresponds to :id in the SQL request
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
		$sql = Sql::getInstance();

		$sqlQuery = 'DELETE FROM comment
					 WHERE id = :id';

		$statement = $sql->pdo->prepare($sqlQuery);

		$statement->execute(array(
			':id' => $idComment
		));

		return $statement->rowCount();
	}
}