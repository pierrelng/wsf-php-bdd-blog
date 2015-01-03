<?php 
namespace MVC;

class Article 
{
	/**
	 * Return all articles.
	 * 
	 * @return array
	 */
	public function getAll()
	{
		// Getting singleton instance from the SQL class
		$sql = Sql::getInstance();

		// Request creation
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

		// Preparating the request, pdo returns an object PDOStatement
		// which will execute the request and contain the results
		$statement = $sql->pdo->prepare($sqlQuery);

		// Executing request on the mySQL server
		$statement->execute();

		// Fetching results
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
	 * Return an article.
	 * 
	 * @param  integer  $id  id of article
	 * @return array
	 */
	public function get($id)
	{
		$sql = Sql::getInstance();

		$sqlQuery = 'SELECT * FROM article WHERE article.id = :id';

		$statement = $sql->pdo->prepare($sqlQuery);

		$statement->execute(array(':id' => $id));

		$all = $statement->fetch();
		/*var_dump($all);*/
		return $all;
	}

	/**
	 * Add an article in DB.
	 * 
	 * @param text  $title  article title
	 * @param text  $body  article body
	 */
	public function add($title, $body)
	{
		$sql = Sql::getInstance();

		$sqlQuery = 'INSERT INTO article(title, body)
					 VALUES (:title, :body)';

		$statement = $sql->pdo->prepare($sqlQuery);

		$statement->execute(array(
			':title' => $title,
			':body' => $body
		));

		$lastId = $sql->pdo->lastInsertId();
		//$lastId = mysql_insert_id();
		// http://stackoverflow.com/questions/1685860/how-do-i-get-the-last-inserted-id-of-a-mysql-table-in-php
		// http://php.net/manual/fr/pdo.lastinsertid.php
		// http://stackoverflow.com/questions/2675766/pdo-lastinsertid-issues-php
		
		/*var_dump($lastId);die();*/
					
		return array($statement->rowCount(), $lastId);
	}

	/**
	 * Associate an article
	 * with the selected tags.
	 * 
	 * @param array  $tagsId  ids of selected tags during article creation
	 */
	public function addToPivot($tagsId, $articleId)
	{
		$sql = Sql::getInstance();

		$sqlQuery = 'INSERT INTO article_tag(id_article, id_tag)
					 VALUES (:articleId, :tagsId)';

		$statement = $sql->pdo->prepare($sqlQuery);

		$statement->execute(array(
			':tagsId' => $tagsId,
			':articleId' => $articleId,
		));

		return ;
	}
}