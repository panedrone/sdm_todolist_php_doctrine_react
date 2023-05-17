<?php

namespace api;

require_once '../Steampixel/Route.php';
require_once 'handlers.php';
require_once 'utils.php';

use Doctrine\ORM\Exception\ORMException;
use Steampixel\Route;

try {
    init_app();
} catch (ORMException $e) {
    log_err($e);
    return;
}

Route::add('/api/projects', function () {
    handle_projects();
}, ['get', 'post']);

Route::add('/api/projects/([0-9]*)', function ($p_id) {
    handle_project($p_id);
}, ['get', 'put', 'delete']);

Route::add('/api/projects/([0-9]*)/tasks', function ($p_id) {
    handle_project_tasks($p_id);
}, ['get', 'post']);

Route::add('/api/tasks/([0-9]*)', function ($t_id) {
    handle_task($t_id);
}, ['get', 'put', 'delete']);

Route::methodNotAllowed(function ($path, $method) {
    header("HTTP/1.1 405 Not Allowed $path $method");
    http_response_code(HTTP_METHOD_NOT_ALLOWED);
});

Route::pathNotFound(function ($path) {
    header("HTTP/1.1 404 Not Found $path");
    http_response_code(HTTP_NOT_FOUND);
});

// === panedrone:
//
// 1) while debugging on apache, $uri is like "/<web-site-home>/api/projects",
//    and $base must be "/<web-site-home>"
//
//    at the same time, it must be fetch("api/projects"), not fetch("/api/projects")
//    because the root is "/<web-site-home>" and there is nothing at "/api/projects"
//
// 2) while debugging on built-in web server, $uri is like "/api/projects", so $base is ""
//
//    "/api/projects" comes even with fetch("api/projects")

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_parts = explode('/api/', $uri);
if (count($uri_parts) > 1) {
    $base = $uri_parts[0];
} else {
    // $base = "/";
    $base = ""; // === panedrone: all my routes are started with "/api", so the base is ""
}

Route::run($base);
