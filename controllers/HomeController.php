<?php 
namespace MVC;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
	public function index(Request $request, Application $app)
	{
		$this->initAction($app);

		// Initialising Article model
		$article = new Article();

		// Inserting in datas['articles'] all my articles
		$this->datas['articles'] = $article->getAll();

		// Calling twig to render HTML
		return $app['twig']->render('home/index.twig', $this->datas);
	}

	/**
	 * Filter index by a specific tag.
	 * 
	 * @param  Request     $request 
	 * @param  Application $app     
	 */
	public function indexByTag(Request $request, Application $app, $searchedTag)
	{
		/*var_dump($searchedTag);die();*/

		$this->initAction($app);

		// Initialising Article model
		$article = new Article();

		// Inserting in datas['articles'] all the articles containing the searched tag
		$this->datas['articles'] = $article->getByTag($searchedTag);

		// Calling twig to render HTML
		return $app['twig']->render('home/index.twig', $this->datas);
	}

	public function getArticle(Request $request, Application $app, $id)
	{
		$this->initAction($app);
		
		// Initialising Article model
		$article = new Article();
		$comment = new Comment();
		
		// Fetching an article
		$this->datas['article'] = $article->get($id);

		// Fetching comments associated
		$this->datas['comments'] = $comment->getByArticle($id);

		// Tried to convert URLs in comments into hyperlinks. Unsuccessful.
			// $text = $comment->getByArticle($id);

			// 	// Converting URLs in text to hyper links
			// 	// http://stackoverflow.com/questions/5531502/how-do-i-auto-convert-an-url-into-a-hyper-link-in-php
			// 	// http://stackoverflow.com/questions/15472033/how-to-update-specific-keys-value-in-an-associative-array-in-php
			// 	foreach($text as $key => $value) {
			// 		$text[$key]['body'] = preg_replace("#http://([\S]+?)#Uis", '<a rel="nofollow" href="http://\\1">\\1</a>', $value['body']);
			// 	}
			// 	/*var_dump($text); die;*/

			// $this->datas['comments'] = $text;
		
		// Calling twig to render HTML
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
			// Adding a comment in DB
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

		// Deleting a comment in DB
		$comment = new Comment();
		$comment->delete($commentId);
		
		return $this->redirectBack($app);
	}
}
