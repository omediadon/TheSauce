<?php
/******************************************************************************
 * Copyright (C) Omedia.top - All Rights Reserved 2020                        *
 *                                                                            *
 * Unauthorized copying of this file, via any medium is strictly prohibited   *
 * Proprietary and confidential                                               *
 *                                                                            *
 * Written by Omar SAKHRAOUI <webmaster@omedia.top>, 8/2020                   *
 ******************************************************************************/

use App\Controllers\Category\CategoryApiController;
use App\Controllers\Home\HomeApiController;
use App\Controllers\Home\HomeBrowserController;
use App\Controllers\Topic\TopicApiController;
use Slim\Routing\RouteCollectorProxy;
use System\Config\SiteSettings;
use System\Middlewares\JsonRequestMiddleware;

$settings = $app->getContainer()
                ->get(SiteSettings::class);

$app->get('[/]', HomeBrowserController::class . ':getHome')
    ->setName('home');

/*
$app->group('/user[/]', function (RouteCollectorProxy $group) {
    $group->get('', UserBrowserController::class . ':createUser')
        ->setName('createuser');
});
*/

$app->group('/api', function(RouteCollectorProxy $group){
	$group->get('[/[info[/]]]', HomeApiController::class . ':index')
	      ->setName('api');

	$group->group("/category", function(RouteCollectorProxy $group){
		$group->get('[/]', CategoryApiController::class . ':getAll');
		$group->get("/all[/]", CategoryApiController::class . ":getAll");
		$group->get("/id/{id:[0-9]+}[/]", CategoryApiController::class . ":getById");
		$group->get("/slug/{slug:[A-Za-z0-9\-]+}[/]", CategoryApiController::class . ":getBySlug");
		$group->get("/type/{type:text|image}[/]", CategoryApiController::class . ":getByType");
	})
	      ->add(JsonRequestMiddleware::class);

	$group->group("/topic", function(RouteCollectorProxy $group){
		$group->get('[/]', TopicApiController::class . ':getAll');
		$group->get("/all[/]", TopicApiController::class . ":getAll");
		$group->get("/id/{id:[0-9]+}[/]", TopicApiController::class . ":getById");
		$group->get("/slug/{slug:[A-Za-z0-9\-]+}[/]", TopicApiController::class . ":getBySlug");
		$group->get("/type/{type:text|image}[/]", TopicApiController::class . ":getByType");
		$group->get("/category/{category:[0-9]+}[/]", TopicApiController::class . ":getByCategory")
		      ->add(JsonRequestMiddleware::class);
	});

})
    ->add(JsonRequestMiddleware::class);

$app->get("/{params:.*}[/]", HomeBrowserController::class . ":get404");
$app->map([
	          'PUT',
	          'POST',
	          'DELETE',
	          'PATCH',
	          'OPTIONS',
          ], "/{params:.*}[/]", HomeApiController::class . ":get404")
    ->add(JsonRequestMiddleware::class);
