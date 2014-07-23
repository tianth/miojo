<?php

namespace Miojo;

class BaseController
{
	
	public $app;
	public $config;
	public $request;
	
	public function __construct($app, $request)
	{
		$this->app = $app;
		$this->config = $app->config;
		$this->request = $request;
	}
	
}
	
?>