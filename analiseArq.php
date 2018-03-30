<?php

function eliminarLinha(){

	$arquivo = file("Bioinfo_TestSequence_Complete_Genome_FASTA.txt") or die("Error");
	$aux = 0;
	$qntd = 0;

	unset($arquivo[0]);

	foreach ($arquivo as $key => $value) {
		$novo = 'newCode.txt';
		$file = fopen($novo, 'a');
		$writeArq = fwrite($file, $value);	
	}
	
}

function separandoSeq(){
	$newFile = fopen('newCode.txt', 'r') or die("Error");
	$aux = 0;
	$count = 803;

	while (!feof($newFile)) {
		$linha = fgetc($newFile);
		$qntd = strlen(trim($linha));	
		$aux = $qntd + $aux;

		if($aux == 803){			
			$seqNova = 'seqNova.txt';
			$fileNew = fopen($seqNova, 'a');
			while ($count != 1264) {
				$linha = fgetc($newFile);
				$qntd = strlen(trim($linha));	
				$escreve = fwrite($fileNew, trim($linha));	
				$count = $qntd + $count;
				//echo $count."</br>";
				//break;
			}		
		}
	}
	fclose($newFile);
	echo $count;
	//echo $aux;
}	

function gerarComplementar(){
	$newFile = file('seqNova.txt') or die("Error");
	$complementar = 'complementar.txt';
	$novo = fopen($complementar, 'a');

	foreach ($newFile as $key => $value) {
		$stringArray = str_split($value);
		$tam = count($stringArray);

		print_r($stringArray);

		for($i=0; $i < $tam; $i+=2) {
	        $aux = $stringArray[$i];
	        $stringArray[$i] = $stringArray[$i + 1];
	        $x = $stringArray[$i];
	        //echo $x;break;

	        $escreve = fwrite($novo, $x);
	        $escreve_v1 = fwrite($novo, $aux);
	        //$stringArray[$i + 1] = $aux;
			//echo $stringArray[$i + 1];break;
	    }
	}

	/*for($i = 0; $i < $tam; $i++) {
		$escreve = fwrite($newFile, $stringArray[$i]);	
	
    }  */
}



//separandoSeq();
gerarComplementar();


?>