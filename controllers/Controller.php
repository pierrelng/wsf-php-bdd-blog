<?php
namespace MVC;

class Controller 
{

	/**
	 * Les données qui seront envoyés à la vue
	 * 
	 * @var array
	 */
	protected $datas = array();

	/**
	 * Initialise le tableau datas avec les données de 
	 * l'utilisateur connecté.
	 * 
	 * @param  Silex\Application $app
	 */
	public function initAction($app)
	{        
		//Recupère la variable user dans la session
		$user = $app['session']->get('user');

		//Si user n'est pas vide
		if (!empty($user)) {

			//Je met user dans le tableau datas
			$this->datas['user'] = $user;

			//Je definis la valeur idAdmin en fonction 
			//du status admin de l'user
			$this->datas['isAdmin'] = $user['admin'];
		}  
	}

	/**
	 * Renvoi une redirection
	 * 
	 * @param  Silex\Application $app   
	 * @param  string $route Route de destination
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