<?php
	
namespace Miojo;

use Exception;

class Configuration 
{
	public $environment;
	private $configArray;
	
	public function __construct($environment)
	{
		// guarda o ambiente
		$this->environment = $environment;
		
		// carrega arquivos de configuração
		$this->load();
	}
	
	// retorna o valor de uma configuração
	// exemplo de keypath: "application.site_name"
	public function get($keypath)
	{
		list($array, $key) = explode(".", $keypath);
		
		// se a chave for nula, retorna o array inteiro
		if (is_null($key)) return $this->configArray[$array];
		
		return $this->configArray[$array][$key];
	}
	
	// define o valor de uma configuração
	// exemplo de keypath: "application.site_name"
	public function set($keypath, $value)
	{
		list($array, $key) = explode(".", $keypath);
		
		// se a chave for nula
		if (is_null($key)) {
			if (is_array($value)) {
				// modifica array inteiro porque foi passado um array como valor
				$this->configArray[$array] = $value;
			} else {
				// manda exceção porque o valor de um array sem chave precisa ser array
				throw new Exception("Valor de array de configurações precisa ser um array, ou uma chave precisa ser informada.");
			}
		}
		
		$this->configArray[$array][$key] = $value;
	}
	
	// carrega os arquivos de configuração e armazena no array interno
	private function load()
	{
		// coloca as variáveis de ambiente no array de configurações para acesso facilitado
		$this->configArray['environment'] = $this->environment->variables;
		
		// a versão do framework é inserida manualmente no ambiente
		$this->configArray['environment']['MIOJO_VERSION'] = MIOJO_VERSION;
		
		// monta o caminho do diretório usando nome do ambiente
		$configdir = "../config/".$this->environment->name."/";
		
		// se o diretório não existe, manda uma exceção
		if (!is_dir($configdir)) throw new Exception("Diretório de configuração \"$configdir\" não encontrado!");
		
		// carrega todos os arquivos PHP
		$d = opendir($configdir);
		while ($file = readdir($d)):
			$info = pathinfo($file);
			
			// ignora arquivos que não forem php
			if ($info['extension'] != "php") continue;
			
			// carrega arquivo
			$code = file_get_contents($configdir.$file);
			
			// é preciso remover a primeira abertura da tag do php,
			// porque eval() não funciona com essa tag
			if (strpos($code, "<?php") === 0) {
				$code = substr($code, 5, strlen($code));
			} elseif (strpos($code, "<?") === 0) {
				$code = substr($code, 3, strlen($code));
			}
			
			// executa arquivo e armazena no array
			$this->configArray[$info['filename']] = eval($code);
		endwhile;
		closedir($d);
	}
}
	
?>