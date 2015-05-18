<?php

// URI: /{frontend}/docs
$router->group(['prefix' => 'docs'], function ($router) {

    $router->get('{page}', ['as' => 'pxcms.docs.page', 'uses' => 'PagesController@getPage']);
    $router->get('/', ['as' => 'pxcms.docs.index', 'uses' => 'PagesController@getIndex']);
});
