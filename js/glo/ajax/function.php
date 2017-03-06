<?php
    
    
    function getPalette($pal,$k){ //создание палитры
	    $res = array();
	    $j = 0;
	    for($i = 0; $i < count($_POST['palette']); $i++){
	    	$res[$j] = intval($_POST['palette'][$i][0])/$k;
	    	$res[$j+1] = intval($_POST['palette'][$i][1])/$k;
	    	$res[$j+2] = intval($_POST['palette'][$i][2])/$k;
	    	//echo($color[$j].' '.$color[$j+1].' '.$color[$j+2].'<br><br>');
	    	$j += 3;
    	}	
    	return $res;
	}
	
	function getArtistName(){
		$db = connect();
		$sql = $db->query('SELECT DISTINCT `artist` FROM `color`');
    
    	$name = array();
    	$i = 0;
    	while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$name[$i] = $row["artist"];
			$i++;
		}
    	$db = null;
    	return $name;
	}
	
	function array_delete(array $array, array $symbols = array('')){
	    return array_diff($array, $symbols);
	}
	
	function getGanre(){
		$db = connect();
		$sql = $db->query('SELECT DISTINCT `genre_name` FROM `genre`');
    
    	$name = array();
    	$i = 0;
    	while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$name[$i] = $row["genre_name"];
			$i++;
		}
    	$db = null;
    	return $name;
	}
	
	function skolarMulti($v1, $v2){
		$res = 0;
		if(count($v1) == count($v2)){
			for($i; $i < count($v1); $i++){
				$p = $v1[$i] * $v2[$i];
				$res += $p;
			}
		}else{
			$res = FALSE;
		}
		return $res;
	}
	
	function sum_squ($x){
		$res = 0;
		foreach ($x as $i){
			$res += $i*$i;
		}
		return $res;
	}
	
	function minimum($x,$y){
		if($x >= $y){
			return $x;
		}else{
			return $y;
		}
	}
	
	function longVector($v){
		
		$res = 0;
		
		for($i = 0; $i < count($v); $i++){
			$res += $v[$i] * $v[$i];
		}
		
		return sqrt($res);
	}
	
		
	function get_table_color($c, $step){
	$color = array( "0" => "#ff0000", "1" => "#ffae00", "2" => "#ff0000", "3" => "#00ffff", "4" => "#00ae00", "5" => "#0000ff");
	//echo($step.' '.round($c*10).'<br>');
		if($step == 1){
			if(round($c*10) > 2){
				return get_table_color($c, 2);
			}else{
				return $color[0];
			}
				
		}
		else{
			if(round($c*10) > $step + 2){
				return get_table_color($c, $step + 2);
			}
			else{
				return $color[round($step/2)];
			}
		}
	}	
		
	
	function print_ganre($v, $type){
		
		$data['table'] = '<table>';
		echo($data['table']);
		
		for($i = 0; $i < count($v['ganre']); $i++){
			$data .= '<tr>';
			$data .= '<td style="width:5%;">'.$v['ganre'][$i].'</td>';
			$data .= '<td style="width:5%;">'.round($v['res'][$type][$i],3).'</td>';
			
			for($k = 0; $k < round($v['res'][$type][$i]*10); $k++){
				

				$color = get_table_color($v['res'][$type][$i], 1);
				
				$data .= '<td style="background:'.$color.';color:'.$color.'">'.round($v['res'][$type][$i]*10).'</td>';
			}
			
			for($k = 0; $k < 10 - round($v['res'][$type][$i]*10); $k++){
				$data .= '<td style="">&nbsp;</td>';
			}
			
			//print ('<td>'.$v['res'][$i].'</td>');
			$data .= '</tr>';
		}

		$data .= '</table>';
		return $data;
	}
	
	function color_proximity($mainColor, $color, $j, $pattern, $p){
		if( (abs($color[$j] - $mainColor[0]) <= $pattern/$p)  && (abs($color[$j+1] - $mainColor[1]) <= $pattern/$p) &&(abs($color[$j+2] - $mainColor[2]) <= $pattern/$p) ){
				return color_proximity($mainColor, $color, $j, $pattern, $p+0.1);		
		}else{
			return $p/10;
		}
	}
	
	function color_proximity_admin($mainColor, $vec, $p, $pattern, $p){


		if ( (abs($mainColor[0] - $vec['r'][$p]) <= $pattern/2) && 
			    	(abs($mainColor[1] - $vec['g'][$p]) <= $pattern/2) &&
			    	(abs($mainColor[2] - $vec['b'][$p]) <= $pattern/2) ){
				    	return color_proximity_admin_admin($mainColor, $vec, $p, $pattern, $p+0.1);
		}else{
			return $p/10;
		}

	}
	
	function summ_sqrt($x){ //сумма квадратов элементов
		$res = 0;
		foreach($x as $i){
			$res += $i*$i;
		}
		return $res;
	}
	
	function summ_multi($x,$y){ //сумма квадратов элементов
		$res = 0;

		for($i = 0; $i < count($x); $i++){
			$res += $x[$i]*$y[$i];

		}
		return $res;
	}
	
	function cosinus($a,$b){
		return skolarMulti($a, $b) / (longVector($a) * longVector($b));
	}
	function dise($a,$b){
		
		return (2 * summ_multi($a, $b) ) / (summ_sqrt($a) + summ_sqrt($b));
	}
	
	
	function jakkard($a,$b){
		return ( summ_multi($a, $b) ) / (summ_sqrt($a) + summ_sqrt($b) - summ_multi($a, $b));
	}	
	
	
	function overlap($a,$b){
		return ( summ_multi($a, $b) ) / min(summ_sqrt($a), summ_sqrt($b));
	}	
	
	
	function hemming($a,$b,$p){
		$res = 0;
		
		for($i = 0; $i < count($a); $i++){
			if(abs($a[$i] - $b[$i]) < $p){
				$res++;
			}
		}
		
		return $res / 16;
	}
	
?>























