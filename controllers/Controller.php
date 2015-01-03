<?php
namespace MVC;

class Controller 
{

	/**
	 * Datas that will be sent to the view
	 * 
	 * @var array
	 */
	protected $datas = array();

	/**
	 * Initialising the table named datas with the infos on the logged in user
	 * 
	 * @param  Silex\Application $app
	 */
	public function initAction($app)
	{        
		// Fetching user variable in the session
		$user = $app['session']->get('user');

		// If user is not empty
		if (!empty($user)) {

			// Inserting user the table named datas
			$this->datas['user'] = $user;

			// Defining idAdmin's value according to the user status (is he admin or not ?)
			$this->datas['isAdmin'] = $user['admin'];
		}  
	}

	/**
	 * Return a redirection.
	 * 
	 * @param  Silex\Application  $app
	 * @param  string             $route  Route targeted
	 * @return Redirect
	 */
	public function redirect($app, $route, $options = array()) // = array() veut dire que si je ne passe pas de var en options, il créera quand même un tableau par défaut pour éviter de planter par ce que $options expects un tableau
	{
		return $app->redirect(
			$app['url_generator']->generate($route, $options)
		);
	}

	public function redirectBack($app) // = array() veut dire que si je ne passe pas de var en options, il créera quand même un tableau par défaut pour éviter de planter par ce que $options expects un tableau
	{
		$previous = $app['request']->server->get('HTTP_REFERER');

		if (empty($previous)) {
			$previous = $app['request']->getBasePath();
		}

		return $app->redirect($previous);
	}

	public function isAdmin()
	{
		return (!isset($this->datas['isAdmin']) || !$this->datas['isAdmin']);
	}
}