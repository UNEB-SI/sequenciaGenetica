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
			while ($count <= 1264) {
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
	foreach ($novo as $key => $value) {
		$x = str_split($value);
		$tam = sizeof($x); //462

		for ($z=0; $z < $tam; $z+=3) { 
			$b = $x[$z].$x[$z+1].$x[$z+2];
			$frame1 = str_split($b,3);
			//print_r($frame1);
		}

		for ($i=1; $i < $tam-2; $i+=3) { //sobram dois
			$y = $x[$i].$x[$i+1].$x[$i+2];
			$frame2 = str_split($y,3);
			//print_r($frame2);
		}

		for ($j=2; $j < $tam-1; $j+=3) { 
			$a = $x[$j].$x[$j+1].$x[$j+2];
			$frame3 = str_split($a,3);
			print_r($frame3);

		}
		
	}




}


separarCodons();
//separandoSeq();
//gerarComplementar();


?>