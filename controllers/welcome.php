<?php
	
namespace Controllers;
use \Miojo\Response as Response;

class Welcome extends \Miojo\BaseController
{
	
	public function get_index()
	{
		$vars = array(
			"config" => $this->config->get('application'),
			"environment" => $this->config->get('environment')
		);
		
		return Response::view("welcome/index", $vars);
	}
	
}
	
?>