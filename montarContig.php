<?php

ini_set('memory_limit', '-1');
set_time_limit(0);

function eliminarLinha(){
	$arquivo = file("Bioinfo_TestSequence_Complete_Genome_FASTA.txt") or die("Error");
	unset($arquivo[0]);
	$novo = 'newCode.txt';

	foreach ($arquivo as $key => $value) {
		$file = fopen($novo, 'a');				
		$string = trim(preg_replace('/\s+/', ' ', $value));
		$writeArq = fwrite($file, $string);		
	}
}

function escreverArquivo(){

	$arquivo = file("ShotgunReads_fitas_10.txt") or die("Error");
	$file = file_get_contents('newCode.txt') or die("Error");	
	$reads = 'reads.txt';
	$fileReads = fopen($reads, 'a') or die("Error");	


	foreach ($arquivo as $key => $value) {
		$arrayLinha = explode(" ", $value);
		$posicaoInicial = $arrayLinha[1];
		$posicaoFinal = $arrayLinha[2];
		$fita = $arrayLinha[3];

		if($fita == 1){
			$valorString = substr($file, $posicaoInicial-1, $posicaoFinal-1);
			$valorString .= PHP_EOL;
			$escreverQuebraLinha = fwrite($fileReads, $valorString);
		} else {
			gerarComplementar($posicaoInicial, $posicaoFinal, $fileReads);
		}
	}

	fclose($fileReads);
}

function gerarComplementar($posicaoInicial, $posicaoFinal, $arquivoRead){


	$arquivo = file_get_contents('newCode.txt')or die("Error");
	$valorString = substr($arquivo, $posicaoInicial-1, $posicaoFinal-1);
	$stringInvert = strrev($valorString);
	$n_caracteres = strlen($stringInvert);

	for( $i=0; $i < $n_caracteres ; $i++ ){
   		if($stringInvert[$i] == 'T'){
			$trocandoT = str_replace('T', 'A', $stringInvert[$i] );
			$escreve = fwrite($arquivoRead, $trocandoT);
		} elseif ($stringInvert[$i] == 'A') {
			$trocandoA = str_replace('A', 'T', $stringInvert[$i]);
			$escreve = fwrite($arquivoRead, $trocandoA);

		} elseif ($stringInvert[$i] == 'C') {
			$trocandoC = str_replace('C', 'G', $stringInvert[$i]);
			$escreve = fwrite($arquivoRead, $trocandoC);

		} elseif ($stringInvert[$i] == 'G') {
			$trocandoG = str_replace('G', 'C', $stringInvert[$i]);
			$escreve = fwrite($arquivoRead, $trocandoG);
		}
	}

	$escreverQuebraLinha = fwrite($arquivoRead, PHP_EOL);
}

escreverArquivo();
//eliminarLinha();



?>
