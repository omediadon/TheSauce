<?php
/******************************************************************************
 * Copyright (C) Omedia.top - All Rights Reserved 2020                        *
 *                                                                            *
 * Unauthorized copying of this file, via any medium is strictly prohibited   *
 * Proprietary and confidential                                               *
 *                                                                            *
 * Written by Omar SAKHRAOUI <webmaster@omedia.top>, 8/2020                   *
 ******************************************************************************/

namespace App\Controllers\Category;

use App\Models\CategoryModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\ServerRequest;
use System\Controllers\ApiController;

class CategoryApiController extends ApiController{
	private CategoryModel $model;

	public function __construct(ContainerInterface $container, CategoryModel $categoryModel){
		parent::__construct($container);
		$this->model = $categoryModel;
	}


	public function getAll(ServerRequest $request, ResponseInterface $response): ResponseInterface{
		$this->request  = $request;
		$this->response = $response;
		$this->prepare();
		$this->data = $this->model->getall();

		return $this->render();
	}

	public function getById(ServerRequest $request, ResponseInterface $response, array $args): ResponseInterface{
		$this->request  = $request;
		$this->response = $response;
		$this->prepare();
		$this->data = $this->model->getById($args["id"]);

		return $this->render();
	}

	/** @noinspection PhpUnused */
	public function getBySlug(ServerRequest $request, ResponseInterface $response, array $args): ResponseInterface{
		$this->request  = $request;
		$this->response = $response;
		$this->prepare();
		$this->data = $this->model->getBySlug($args["slug"]);

		return $this->render();
	}

	public function getByType(ServerRequest $request, ResponseInterface $response, array $args): ResponseInterface{
		$this->request  = $request;
		$this->response = $response;
		$this->prepare();
		$this->data = $this->model->getByType($args["type"]);

		return $this->render();
	}

}
