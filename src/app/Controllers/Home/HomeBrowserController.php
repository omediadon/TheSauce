<?php

namespace App\Controllers\Home;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\ServerRequest;
use System\Controllers\BrowserController;
use System\Utils\Translator;

final class HomeBrowserController extends BrowserController{
	public function __construct(ContainerInterface $container, Translator $translator){
		parent::__construct($container, $translator);
		//$this->model = $homeModel;
	}

	public function getHome(ServerRequest $request, ResponseInterface $response): ResponseInterface{
		$this->request = $request;
		$hl            = $this->prepare($request);
		$response      = $response->withHeader("Content-Language", $hl);
		$title         = $this->siteSetup->apiInfo->name;

		$assets = [$this->siteSetup->assets->landing];

		$icons = [
			[
				"icon"  => "icon-screen-desktop",
				"title" => "Fully Responsive",
				"text"  => "This theme will look great on any device, no matter the size!",
			],
			[
				"icon"  => "icon-organization",
				"title" => "Fully Responsive",
				"text"  => "This theme will look great on any device, no matter the size!",
			],
			[
				"icon"  => "icon-chemistry",
				"title" => "Fully Responsive",
				"text"  => "This theme will look great on any device, no matter the size!",
			],
		];

		$testemonials = [
			[
				"image"  => "/assets/img/testimonials-1.jpg",
				"author" => "Fully Responsive",
				"text"   => "This theme will look great on any device, no matter the size!",
			],
			[
				"image"  => "/assets/img/testimonials-2.jpg",
				"author" => "Fully Responsive",
				"text"   => "This theme will look great on any device, no matter the size!",
			],
			[
				"image"  => "/assets/img/testimonials-3.jpg",
				"author" => "Fully Responsive",
				"text"   => "This theme will look great on any device, no matter the size!",
			],
		];

		$alerts = [
			["title" => "This is a general alert"],
			["text" => "This is a general alert"],
			[
				"title"  => "This is a long warning alert",
				"type"   => "danger",
				"text"   => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
				"footer" => "Lorem ipsum dolor sit amet",
			],
			[
				"title"    => "This is a general alert",
				"closable" => false,
			],
			[
				"title" => "This is a dark alert",
				"type"  => "dark",
			],
		];

		$cards = [
			[
				"image"  => "/assets/img/bg-showcase-2.jpg",
				"title"  => "title tile",
				"header" => "Very Hot",
				"footer" => date('j/F/Y H:i:s'),
				"text"   => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
				"link"   => [
					"text" => "A link",
					"href" => "/",
				],
			],
			[
				"image"  => "/assets/img/bg-showcase-2.jpg",
				"title"  => "title tile",
				"header" => "Very Hot",
				"footer" => date('j/F/Y H:i:s'),
				"text"   => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
				"link"   => [
					"text" => "A link",
					"href" => "/",
				],
			],
			[
				"image"  => "/assets/img/bg-showcase-2.jpg",
				"title"  => "title tile",
				"header" => "Very Hot",
				"footer" => date('j/F/Y H:i:s'),
				"text"   => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
				"link"   => [
					"text" => "A link",
					"href" => "/",
				],
			],
			[
				"image"  => "/assets/img/bg-showcase-2.jpg",
				"title"  => "title tile",
				"header" => "Very Hot",
				"footer" => date('j/F/Y H:i:s'),
				"text"   => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
				"link"   => [
					"text" => "A link",
					"href" => "/",
				],
			],
			[
				"image"  => "/assets/img/bg-showcase-2.jpg",
				"title"  => "title tile",
				"header" => "Very Hot",
				"footer" => date('j/F/Y H:i:s'),
				"text"   => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
				"link"   => [
					"text" => "A link",
					"href" => "/",
				],
			],
		];

		$params = compact("title", "hl", "assets", "icons", "testemonials", "alerts", "cards");

		return $this->render($response, "/pages/home.twig", $params);
	}

	public function get404(ServerRequest $request, ResponseInterface $response): ResponseInterface{
		$this->request = $request;
		$hl            = $this->prepare($request);
		$response      = $response->withHeader("Content-Language", $hl);
		$title         = $this->siteSetup->apiInfo->name;

		$assets = [$this->siteSetup->assets->landing];

		$alerts = [
			[
				"title"  => "404",
				"type"   => "danger",
				"text"   => "Page was not found!.",
				"footer" => "go home",
			],
		];

		$params = compact("title", "hl", "assets", "alerts");

		return $this->render($response, "/pages/home.twig", $params);
	}
}
