<?php

namespace System\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Csrf\Guard;
use Slim\Http\ServerRequest;
use Slim\Interfaces\RouteParserInterface;
use Slim\Routing\RouteParser;
use Slim\Views\Twig;
use SlimSession\Helper;
use System\Config\Asset;
use System\Config\SiteSettings;
use System\Utils\Translator;

abstract class BrowserController{
	protected ContainerInterface      $container;
	protected SiteSettings            $siteSetup;
	protected RouteParserInterface    $router;
	protected Helper                  $session;
	protected Guard                   $csrf;
	protected Translator              $translator;
	protected ServerRequest           $request;

	public function __construct(ContainerInterface $container, Translator $translator){
		$this->container  = $container;
		$this->router     = $this->container->get(RouteParser::class);
		$this->siteSetup  = $this->container->get(SiteSettings::class);
		$this->session    = $this->container->get(Helper::class);
		$this->csrf       = $this->container->get(Guard::class);
		$this->translator = $translator;
	}

	final public function render(ResponseInterface $response, string $file, array $params = []): ResponseInterface{
		$params = $this->prepareParams($params);

		return $this->container->get(Twig::class)
		                       ->render($response, $file, $params);
	}

	/**
	 * @param array $params
	 *
	 * @return array
	 */
	final private function prepareParams(array $params): array{
		$settings    = $this->siteSetup;
		$sitename    = $this->siteSetup->apiInfo->name;
		$sitelogo    = $this->siteSetup->apiInfo->logo;
		$sitelogobig = $this->siteSetup->apiInfo->logobig;
		$__          = $this->translator;
		$router      = $this->router;

		$js  = [];
		$css = [];

		if(isset($params["assets"])){
			$this->prepareAssets($params, $css, $js);
		}

		if(!isset($params["navigation"])){
			$navigation = [
				[
					"type" => "link",
					"href" => "/",
					"text" => "Home",
				],
				[
					"type"     => "dropdown",
					"text"     => "User",
					"elements" => [
						[
							"type" => "link",
							"href" => "/profile",
							"text" => "Profile",
						],
						[
							"type" => "link",
							"href" => "/settings",
							"text" => "Settings",
						],
						["type" => "divider"],
						[
							"type" => "link",
							"href" => "/logout",
							"text" => "Logout",
						],
					],
				],
			];
		}

		if(!isset($params["breadcrumb"])){
			$breadcrumbs = [];

			$urlParts = explode("/", $this->request->getUri()
			                                       ->getPath());
			unset($urlParts[0]);

			$fullurl = "";

			foreach($urlParts as $part){
				if($part == ""){
					$breadcrumbs[] = [
						"text" => "Home",
						"href" => "/",
					];
					continue;
				}
				$fullurl .= "/" . $part;

				$breadcrumbs[] = [
					"text" => ucwords($part),
					"href" => $fullurl,
				];
			}

			$last = array_pop($breadcrumbs);

			$last["last"] = true;
			unset($last["href"]);
			$breadcrumbs[] = $last;
		}

		$params = array_merge(compact("__", "settings", "router", "js", "css", "sitename", "sitelogobig", "sitelogo", "navigation", "breadcrumbs"),
		                      $params);

		return $params;
	}

	/**
	 * @param array $params
	 * @param array $css
	 * @param array $js
	 */
	private function prepareAssets(array &$params, array &$css, array &$js): void{
		$newAssets          = [
			$this->siteSetup->assets->jQuery,
			$this->siteSetup->assets->bootstrap,
			$this->siteSetup->assets->popper,
			$this->siteSetup->assets->fontAwesome,
			$this->siteSetup->assets->simpleLineIcons,
		];
		$params["assets"]   = array_merge($newAssets, $params["assets"]);
		$params["assets"][] = $this->siteSetup->assets->siteWide;

		foreach($params["assets"] as $asset){
			assert($asset instanceof Asset);
			if($asset->css != null){
				foreach($asset->css as $acss){
					$css[] = $acss;
				}
			}
			if($asset->js != null){
				foreach($asset->js as $ajs){
					$js[] = $ajs;
				}
			}
		}

		unset($params["assets"]);
	}

	final public function redirect(ResponseInterface $response, string $name, int $status = 302, array $params = []): ResponseInterface{
		if(empty($params)){
			return $response->withHeader('Location', $this->router->urlFor($name))
			                ->withStatus($status);
		}

		return $response->withHeader('Location', $this->router->urlFor($name, $params))
		                ->withStatus($status);
	}

	/**
	 * @param ServerRequest $request
	 *
	 * @return string
	 */
	final protected function prepare(ServerRequest $request): string{
		$params = $request->getQueryParams();

		$hl = $this->siteSetup->i18n->defaultLanguage;

		if($request->hasHeader("Accept-Language")){
			$hq = $request->getHeaderLine("Accept-Language");
			$hq = substr($hq, strpos($hq, ",") + 1, 2);
			if(in_array($hq, $this->siteSetup->i18n->availableLanguages)){
				$hl = $hq;
			}
		}

		if($this->session->exists("hl")){
			$hl = $this->session->get("hl");
		}
		if(isset($params["hl"])){
			$hl = $params["hl"];
			$this->session->set("hl", $hl);
		}

		$this->translator->setTheLanguage($hl);

		return $hl;
	}
}
