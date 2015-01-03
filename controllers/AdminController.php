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

	/**
	 * [getAdminArticle description]
	 * 
	 * @param  Request     $request [description]
	 * @param  Application $app     [description]
	 * @return [type]               [description]
	 */
	public function getAdminArticle(Request $request, Application $app)
	{
		// J'initialise le tableau datas avec les données de l'utilisateur connecté.
		$this->initAction($app);

		// J'initialise le model Tag.
		$tag = new Tag();

		//Je mets dans datas['tags'] tous mes tags.
		$this->datas['tags'] = $tag->getAll();

		/*var_dump($this->datas);die();*/

		// J'appelle Twig pour qu'il me génère le rendu HTML de la page Admin Article.
		return $app['twig']->render('admin/article.twig', $this->datas);
	}

	public function postAdminArticle(Request $request, Application $app)
	{
		$this->initAction($app);

		$title = $request->request->get('title');
		$body = $request->request->get('body');

		/*var_dump($title);
		var_dump($body); die();*/

		if (!empty($title) && !empty($body)) {
			// Ajout d'un article dans la BDD
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

		// S'il y a des erreurs, afficher les msgs ci-dessous
		if (count($errorsEmail) > 0 || count($errorsPassword) > 0) {
			foreach ($errorsEmail as $error) {
				$app['session']->getFlashBag()->add('errorsEmail', $error->getMessage());
			}
			foreach ($errorsPassword as $error) {
				$app['session']->getFlashBag()->add('errorsPassword', $error->getMessage());
			}

			return $this->redirect($app, 'getAdminUser');
		}

		// Je chope la value de la checkbox
		// ? = si elle existe, alors
		// true, sinon false
		$admin = $request->request->get('createAdmin') ? true : false;
		/*var_dump($admin); die();*/

		$hash = password_hash($password, PASSWORD_BCRYPT); // https://github.com/ircmaxell/password_compat#usage // http://php.net/manual/fr/password.constants.php

		// Ajout d'un user dans la BDD
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
	 * Génère et affiche la page Tag.
	 * 
	 * @param  Request     $request
	 * @param  Application $app
	 * @return Render
	 */
	public function getAdminTag(Request $request, Application $app)
	{
		// J'initialise le tableau datas avec les données de l'utilisateur connecté.
		$this->initAction($app);

		// J'appelle Twig pour qu'il me génère le rendu HTML de la page Tag.
		return $app['twig']->render('admin/tag.twig', $this->datas);
	}

	/**
	 * Création d'un nouveau tag.
	 * 
	 * @param  Request     $request 
	 * @param  Application $app     
	 * @return Redirect
	 */
	public function postAdminTag(Request $request, Application $app)
	{
		$this->initAction($app);

		// Je récupère la valeur postée dans le champ input avec l'id='tag'.
		$newTag = $request->request->get('tag');
		/*var_dump($newTag); die();*/

		// Si $newTag n'est pas vide, alors...
		if (!empty($newTag)) {

			// ...j'instancie la classe Tag.
			$tag = new Tag();

			// ...j'ajoute le nouveau tag dans la BDD en appelant la méthode add de la classe Tag
			// et je stocke dans $nb le nombre de lignes affectées dans la table SQL.
			$nb = $tag->add($newTag);

			// Si $nb n'est pas vide, alors la valeur a bien été insérée dans la table et j'affiche le flashbag de succès.
			// Sinon, aucune ligne n'a été affectée dans la table SQL, donc la valeur n'a pas été insérée dans la table
			// et j'affiche le flashbag d'échec.
			if ($nb) {
				$app['session']->getFlashBag()->add('success', 'New tag added');
			}
			else {
				$app['session']->getFlashBag()->add('error', 'Tag not added');
			}
		}

		// Sinon, j'affiche le flashbag d'échec correspondant.
		else {
			$app['session']->getFlashBag()->add('error', 'No tag to add :(');
		}

		// Je fais une redirection vers la route getAdminTag.
		return $this->redirect($app, 'getAdminTag');
	}

}