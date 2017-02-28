
<?php
       
    require_once ( 'connect.php' );
    require_once ( 'function.php' );
    require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
    
     if($_POST['flag'] == 'face'){

	    $face = $_POST['face'];
	    
	    //print_r($face);
	    
	     $height = $_POST['height'];
	     $width = $_POST['width'];
	     
	     if(count($face) == 1){
		 	echo('Вероятно, на изображении портрет одного человека');
	     }
	     if(count($face) > 1){
		      echo('Вероятно, на изображении расположена группа людей');
	     }
	     if(count($face) == 0){
		      echo('Не удалось распознать лица людей');
	     }
	     
     }
       
	 if($_POST['flag'] == 'sig'){ //юзеор залил картинку
		$img = $_POST['img'];
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$result = file_put_contents('img/'.date('m_d_y_H_i_s').'.png', base64_decode($img));
		
		
		$color = getPalette($_POST['palette'], 8);
		
		
		$db = connect();
    	
    	$lang = $db->query('SET CHARACTER SET utf8');
    	
    	
    	$vectorUser = array();
    	
    	$pattern = 18;
    	$k = 8;
    	
    	$big    = 256 / $k;
    	$middle = 192 / $k;
    	$small  = 128 / $k;
    	
    	
    	$mainColor = array( array(0, $big, $big), array(0,0,0), array(0,0, $big), array( $big,0, $big), array( $small, $small, $small), array(0, $small,0), array(0, $big,0), array( $small,0,0), array(0,0, $small), array( $small, $small,0), array( $small,0, $small),array( $big,0,0),array( $middle, $middle, $middle),array(0, $small, $small),array( $big, $big, $big),array( $big, $big,0));
    	
    	
    	$f = 0;
    	for($i = 0; $i < count($mainColor); $i++){
	    	$vectorUser[$f] = 0;
	    	for($j = 0; $j < count($color); $j+=3){
		    	
		    	$vectorUser[$f] = colot_proximity($mainColor[$i], $color, $j, $pattern, 1);
		    	
	    	}
	    	$f++;
    	}
    	
    	
		$sql = 'SELECT * FROM `vector`';
		$res = $db->query($sql);
    	
    	
    	$ganreStat = array();

    	$ganreResult = array();
    	$u = 0;
    	
    	//косинусовая мера
    	while($obj = $res->fetch(PDO::FETCH_ASSOC)){
	    	$tmp = explode(';',$obj['coordinate']);
			$ganreResult['ganre'][$u] = $obj['ganre'];
			$ganreResult['res'][$u] = (skolarMulti($vectorUser, $tmp) / (longVector($vectorUser) * longVector($tmp)));
	    	$u++;
    	}
    	//косинусовая мера
    	
    	
    	
    	

    	$data['table'] = print_ganre($ganreResult);
    	
    	
    	
    	$artist = array();
    	$j = 0;
		
		$h = 1;

		for($i = 0; $i < count($color); $i+=3){
			$sql1 = 'SELECT `artist` FROM `color` WHERE `r` > '.mysql_real_escape_string(round($color[$i])-$h).' AND `r` < '.mysql_real_escape_string(round($color[$i])+$h).' AND `g` > '.mysql_real_escape_string(round($color[$i+1])-$h).' AND `g` < '.mysql_real_escape_string(round($color[$i+1])+$h).' AND `b` > '.mysql_real_escape_string(round($color[$i+2])-$h).' AND `b` < '.mysql_real_escape_string(round($color[$i+2])+$h);			

			$res = $db->query($sql1);
			
			if ($obj = $res->fetch(PDO::FETCH_ASSOC)){
				if($obj['artist'] != ''){
					$artist[$j] = $obj['artist'];
					$j++;
				}
			}
		}
		
		$artist = array_unique($artist);
		
		
		if(count($artist) >= 1){

			
			if(count($artist) != 1)
				$data['artist'] = ('Создавали работы с аналогичными цветами: ');
			else
				$data['artist'] = ('Создавал работы с аналогичными цветами: ');
				
			for($i = 0; $i < count($artist); $i++){
				if($artist[$i] != ' '){
				$data['artist'] .= ('<a href="https://artrue.ru/?s='.$artist[$i].'">'.$artist[$i].'</a>');
				if($i+1 != count($artist))
					$data['artist'] .= (', ');
				else
					$data['artist'] .= ('.');
				}
			}
		}else{
			$data['artist'] .= ('Цветовых аналогий не найдено');
		}
		
		$sql = 'DELETE FROM `color` WHERE `artist` = ""';
			$res = $db->query($sql);
		
		$data['artist'] .= ('<div class="whiteSpace">');
		
		$h = min(count($artist),3);
		for($i = 0; $i < $h; $i++){
			if($artist[$i] != ' ' && $artist[$i] != ''){
				$data['artist'] .= do_shortcode('[mla_gallery s="'.$artist[$i].'" link=file columns=4 thumbnail="medium" numberposts=8] '.'<span style="font-size: 400%">...</span>');
			}
		}

		$data['artist'] .= ('</div>');
		
		print JSON_encode($data);
		
		
		$db = null;
		$sql = null;
		$sql1 = null;
		$sql2 = null;
		$res = null;
    	$res2 = null;
    	exit();
		
	 }
    
    if($_POST['type'] == 'test'){ //юзеор залил картинку
	}
    	
    if($_POST['dominant'] != '' || $_POST['palette'] != '' || $_POST['name'] != ''){ //когда пришел ajax запрос
    	
    	$dayTime = $_POST['dayTime'];
    	echo($dayTime);
    	
    	
    	
    	$color = array();
  
    	$color = getPalette($_POST['palette'], 8);

    	
    	$db = connect();
    	
    	$sql = 'SELECT `artist` FROM `color` WHERE `artist` = "'.mysql_real_escape_string($_POST['name']).'" LIMIT 1';
    
    	$res = $db->query($sql);
    	
    	if(!$obj = $res->fetch(PDO::FETCH_OBJ)) { //нет художник. Добавляем его в базу и таблицу к нейронной сети.
			
			for($i = 0; $i < count($color); $i+=3){ 
	    		$sql = $db->query('INSERT INTO `color` VALUES ('. mysql_real_escape_string(round($color[$i])).','.mysql_real_escape_string(round($color[$i+1])).','.mysql_real_escape_string(round($color[$i+2])).', "'.mysql_real_escape_string($_POST['name']).'",1,"'.mysql_real_escape_string($dayTime).'")');
	    		//echo($sql);
	    	}
	    	
	    	$sql = $db->query('INSERT INTO `network` VALUES ("'.mysql_real_escape_string($_POST['name']).'", 0)');

		}
		
		
		else{ //есть художника
			//echo('есть художник');
			
			for($i = 0; $i < count($color); $i+=3){ //добавляем все цвета
				
				
				$sql1 = 'SELECT `artist` FROM `color` WHERE `artist` = "'.mysql_real_escape_string($_POST['name']).'" AND r = '.mysql_real_escape_string(round($color[$i])).' AND g = '.mysql_real_escape_string(round($color[$i+1])).' AND b = '.mysql_real_escape_string(round($color[$i+2])).' LIMIT 1';
				
				$res = $db->query($sql1);

				
				if( $row = $res->fetch(PDO::FETCH_ASSOC)) { //есть цвет
					
					
					$sql = $db->query('SELECT `inputs` FROM `color` WHERE `artist` = "'.mysql_real_escape_string($_POST['name']).'" AND r = '.mysql_real_escape_string(round($color[$i])).' AND g = '.mysql_real_escape_string(round($color[$i+1])).' AND b = '.mysql_real_escape_string(round($color[$i+2])).' LIMIT 1');
					
					$inputs = 0;
					while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
						$inputs = $row["inputs"];
					}
					
					$inputs++;
					
					
					$upd = $db->query('UPDATE `color` SET `inputs` = '.mysql_real_escape_string($inputs).' WHERE r = '.mysql_real_escape_string(round($color[$i])).' AND g = '.mysql_real_escape_string(round($color[$i+1])).' AND b = '.mysql_real_escape_string(round($color[$i+2])));	//обновляем число вхождений цвета
							
				}
				else{ //нет цвета
					//echo($i);
					$sql = $db->query('INSERT INTO `color` VALUES ('.mysql_real_escape_string(round($color[$i])).','.mysql_real_escape_string(round($color[$i+1])).','.mysql_real_escape_string(round($color[$i+2])).', "'.mysql_real_escape_string($_POST['name']).'",1,"'.mysql_real_escape_string($dayTime).'")');
				}
	    	}
		}
		
	$db = null;
	$sql = null;
	$sql1 = null;
	$res = null;
    	
    	
   			  
    }
    
    
    
    
    //админство
    
	
    if($_POST['type'] == 'deletName'){ //удаление художника
		$db = connect();
		$sql = 'DELETE FROM `color` WHERE `artist` = "'.mysql_real_escape_string($_POST['dname']).'"';
    	$res = $db->query($sql);
    	
		$sql2 = 'DELETE FROM `network` WHERE `Name` = "'.mysql_real_escape_string($_POST['dname']).'"';
    	$res2 = $db->query($sql2);
    	
    	$db = null;
		$sql = null;
		$sql2 = null;
		$res = null;
    	$res2 = null;
    	
    	print('Удаление завершено.');
    }
    
    if($_POST['type'] == 'deletAll'){
	   	$db = connect();
		$sql = 'TRUNCATE TABLE `color`';
    	$res = $db->query($sql);
    	$sql2 = 'TRUNCATE TABLE `network`';
    	$res2 = $db->query($sql2);
    	
    	$db = null;
		$sql = null;
		$sql2 = null;
		$res = null;
    	$res2 = null;
    	print('Удаление завершено.');
    }
    
    if($_POST['type'] == 'timeTeach'){
	    $db = connect();
	    
	    //проверить таблы
	    
	    $sql = 'SELECT `artist` FROM `color` LIMIT 1';
    	$res = $db->query($sql);
    	
    	if(!$obj = $res->fetch(PDO::FETCH_OBJ)) { //нет данных
	    	print ("Таблица пуста. Обучение не удалось.");
    	}
    	
    	else{
	    
	    //выгрузить массив цвет->время
	    
	    $sql = $db->query('SELECT `r`,`g`,`b` FROM `color` WHERE `time` = "день"');	 //выбираем "дневные" цвета
		$day = array();
		$i = 0;
		while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$day[$i]['r'] = $row["r"];
			$day[$i]['g'] = $row["g"];
			$day[$i]['b'] = $row["b"];
			$i++;
		}
	   
	    $sql2 = $db->query('SELECT `r`,`g`,`b` FROM `color` WHERE `time` = "ночь"'); //выбираем "ночные" цвета
		$night = array();
		$i = 0;
		while($row2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
			$night[$i]['r'] = $row2["r"];
			$night[$i]['g'] = $row2["g"];
			$night[$i]['b'] = $row2["b"];
			$i++;
		}
		
		
		for($i = 0; $i < count($day); $i++){
			for($j = 0; $j < count($night); $j++){
				if ($day[$i]['r'] == $night[$j]['r'] && $day[$i]['g'] == $night[$j]['g'] && $day[$i]['b'] == $night[$j]['b']){
	
					unset($night[$j]);

				}
			}
		}	
		
	$night =  array_delete($night);
		
	  $count = min(count($day),count($night));
	  
	  
	    $db = null;
		$sql = null;
		$sql2 = null;
		$res = null;
    	$res2 = null;
	   	
    }

    }
    
    if($_POST['type'] == 'ganre'){
	    
	    $db = connect();
		$lang = $db->query('SET CHARACTER SET utf8');
		
		
		$sql = 'SELECT * FROM `genre` WHERE `genre_name` = "'.mysql_real_escape_string($_POST['ganre']).'" AND `artist` = "'.mysql_real_escape_string($_POST['artist']).'" LIMIT 1';
		//echo($sql);
    	$res = $db->query($sql);
    	
    	if(!$obj = $res->fetch(PDO::FETCH_OBJ)){

			$sql = $db->query('INSERT INTO `genre` VALUES ("'.mysql_real_escape_string($_POST['ganre']).'","'.mysql_real_escape_string($_POST['artist']).'")');
			echo('Добавлено');
		}else{
			echo('Такие данные уже есть');
		}
			
	    
	    $db = null;
		$sql = null;
		$lang = null;

	    
	    
    }
    
    if($_POST['type'] == 'vector'){
	    $db = connect();
		$lang = $db->query('SET CHARACTER SET utf8');
		
		$sql = 'SELECT distinct `genre_name` FROM `genre`';
		
		$res = $db->query($sql);
		$vec = array();
		$nameGenre = array();
		$i = 0;
		$j = 0;
    	
    	while($obj = $res->fetch(PDO::FETCH_ASSOC)){
	    	
	    	$sql = 'SELECT `artist` FROM `genre` WHERE `genre_name` = "'.mysql_real_escape_string($obj['genre_name']).'"';
	    	
	    	$res2 = $db->query($sql);
	    	
	    	while($art = $res2->fetch(PDO::FETCH_ASSOC)){
		    	$sql2 = 'SELECT `r`,`g`,`b` FROM `color` WHERE artist = "'.mysql_real_escape_string($art['artist']).'"';
		    	$res3 = $db->query($sql2);
		    	while($color = $res3->fetch(PDO::FETCH_ASSOC)){
		    		$vec[$obj['genre_name']]['r'][$i] = $color['r'];
		    		$vec[$obj['genre_name']]['g'][$i] = $color['g'];
		    		$vec[$obj['genre_name']]['b'][$i] = $color['b'];
		    		$nameGenre[$j] = $obj['genre_name'];
		    		$j++;
		    		$i++;
		    	}
	    	}
    	}
    	
    	$tempGenre = array_unique($nameGenre);
    	
    	$y = 0;
    	//print_r($vec); //цвета жанров
    	echo('<br><br>');
    	$nameGenre = null;
    	foreach ($tempGenre as $t){
	    	$nameGenre[$y] = $t; 
	    	$y++;
    	}
    	
    	$k = 8;
    	
    	$big    = 256 / $k;
    	$middle = 192 / $k;
    	$small  = 128 / $k;
    	
    	
    	$mainColor = array( array(0, $big, $big), array(0,0,0), array(0,0, $big), array( $big,0, $big), array( $small, $small, $small), array(0, $small,0), array(0, $big,0), array( $small,0,0), array(0,0, $small), array( $small, $small,0), array( $small,0, $small),array( $big,0,0),array( $middle, $middle, $middle),array(0, $small, $small),array( $big, $big, $big),array( $big, $big,0));
    	
    	
    	
    	$z = 0;
    	
    	
		$vector = array();
		$y = 0;
		$f = 0;
		$l = 0;
    	foreach ($vec as $m){
	    	
		    for($s = 0; $s < count($m['r']); $s++){
				$vector[$nameGenre[$y]]['r'][$l] = $m['r'][$f];
				$vector[$nameGenre[$y]]['g'][$l] = $m['g'][$f];
				$vector[$nameGenre[$y]]['b'][$l] = $m['b'][$f];
				$f++;
				$l++;
		    }
		    //echo('<br><br>');
		    $y++;
		    $l = 0;
    	}
    	
		$vec = $vector;
    	
    	$pattern = 	18;
    	$finalVector = array();
    	
    	$t = 0;
    	$h = 0;
    	$prom = array();
    	
    	echo(count($mainColor).'<-');
    	for($g = 0; $g < count($nameGenre); $g++){
    		for($m = 0; $m < count($mainColor); $m++){
		    	$prom[$nameGenre[$g]][$m] = 0;
	    	}
    	}
    	
    	for($m = 0; $m < count($mainColor); $m++){
	    	
	    	$flag = 0;
	    	
	    	for($g = 0; $g < count($nameGenre); $g++){
		    	$prom[$g] = 0;
	    	}
	    	
	    	//echo(count($nameGenre));
	    	for($g = 0; $g < count($nameGenre); $g++){
		    	//$lim = max(count($vec[$nameGenre[$g]]['r']),count($mainColor));
		    	for($p = 0; $p < count($vec[$nameGenre[$g]]['r']); $p++){
			    	
			    	$prom[$nameGenre[$g]][$m] = colot_proximity_admin($mainColor[$i], $vec[$nameGenre[$g]], $p, $pattern, 1);
			    }
	    	}
	    }
    	//echo('<br><br>');
    	print_r($prom);
    	
    	$sql = 'TRUNCATE TABLE `vector`';
    	$res = $db->query($sql);
    	
    	for($g = 0; $g < count($nameGenre); $g++){
	    	$s = implode(';',$prom[$nameGenre[$g]]);
	    	
			$sql = 'INSERT INTO `vector` VALUES ("'.$nameGenre[$g].'","'.$s.'")';
			//echo($sql);
	    	$res = $db->query($sql);
	    }
    	
		
		$db = null;
		$sql = null;
		$sql2 = null;
		$res = null;
		$res2 = null;
		$lang = null;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    