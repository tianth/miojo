<?php

namespace Miojo;

// a versão do framework
define('MIOJO_VERSION', '0.0.1');

// carrega classes automaticamente de acordo com o nome da classe/namespace
spl_autoload_register(function($className){
	// transforma o nome da classe com namespace no caminho para encontrar na estrutura de arquivos
	$class_path = "../".strtolower(str_replace("\\", "/", $className)).".php";

	require_once($class_path);
});

error_reporting(E_PARSE|E_ERROR);

class Application 
{
	
	private static $instance;
	
	private $environment;
	public $config;
	
	// "Application" é um singleton, só pode haver uma instância rodando
	// esta função estática cria a instância caso não exista e retorna a instância única
	public static function sharedApplication()
	{
		if (is_null(self::$instance)) self::$instance = new self();
		
		return self::$instance;
	}
	
	private function __construct()
	{
		// carrega o arquivo .env
		$this->loadDotenv();
		
		// inicializa a configuração, que irá carregar configurações específicas do projeto
		$this->config = new Configuration($this->environment);
	}
	
	// carrega as variáveis de ambiente colocadas num arquivo ".env" na raíz do projeto
	private function loadDotenv()
	{
		// caminho padrão para o arquivo dotenv relativo à pasta do framework
        $filePath = "../.env";
		
		// se o arquivo não existir, carrega ambiente padrão
		if (!is_file($filePath) || !is_readable($filePath)) {
			$this->loadDefaultEnv();
			return;
		}
		
		// código abaixo copiado de: https://github.com/vlucas/phpdotenv/blob/master/src/Dotenv.php
		
        // Read file into an array of lines with auto-detected line endings
        $autodetect = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        $lines = file($filePath, FILE_SKIP_EMPTY_LINES);
        ini_set('auto_detect_line_endings', $autodetect);

        foreach ($lines as $line) {
            // Only use non-empty lines that look like setters
            if (strpos($line, '=') !== false) {
                // Strip quotes because putenv can't handle them. Also remove 'export' if present
                $line = str_replace(array('export ', '\'', '"'), '', $line);
                // Remove whitespaces around key & value
                list($key, $val) = array_map('trim', explode('=', $line, 2));

                // Don't overwrite existing environment variables.
                // Ruby's dotenv does this with `ENV[key] ||= value`.
                if (getenv($key) === false) {
                    putenv("$key=$val");
                    // Set PHP superglobals
                    $_ENV[$key] = $val;
                    $_SERVER[$key] = $val;
                }
            }
        }
		
		// define o ambiente que foi especificado no arquivo .env
		$this->setEnvironment($_ENV['MIOJO_ENV']);
	}
	
	// define o ambiente padrão caso um arquivo .env não exista
	public function loadDefaultEnv()
	{
		$this->setEnvironment("development");
	}
	
	// define em qual ambiente o app vai rodar (development, production, etc)
	public function setEnvironment($environment_name)
	{
		$this->environment = new Environment($environment_name);
	}
	
	// ponto de entrada de todas as páginas,
	// responsável por interpretar a URL e chamar o controller mais adequado
	public function run()
	{
		$uri = parse_url($_SERVER['REQUEST_URI']);
		$path = $uri['path'];
		$components = explode("/", $path);
		$method = $_SERVER['REQUEST_METHOD'];
		
		$componentCount = count($components);
		
		if ($componentCount <= 2) {
			// sem caminho, rota padrão
			$controller = $this->config->get("application.default_controller");
			$action = $this->config->get("application.default_action");
		} elseif ($componentCount == 3) {
			// somente controller
			$controller = $components[2];
			$action = $this->config->get("application.default_action");
		} elseif ($componentCount == 4) {
			// controller e action
			$controller = $components[2];
			$action = $components[3];
		} else {
			// controller, action e parâmetros adicionais
			$controller = $components[2];
			$action = $components[3];
			$args = array();
			for ($i = 4; $i < $componentCount; $i++) {
				$args[] = $components[$i];
			}
		}
		
		$controllerClass = "\\Controllers\\".ucwords($controller);
		$function = strtolower($method)."_".strtolower($action);
	
		$request = new Request($uri, $controller, $action, $uri['query']);
	
		$controllerInstance = new $controllerClass($this, $request);
		
		if (!method_exists($controllerInstance, $function)) {
			throw new Exception("A página $path não existe! Você precisa criar um controller e método que sirvam esta requisição.");
		}

		if (!is_array($args)) {
			$controllerInstance->$function();
		} else {
			call_user_func_array(array($controllerInstance, $function), $args);
		}
	}
	
}
	
?>