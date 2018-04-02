<?php

function eliminarLinha(){

	$arquivo = file("Bioinfo_TestSequence_Complete_Genome_FASTA.txt") or die("Error");

	unset($arquivo[0]);

	foreach ($arquivo as $key => $value) {
		$novo = 'newCode.txt';
		$file = fopen($novo, 'a');
		$writeArq = fwrite($file, $value);	
	}
	
}

function separandoSeq(){
	
	$newFile = file_get_contents('newCode.txt')or die("Error");
	$arqvInvert = strrev($newFile);

	$arquivoInvertido = file_put_contents('arquivoInvertido.txt', $arqvInvert);
	$fileAberto = fopen('arquivoInvertido.txt', 'r');

	//print_r($arquivoInvertido); 
	$aux = 0;
	$count = 803;

	while (!feof($fileAberto)) {
		$linha = fgetc($fileAberto);
		$qntd = strlen(trim($linha));	
		$aux = $qntd + $aux;
		
		if($aux == 803){			
			$seqNova = 'seqNova.txt';
			$fileNew = fopen($seqNova, 'a');
			while ($count <= 1264) {
				$linha = fgetc($fileAberto);
				$qntd = strlen(trim($linha));	
				$escreve = fwrite($fileNew, trim($linha));	
				$count = $qntd + $count;
				//echo $count."</br>";
				//break;
			}		
		}
	}
	fclose($fileAberto);
	//echo $count;
	//echo $aux;
}	

function gerarComplementar(){
	$newFile = file('seqNova.txt') or die("Error");
	$complementar = 'complementar.txt';
	$novo = fopen($complementar, 'a');

	foreach ($newFile as $key => $value) {
		$stringArray = str_split($value);
		$tam = sizeof($stringArray);

		//print_r($stringArray);

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

	fclose($novo);
	
	/*for($i = 0; $i < $tam; $i++) {
		$escreve = fwrite($newFile, $stringArray[$i]);	
	
    }  */
}

function separarCodons(){
	$novo = file('complementar.txt');
	$codon1 = [];
	$codon2 = [];
	$codon3 = [];

	foreach ($novo as $key => $value) {
		$x = str_split($value);
		//print_r($x);
		$tam = sizeof($x); //462

		for ($z=0; $z < $tam; $z+=3) { 
			$b = $x[$z].$x[$z+1].$x[$z+2];		
			array_push($codon1, $b);
		}
		//print_r($codon1);

		for ($i=1; $i < $tam-2; $i+=3) { //sobram dois
			$y = $x[$i].$x[$i+1].$x[$i+2];
			array_push($codon2, $y);
		}
		//print_r($codon2);


		for ($j=2; $j < $tam-1; $j+=3) { 
			$a = $x[$j].$x[$j+1].$x[$j+2];
			array_push($codon3, $a);
		}
		//print_r($codon3);
		gerarFrames($codon1, $codon2, $codon3);
	}
}

function gerarFrames($array1, $array2, $array3){ // incompleto, pensar mais
	$tamCod1= sizeof($array1);
	$tamCod2= sizeof($array2);
	$tamCod3= sizeof($array3);
	$arrayFrames = array();

	print_r($array1);

	for ($i=0; $i < $tamCod1; $i++) { 
		if ($array1[$i] == 'ATG' || $array1[$i] == 'CTG' || $array1[$i] =='ATT'|| $array1[$i] =='ATA' 
			|| $array1[$i] =='GTG' || $array1[$i] == 'TTG'){ //considerando que timinia será trocada por uracila, o t será usado
			$arrayFrames['posicaoInicial'] =$i;
			print_r($arrayFrames['posicaoInicial']);
		
		}
		if ($array1[$i] == 'TAA' || $array1[$i] == 'TAG' || $array1[$i] == 'TGA'){
				$arrayFrames['posicaoFinal'] =$i;
			//print_r($arrayFrames);
		}	
	}

}

/*
falta ver a melhor forma de salvar as posições iniciais e finais, printar em qual frame o gene se encontra, determinar os aminoácidos da proteína codificada, usando a tabela.
*/



//separarCodons();
//separandoSeq();
gerarComplementar();


?>