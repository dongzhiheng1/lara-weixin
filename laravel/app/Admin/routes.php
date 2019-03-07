<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resource('/wx/wxusers',WeixinController::class);
    $router->get('/wx/sendAll','WeixinController@sendAll');
    $router->post('/wx/chatAll','WeixinController@chatAll');
});
