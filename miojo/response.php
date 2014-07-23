<?php
	
namespace Miojo;

class Response
{
	
	public static function json($data)
	{
		header("Content-type: application/json; charset=utf-8");
		echo json_encode($data);
	}
	
	public static function text($data)
	{
		header("Content-type: text/plain; charset=utf-8");
		echo $data;
	}
	
	public static function html($data)
	{
		header("Content-type: text/html; charset=utf-8");
		echo $data;
	}
	
	public static function view($name, $vars=array())
	{
		foreach($vars as $key=>$value):
			$$key = $value;
		endforeach;
		
		$viewPath = "../views/".$name.".php";
		$viewCode = file_get_contents($viewPath);
		
		// a função eval() é meio chata, então para poder intercalar HTML e PHP
		// tranquilamente eu tive que colocar um pedacinho de PHP no começo pra "fechar"
		// o PHP e "começar" o HTML. Deve ter um jeito melhor de fazer isto, mas por questões de tempo deixei assim =x
		eval("echo ''; ?>\n".$viewCode);
	}
	
}
	
?>