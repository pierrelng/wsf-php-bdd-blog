<?php 
namespace MVC;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
	public function index(Request $request, Application $app)
	{
		$this->initAction($app);

		//initialise le model Article
		$article = new Article();

		//Je mets dans datas['articles'] tous mes articles
		$this->datas['articles'] = $article->getAll();

		//Appel de twig, pour générer le rendu html
		return $app['twig']->render('home/index.twig', $this->datas);
	}

	public function getArticle(Request $request, Application $app, $id)
	{
		$this->initAction($app);
		
		//initialise le model Article
		$article = new Article();
		$comment = new Comment();

		//Recupère un article
		$this->datas['article'] = $article->get($id);
		$this->datas['comments'] = $comment->getByArticle($id);

		//Appel de twig, pour générer le rendu html
		return $app['twig']->render('home/article.twig', $this->datas);

	}

	public function postComment(Request $request, Application $app, $idArticleFetchedFromRoute)
	{
		$this->initAction($app);
		$userId = $this->datas['user']['id'];
		$articleId = $idArticleFetchedFromRoute;
		
		$body = $request->request->get('comment');
		/*var_dump($body); die;*/

		if (!empty($body)) {
			// Ajout d'un comment dans la BDD
			$comment = new Comment();
			$nb = $comment->add($body, $articleId, $userId);

			if (!$nb) {
				$app['session']->getFlashBag()->add('error', 'Comment not added');
			}
		}
		else {
			$app['session']->getFlashBag()->add('error', 'Comment empty');
		}

		return $this->redirect($app, 'getArticle', array('id' => $articleId));
	}

	public function getDeleteComment(Request $request, Application $app, $idCommentFetchedFromRoute)
	{
		$this->initAction($app);

		if ($this->isAdmin()) {
			return 'Fuck off';
		}

		$commentId = $idCommentFetchedFromRoute;

		// Suppression d'un comment dans la BDD
		$comment = new Comment();
		$comment->delete($commentId);
		
		return $this->redirectBack($app);
	}
}
