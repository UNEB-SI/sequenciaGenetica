<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<?php
//gtt antepenultimo
// taa ultimo

ini_set('memory_limit', '-1');
set_time_limit(0);

if (!empty($_POST)) {
	if(isset($_POST['fita']) && isset($_POST['posicaoInicial']) && isset($_POST["posicaoFinal"])){
		    $fita = $_POST["fita"];
			$posicaoInicial = $_POST["posicaoInicial"];
			$posicaoFinal = $_POST["posicaoFinal"];
		if(isset($_POST['analisarSequencia'])){ 
			$submit = $_POST['analisarSequencia'];
		    eliminarLinha($posicaoInicial, $posicaoFinal, $fita, $submit);
		} else if (isset($_POST['encontrarPromotor'])){
			$submit = $_POST['encontrarPromotor'];
			eliminarLinha($posicaoInicial, $posicaoFinal, $fita, $submit);		
		} else if(isset($_POST['realizarRestricao'])){
			$submit = $_POST['realizarRestricao'];
			eliminarLinha($posicaoInicial, $posicaoFinal, $fita, $submit);	
		} else{
			echo "Não houve submit no formulário";
		}	
	} else{
		echo "<div class='alert alert-danger' role='alert'>
			Valores indefinidos
		</div>";
	}	
}else{
	echo "Não houve submit no formulário";
}
	
function eliminarLinha($posicaoInicial, $posicaoFinal, $fita, $submit){
	$arquivo = file("Bioinfo_TestSequence_Complete_Genome_FASTA.txt") or die("Error");
	unset($arquivo[0]);
	$novo = 'newCode.txt';

	foreach ($arquivo as $key => $value) {
		$file = fopen($novo, 'a');				
		$string = trim(preg_replace('/\s+/', ' ', $value));
		$writeArq = fwrite($file, $string);		
	}

	if($submit == 'Analisar Sequência'){
		escreverArquivo($posicaoInicial, $posicaoFinal, $fita, $submit);
	} else if($submit == 'Encontrar Promotor'){
		$novaPosicaoInicial = $posicaoInicial - 35; //para encontrar região promotora W == valor variável
		escreverArquivo($novaPosicaoInicial, $posicaoFinal, $fita, $submit);
	} else if($submit == 'Realizar Restrição'){
		if($fita == 'negativa'){
			gerarComplementar($posicaoInicial, $posicaoFinal, $fita, $submit);
		} else{
			realizarRestricao($posicaoInicial, $posicaoFinal, $fita, $submit);
		}
	} else{
		echo "Error! Nenhum botão foi clicado";
	}	
}



function escreverArquivo($posIni, $posFim, $fita, $submit){

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

	if($fita == 'negativa'){
		if($submit == 'Analisar Sequência' || $submit == 'Encontrar Promotor'){
			gerarComplementar($posIni, $posFim, $fita, $submit);
		} 		
	} 

	if($fita == 'positiva'){
		if($submit == 'Encontrar Promotor'){
			encontrarPromotor($posIni, $posFim, $fita);
		} else{
			encontrarAminoacido($posIni, $posFim, $fita, $submit);
		}		
	}	
}

function gerarComplementar($posIni, $posFim, $fita, $submit){

	$complementar = 'complementar.txt';
	$novo = fopen($complementar, 'a');

	if($submit == 'Analisar Sequência' || $submit == 'Encontrar Promotor'){
		$arquivo = file_get_contents('seqNova.txt')or die("Error");
		$arqvInvert = strrev($arquivo);
		$arquivoInvertido = file_put_contents('arquivoInvertido.txt', $arqvInvert);
		$fileAberto = file('arquivoInvertido.txt');
	} else{
		$arquivo = file_get_contents('newCode.txt')or die("Error");
		$arqvInvert = strrev($arquivo);
		$arquivoInvertido = file_put_contents('arquivoInvertido.txt', $arqvInvert);
		$fileAberto = file('arquivoInvertido.txt');
	}

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

	fclose($novo);
	if($submit == 'Analisar Sequência'){
		encontrarAminoacido($posIni, $posFim, $fita, $submit);
	} else if($submit == 'Realizar Restrição'){
		realizarRestricao($posIni, $posFim, $fita, $submit);
	} else{
		encontrarPromotor($posIni, $posFim, $fita);
	}	
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

function encontrarAminoacido($posIni, $posFim, $fita, $submit){
	
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

function encontrarPromotor($posIni, $posFim, $fita){

	$conjuntoPromoter = [];
	$conjuntoNoPromoter = [];
	$stringPromoter = "";
	$promotor =[];
	$stringNoPromoter = "";
	$i =0;
	$promotores = [];

	if($fita == 'negativa'){
		$sequenciaNegativa = file('complementar.txt') or die("Erro ao abrir complementar");
		foreach ($sequenciaNegativa as $value) {
			$base = str_split($value);
			$tamBase = sizeof($base);

			while ( $i <= 34) {
				if($base[$i] == 'T' || $base[$i] == 'A'){
					$stringPromoter = $stringPromoter.$base[$i];
					if($base[$i+1] != 'T' || $base[$i+1] != 'A'){
						$stringPromoter = $stringPromoter."</br>";
						$promotor = array('Base' => $stringPromoter);	
						array_push($conjuntoPromoter, $promotor);		
					} else{
						for ($j=$i+1; $j <=34 ; $j++) {
							$stringPromoter = $stringPromoter.$base[$j];
							$promotor = array('Base' => $stringPromoter);	
						}
					}
				}else{
					$stringPromoter = $stringPromoter."</br>";
					array_push($conjuntoPromoter, $promotor);
				}
				$i++;
			}
			print_r($promotor);
		}
	}else{
		$sequenciaPositiva = file('seqNova.txt') or die("Erro ao abrir sequencia nova");
		foreach ($sequenciaPositiva as $value) {
			$base = str_split($value);
			$tamBase = sizeof($base);
			
			while ( $i <= 34) {
				if($base[$i] == 'T' || $base[$i] == 'A'){
					$stringPromoter = $stringPromoter.$base[$i];
					if($base[$i+1] != 'T' || $base[$i+1] != 'A'){
						$stringPromoter = $stringPromoter."</br>";
						$promotor = array('Base' => $stringPromoter);	
						array_push($conjuntoPromoter, $promotor);		
					} else{
						for ($j=$i+1; $j <=34 ; $j++) {
							$stringPromoter = $stringPromoter.$base[$j];
							$promotor = array('Base' => $stringPromoter);
						}
					}
				}else{
					$stringPromoter = $stringPromoter."</br>";
					array_push($conjuntoPromoter, $promotor);
				}
				$i++;
			}
			print_r($promotor);
		}
	}
}

function realizarRestricao($posicaoInicial, $posicaoFinal, $fita, $submit){
	$ecoriPos = ['GAATTC']; //o corte deverá ser assim G AATTC [$base]
	$ecoriNeg = ['CTTAAG']; //o corte deverá ser assim CTTAA G [$base+5]
	$fragmentos = [];
	$fragConcat= "";
	$concatFrag="";
	$fragIndices = [];
	$posicoes = [];
	$posicaoI= 0;
	/*if($fita == 'negativa'){
		$fileAberto = file('complementar.txt') or die("Erro ao abrir complementar");
		
		foreach ($fileAberto as $value) {
			$base = str_split($value);
			$tamBase = sizeof($base);

			for ($i=0; $i < $tamBase-5; $i++) {
				$fragmento = $base[$i].$base[$i+1].$base[$i+2].$base[$i+3].$base[$i+4].$base[$i+5]; 
				$posicao = $base[$i+5];
				if(in_array($fragmento, $ecoriNeg)){
					fazerClivagem($fragmento, $fragmentos, $fragConcat, $fita);
					for ($j=$i+1; $j < $tamBase-5; $j++) { 
						$fragNew = $posicao.$base[$j+1].$base[$j+2].$base[$j+3].$base[$j+4].$base[$j+5]; 
						if(in_array($fragNew, $ecoriNeg)){
							fazerClivagem($fragNew, $fragmentos, $concatFrag, $fita);
							$posicao = $base[$j+5]; 
						} else{
							$concatFrag = $concatFrag . $base[$j];
						}
					} 
				} else{
					$fragConcat = $fragConcat . $base[$i];
				}
			}
			print_r($fragmentos);
			exit();
		}
	} else{*/
		$arquivo = file('newCode.txt') or die("Error ao abrir arquivo para realizarRestricao");
		foreach ($arquivo as $value) {
			$base = str_split($value);
			$tamBase = sizeof($base);

			for ($i=0; $i < $tamBase-5; $i++) {
				$fragmento = $base[$i].$base[$i+1].$base[$i+2].$base[$i+3].$base[$i+4].$base[$i+5];
				if(in_array($fragmento, $ecoriPos)){
					$posicaoF = $i;
					//echo $i . "</br>";
					array_push($fragmentos,fazerClivagem($fragmento,$fita,$fragConcat)); 
					//$fragReturn = fazerClivagem($fragmento, $fragmentos, $fragConcat, $fita);
					$fragNew = $base[$i+1].$base[$i+2].$base[$i+3].$base[$i+4].$base[$i+5].$base[$i+6];			
					$fragConcat = "";		
					$fragConcat = $fragConcat . $fragNew;
					$posicoes = array('inicio' => $posicaoI, 'final' => $posicaoF);
					$posicaoI = $posicaoF +1;

					array_push($fragIndices, $posicoes);




					//print_r($posicoes);
					

					/*for ($j=$i+1; $j < $tamBase-5; $j++) { 
						$fragNew = $base[$j].$base[$j+1].$base[$j+2].$base[$j+3].$base[$j+4].$base[$j+5]; 
						if(in_array($fragNew, $ecoriPos)){
							$fragReturn  = fazerClivagem($fragNew, $fragmentos, $concatFrag, $fita);
							$fragNew = $base[$j+1].$base[$j+2].$base[$j+3].$base[$j+4].$base[$j+5].$base[$j+6];
							$concatFrag = "";
							$concatFrag = $concatFrag . $fragNew;
							print_r($fragReturn);
							exit();
						} else{
							$concatFrag = $concatFrag . $base[$j];
						}
					}*/
				} else{
					$fragConcat = $fragConcat . $base[$i];			
				}
			}

			gerarTable($fragmentos, $fragIndices);

			
				//print_r($fragReturn);
					//exit();
				
		}
	//}
}

function fazerClivagem($fragmento,$fita,$inicioFragmento){
	$novoFragArray = explode("AATTC",$fragmento); //explode trasforma a string num array
	return array($inicioFragmento . implode("",$novoFragArray));	
}

function gerarTable($fragmentos, $posicoes){

	echo "<table class='table table-responsive'>";
	echo "    <thead>";
	echo "    	<tr>";
	echo "        <td>";
	echo "            Indice";
	echo "        </td>";
	/*echo "        <td>";
	echo "            Fragmento";
	echo "        </td>";*/
	echo "        <td>";
	echo "            Posicao Inicial";
	echo "        </td>";
	echo "        <td>";
	echo "            Posicao Final";
	echo "        </td>";
	echo "    	</tr>";
	echo "    </thead>";
	echo "    </tbody>";

	foreach ($posicoes as $value) {
		
		print_r($value['inicio']);
		print_r($value['final']);
		exit();
	}

	foreach ($fragmentos as $key => $value) {
		foreach ($value as $k => $valor) {
			echo "<tr>";
		    echo "   <td>";
		    echo        $key;
		    echo "   </td>";
		    /*echo "   <td>";
		    print_r  ($value[$k]);
		    echo "   </td>";*/
		    echo "   <td>";
		    echo       $posicaoI;
		    echo "   </td>";
		    echo "   <td>";
		    echo       $posicaoF;
		    echo "   </td>";
		    echo "</tr>";
		}
	}
	echo "	</tbody>";
	echo "</table>";
}

?>

</head>
</html>