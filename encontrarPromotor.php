<!DOCTYPE html>
<html lang="en">
<head>
  <title>Análise - Sequência ´Genética</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container jumbotron">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
		    <ul class="nav navbar-nav">
			    <div class="navbar-header">
			    	<a class="navbar-brand" href="viewAnalaise.php">Home</a>
			    </div>
		      	<li><a href="analisarSeq.php">Analisar Sequência</a></li>
		      	<li><a href="encontrarPromotor.php">Encontrar Promotor</a></li>
		    </ul>
		 </div>
	</nav>
	<center><h2>Região Promotora</h2>
  	<p> A região promotora está localizada geralmente na região 5' de um gene. O promotor contém sequência de DNA específicas que são reconhecidas por proteínas conhecidas como fatores de transcrição. Estes fatores se ligam às sequências dp promotor, recrutando a DNA Polimerase para realizar a transcrição.</p></center>
  	<hr/>
	<form method="get" action="analiseArq.php">
	    <div class="form-group col-md-12">
			<button type="submit" class="btn btn-primary">Encontrar Promotor</button>
	    </div>
	</form>
</div>
</body>
</html>
