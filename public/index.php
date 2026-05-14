<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('<a href="/artista/world">Try /artista/world</a>');
    return $response;
});

$app->get('/artista/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $response->getBody()->write("Hello, artista $id");
    return $response;
});

$app->run();
