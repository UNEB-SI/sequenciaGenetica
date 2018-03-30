<?php

function eliminarLinha(){

	$arquivo = file("Bioinfo_TestSequence_Complete_Genome_FASTA.txt") or die("Error");
	$aux = 0;
	$qntd = 0;

	unset($arquivo[0]);


	$tam = sizeof($arquivo);//echo $aux;

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
				$escreve = fwrite($fileNew, $linha);	
				$count = $qntd + $count;
				//echo $count."</br>";
				//break;
			}		
		}
	}
	echo $count;
	//echo $aux;
}	

separandoSeq();



?>