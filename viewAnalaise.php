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
  	<center><h2>Análise Sequência Genética</h2>
  	<p>Este trabalho foi desenvolvido pelas alunas Stephanie Lima e Ana Cecília para compor a nota da disciplina de Bioinformática lecionada pelo professor Diego Frias.</p></center>
  	<hr/>
	<form method="POST" action="analiseArq.php">
    	<div class="form-group col-md-12">
	      	<label for="fita">Fita:</label>
	  		<select id="fita" name="fita" class="form-control">
	    		<option value="positiva">Fita Positiva</option>
	    		<option value="negativa">Fita Negativa</option>
	  		</select>
	    </div>
	    <div class="form-group col-md-12">
	      	<label for="posicaoInicial">Posição Inicial:</label>
	      	<input type="text" class="form-control" name="posicaoInicial" id="posicaoInicial" placeholder="Escolha a posição inicial na fita:">
	    </div>
	    <div class="form-group col-md-12">
	      	<label for="posicaoFinal">Posição Final:</label>
	      	<input type="text" class="form-control" name="posicaoFinal" id="posicaoFinal" placeholder="Escolha a posição final na fita:">
	    </div>
	    <div class="form-group col-md-12">
			<button type="submit" class="btn btn-primary">Analisar Sequência</button>
	    </div>
	</form>
</div>
</body>
</html>