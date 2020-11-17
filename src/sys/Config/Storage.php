<?php

namespace System\Config;

class Storage{
	public string  $avatarsFolder  = "public/upload/avatars/";
	public string  $publicAvatars  = "upload/avatars/";
	public string  $emailTemplates = "var/templates/email/";
	public string  $keys           = "var/keys/";
	public string  $lang           = "var/i18n.php";
	public string  $logs           = "var/logs/app.log";
	public string  $cache          = "var/cache/";
	public string  $views          = "src/app/Views/";
	private string $home;

	public function __construct(){
		$this->home           = str_replace("src" . DS . "sys" . DS . "Config", "", __DIR__);
		$this->avatarsFolder  = $this->home . $this->avatarsFolder;
		$this->emailTemplates = $this->home . $this->emailTemplates;
		$this->keys           = $this->home . $this->keys;
		$this->lang           = $this->home . $this->lang;
		$this->logs           = $this->home . $this->logs;
		$this->cache          = $this->home . $this->cache;
		$this->views          = $this->home . $this->views;
	}
}