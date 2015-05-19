<?php

// URI: /{frontend}/docs
$router->group(['prefix' => 'docs'], function ($router) {

    $router->get('{version}/{page?}', ['as' => 'pxcms.docs.page', 'uses' => 'PagesController@show']);
    $router->get('/', ['as' => 'pxcms.docs.index', 'uses' => 'PagesController@getIndex']);
});
