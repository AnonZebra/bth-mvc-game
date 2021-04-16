<?php

/**
 * Load the routes into the router, this file is included from
 * `htdocs/index.php` during the bootstrapping to prepare for the request to
 * be handled.
 */

declare(strict_types=1);

use FastRoute\RouteCollector;

$router = $router ?? null;

$router->addRoute("GET", "/test", function () {
    // A quick and dirty way to test the router or the request.
    return "Testing response";
});

$router->addRoute('GET', '/', '\Mos\Controller\Index');
$router->addRoute('GET', '/debug', '\Mos\Controller\Debug');
$router->addRoute('GET', '/twig', '\Mos\Controller\TwigView');

$router->addGroup('/session', function (RouteCollector $router) {
    $router->addRoute('GET', '', ["\Mos\Controller\Session", "index"]);
    $router->addRoute('GET', '/destroy', ["\Mos\Controller\Session", "destroy"]);
});

$router->addGroup("/some", function (RouteCollector $router) {
    $router->addRoute('GET', '/where', ['\Mos\Controller\Sample', 'where']);
});

$router->addGroup("/form", function (RouteCollector $router) {
    $router->addRoute("GET", "/view", ["\Mos\Controller\Form", "view"]);
    $router->addRoute("POST", "/process", ["\Mos\Controller\Form", "process"]);
});

$router->addRoute("GET", "/dice", ["\dtlw\Controller\Game", "dice"]);
// $router->addRoute(
//     "GET",
//     "/blackjack",
//     ["\dtlw\Controller\Game", "blackjack"]
// );

$router->addGroup("/blackjack", function (RouteCollector $router) {
    $router->addRoute(
        "GET",
        "",
        ["\dtlw\Controller\BlackjackController", "blackjackShow"]
    );
    $router->addRoute(
        "POST",
        "",
        ["\dtlw\Controller\BlackjackController", "blackjackProcess"]
    );
});

$router->addGroup("/yahtzee", function (RouteCollector $router) {
    $router->addRoute(
        "GET",
        "",
        ["\dtlw\Controller\YahtzeeController", "yahtzeeShow"]
    );
    $router->addRoute(
        "POST",
        "",
        ["\dtlw\Controller\YahtzeeController", "yahtzeeProcess"]
    );
});

// $router->addRoute('GET', '/layout/page.php', '');
//
// $router->addRoute('GET', '/dice', '');
//
// $router->addRoute('GET', '/blackjack', '');
//
// $router->addRoute('POST', '/blackjack', '');
