<?php

set_time_limit(0);
if (!empty($_POST)) {

	if(isset($_POST['fita']) && isset($_POST['posicaoInicial']) && isset($_POST["posicaoFinal"])){
	    $fita = $_POST["fita"];
		$posicaoInicial = $_POST["posicaoInicial"];
		$posicaoFinal = $_POST["posicaoFinal"];

	    eliminarLinha($posicaoInicial, $posicaoFinal, $fita);

	} else{
		echo "<div class='alert alert-danger' role='alert'>
	  			Valores indefinidos
			</div>";
	}
}else{
	echo "Não houve submit no formulário";
}

function apagarArquivo($arquivo){
	if (file_exists($arquivo)) {
    	unlink($arquivo);
	} 
}

function eliminarLinha($posicaoInicial, $posicaoFinal, $fita){
	$arquivo = file("Bioinfo_TestSequence_Complete_Genome_FASTA.txt") or die("Error");
	unset($arquivo[0]);
	$novo = 'newCode.txt';

	foreach ($arquivo as $key => $value) {
		$file = fopen($novo, 'a');
		$writeArq = fwrite($file, $value);	
	}

	separandoSeq($posicaoInicial, $posicaoFinal, $fita);
}

function separandoSeq($posIni, $posFim, $fita){	
	
	if($fita == 'negativa'){
		$newFile = file_get_contents('newCode.txt')or die("Error");
		$arqvInvert = strrev($newFile);

		$arquivoInvertido = file_put_contents('arquivoInvertido.txt', $arqvInvert);
		$fileAberto = fopen('arquivoInvertido.txt', 'r');
		escreverArquivo($posIni, $posFim, $fileAberto);
	} else {
		$newFile = fopen('newCode.txt', 'r') or die("Error");
		escreverArquivo($posIni, $posFim, $newFile);
	}		
}	

function escreverArquivo($posIni, $posFim, $file){
	$aux = 0;
	$count = $posIni;
	$seqNova = 'seqNova.txt';

	while (!feof($file)) {
		$linha = fgetc($file);
		$qntd = strlen(trim($linha));	
		$aux = $qntd + $aux;
		
		if($aux == $posIni){			
			$fileNew = fopen($seqNova, 'a');
			while ($count <= $posFim) {
				$linha = fgetc($file);
				$qntd = strlen(trim($linha));	
				$escreve = fwrite($fileNew, trim($linha));	
				$count = $qntd + $count;
			}		
		}
	}

	fclose($file);
	gerarComplementar();
}

function gerarComplementar(){

	$newFile = file('seqNova.txt') or die("Error");
	$complementar = 'complementar.txt';
	$novo = fopen($complementar, 'a');

	foreach ($newFile as $key => $value) {
		$stringArray = str_split($value);
		foreach ($stringArray as $key => $value) {
			if($stringArray[$key] == 'T'){
				$trocandoT = str_replace('T', 'A', $stringArray[$key]);
				$escreve = fwrite($novo, $trocandoT);

			} elseif ($stringArray[$key] == 'A') {
				$trocandoA = str_replace('A', 'T', $stringArray[$key]);
				$escreve = fwrite($novo, $trocandoA);

			} elseif ($stringArray[$key] == 'C') {
				$trocandoC = str_replace('C', 'G', $stringArray[$key]);
				$escreve = fwrite($novo, $trocandoC);

			} elseif ($stringArray[$key] == 'G') {
				$trocandoG = str_replace('G', 'C', $stringArray[$key]);
				$escreve = fwrite($novo, $trocandoG);
			}
		}
	}

	fclose($novo);
	encontrarAminoacido();
}

function dicionarioAminoacidos($sequencia, $posicao){

	$aminoacidos = [];
	$aminoacidos['TTT'] = 'Fenilanina';
	$aminoacidos['TTC'] = 'Fenilanina';
	$aminoacidos['TTA'] = 'Leucina';
	$aminoacidos['TTG'] = 'Leucina';
	$aminoacidos['TCT'] = 'Serina';
	$aminoacidos['TCC'] = 'Serina';
	$aminoacidos['TCA'] = 'Serina';
	$aminoacidos['TCG'] = 'Serina';
	$aminoacidos['TAT'] = 'Tirosina';
	$aminoacidos['TAC'] = 'Tirosina';
	$aminoacidos['TAA'] = 'Stop Codon';
	$aminoacidos['TAG'] = 'Stop Codon';
	$aminoacidos['TGT'] = 'Cysteine';
	$aminoacidos['TGC'] = 'Cysteine';
	$aminoacidos['TGA'] = 'Stop Codon';
	$aminoacidos['TGG'] = 'Tryptophan';
	$aminoacidos['CTT'] = 'Leucina';
	$aminoacidos['CTC'] = 'Leucina';
	$aminoacidos['CTA'] = 'Leucina';
	$aminoacidos['CTG'] = 'Leucina';
	$aminoacidos['CCT'] = 'Prolina';
	$aminoacidos['CCC'] = 'Prolina';
	$aminoacidos['CCA'] = 'Prolina';
	$aminoacidos['CCG'] = 'Prolina';
	$aminoacidos['CAT'] = 'Histidina';
	$aminoacidos['CAC'] = 'Histidina';
	$aminoacidos['CAA'] = 'Glutamina';
	$aminoacidos['CAG'] = 'Glutamina';
	$aminoacidos['CGT'] = 'Arginina';
	$aminoacidos['CGC'] = 'Arginina';
	$aminoacidos['CGA'] = 'Arginina';
	$aminoacidos['CGG'] = 'Arginina';
	$aminoacidos['ATT'] = 'Isolecina';
	$aminoacidos['ATC'] = 'Isolecina';
	$aminoacidos['ATA'] = 'Isolecina';
	$aminoacidos['ATG'] = 'Metionina';
	$aminoacidos['ACT'] = 'Treonina';
	$aminoacidos['ACC'] = 'Treonina';
	$aminoacidos['ACA'] = 'Treonina';
	$aminoacidos['ACG'] = 'Treonina';
	$aminoacidos['AAT'] = 'Asparagina';
	$aminoacidos['AAC'] = 'Asparagina';
	$aminoacidos['AAA'] = 'Lisina';
	$aminoacidos['AAG'] = 'Lisina';
	$aminoacidos['AGT'] = 'Serina';
	$aminoacidos['AGC'] = 'Serina';
	$aminoacidos['AGA'] = 'Arginina';
	$aminoacidos['AGG'] = 'Arginina';
	$aminoacidos['GTT'] = 'Valina';
	$aminoacidos['GTC'] = 'Valina';
	$aminoacidos['GTA'] = 'Valina';
	$aminoacidos['GTG'] = 'Valina';
	$aminoacidos['GCT'] = 'Alanina';
	$aminoacidos['GCC'] = 'Alanina';
	$aminoacidos['GCA'] = 'Alanina';
	$aminoacidos['GCG'] = 'Alanina';
	$aminoacidos['GAT'] = 'Ácido Aspártico';
	$aminoacidos['GAC'] = 'Ácido Aspártico';
	$aminoacidos['GAA'] = 'Ácido Glutâmico';
	$aminoacidos['GAG'] = 'Ácido Glutâmico';
	$aminoacidos['GGT'] = 'Glicina';
	$aminoacidos['GGC'] = 'Glicina';
	$aminoacidos['GGA'] = 'Glicina';
	$aminoacidos['GGG'] = 'Glicina';

	$frame = $posicao - 3 * floor($posicao-1/3);
	echo "Frame => ". $frame;

	foreach ($sequencia as $key => $value) {
		echo "Códon =>" . $value . ' - ' ;
		echo "Aminoácido =>";
		print_r($aminoacidos[$value]);
		echo "</br>";
	}
}

function encontrarAminoacido(){
	
	$complementar = file('complementar.txt') or die('Error');
	$sequenciaCodificada = array();
	$stopCodon = ['TAA', 'TAG','TGA'];
	$startCodon = 'ATG';
	$posicaoInicial = 0;

	foreach ($complementar as $value) {
		$codons = str_split($value);
		$tam = sizeof($codons);

		for ($i=0; $i < $tam; $i++) { 
			$codon = $codons[$i].$codons[$i+1].$codons[$i+2];
			if ($codon == $startCodon){
				$posicaoInicial = $i;
				array_push($sequenciaCodificada, $codon);
				for ($j=$i +3; $j < $tam; $j+=3) { 
					$codon = $codons[$j].$codons[$j+1].$codons[$j+2];
					if(in_array($codon, $stopCodon)){
						array_push($sequenciaCodificada, $codon);
						dicionarioAminoacidos($sequenciaCodificada, $posicaoInicial);
						exit();
					} else{
						array_push($sequenciaCodificada, $codon);
					}
				}
			}
		}
	}
}

?>