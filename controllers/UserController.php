<?php 
namespace MVC;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
	public function postLogin(Request $request, Application $app)
	{
		//Récupérer les valeurs postés par les champs input
		$login = $request->request->get('login');
		$password = $request->request->get('password');

		//Si $login n'est pas vide
		if (!empty($login)) {
			//J'instancie le model User
			$user = new User();

			//Je recupère l'utilisateur qui correspond au login
			$myUser = $user->getByLogin($login);

			/*var_dump($myUser); die();*/

			//Si $myUser n'est pas vide
			if (!empty($myUser)) {
				//Si le password de l'user est égale au password du formulaire
				if (password_verify($password, $myUser['password'])) { // https://github.com/ircmaxell/password_compat#usage

					//On enregistre l'user dans la session
					$app['session']->set('user', array(
						'username' => $login,
						'id' => $myUser['id'],
						'admin' => $myUser['admin']
					));

					//on redirige vers la home
					return $this->redirect($app, 'home');
				}
			}
		}

		//Ajoute un message temporaire (flash) dans la session
		$app['session']->getFlashBag()->add('loginError', 'Votre identifiant ou votre mot de passe est incorrect');

		//On redirige vers la home sans modifier la session
		return $this->redirect($app, 'home');
	}

	public function getLogout(Request $request, Application $app)
	{
		//On efface l'user de la session
		$app['session']->set('user', null);

		//Redirection home
		return $this->redirect($app, 'home');
	}
}
