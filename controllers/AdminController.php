<?php 
namespace MVC;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;


class AdminController extends Controller
{

	public function getAdminDashboard(Request $request, Application $app)
	{
		$this->initAction($app);

		return $app['twig']->render('admin/dashboard.twig', $this->datas);
	}

	public function getAdminArticle(Request $request, Application $app)
	{
		// Initialising the table named datas (contains infos on the logged in user)
		$this->initAction($app);

		// Initialising the Tag model
		$tag = new Tag();

		// Inserting in datas['tags'] all my tags
		$this->datas['tags'] = $tag->getAll();

		/*var_dump($this->datas);die();*/

		// Calling Twig to render HTML of Admin Article page
		return $app['twig']->render('admin/article.twig', $this->datas);
	}

	public function postAdminArticle(Request $request, Application $app)
	{
		$this->initAction($app);

		$title = $request->request->get('title');
		$body = $request->request->get('body');
		$tag = $request->request->get('tagsSelected');

		/*var_dump($title);
		var_dump($body);
		var_dump($tag);
		die();*/

		if (!empty($title) && !empty($body)) {
			// Adding an article in DB
			$article = new Article();
			$nb = $article->add($title, $body);

			if ($nb) {
				$app['session']->getFlashBag()->add('success', 'Article added');
			}
			else {
				$app['session']->getFlashBag()->add('error', 'Article not added');
			}
		}
		else {
			$app['session']->getFlashBag()->add('error', 'Title or body empty');
		}

		return $this->redirect($app, 'getAdminArticle');
	}

	public function getAdminUser(Request $request, Application $app)
	{
		$this->initAction($app);

		return $app['twig']->render('admin/user.twig', $this->datas);
	}

	public function postAdminUser(Request $request, Application $app)
	{
		$this->initAction($app);

		$email = $request->request->get('email');
		$emailConfirm = $request->request->get('confirm_email');

		$password = $request->request->get('password');
		$passwordConfirm = $request->request->get('confirm_password');

		$errorsEmail = $app['validator']->validateValue(
			$email, 
			array(
				new Assert\Email(),
				new Assert\NotBlank(),
				new Assert\IdenticalTo(array(
					'value' => $emailConfirm,
				)),
			)
		);
		
		$errorsPassword = $app['validator']->validateValue(
			$password, 
			array(
				new Assert\NotBlank(),
				new Assert\IdenticalTo(array(
					'value' => $passwordConfirm,
				)),
			)
		);

		// If errors, show the following msgs
		if (count($errorsEmail) > 0 || count($errorsPassword) > 0) {
			foreach ($errorsEmail as $error) {
				$app['session']->getFlashBag()->add('errorsEmail', $error->getMessage());
			}
			foreach ($errorsPassword as $error) {
				$app['session']->getFlashBag()->add('errorsPassword', $error->getMessage());
			}

			return $this->redirect($app, 'getAdminUser');
		}

		// Fetching value entered in checkbox
		// ? = if exist, then
		// true, otherwise false
		$admin = $request->request->get('createAdmin') ? true : false;
		/*var_dump($admin); die();*/

		$hash = password_hash($password, PASSWORD_BCRYPT); // https://github.com/ircmaxell/password_compat#usage // http://php.net/manual/fr/password.constants.php

		// Adding a user in DB
		$user = new User();
		
		try {
			$nb = $user->add($email, $hash, $admin);

			if ($nb) {
				$app['session']->getFlashBag()->add('success', 'User added');
			}
			else {
				$app['session']->getFlashBag()->add('error', 'User not added');
			}
		}
		catch (\Exception $e) {
			$app['session']->getFlashBag()->add('error', $e->getMessage());
		}

		return $this->redirect($app, 'getAdminUser');
	}

	/**
	 * Render Tag page.
	 * 
	 * @param  Request     $request
	 * @param  Application $app
	 * @return Render
	 */
	public function getAdminTag(Request $request, Application $app)
	{
		// Initialising the table named datas with the infos on the logged in user
		$this->initAction($app);

		// Calling Twig to render HTML of Tag page
		return $app['twig']->render('admin/tag.twig', $this->datas);
	}

	/**
	 * Create a new tag.
	 * 
	 * @param  Request     $request 
	 * @param  Application $app     
	 * @return Redirect
	 */
	public function postAdminTag(Request $request, Application $app)
	{
		$this->initAction($app);

		// Fetching value entered in input field marked with the id='tag'
		$newTag = $request->request->get('tag');
		/*var_dump($newTag); die();*/

		// If $newTag not empty, then...
		if (!empty($newTag)) {

			// ...instantiate the Tag class
			$tag = new Tag();

			// ...add the new tag in the DB by calling the add method from the Tag class
			// and store in $nb the number of lines affected in the SQL table
			$nb = $tag->add($newTag);

			// If $nb isn't empty, then the value has been inserted in the table and I display the success flashbag
			// Otherwise, no lines has been affected in the SQL table, so the value has not been inserted in the table
			// and I display the error flashbag.
			if ($nb) {
				$app['session']->getFlashBag()->add('success', 'New tag added');
			}
			else {
				$app['session']->getFlashBag()->add('error', 'Tag not added');
			}
		}

		// Otherwise, display the corresponding fail flashbag
		else {
			$app['session']->getFlashBag()->add('error', 'No tag to add :(');
		}

		// Redirecting towards the getAminTag route
		return $this->redirect($app, 'getAdminTag');
	}

}