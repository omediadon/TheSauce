<?php

namespace System\Config;

class ApiInfo{
	public string $version             = "1.0.0";
	public string $appVersion          = "1.0.0";
	public bool   $debug               = true;
	public string $name                = "Life";
	public string $logo                = "/assets/img/logo.png";
	public string $logobig             = "/assets/img/logobig.png";
	public string $coonectedCookieName = "connectedMail";
	public array  $routesList          = [
		"base"       => "/api",
		"topics"     => "/topic",
		"categories" => "/category",
		"images"     => "/image",
	];
}
