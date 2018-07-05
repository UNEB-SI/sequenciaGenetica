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

	foreach ($arquivo as $key => $value) {

		$arrayLinha1 = explode(" ", $arquivo[$key]);
		$arrayLinha2 = explode(" ", $arquivo[$key+1]);

		$posicaoInicial = $arrayLinha1[1];
		$posicaoFinal = $arrayLinha1[2];
		$fita = $arrayLinha1[3];
		$posini = $posicaoInicial-1;
		$posfim = $posicaoFinal-1;

		//$contig = gerarRead($posini,$posfim, $fita);
		$contig = '0000111122223333';
		$read = '22334400';

		$posicaoInicial_ = $arrayLinha2[1];
		$posicaoFinal_ = $arrayLinha2[2];
		$fita_ = $arrayLinha2[3];
		$posIni_ = $posicaoInicial_-1;
		$posFim_ = $posicaoFinal_-1;

		//$read = gerarRead($posIni_,$posFim_, $fita_);
		$resultado = encontrarMatch($contig, $read, $subsequencia);	
		echo "Seed =>". $contig;
		echo "</br>";
		print_r($resultado);
		echo "</br>";
		exit();
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
	//$stringInvert = strrev($valorString);

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

	//$contgInvert = strrev($stringInvert);
	return $stringInvert;
}



function encontrarMatch($str1, $str2, $subsequencia){

	$contig = $str1;    
    $match = false;
    $str1Array = str_split($str1);
    $str2Array = str_split($str2);
   
    if ($str1 == $str2){
        $contig = $str1;
    } elseif (in_array($str2, $str1Array)) {
    	$match = true;
    	$contig = $str2;
    } elseif (in_array($str1, $str2Array)) {
    	$contig = $str1;
    } else{
    	$tam1 = strlen($str1);
    	$tam2 = strlen($str2);
    	$ref = "";
    	$slide = "";

    	if($tam1 < $tam2){
    		$ref = $str2;
    		$slide = $str1;
    	} else{
    		$ref = $str1;
    		$slide = $str2;
    	}
    }

    $seqComparada = longest_common_substring($ref, $slide);
   
   	if(strlen( $seqComparada >= $subsequencia)){
   		$end_of_ref = explode($seqComparada, $ref)[1];
   		$start_of_ref = explode($seqComparada, $ref)[0];

   		$end_of_slide = explode($seqComparada, $slide)[1];
   		$start_of_slide = explode($seqComparada, $slide)[0];

   		if($start_of_slide != "" && $end_of_slide != ""){
   			$contig = $contig;
   			$match = false;	
   		} elseif ($end_of_ref == "") {
   			if($start_of_slide == ""){
   				$contig = $ref.$end_of_slide;
   				$match = true;	
   			} else{
   				$contig = $contig;
   				$match = false;
   			}
   		} elseif ($start_of_ref == "") {
   			if($end_of_slide == ""){
   				$contig = $start_of_slide.$ref;
   				$match = true;	
   			} else{
   				$contig = $contig;
   				$match = false;
   			} 
   		}else{
   			$match = false;
   		}
    } else{
    	$match = false;
    }
    return $array = array('contig' => $contig, 'match' => $match);

/*




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

	for ($i=$subsequencia; $i != $calculoOver; $i++) { 
		//$x = min($nslide, $nslide-$i+$nref)- max(1, $nslide-$i+1) +1 ;
		//$y =  min($i,$nref) - max(0, $i-$nslide+1)-1 + 1;
		
		$slideSeq = substr($slide,max(0, $nslide-$i), min($nslide, ($nslide-$i+$nref)-1));
		//print_r($slideSeq);
		$refSeq = substr($ref,max(0, $i-$nslide-1), (min($i,$nref-2)-max(0,$i-$nslide-1)));
		/*echo "</br>";
		print_r($refSeq);
		$result = Compare2Seq($refSeq,$slideSeq);*/
		
		/*if(($result['Mpontos'] == $result['pontos']) && ($result['pontos']>$maxScore)){
			$maxScore = $result['pontos'];
			$pos = $i;
		}
	//}

	$tipo=-2;
    if ($maxScore>0){
        $match=true;
        if($pos < $nslide){
			$tipo=-1;
			for ($j=0; $j < $nslide-$pos ; $j++) { 
				$novoContig = $novoContig.substr($slide,$j);
			}
        } elseif ($pos > $nref) {
        	print_r($pos);
        	echo "string";
        	print_r($nslide-($pos-$nref)+1);
        	exit();
        	$tipo = 1;
        	for ($j=$nslide-($pos-$nref)+1; $j < $nslide; $j++) { 
				$novoContig = $novoContig.substr($slide,$j);
        	}
        } else{
        	$tipo = 0;
        }	
    } else{
		$match=false;   
		$novoContig = $slide;
	} 

	return $array = array('novoContig' => $novoContig, 'pos'  => $pos, 'tipo' => $tipo, 'match' => $match);*/
}

function longest_common_substring($string1, $string2) {
    $L = array();
    $length = 0;
    $pos = 0;
    $array1 =str_split($string1);
    $array2 =str_split($string2);
    foreach ($array1 as $i => $c1) { 
        $L[$i] = array();
        foreach ($array2 as $j => $c2) { 
            $L[$i][$j] = 0;
            if ($c1 == $c2) {
                if ($i == 0 || $j == 0) {
                    // initialize that this character position exists.
                    $L[$i][$j] = 1;
                } else {
                    // increment previous or reset.
                    if (isset($L[$i-1][$j-1])) {
                        $L[$i][$j] = $L[$i-1][$j-1] + 1;
                    } else {
                        $L[$i][$j] = 0;
                    }
                }
                if ($L[$i][$j] > $length) {
                    $length = $L[$i][$j];
                }
                if ((isset($L[$i][$j]))&&($L[$i][$j] == $length)) {
                    $pos = $i;
                }
            }
        }
    }
    if ($length > 0) {
        return substr($string1, $pos - $length + 1, $length);
    } else {
        return '';
    }
}


function Compare2Seq($str1, $str2){

	
	//$matrix = [[0]*(1+len(str2)) for i in range(1+len(str1))]
	for ($i=0; $i < 1+strlen($str1) ; $i++) { 
		$matriz[] = array_fill(0, (1+strlen($str2)), 0);
	}
	/*print_r($str1[8-1]);
	exit();*/
	
  	$longest = 0;
  	$x_longest = 0;

  	for ($x=1; $x < 1+strlen($str1); $x++) { 
  		for ($y=1; $y < 1+strlen($str2) ; $y++) { 
  			if($str1[$x-1] == $str2[$y-1]){
  				$matriz[$x][$y] = $matriz[$x-1][$y-1] +1; 
  				if ($matriz[$x][$y] > $longest) {
  					$longest = $matriz[$x][$y];
  					$x_longest = $x;
  				}
  			}else{
  				$matriz[$x][$y] = 0;
  			}
  		}
  	}
  	//print_r( substr($str1, $x_longest - $longest, $x_longest));
	//exit();

  	return substr($str1, $x_longest - $longest, $x_longest);
  /*for x in range(1,1+len(str1)):
    for y in range(1,1+len(str2)):
        if str1[x-1] == str2[y-1]:
            matrix[x][y] = matrix[x-1][y-1] + 1
            if matrix[x][y] > longest:
                longest = matrix[x][y]
                x_longest = x
        else:
            matrix[x][y] = 0
  return str1[x_longest-longest: x_longest]*/
/*
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

	return $array = array('pontos' => $pontos, 'Mpontos' => $Mpontos);*/
}

escreverArquivo();
//eliminarLinha();



?>
