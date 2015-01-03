<?php 
namespace MVC;

class Tag
{
	/**
	 * Add a tag in the DB.
	 * 
	 * @param  string  $newTag  Tag to add
	 * @return array
	 */
	public function add($newTag)
	{
		// Getting singleton instance from the SQL class
		$sql = Sql::getInstance();

		// Request creation
		$sqlQuery = 'INSERT INTO tag(name)
					 VALUES (:name)';

		// Preparating the request, pdo returns an object PDOStatement
		// which will execute the request and contain the results
		$statement = $sql->pdo->prepare($sqlQuery);

		// Initialising request parameters
		// Executing request on the mySQL server
		$statement->execute(array(':name' => $newTag));

		// Returning the number of ligns affected by the request in the SQL table (1 in this case)
		return $statement->rowCount();
	}

	/**
	 * Return all the tags.
	 * 
	 * @return array
	 */
	public function getAll()
	{
		// Getting singleton instance from the SQL class
		$sql = Sql::getInstance();

		// Request creation
		$sqlQuery = 'SELECT t.name as tagName, t.id as tagId
					 FROM tag t';

		// Preparating the request, pdo returns an object PDOStatement
		// which will execute the request and contain the results
		$statement = $sql->pdo->prepare($sqlQuery);

		// Executing request on the mySQL server
		$statement->execute();

		// Fetching results
		/*var_dump($statement->fetchAll());die();*/
		$results = $statement->fetchAll();

		return $results;
	}

	/**
	 * Return an article.
	 * 
	 * @param  integer $id  id of the article
	 * @return array
	 */
	public function get($id)
	{
		// Getting singleton instance from the SQL class
		$sql = Sql::getInstance();

		// Request creation
		$sqlQuery = 'SELECT * FROM article WHERE article.id = :id';

		// Preparating the request, pdo returns an object PDOStatement
		// which will execute the request and contain the results
		$statement = $sql->pdo->prepare($sqlQuery);

		// Executing request on the mySQL server
		// Initialising request parameters
		// The value of the key :id corresponds to :id in the SQL request
		$statement->execute(array(':id' => $id));

		// Fetching results
		$all = $statement->fetch();
		/*var_dump($all);*/
		return $all;
	}
}