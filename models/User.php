<?php 
namespace MVC;

class User 
{
	public function getByLogin($login)
	{
		// Getting singleton instance from the SQL class
		$sql = Sql::getInstance();

		// Request creation.
		// The parameters are written under this form ':variable'
		// and will be initialised in the execute(); method
		$sqlQuery = 'SELECT * FROM user WHERE user.login = :login';

		// Preparating the request, pdo returns an object PDOStatement
		// which will execute the request and contain the results
		$statement = $sql->pdo->prepare($sqlQuery);

		// Executing request on the mySQL server
		// Initialising request parameters
		// The value of the key :id corresponds to :id in the SQL request
		$statement->execute(array(':login' => $login));

		// Retrieving results
		$all = $statement->fetch();
		/*var_dump($all);*/
		return $all;
	}

	public function add($email, $password, $admin)
	{
		// If getByLogin returns something, the email already exists
		// So I create an exception that will be catched in AdminController
		if ($this->getByLogin($email)) {
			throw new \Exception('Email already exist.');
		}

		// Getting singleton instance from the SQL class
		$sql = Sql::getInstance();

		// Request creation.
		// The parameters are written under this form ':variable'
		// and will be initialised in the execute(); method
		$sqlQuery = 'INSERT INTO user(login, password, admin)
					 VALUES (:login, :password, :admin)';

		// Preparating the request, pdo returns an object PDOStatement
		// which will execute the request and contain the results
		$statement = $sql->pdo->prepare($sqlQuery);

		// Executing request on the mySQL server
		// Initialising request parameters
		// The value of the key :id corresponds to :id in the SQL request
		$statement->execute(array(
			':login' => $email,
			':password' => $password,
			':admin' => $admin
		));

		return $statement->rowCount();
	}
}