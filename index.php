<?php

require_once "./src/DNSRemote.php";
require_once "./src/Route.php";
require_once './src/Controller.php';
require_once './src/Application.php';

use App\Application;
use App\Route;

define( 'ROOT_DIR', __DIR__ );
define( 'APP_DIR', ROOT_DIR . "/app" );

Route::add('/', [
    'controller' => 'homepage',
    'action' => 'default',
    'id' => ''
]);

Route::add('/service/', [
    'controller' => 'service',
    'action' => 'default',
    'id' => ''
]);

Route::add('/service/:id', [
    'controller' => 'homepage',
    'action' => 'detail',
    'id' => ':id'
]);

Route::add('/:controller/:action/:id', [
    'controller' => ':controller',
    'action' => ':action',
    'id' => ':id'
]);

// Route::add('/service/edit/:id', false);

Route::pathNotFound( function( $path ) {
    // Do not forget to send a status header back to the client
    // The router will not send any headers by default
    // So you will have the full flexibility to handle this case
    header('HTTP/1.0 404 Not Found');
    echo 'Error 404 :-(<br>';
    echo "The requested path \"$path\" was not found!";
});

Route::methodNotAllowed( function($path, $method) {
    // Do not forget to send a status header back to the client
    // The router will not send any headers by default
    // So you will have the full flexibility to handle this case
    header('HTTP/1.0 405 Method Not Allowed');
    echo 'Error 405 :-(<br>';
    echo "The requested path \"$path\" exists. But the request method \"$method\" is not allowed on this path!";
});

Route::run('/');

(new Application)->start( Route::getParams() );