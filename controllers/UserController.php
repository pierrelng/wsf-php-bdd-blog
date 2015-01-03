<?php 
namespace MVC;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
	public function postLogin(Request $request, Application $app)
	{
		// Fetching values entered in input fields
		$login = $request->request->get('login');
		$password = $request->request->get('password');

		// If $login is not empty
		if (!empty($login)) {
			// Instantiate User model
			$user = new User();

			// Fetch user matching the login entered
			$myUser = $user->getByLogin($login);

			/*var_dump($myUser); die();*/

			// If $myUser is not empty
			if (!empty($myUser)) {
				// If user password matches the password entered in the form
				if (password_verify($password, $myUser['password'])) { // https://github.com/ircmaxell/password_compat#usage

					// Register user in the session
					$app['session']->set('user', array(
						'username' => $login,
						'id' => $myUser['id'],
						'admin' => $myUser['admin']
					));

					// Redirect towards home
					return $this->redirect($app, 'home');
				}
			}
		}

		// Add a temporary message (flash) in the session
		$app['session']->getFlashBag()->add('loginError', 'Votre identifiant ou votre mot de passe est incorrect');

		// Redirect towards home without modifying the session
		return $this->redirect($app, 'home');
	}

	public function getLogout(Request $request, Application $app)
	{
		// Erase user from the session
		$app['session']->set('user', null);

		// Redirect towards home
		return $this->redirect($app, 'home');
	}
}
