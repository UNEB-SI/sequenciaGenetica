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

function eliminarLinha($posicaoInicial, $posicaoFinal, $fita){
	$arquivo = file("Bioinfo_TestSequence_Complete_Genome_FASTA.txt") or die("Error");
	unset($arquivo[0]);

	foreach ($arquivo as $key => $value) {
		$novo = 'newCode.txt';
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

	while (!feof($file)) {
		$linha = fgetc($file);
		$qntd = strlen(trim($linha));	
		$aux = $qntd + $aux;
		
		if($aux == $posIni){			
			$seqNova = 'seqNova.txt';
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
}

function dicionarioAminoacidos($codon){

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

	print_r($aminoacidos);

	if(in_array($aminoacidos[$codon], $codon )){
		echo "string";
		exit();
	}
}

function encontrarAminoacido(){
	
	$complementar = file('complementar.txt') or die('Error');
	$sequenciaCodificada = array();
	$stopCodon = ['TAA', 'TAG','TGA'];
	$startCodon = 'ATG';
	$count = 0;

	foreach ($complementar as $value) {
		$codons = str_split($value);
		$tam = sizeof($codons);

		for ($i=0; $i < $tam; $i++) { 
			$codon = $codons[$i].$codons[$i+1].$codons[$i+2];
			if ($codon == $startCodon){
				array_push($sequenciaCodificada, $codon);
				for ($j=$i +3; $j < $tam; $j+=3) { 
					$codon = $codons[$j].$codons[$j+1].$codons[$j+2];
					if(in_array($codon, $stopCodon)){
						array_push($sequenciaCodificada, $codon);
						foreach ($sequenciaCodificada as $sequencia) {
							dicionarioAminoacidos($sequencia);					
						}
					} else{
						array_push($sequenciaCodificada, $codon);
					}
				}
			}
		}
	}


	/*$aminoacidos = ['Fenilanina' => 'Fen','Leucina' => 'Len', 'Serina'=> 'Ser', 'Tirosina' =>'Tir', 'Cysteine' => 'Cis', 'Tryptophan' => 'Trp'  ];
	$stopCodon = ['UAA', 'UAG','UGA' ];
	
	$d = array_map(null, $a, $b, $c);

	//print_r($aminoacidos);

	foreach ($codons as $key => $codon) {
		if($codons[$key] == )
		print_r($codons[$key]);
						
	}*/
	//print_r($aminoacido);

}

encontrarAminoacido();



/*function separarCodons(){
	$novo = file('complementar.txt');
	$codon1 = [];
	$codon2 = [];
	$codon3 = [];

	foreach ($novo as $value) {
		$x = str_split($value);
		$tam = sizeof($x); //462

		for ($z=0; $z < $tam; $z+=3) { 
			$b = $x[$z].$x[$z+1].$x[$z+2];		
			array_push($codon1, $b);
		}

		for ($i=1; $i < $tam-2; $i+=3) { //sobram dois
			$y = $x[$i].$x[$i+1].$x[$i+2];
			array_push($codon2, $y);
		}

		for ($j=2; $j < $tam-1; $j+=3) { 
			$a = $x[$j].$x[$j+1].$x[$j+2];
			array_push($codon3, $a);
		}

		gerarFrames($codon1, $codon2, $codon3);
	}
}

function gerarFrames($array1, $array2, $array3){ // incompleto, pensar mais
	$tamCod1= sizeof($array1);
	$tamCod2= sizeof($array2);
	$tamCod3= sizeof($array3);
	$posicoesIniciais = array();
	$posicoesFinais = array();


	//frame 1
	for ($i=0; $i < $tamCod1; $i++) { 
		if ($array1[$i] == 'ATG' || $array1[$i] == 'CTG' || $array1[$i] =='ATT'|| $array1[$i] =='ATA' 
			|| $array1[$i] =='GTG' || $array1[$i] == 'TTG'){ //considerando que timinia será trocada por uracila, o t será usado
			$posicoesIniciais = array_push($posicoesIniciais, array("Posicao Inicial" => $array1[$i], "Contador Inicio" => $i, "Frame " => "Frame 1")		
			);		
		}
			print_r($posicoesIniciais);


		if ($array1[$i] == 'TAA' || $array1[$i] == 'TAG' || $array1[$i] == 'TGA'){
			$posicoesFinais = array(
				array("Posicao Final" => $array1[$i], "Contador Final" => $i, "Frame " => "Frame 1")
			);		
		}	
	}

			print_r($posicoesFinais);

}*/

/*
o gene codificante tem que ser m
falta ver a melhor forma de salvar as posições iniciais e finais, printar em qual frame o gene se encontra, determinar os aminoácidos da proteína codificada, usando a tabela.
*/
//escolhendoSeq();
//eliminarLinha();
//separarCodons();
//separandoSeq();
//gerarComplementar();

?>