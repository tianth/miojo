<?php
	
namespace Miojo;

class Environment
{
	public $name;
	public $variables;
	
	public function __construct($name)
	{
		$this->name = $name;
		$this->variables = array_merge($_ENV, $_SERVER);
	}
}
	
?>