<?php
//gtt antepenultimo
// taa ultimo

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
	$novo = 'newCode.txt';

	foreach ($arquivo as $key => $value) {
		$file = fopen($novo, 'a');				
		$string = trim(preg_replace('/\s+/', ' ', $value));
		$writeArq = fwrite($file, $string);		
	}
	escreverArquivo($posicaoInicial, $posicaoFinal, $fita);
}

function escreverArquivo($posIni, $posFim, $fita){

	$aux = 0;
	$count = $posIni;
	$seqNova = 'seqNova.txt';
	$file = fopen('newCode.txt', 'r') or die("Error");	

	while (!feof($file)) {
		$linha = fgetc($file);
		$qntd = strlen($linha);	
		$aux = $qntd + $aux;

		if($aux == $posIni){	
			$fileNew = fopen($seqNova, 'a');
			$escreve = fwrite($fileNew, $linha);
			
			while ($count < $posFim) {	
				$linha_1 = fgetc($file);
				$qntd_1 = strlen($linha_1);	
				$escreve = fwrite($fileNew, $linha_1);	
				$count = $qntd_1 + $count;
			}		
		}
	}

	fclose($file);
	gerarComplementar($fita);
}

function gerarComplementar($fita){

	$complementar = 'complementar.txt';
	$novo = fopen($complementar, 'a');

	if($fita == 'negativa'){
		$arquivo = file_get_contents('seqNova.txt')or die("Error");
		$arqvInvert = strrev($arquivo);

		$arquivoInvertido = file_put_contents('arquivoInvertido.txt', $arqvInvert);
		$fileAberto = file('arquivoInvertido.txt');

		foreach ($fileAberto as $arquivoArbeto) {
			$stringArray = str_split($arquivoArbeto);
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
	}else{
		encontrarAminoacido($fita);
	}

	fclose($novo);
	encontrarAminoacido($fita);
}

function dicionarioAminoacidos($sequencia, $posicao, $fita){

	$aminoacidos = [];
	$aminoacidos['TTT'] = 'F';//Fenilanina
	$aminoacidos['TTC'] = 'F';//Fenilanina
	$aminoacidos['TTA'] = 'L'; //leucina
	$aminoacidos['TTG'] = 'L'; //leucina
	$aminoacidos['TCT'] = 'S'; //serina
	$aminoacidos['TCC'] = 'S'; //serina
	$aminoacidos['TCA'] = 'S'; //serina
	$aminoacidos['TCG'] = 'S'; //serina
	$aminoacidos['TAT'] = 'Y'; //tirosina
	$aminoacidos['TAC'] = 'Y'; //tirosina
	$aminoacidos['TAA'] = 'Stop Codon';
	$aminoacidos['TAG'] = 'Stop Codon';
	$aminoacidos['TGT'] = 'C'; //cisteina
	$aminoacidos['TGC'] = 'C'; //cisteina
	$aminoacidos['TGA'] = 'Stop Codon';
	$aminoacidos['TGG'] = 'W'; //triptofano
	$aminoacidos['CTT'] = 'L'; //leucina
	$aminoacidos['CTC'] = 'L';//leucina
	$aminoacidos['CTA'] = 'L'; //leucina
	$aminoacidos['CTG'] = 'L'; //leucina
	$aminoacidos['CCT'] = 'P';//prolina
	$aminoacidos['CCC'] = 'P'; //prolina
	$aminoacidos['CCA'] = 'P'; //prolina
	$aminoacidos['CCG'] = 'P'; //prolina
	$aminoacidos['CAT'] = 'H';//Histidina
	$aminoacidos['CAC'] = 'H'; //histidina
	$aminoacidos['CAA'] = 'Q'; //glutamina
	$aminoacidos['CAG'] = 'Q'; //glutamina
	$aminoacidos['CGT'] = 'R'; //arginina
	$aminoacidos['CGC'] = 'R'; //arginina
	$aminoacidos['CGA'] = 'R';//arginina
	$aminoacidos['CGG'] = 'R';//arginina
	$aminoacidos['ATT'] = 'I'; //Isolecina
	$aminoacidos['ATC'] = 'I';//Isolecina
	$aminoacidos['ATA'] = 'I';//Isolecina
	$aminoacidos['ATG'] = 'M'; // METionina
	$aminoacidos['ACT'] = 'T'; //treonina
	$aminoacidos['ACC'] = 'T'; //treonina
	$aminoacidos['ACA'] = 'T'; //treonina
	$aminoacidos['ACG'] = 'T'; //treonina
	$aminoacidos['AAT'] = 'N'; //asparagina
	$aminoacidos['AAC'] = 'N'; //asparagina
	$aminoacidos['AAA'] = 'K'; //lisina
	$aminoacidos['AAG'] = 'K'; //lisina
	$aminoacidos['AGT'] = 'S'; //serina
	$aminoacidos['AGC'] = 'S'; //serina
 	$aminoacidos['AGA'] = 'R'; //arginina
	$aminoacidos['AGG'] = 'R'; //arginina
	$aminoacidos['GTT'] = 'V'; //valina
	$aminoacidos['GTC'] = 'V'; //valina
	$aminoacidos['GTA'] = 'V'; //valina
	$aminoacidos['GTG'] = 'V'; //valina
	$aminoacidos['GCT'] = 'A'; //alanina
	$aminoacidos['GCC'] = 'A'; //alanina
	$aminoacidos['GCA'] = 'A'; //alanina
	$aminoacidos['GCG'] = 'A'; //alanina
	$aminoacidos['GAT'] = 'D'; //aspartato
	$aminoacidos['GAC'] = 'D';//aspartato
	$aminoacidos['GAA'] = 'E';//Ácido Glutâmico
	$aminoacidos['GAG'] = 'E';//Ácido Glutâmico
	$aminoacidos['GGT'] = 'G'; //glicina
	$aminoacidos['GGC'] = 'G'; //glicina
	$aminoacidos['GGA'] = 'G'; //glicina
	$aminoacidos['GGG'] = 'G';//glicina


	$restoDiv = floor($posicao-1)/3;
	$frame = $posicao -  (3 * ($restoDiv));
	
	if ($fita == 'negativa'){
		$frame = $frame*(-1);
		echo "Frame => ". $frame;
		echo "</br>";
	} else{
		echo "Frame => ". $frame;
		echo "</br>";
	}
	

	foreach ($sequencia as $key => $value) {
		echo "Códon =>" . $value . ' - ' ;
		echo "Aminoácido =>";
		print_r($aminoacidos[$value]);
		echo "</br>";
	}
}

function encontrarAminoacido($fita){
	
	$sequenciaCodificada = array();
	$stopCodon = ['TAA', 'TAG','TGA'];
	$startCodon = 'ATG';
	$posicaoInicial = 0;

	if($fita == 'negativa'){
		$complementar = file('complementar.txt') or die('Error ao abrir complementar');
		foreach ($complementar as $value) {
			$codons = str_split($value);
			$tam = sizeof($codons);

			for ($i=0; $i <= $tam; $i++) { 
				$codon = $codons[$i].$codons[$i+1].$codons[$i+2];
				if ($codon == $startCodon){
					$posicaoInicial = $i;
					array_push($sequenciaCodificada, $codon);
					for ($j=$i+3; $j <=$tam; $j+=3) { 
						$codon = $codons[$j].$codons[$j+1].$codons[$j+2];
						if(in_array($codon, $stopCodon)){
							array_push($sequenciaCodificada, $codon);
							dicionarioAminoacidos($sequenciaCodificada, $posicaoInicial, $fita);
							exit();
						} else{
							array_push($sequenciaCodificada, $codon);
						}
					}
				}
			}
		}
	} else{
		$fitaPositiva = file('seqNova.txt') or die('Error ao abrir fita positiva');
		foreach ($fitaPositiva as $value) {
			$codons = str_split($value);
			$tam = sizeof($codons);

			for ($i=0; $i <= $tam; $i++) { 
				$codon = $codons[$i].$codons[$i+1].$codons[$i+2];
				if ($codon == $startCodon){
					$posicaoInicial = $i;
					array_push($sequenciaCodificada, $codon);
					for ($j=$i+3; $j <=$tam; $j+=3) { 
						$codon = $codons[$j].$codons[$j+1].$codons[$j+2];
						if(in_array($codon, $stopCodon)){
							array_push($sequenciaCodificada, $codon);
							dicionarioAminoacidos($sequenciaCodificada, $posicaoInicial, $fita);
							exit();
						} else{
							array_push($sequenciaCodificada, $codon);
						}
					}
				}
			}
		}
	}	
}

?>