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

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function resolveImageUrl(string $img): string
{
    $img = trim($img);
    if ($img === '') {
        return '';
    }

    if (preg_match('#^(https?://|//)#i', $img)) {
        return $img;
    }

    if (strpos($img, '../assets/') === 0) {
        return '/' . ltrim(substr($img, 3), '/');
    }

    if (strpos($img, 'assets/') === 0) {
        return '/' . $img;
    }

    return $img;
}

function renderPage(string $title, string $body): string
{
    return "<!DOCTYPE html>
    <html lang='ca'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>" . escape($title) . "</title>
        <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap' rel='stylesheet'>
        <link rel='stylesheet' href='/style.css'>
    </head>
    <body>
        <div class='container'>
            <h1>" . escape($title) . "</h1>
            <div class='content'>
                $body
            </div>
        </div>
    </body>
    </html>";
}

$app->get('/', function (Request $request, Response $response) use ($pdo) {
    $queryParams = $request->getQueryParams();
    $search = trim($queryParams['q'] ?? '');

    if ($search !== '') {
        $stmt = $pdo->prepare('SELECT id, nom, img, biografia, titol, video FROM musics WHERE nom LIKE ? OR biografia LIKE ?');
        $stmt->execute(["%$search%", "%$search%"]);
    } else {
        $stmt = $pdo->query('SELECT id, nom, img, biografia, titol, video FROM musics');
    }

    $musics = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $items = '';
    foreach ($musics as $music) {
        $items .= sprintf(
            "<div class='card'>
                <h2>%s</h2>
                <img src='%s' alt='%s'>
                <p>%s</p>
                <div class='actions'><a href='/music/%s'>Veure més</a></div>
            </div>",
            escape($music['nom']),
            escape(resolveImageUrl($music['img'])),
            escape($music['nom']),
            escape(substr($music['biografia'], 0, 150)) . '...',
            escape($music['id'])
        );
    }

    if ($items === '') {
        $items = '<p>No hi ha artistes disponibles.</p>';
    }

    $body = "<button class='main-button' onclick=\"window.location.href='/music/create'\">Afegeix un artista nou</button>";
    $body .= "<form method='get' action='/' class='search-form'><label>Busca artista<small> (nom o biografia)</small><br><input type='text' name='q' value='" . escape($search) . "'></label><button type='submit'>Buscar</button></form>";
    $body .= "<div class='cards'>" . $items . "</div>";

    $response->getBody()->write(renderPage('Llista d artistes', $body));
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/music/create', function (Request $request, Response $response) {
    $body = "<form method='post' action='/music'>
        <label>Nom<br><input type='text' name='nom' required></label>
        <label>Imatge URL<br><input type='text' name='img' required></label>
        <label>Biografia<br><textarea name='biografia' rows='5' required></textarea></label>
        <label>Títol<br><input type='text' name='titol' required></label>
        <label>Vídeo YouTube ID<br><input type='text' name='video'></label>
        <button type='submit'>Guardar artista</button>
    </form>";

    $body .= "<p class='back-link'>← Tornar a la llista</p>";
    $response->getBody()->write(renderPage('Afegeix artista', $body));
    return $response->withHeader('Content-Type', 'text/html');
});

$app->post('/music', function (Request $request, Response $response) use ($pdo) {
    $data = $request->getParsedBody();
    $stmt = $pdo->prepare('INSERT INTO musics (nom, img, biografia, titol, video) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        trim($data['nom'] ?? ''),
        trim($data['img'] ?? ''),
        trim($data['biografia'] ?? ''),
        trim($data['titol'] ?? ''),
        trim($data['video'] ?? ''),
    ]);

    $id = $pdo->lastInsertId();
    return $response
        ->withHeader('Location', '/music/' . $id)
        ->withStatus(302);
});

$app->get('/music/{id:[0-9]+}/edit', function (Request $request, Response $response, array $args) use ($pdo) {
    $stmt = $pdo->prepare('SELECT id, nom, img, biografia, titol, video FROM musics WHERE id = ?');
    $stmt->execute([$args['id']]);
    $music = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$music) {
        $response->getBody()->write(renderPage('No trobat', '<p>No s’ha trobat l’artista.</p><p><a href="/">← Tornar</a></p>'));
        return $response->withStatus(404)->withHeader('Content-Type', 'text/html');
    }

    $body = "<form method='post' action='/music/" . escape($music['id']) . "/edit'>
        <label>Nom<br><input type='text' name='nom' value='" . escape($music['nom']) . "' required></label>
        <label>Imatge URL<br><input type='text' name='img' value='" . escape($music['img']) . "' required></label>
        <label>Biografia<br><textarea name='biografia' rows='5' required>" . escape($music['biografia']) . "</textarea></label>
        <label>Títol<br><input type='text' name='titol' value='" . escape($music['titol']) . "' required></label>
        <label>Vídeo YouTube ID<br><input type='text' name='video' value='" . escape($music['video']) . "'></label>
        <button type='submit'>Actualitzar artista</button>
    </form>";

    $body .= "<p class='back-link'>← Tornar a la fitxa</p>";
    $response->getBody()->write(renderPage('Edita artista', $body));
    return $response->withHeader('Content-Type', 'text/html');
});

$app->post('/music/{id:[0-9]+}/edit', function (Request $request, Response $response, array $args) use ($pdo) {
    $data = $request->getParsedBody();
    $stmt = $pdo->prepare('UPDATE musics SET nom = ?, img = ?, biografia = ?, titol = ?, video = ? WHERE id = ?');
    $stmt->execute([
        trim($data['nom'] ?? ''),
        trim($data['img'] ?? ''),
        trim($data['biografia'] ?? ''),
        trim($data['titol'] ?? ''),
        trim($data['video'] ?? ''),
        $args['id'],
    ]);

    return $response
        ->withHeader('Location', '/music/' . $args['id'])
        ->withStatus(302);
});

$app->post('/music/{id:[0-9]+}/delete', function (Request $request, Response $response, array $args) use ($pdo) {
    $stmt = $pdo->prepare('DELETE FROM musics WHERE id = ?');
    $stmt->execute([$args['id']]);

    return $response
        ->withHeader('Location', '/')
        ->withStatus(302);
});

$app->get('/music/{id:[0-9]+}', function (
    Request $request,
    Response $response,
    array $args
) use ($pdo) {
    $stmt = $pdo->prepare('SELECT id, nom, img, biografia, titol, video FROM musics WHERE id = ?');
    $stmt->execute([$args['id']]);
    $music = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$music) {
        $response->getBody()->write(renderPage('No trobat', '<p>No s’ha trobat l’artista.</p><p><a href="/">← Tornar</a></p>'));
        return $response->withStatus(404)->withHeader('Content-Type', 'text/html');
    }

    $videoHtml = '';
    if ($music['video']) {
        $videoHtml = "<h2>Vídeo destacat</h2><div><iframe width='560' height='315' src='https://www.youtube.com/embed/" . escape($music['video']) . "?autoplay=0' title='Vídeo de " . escape($music['nom']) . "' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe></div>";
    }

    $body = "<img src='" . escape(resolveImageUrl($music['img'])) . "' alt='" . escape($music['nom']) . "' class='music-detail'>";
    $body .= "<h2>" . escape($music['titol']) . "</h2>";
    $body .= "<p>" . nl2br(escape($music['biografia'])) . "</p>";
    if ($music['video']) {
        $body .= "<h3 class='video-title'>" . escape($music['titol']) . "</h3><div class='video-container'><iframe width='560' height='315' src='https://www.youtube.com/embed/" . escape($music['video']) . "?autoplay=0' title='Vídeo de " . escape($music['nom']) . "' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe></div>";
    }
    $body .= "<div class='actions'>
        <a href='/music/" . escape($music['id']) . "/edit'>Edita</a>
        <form method='post' action='/music/" . escape($music['id']) . "/delete'>
            <button type='submit'>Eliminar</button>
        </form>
    </div>
    <p class='back-link'>← Tornar a la llista</p>";

    $response->getBody()->write(renderPage($music['nom'], $body));
    return $response->withHeader('Content-Type', 'text/html');
});

$app->run();