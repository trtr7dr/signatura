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
	
	
	function json_encode_($string) {

		$arrayUtf = array('\u0410', '\u0430', '\u0411', '\u0431', '\u0412', '\u0432', '\u0413', '\u0433', '\u0414', '\u0434', '\u0415', '\u0435', '\u0401', '\u0451', '\u0416', '\u0436', '\u0417', '\u0437', '\u0418', '\u0438', '\u0419', '\u0439', '\u041a', '\u043a', '\u041b', '\u043b', '\u041c', '\u043c', '\u041d', '\u043d', '\u041e', '\u043e', '\u041f', '\u043f', '\u0420', '\u0440', '\u0421', '\u0441', '\u0422', '\u0442', '\u0423', '\u0443', '\u0424', '\u0444', '\u0425', '\u0445', '\u0426', '\u0446', '\u0427', '\u0447', '\u0428', '\u0448', '\u0429', '\u0449', '\u042a', '\u044a', '\u042b', '\u044b', '\u042c', '\u044c', '\u042d', '\u044d', '\u042e', '\u044e', '\u042f', '\u044f');

		$arrayCyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е', 'Ё', 'ё', 'Ж', 'ж', 'З', 'з', 'И', 'и', 'Й', 'й', 'К', 'к', 'Л', 'л', 'М', 'м', 'Н', 'н', 'О', 'о', 'П', 'п', 'Р', 'р', 'С', 'с', 'Т', 'т', 'У', 'у', 'Ф', 'ф', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш',  'Щ', 'щ', 'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь', 'Э', 'э', 'Ю', 'ю', 'Я', 'я');

		return str_replace($arrayUtf,$arrayCyr,json_encode($string));
}
	
	
?>























