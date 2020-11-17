<?php
/******************************************************************************
 * Copyright (C) Omedia.top - All Rights Reserved 2020                        *
 *                                                                            *
 * Unauthorized copying of this file, via any medium is strictly prohibited   *
 * Proprietary and confidential                                               *
 *                                                                            *
 * Written by Omar SAKHRAOUI <webmaster@omedia.top>, 8/2020                   *
 ******************************************************************************/

namespace App\Controllers\Topic;

use App\Models\TopicModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\ServerRequest;
use System\Controllers\ApiController;

class TopicApiController extends ApiController{
	private TopicModel $model;

	public function __construct(ContainerInterface $container, TopicModel $topicModel){
		parent::__construct($container);
		$this->model = $topicModel;
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

	public function getBySlug(ServerRequest $request, ResponseInterface $response, array $args): ResponseInterface{
		$this->request  = $request;
		$this->response = $response;
		$this->prepare();
		$this->data = $this->model->getBySlug($args["slug"]);

		return $this->render();
	}

	public function getByCategory(ServerRequest $request, ResponseInterface $response, array $args): ResponseInterface{
		$this->request  = $request;
		$this->response = $response;
		$this->prepare();
		$this->data = $this->model->getByCategory($args["category"]);

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
