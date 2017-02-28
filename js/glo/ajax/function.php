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
		}else
		{
			$res = FALSE;
		}
		
		return $res;
	}
	
	function longVector($v){
		
		$res = 0;
		
		for($i = 0; $i < count($v); $i++){
			$res += $v[$i] * $v[$i];
		}
		
		return sqrt($res);
	}
	
	function print_ganre($v){
		
		
		$data['table'] = '<table>';
		
		for($i = 0; $i < count($v['ganre']); $i++){
			$data .= '<tr>';
			$data .= '<td style="width:5%;">'.$v['ganre'][$i].'</td>';
			$data .= '<td style="width:5%;">'.round($v['res'][$i],3).'</td>';

			for($k = 0; $k < round($v['res'][$i]*10); $k++){
				
				
				
				if(round($v['res'][$i]*10 < 2)){
					$color = '#ff5252';
				}else{
					if(round($v['res'][$i]*10 < 4)){
						$color = '#ff5252';
					}else{
						if(round($v['res'][$i]*10 < 6)){
							$color = '#ffeb3b';
						}else{
							if(round($v['res'][$i]*10 < 8)){
								$color = '#009688';
							}else{
								$color = "#2196f3";
							}
						}
					}
				}
				
				$data .= '<td style="background:'.$color.';color:'.$color.'">'.round($v['res'][$i]*10).'</td>';
			}
			
			for($k = 0; $k < 10 - round($v['res'][$i]*10); $k++){
				$data .= '<td style="">&nbsp;</td>';
			}
			
			//print ('<td>'.$v['res'][$i].'</td>');
			$data .= '</tr>';
		}

		$data .= '</table>';
		return $data;
	}
	
	function colot_proximity($mainColor, $color, $j, $pattern, $p){
		if( (abs($color[$j] - $mainColor[0]) <= $pattern/$p)  && (abs($color[$j+1] - $mainColor[1]) <= $pattern/$p) &&(abs($color[$j+2] - $mainColor[2]) <= $pattern/$p) ){
				return colot_proximity($mainColor, $color, $j, $pattern, $p+0.1);		
		}else{
			return $p/10;
		}
	}
	
	function colot_proximity_admin($mainColor, $vec, $p, $pattern, $p){


		if ( (abs($mainColor[0] - $vec['r'][$p]) <= $pattern/2) && 
			    	(abs($mainColor[1] - $vec['g'][$p]) <= $pattern/2) &&
			    	(abs($mainColor[2] - $vec['b'][$p]) <= $pattern/2) ){
				    	return colot_proximity_admin_admin($mainColor, $vec, $p, $pattern, $p+0.1);
		}else{
			return $p/10;
		}

	}
	
	
?>























