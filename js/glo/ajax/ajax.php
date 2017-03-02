
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
		//$img = $_POST['img'];
		//$img = str_replace('data:image/jpeg;base64,', '', $img);
		//$img = str_replace(' ', '+', $img);
		//$result = file_put_contents('img/'.date('m_d_y_H_i_s').'.png', base64_decode($img));
		
		
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
		    	
		    	$vectorUser[$f] = color_proximity($mainColor[$i], $color, $j, $pattern, 1);
		    	
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
    	
		print ($data['table']);
    	
    	
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
		
		
		print ($data['artist']);
		
		
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
    
    
        
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    