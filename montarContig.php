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
	$subsequencia = 2;
	$resultado = "";
	$tamArquivo = sizeof($arquivo);
	
	$arrayLinha1 = explode(" ", $arquivo[0]);
	$arrayLinha2 = explode(" ", $arquivo[1]);

	$linha = $arrayLinha1[0];
	$posicaoInicial = $arrayLinha1[1];
	$posicaoFinal = $arrayLinha1[2];
	$fita = $arrayLinha1[3];

	/*$posini = $posicaoInicial-1;
	$posfim = $posicaoFinal-1;*/

	$contig = gerarRead($posicaoInicial,$posicaoFinal, $fita);
	//$contig = '0000111122223333';
	//$read = '333000111';  

	$linha_ = $arrayLinha2[0];
	$posicaoInicial_ = $arrayLinha2[1];
	$posicaoFinal_ = $arrayLinha2[2];
	$fita_ = $arrayLinha2[3];
	/*$posIni_ = $posicaoInicial_-1;
	$posFim_ = $posicaoFinal_-1;*/

	$read = gerarRead($posicaoInicial_,$posicaoFinal_, $fita_);
	$resultado = encontrarMatch($contig, $read, $subsequencia);	
	echo "Seed =>". $contig;
	echo "</br>";
	$cntg = "";
	$cntg = $resultado['contig'];
	$countMatch = 0;

	for ($i=2; $i <= $tamArquivo; $i++) { 
		$arqExplode = explode(" ", $arquivo[$i]);
		$line = $arqExplode[0];
		$inicio = $arqExplode[1];
		$final 	= $arqExplode[2];
		$fit 	= $arqExplode[3];

		$stringRead = gerarRead($inicio,$final, $fit);
		$match = encontrarMatch($cntg, $stringRead, $subsequencia);	
		$cntg = $match['contig'];

		if($match['match'] == 1){
			$countMatch = $countMatch + 1;
			print_r($match);
			echo "</br>";
			if($i == $tamArquivo){
				$tamCntg = strlen($cntg);
				echo "Tamanho do Contig Final => " . $tamCntg;
				echo "</br>";
				echo "Quantidade de Matchs => ". $countMatch;
				exit();
			}
		}	
	}
}

function gerarRead($posini,$posfim, $fita){
	
	$file = file_get_contents('newCode.txt') or die("Error");	
	if($fita == 1){
		$read = substr($file, $posini,$posfim ); //posini começa com 0 e vai até fim-1
		return $read;
	} else {
		$read = gerarComplementar($posini,$posfim);
		return $read;
	}
}

function gerarComplementar($posicaoInicial, $posicaoFinal){

	$arquivo = file_get_contents('newCode.txt')or die("Error");
	$arquivoTras = strrev($arquivo);
	$stringInvert = substr($arquivoTras, $posicaoInicial, $posicaoFinal);
	$n_caracteres = strlen($stringInvert);

	for( $i=0; $i < $n_caracteres ; $i++ ){
   		if($stringInvert[$i] == 'T'){
			$stringInvert[$i] = str_replace('T', 'A', $stringInvert[$i] );
		} elseif ($stringInvert[$i] == 'A') {
			 $stringInvert[$i] = str_replace('A', 'T', $stringInvert[$i]);

		} elseif ($stringInvert[$i] == 'C') {
			$stringInvert[$i]  = str_replace('C', 'G', $stringInvert[$i]);

		} elseif ($stringInvert[$i] == 'G') {
			$stringInvert[$i]  = str_replace('G', 'C', $stringInvert[$i]);
		}
	}
	return $stringInvert;
}

function encontrarMatch($contig, $read, $subsequencia){

	$contig_ = strlen($contig);
	$read_ = strlen($read);
	$aux = false;

	if($contig_> $read_){ // ref é o contig sempre maior e slide é o menor
		$ref = $contig;
		$nref = $contig_;
		$slide = $read;
		$nslide = $read_;
	} else{
		$ref = $read;
		$nref = $read_;
		$slide = $contig;
		$nslide = $contig_;	
	}

	$novoContig = $ref;
	$maxScore = 0;
	$pos = 0;
	$slideSeq = '';
	$refSeq = '';
	$calculoOver = $nref+$nslide-$subsequencia;

	for ($i=$subsequencia; $i < $calculoOver; $i++) { 
		$x =min($nslide-1, $nslide-$i + $nref-1) - max(0, $nslide-$i) +1 ;
		$y =  min($i-1,$nref-1) - max(0, $i-$nslide) +1;
	
		$slideSeq = substr($slide,max(0, $nslide-$i), $x);
		$refSeq = substr($ref,max(0, $i-$nslide), $y );
		$result = Compare2Seq($refSeq,$slideSeq);
		
		if(($result['Mpontos'] == $result['pontos']) && ($result['pontos']>$maxScore)){
			$maxScore = $result['pontos'];
			$pos = $i;
		}
	}

	$tipo=-2;
    if ($maxScore>0){
        $match=true;
        if($pos < $nslide){
			$tipo=-1;
			$novoContig = substr($slide,0, $nslide-$pos-1).$novoContig;
        } elseif ($pos > $nref) {
        	$tipo = 1;
			$novoContig =$novoContig.substr($slide, $nslide-($pos-$nref), $nslide-1);
        } else{
        	$tipo = 0;
        }	
    } else{
		$match=false;   
		$novoContig = $slide;
	} 

	return $array = array('contig' => $novoContig, 'pos'  => $pos, 'tipo' => $tipo, 'match' => $match);
}

function Compare2Seq($refseq, $slideseq){

	$lengthRef = strlen($refseq);
	$lengthSlide = strlen($slideseq);

	if($lengthRef != $lengthSlide){
		$pontos = -1;
		$Mpontos = -1;
	} else{
		$pontos = 0;
		for ($i=0; $i < $lengthRef; $i++) { 
			if(substr($refseq, $i) == substr($slideseq, $i)){
				$pontos = $pontos+1; 
			}
		}
		$pontos = $pontos*(1+$pontos - $lengthRef);
		$Mpontos = $lengthRef;
	}

	return $array = array('pontos' => $pontos, 'Mpontos' => $Mpontos);
}
escreverArquivo();
?>
