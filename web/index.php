<?php 

//Chargement de l'auto loader
require_once __DIR__.'/../vendor/autoload.php';

// Creation de l'instance Silex
$app = new Silex\Application();

//Activation du mode debug
$app['debug'] = true;

//Configuration de twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	//twig trouvera les fichiers templates dans le dossier views
	'twig.path' => __DIR__.'/../views',
));

//Configuration du service URL Generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

//Configuration du service session
$app->register(new Silex\Provider\SessionServiceProvider());

//Validator service 
$app->register(new Silex\Provider\ValidatorServiceProvider());



//Routes
$app->get('/', 'MVC\\HomeController::index')
	->bind('home');

$app->get('/article/{id}', 'MVC\\HomeController::getArticle')
	->bind('getArticle');

$app->post('/article/{idArticleFetchedFromRoute}/comment', 'MVC\\HomeController::postComment')
	->bind('postComment');

$app->post('/login', 'MVC\\UserController::postLogin')
	->bind('postLogin');

$app->get('/logout', 'MVC\\UserController::getLogout')
	->bind('getLogout');

/**
 * Routes Admin
 */
$app->get('/admin', 'MVC\\AdminController::getAdminDashboard')
	->bind('getAdmin');

$app->get('/admin/article', 'MVC\\AdminController::getAdminArticle')
	->bind('getAdminArticle');

$app->post('/admin/article', 'MVC\\AdminController::postAdminArticle')
	->bind('postAdminArticle');

$app->get('/admin/user', 'MVC\\AdminController::getAdminUser')
	->bind('getAdminUser');

$app->post('/admin/user', 'MVC\\AdminController::postAdminUser')
	->bind('postAdminUser');

$app->get('/delete/comment/{idCommentFetchedFromRoute}', 'MVC\\HomeController::getDeleteComment')
	->bind('getDeleteComment');

// Lorsque cette route est appelée, j'appelle la méthode getAdminTag de l'AdminController.
$app->get('/admin/tag', 'MVC\\AdminController::getAdminTag')
	->bind('getAdminTag');

// Lorsque cette route est appelée, j'appelle la méthode postAdminTag de l'AdminController.
$app->post('/admin/tag', 'MVC\\AdminController::postAdminTag')
	->bind('postAdminTag');

$app->run();
