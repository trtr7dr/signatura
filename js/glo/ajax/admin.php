
<?php
       
    require_once ( 'connect.php' );
    require_once ( 'function.php' );
    require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
    
      
    
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    