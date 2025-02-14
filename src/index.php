<?php

$requestUri = $_SERVER['REQUEST_URI'];

$requestUri = trim($requestUri, '/');
$requestUri = explode('?', $requestUri)[0];

$basePath = 'reconocimiento';
if (strpos($requestUri, $basePath) === 0) {
    // Eliminar el prefijo "reconocimiento" de la URL
    $requestUri = substr($requestUri, strlen($basePath) ?: '/');
    $requestUri = trim($requestUri, '/');
}

$routes = [
    '' => ['controller' => 'RecognitionRoute', 'method' => 'index'],
    '(\d+)' => ['controller' => 'RecognitionRoute', 'method' => 'show'],

    'evento' => ['controller' => 'EventRoute', 'method' => 'index'],
    'evento/(\d+)' => ['controller' => 'EventRoute', 'method' => 'show'],

    'categoria' => ['controller' => 'CategoryRoute', 'method' => 'index'],
    'categoria/(\d+)' => ['controller' => 'CategoryRoute', 'method' => 'show'],
];


function findMatchingRoute($routes, $requestUri) {
    foreach ($routes as $route => $handler) {
        $routePattern = "#^$route$#";

        if (preg_match($routePattern, $requestUri, $matches)) {
            array_shift($matches);

            return [
                'controller' => $handler['controller'],
                'method' => $handler['method'],
                'params' => $matches,
            ];
        }
    }
    return null;
}

$route = findMatchingRoute($routes, $requestUri);
if ($route) {
    require_once "routes/{$route['controller']}.controller.php";

    $controller = new $route['controller']();
    call_user_func_array([$controller, $route['method']], $route['params']);
} else {
    include_once __DIR__ . '/views/404.php';
}