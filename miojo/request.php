<?php
	
namespace Miojo;

class Request
{
	public $uri;
	public $controller;
	public $action;
	public $query;
	
	public function __construct($uri, $controller, $action, $query)
	{
		$this->uri = $uri;
		$this->controller = $controller;
		$this->action = $action;
		$this->query = $query;
	}
}
	
?>