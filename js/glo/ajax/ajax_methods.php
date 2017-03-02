
<?php
       
    require_once ( 'connect.php' );
    require_once ( 'function.php' );
    require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
    
       
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
    	
    	
    	while($obj = $res->fetch(PDO::FETCH_ASSOC)){
	    	$tmp = explode(';',$obj['coordinate']);
			$ganreResult['ganre'][$u] = $obj['ganre'];
			$ganreResult['res']['cos'][$u] = cosinus($vectorUser, $tmp);
			$ganreResult['res']['dise'][$u] = dise($vectorUser, $tmp);
			//Overlap 
	    	$u++;
    	}
    	
    	$ganreResult['alg'] = array('cos','dise');
    	print json_encode($ganreResult);
    	
    	//print_r($ganreResult['res']['dise']);
    	
    	//print_r($ganreResult['res']['cos']);
    	
    	
    	//$data['cos'] = print_ganre($ganreResult,'cos');
    	
    	//$data['dise'] = print_ganre($ganreResult,'dise');
    	
    	
    	
    	//print($data['cos']);
    	//echo('<br>');
    	//print($data['dise']);
    	
    	    	
    	//print json_encode(($tst),JSON_UNESCAPED_SLASHES);
		

		
		
		$db = null;
		$sql = null;
		$sql1 = null;
		$sql2 = null;
		$res = null;
    	$res2 = null;
    	exit();
		
	 }
    
       
    
        
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    