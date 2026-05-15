<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dbFile = __DIR__ . '/../database/musics.db';

if (!is_dir(dirname($dbFile))) {
    mkdir(dirname($dbFile), 0755, true);
}

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) use ($pdo) {

    $stmt = $pdo->query('SELECT id, nom, img, biografia, titol, video FROM musics');
    $musics = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $items = '';

    foreach ($musics as $music) {

        $items .= sprintf(
            "
            <div class='card'>
                <h2>%s</h2>
                <img src='%s' width='300'>
                <p>%s</p>

                <a href='/music/%s'>
                    Veure més
                </a>
            </div>
            ",
            htmlspecialchars($music['nom'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($music['img'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars(substr($music['biografia'], 0, 150), ENT_QUOTES, 'UTF-8') . '...',
            htmlspecialchars($music['id'], ENT_QUOTES, 'UTF-8')
        );
    }

    if ($items === '') {
        $items = '<p>No hi ha artistes disponibles.</p>';
    }

    $htmlContent = "
    <!DOCTYPE html>
    <html lang='ca'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Músics</title>
    </head>

    <body>
        <h1>Llista d'artistes</h1>
        $items
    </body>
    </html>
    ";

    $response->getBody()->write($htmlContent);

    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/music/{id:[0-9]+}', function (
    Request $request,
    Response $response,
    array $args
) use ($pdo) {

    $stmt = $pdo->prepare('
        SELECT nom, img, biografia, titol, video
        FROM musics
        WHERE id = ?
    ');

    $stmt->execute([$args['id']]);

    $music = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$music) {
        $response->getBody()->write('<h1>No s’ha trobat l’artista</h1>');
        return $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html');
    }

    $htmlContent = "
    <!DOCTYPE html>
    <html lang='ca'>

    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>" . htmlspecialchars($music['nom'], ENT_QUOTES, 'UTF-8') . "</title>
    </head>

    <body>
        <h1>" . htmlspecialchars($music['nom'], ENT_QUOTES, 'UTF-8') . "</h1>
        <img src='" . htmlspecialchars($music['img'], ENT_QUOTES, 'UTF-8') . "'>
        <h2>" . htmlspecialchars($music['titol'], ENT_QUOTES, 'UTF-8') . "</h2>
        <p>" . nl2br(htmlspecialchars($music['biografia'], ENT_QUOTES, 'UTF-8')) . "</p>
        <iframe
            width='560'
            height='315'
            src='https://www.youtube.com/embed/" . htmlspecialchars($music['video'], ENT_QUOTES, 'UTF-8') . "?autoplay=0'
            frameborder='0'
            allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture'
            allowfullscreen>
        </iframe>
        <p>
            <a href='/'>← Tornar</a>
        </p>
    </body>
    </html>
    ";

    $response->getBody()->write($htmlContent);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->run();