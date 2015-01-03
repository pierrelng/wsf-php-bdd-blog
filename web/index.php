<?php 

// Autoloader load
require_once __DIR__.'/../vendor/autoload.php';

// Silex instance creation
$app = new Silex\Application();

// Debug mode activation
$app['debug'] = true;

// Twig configuration
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	// twig will find the template files in the folder views
	'twig.path' => __DIR__.'/../views',
));

// URL Generator service configuration
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// Session service configuration
$app->register(new Silex\Provider\SessionServiceProvider());

// Validator service configuration
$app->register(new Silex\Provider\ValidatorServiceProvider());


/**
 * Routes
 */
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
 * Admin Routes
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

// When this route is called, execute the method getAdminTag of AdminController.
$app->get('/admin/tag', 'MVC\\AdminController::getAdminTag')
	->bind('getAdminTag');

// When this route is called, execute the method postAdminTag of AdminController.
$app->post('/admin/tag', 'MVC\\AdminController::postAdminTag')
	->bind('postAdminTag');

$app->run();
