MIOJO
=====

Um framework muito básico (ênfase no *muito*), criado para demonstrar diversos conceitos em aulas do [Campus Tableless](http://campus.tableless.com.br)

Configuração
============

A pasta "config" contém arquivos de configuração dentro de pastas para cada ambiente.

Um arquivo de configuração deve retornar um array contendo configurações, o mínimo que um projeto precisa ter é o arquivo
application.php com as chaves base_url, default_controller e default_action.

Podem ser criados quantos arquivos de configuração forem desejados, os dados destes arquivos são carregados pela aplicação e podem ser acessados
através da propriedade config dentro de Application. Por exemplo:

	$app = Miojo\Application::sharedApplication();
	
	// configuração em config/(ambiente)/application.php
	$app->config->get("application.base_url");
	
	// configuração em config/(ambiente)/blog.php
	$app->config->get("blog.max_comment_length");

.env
====

Para definir em qual ambiente o projeto irá rodar, crie um arquivo ".env" na raíz do projeto e defina um valor para a variável "MIOJO_ENV".
Por exemplo:

	MIOJO_ENV=production

Se não existir um arquivo .env, será utilizado o ambiente "development".

Controllers
===========

O framework trabalha com MVC (Model, View, Controller), mas não fornece um componente de acesso ao banco de dados, portanto a camada model deve
ser implementada pelo programador conforme achar mais conveniente.

Controllers devem ser colocados na pasta "controllers" na raíz do projeto e suas classes devem herdar de BaseController e estar localizadas no
namespace "Controllers".

O nome do controller precisa ser o mesmo nome do arquivo com a primeira letra maiúscula. Se o arquivo for "posts.php" o nome da classe deverá
ser "Posts".

Controllers precisam herdar a class BaseController.

Os métodos do controller serão chamados de acordo com o método da requisição e o caminho requisitado.
Portanto, os métodos devem seguir o seguinte padrão de nomenclatura:
(método)_(ação)

Exemplos:

	function get_index()
	function put_index($id)
	function delete_destroy($id)
	function post_index()

Exemplo de controller:

	namespace Controllers;

	class Posts extends \Miojo\BaseController
	{
		public function get_index()
		{
			echo "Posts get_index";
		}
	}
	
Rotas
=====

Para simplificar o framework, não existe um sistema de rotas personalizado, as rotas são totalmente definidas pelos controllers.

Quando a aplicação recebe uma requisição, o framework irá detectar a URI requisitada e chamar o método no controller mais adequado.

Para a URI raíz "/", será utilizado o controller e método definidos no arquivo de configuração (ver config/(ambiente)/application.php).

Exemplos de URIs e para onde o framework irá rotear as requisições:

	GET /index.php/posts --------------------> \Controllers\Posts\get_index()
	GET /index.php/posts/index --------------> \Controllers\Posts\get_index()
	GET /index.php/posts/show/1 -------------> \Controllers\Posts\get_show(1)
	POST /index.php/users/index -------------> \Controllers\Users\post_index()
	DELETE /index.php/users/index/1/true ----> \Controllers\Users\delete_index(1, true)

Respostas
=========

A classe Response fornece algumas funcionalidades de resposta.

Response::text($text) retorna uma resposta de texto puro
Response::html($html) retorna uma resposta html
Response::json($data) retorna uma resposta json, codificando o objeto passado

Estas funções já passam os cabeçalhos adequados, o charset utf-8 é utilizado por praticidade.

Views
=====

A pasta views deve ser utilizada para armazenar views. É recomendável criar pastas para cada controller, porém isto não é obrigatório.

Utilize o método Response::view para retornar uma view.

Exemplo de uso de view:

Controller:

	namespace Controllers;
	use \Miojo\Response as Response;

	class Posts extends \Miojo\BaseController
	{
	
		public function get_index()
		{
			$posts = array("Meu primeiro post", "Outro post", "Mais um post");
		
			return Response::view("posts/index", array("posts" => $posts));
		}
		
		// ...

View:

	<h1>Um blog fake para um framework fake</h1>

	<h2>"Posts":</h2>

	<ol>
		<?php foreach($posts as $post): ?>
			<li>
				<h3><?=$post?></h3>
			</li>
		<?php endforeach; ?>
	</ol>

Disclaimer
==========

O framework tem mais algumas coisinhas ainda não documentadas, deve estar cheio de bugs e nem tudo foi feito necessariamente
da forma mais "correta" por falta de tempo. Se alguém quiser aprimorá-lo e enviar pull requests, fique à vontade ;)

OBS: O nome é apenas uma brincadeira.