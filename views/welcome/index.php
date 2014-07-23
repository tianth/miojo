<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Miojo Framework</title>
	<style>
	body {
		-webkit-font-smoothing:antialiased;
		font:13px 'Helvetica Neue', Helvetica, sans-serif;
	}
	#main {
		width:60%;
		margin:0 auto;
	}
	pre {
		font:13px Monaco, Menlo, monospace;
	}
	h1, h2 {
		font-weight:200;
		font-size:400%;
		margin-bottom:20px;
	}
	
	hr {
		visibility:hidden;
		margin:40px 0;
	}
	article {
		text-align:center;
	}
	pre {
		border-radius:5px;
		border:1px solid #eee;
		background:#f4f4f4;
		padding:10px;
		overflow:auto;
	}
	</style>
</head>
<body>
	<section id="main">
		<article>
			<h1>Olá, mundo!</h1>

			<p>Isto aqui é uma página de boas vindas, você pode encontrá-la na pasta "views/welcome".</p>
	
			<p>O controller responsável por esta página se chama Welcome e está na pasta "controllers".</p>
		</article>

		<hr>
		
		<h2>Coisas nerds</h2>

		<h3>App->Config[application]:</h3>
	
		<pre><? print_r($config); ?></pre>
	
		<h3>App->Environment:</h3>
	
		<pre><? print_r($environment); ?></pre>
	</section>
</body>
</html>